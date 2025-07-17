<?php

namespace App\Http\Controllers\API;

use App\Enums\Status;
use App\Helpers\GroupStatus;
use App\Models\CitizenServiceFile;
use Illuminate\Http\Request;
use App\Models\Citizen;
use App\Models\Service;
use App\Services\CitizenServiceService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;
use App\Http\Controllers\Controller;
use App\Models\CitizenService;
use Carbon\Carbon;

class CitizenServiceController extends Controller
{

    protected $citizenServiceService;

    public function __construct(CitizenServiceService $citizenServiceService)
    {
        $this->citizenServiceService = $citizenServiceService;
    }

    public function registerService(Request $request)
    {
        $request->validate([
            'zalo_id' => 'required|string',
            'code' => 'required|string',
        ]);

        $citizen = Citizen::where('zalo_id', $request->zalo_id)->first();
        if (!$citizen) return response()->json(['message' => 'Citizen not found'], 404);

        $service = Service::where('code', $request->code)->first();
        if (!$service) return response()->json(['message' => 'Service not found'], 404);

        // Thời gian bắt đầu và kết thúc của ngày hôm nay
        $startOfDay = now('Asia/Ho_Chi_Minh')->startOfDay()->timezone('UTC');
        $endOfDay = now('Asia/Ho_Chi_Minh')->endOfDay()->timezone('UTC');

        // Check 1: Citizen đã đăng ký dịch vụ này hôm nay và chưa hoàn thành/chưa đóng
        // $existingRegistration = CitizenService::where('citizen_id', $citizen->id)
        //     ->where('service_id', $service->id)
        //     ->whereBetween('created_at', [$startOfDay, $endOfDay])
        //     ->whereIn('status', [
        //         Status::New->value,
        //         Status::Reviewing->value,
        //         Status::InProgress->value
        //     ])
        //     ->first();

        // if ($existingRegistration) {
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'Hệ thống ghi nhận quý công dân đã có một lượt đăng ký dịch vụ này đang được xử lý trong ngày hôm nay. Vui lòng chờ hoàn tất hoặc đăng ký lại vào ngày hôm sau!',
        //         'data' => null
        //     ], 200);
        // }

        // // Check 2: Citizen đã đăng ký bao nhiêu dịch vụ khác nhau hôm nay
        // $countServicesToday = CitizenService::where('citizen_id', $citizen->id)
        //     ->whereBetween('created_at', [$startOfDay, $endOfDay])
        //     ->distinct('service_id')
        //     ->count('service_id');

        // if ($countServicesToday >= 3) {
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'Không thể thực hiện đăng ký. Theo quy định, mỗi công dân chỉ được phép đăng ký tối đa 03 dịch vụ mỗi ngày!',
        //         'data' => null
        //     ], 200);
        // }

        // Calculate appointment date based on service process time
        $now = now();
        $appointmentDate = $now->copy();

        if (!$service->unlimited_duration || $service->unlimited_duration == 0) {
            // Add process hours and minutes
            $hours = intval(!$service->process_hours || $service->process_hours < 0 ? 0 : $service->process_hours);
            $minutes = intval(!$service->process_minutes || $service->process_minutes < 0 ? 0 : $service->process_minutes);
            $appointmentDate->addHours($hours)
                ->addMinutes($minutes);
        } else {
            // If unlimited duration, set to next day
            $appointmentDate->addDay();
        }

        $data = [
            'citizen_id' => $citizen->id,
            'service_id' => $service->id,
            'created_date' => $now,
            'updated_date' => $now,
            'appointment_date' => $appointmentDate,
            'order' => $service->order
        ];

        // Create citizen service record
        $result = $this->citizenServiceService->create($data);

        $citizenService =  $result->record;

        $customLink = url("/dashboard/{$citizenService->id}");

        $qrResult = Builder::create()
            ->writer(new PngWriter())
            ->data($customLink)
            ->encoding(new Encoding('UTF-8'))
            ->errorCorrectionLevel(new ErrorCorrectionLevelHigh())
            ->size(300)
            ->margin(10)
            ->build();

        $fileName = 'qr_codes/' . Str::uuid() . '.png';
        Storage::disk('public')->put($fileName, $qrResult->getString());

        $citizenService->qr_code = 'storage/' . $fileName;
        $citizenService->save();

        return response()->json([
            'success' => true,
            'message' => 'Service registered successfully',
            'qr_code_url' => asset('storage/' . $fileName),
            'data' => [
                'id' => $citizenService->id,
                'name' => $citizen->name,
                'phone_number' => $citizen->phone_number,
                'sequence_number' => $citizenService->sequence_number,
                'appointment_date' => $appointmentDate->format('Y-m-d H:i:s'),
                'created_date' => $citizenService->created_date->format('Y-m-d H:i:s'),
                'service_name' => $service->name,
                'counter' => $service->order,
                'qr_code' => $citizenService->qr_code,
                'count_ahead' =>  $result->count_ahead
            ]
        ]);
    }


    public function summaryByZaloId(Request $request)
    {
        $zaloId = $request->input('zalo_id');

        if (!$zaloId) {
            return response()->json(['error' => 'zalo_id is required'], 400);
        }

        $citizen = Citizen::where('zalo_id', $zaloId)->first();

        if (!$citizen) {
            return response()->json(['error' => 'Citizen not found'], 404);
        }

        $query = $citizen->services();

        $summary = [
            'created'    => $query->clone()->count(), // Tất cả status
            'processing'  => $query->clone()->whereIn('status', [
                Status::New->value,
                Status::Reviewing->value,
                Status::InProgress->value
            ])->count(),
            'completed'  => $query->clone()->whereIn('status', [
                Status::Done->value,
                Status::Closed->value
            ])->count(),
        ];

        return response()->json([
            'data' => $summary
        ]);
    }

