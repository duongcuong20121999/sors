<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use App\Models\Citizen;
//use App\Models\CitizenService;
use App\Models\Service;
use App\Services\CitizenServiceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;
use Endroid\QrCode\Writer\PngWriter;
use App\Helpers\WorkingTimes;
use App\Models\Setting;
class ServiceKioskController extends Controller
{
    protected $citizenServiceService;

    public function __construct(CitizenServiceService $citizenServiceService)
    {
        $this->citizenServiceService = $citizenServiceService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $setting = Setting::first();
        $urlApiPrint = $setting->url_api_print;
        $services = Service::orderBy('order')->get();

        return view('frontend.service-kiosk.index', compact('services', 'urlApiPrint'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $service = Service::find($id);

        if (!$service) {
            return response()->json(['message' => 'Service not found'], 404);
        }

        return response()->json([
            'id' => $service->id,
            'name' => $service->name,
            'tasks' => $service->mission,   // Giả sử tasks lưu dạng xuống dòng
            'notes' => $service->description    // Giả sử notes lưu dạng xuống dòng
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function getNumber($id)
    {
        $service = Service::find($id);

        if (!$service) {
            return response()->json([
                'success' => false,
                'message' => 'Service not found'
            ], 404);
        }

        $citizen = Citizen::where('identity_number', '000000000000')->first();

        if (!$citizen) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy công dân với CMND/CCCD là 000000000000.'
            ], 404);
        }

        
        
        $data = [
            'citizen_id' => $citizen->id,
            'service_id' => $service->id,
            'order' => $service->order,
            'appointment_date' => now()->setTimezone('Asia/Ho_Chi_Minh'),
            'source' => 'kiosk'
        ];

        // Create citizen service record
        $result = $this->citizenServiceService->create($data);

        $registeredRecord =  $result->record;

        $customLink = url("/dashboard/{$registeredRecord->id}");

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

        $registeredRecord->qr_code = 'storage/' . $fileName;
        $registeredRecord->save();


        //$this->citizenService->update($citizenServiceRecord->id, $citizenServiceRecord);

        return response()->json([
            'success' => true,
            'service_name' => $service->name,
            'counter' => $service->order,
            'notes' => $service->description,
            'sequence_number' => $registeredRecord->sequence_number,
            'appointment_start_date' => $registeredRecord->appointment_date->format('H:i:s Y-m-d'),
            'appointment_date' => $registeredRecord->appointment_date->format('H:i:s Y-m-d'),
            'created_date' => $registeredRecord->created_date->format('H:i:s Y-m-d'),
            'record_id' => $registeredRecord->id,
            'qr_code_url' => asset('storage/' . $fileName),
            'count_ahead' =>  $result->count_ahead
        ]);
    }
}