    public function getByZaloAndStatus(Request $request)
    {
        $zaloId = $request->input('zalo_id');
        $statusGroup = $request->input('status_group'); // nhận '0', '1', '2'

        $statusMap = GroupStatus::statusGroups();

        if (!isset($statusMap[$statusGroup])) {
            return response()->json(['error' => 'Nhóm trạng thái không hợp lệ'], 400);
        }

        $statusValues = $statusMap[$statusGroup];

        $records = CitizenService::with('service')
            ->whereHas('citizen', function ($query) use ($zaloId) {
                $query->where('zalo_id', $zaloId);
            })
            ->whereIn('status', $statusValues)
            ->get();

        $result = $records->map(function ($record) {
            return [
                'id' => $record->id,
                'service_name' => $record->service->name ?? null,
                'code' => $record->service->code ?? null,
                'created_date' => $record->created_date,
                'appointment_date' => $record->appointment_date,
                'citizen_note' => $record->citizen_note,
                'status' => $record->status,
            ];
        });

        return response()->json($result);
    }

    public function cancelNew(Request $request)
    {

        try {

            $citizenService = CitizenService::findOrFail($request->id);


            $citizenService->status = 6;
            $citizenService->save();

            return response()->json([
                'message' => 'Cập nhật trạng thái thành công!',
                'data' => $citizenService
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Đã có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getDetailCitizenServices(Request $request)
    {
        // Lấy zalo_id từ query string
        $zaloId = $request->input('zalo_id');

        // Kiểm tra nếu không có zalo_id thì trả về lỗi
        if (!$zaloId) {
            return response()->json(['error' => 'Vui lòng cung cấp zalo_id'], 400);
        }

        // Lấy danh sách dịch vụ theo zalo_id
        $records = CitizenService::with('service')
            ->whereHas('citizen', function ($query) use ($zaloId) {
                $query->where('zalo_id', $zaloId);
            })
            ->get();


        // Định dạng kết quả
        $result = $records->map(function ($record) {
            return [
                'id' => $record->id,
                'service_name' => $record->service->name ?? null,
                'code' => $record->service->code ?? null,
                'created_date' => $record->created_date->format('Y-m-d H:i:s'),
                'appointment_date' => optional($record->appointment_date)->format('Y-m-d H:i:s'),
                'status' => $record->status,
            ];
        });

        // Trả về JSON
        return response()->json($result);
    }


    public function getReviewedTickets()
    {
        $citizenServices = CitizenService::where('read', 0)
            ->where('status', 1)
            ->orderBy('appointment_date', 'asc')->get();
        return response()->json(['success' => true, 'citizenServices' => $citizenServices]);
    }

    public function updateReadTicket(string $id, Request $request)
    {
        $service = CitizenService::find($id);

        if (!$service) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy dịch vụ cần hủy!',
                'alertType' => 'error'
            ]);
        };

        $service->read = 1;
        $service->save();

        return response()->json(['success' => true, 'message' => 'Cập nhật thành công!']);
    }



    public function uploadFile($id, Request $request)
{
    $request->validate([
        'files' => 'required|array|min:1',
        'files.*.title' => 'required|string|max:255',
        'files.*.file' => 'required|file|max:10240', 
    ]);

    $citizenService = CitizenService::findOrFail($id);

  
    $existingFileCount = CitizenServiceFile::where('citizen_service_id', $citizenService->id)->count();

   
    $uploadCount = count($request->input('files'));

 
    if (($existingFileCount + $uploadCount) > 10) {
        return response()->json([
            'success' => false,
            'message' => 'Tổng số tài liệu không được vượt quá 10.',
        ], 422); 
    }

    $savedFiles = [];

    foreach ($request->input('files') as $index => $inputFile) {
        $title = $inputFile['title'];
        $uploadedFile = $request->file("files.$index.file");

        if ($uploadedFile && $uploadedFile->isValid()) {
            $originalExtension = $uploadedFile->getClientOriginalExtension();
            $slugTitle = Str::slug($title, '_');
            $fileName = $slugTitle . '_' . uniqid() . '.' . $originalExtension;

            $filePath = $uploadedFile->storeAs('citizen_files', $fileName, 'public');

            $saved = CitizenServiceFile::create([
                'citizen_service_id' => $citizenService->id,
                'title' => $title,
                'file_path' => $filePath,
            ]);

            $savedFiles[] = $saved;
        }
    }

    return response()->json([
        'success' => true,
        'message' => 'Tải file thành công',
        'data' => $savedFiles
    ], 201);
}

    public function getFiles($id)
    {
        $cs = CitizenService::with('files')->find($id);

        if (!$cs) {
            return response()->json(['success' => false, 'error' => 'Không tìm thấy bản ghi.'], 404);
        }

        $files = $cs->files->map(function ($file) {
            return [
                'id' => $file->id,
                'title' => $file->title,
                'filename' => basename($file->file_path),
                'file_path' => asset('storage/' . $file->file_path),
                'size' => Storage::disk('public')->exists($file->file_path)
                    ? Storage::disk('public')->size($file->file_path)
                    : 0,
                'extension' => pathinfo($file->file_path, PATHINFO_EXTENSION),
            ];
        });

        return response()->json([
            'success' => true,
            'citizen_service_id' => $cs->id,
            'files' => $files
        ]);
    }
}
