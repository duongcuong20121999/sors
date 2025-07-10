<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\CitizenService;
use App\Models\Service;
use Illuminate\Http\Request;

use App\Enums\Status;

class DashboardController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $services = Service::all()->sortBy('order');
        $statusOptions = getAllStatusCS();

        $query = CitizenService::with(['citizen', 'service'])->whereIn('status', [
            Status::New->value,
            Status::Reviewing->value,
            Status::InProgress->value,
            Status::Done->value
        ]);

        // Lọc theo mã dịch vụ nếu có
        if ($request->filled('service_code')) {
            $serviceCodes = json_decode($request->service_code, true);

            if (is_array($serviceCodes)) {
                $query->whereHas('service', function ($q) use ($serviceCodes) {
                    $q->whereIn('code', $serviceCodes);
                });
            } else {
                $query->whereHas('service', function ($q) use ($request) {
                    $q->where('code', $request->service_code);
                });
            }
        }

        // Lọc theo tên công dân nếu có
        if ($request->filled('citizen_name')) {
            $query->whereHas('citizen', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->citizen_name . '%');
            });
        }

        if ($request->filled('value_status')) {
            $statuses = json_decode($request->value_status, true);

            if (is_array($statuses)) {
                $query->whereIn('status', $statuses);
            } else {
                $query->where('status', $statuses); // fallback nếu chỉ 1 trạng thái
            }
        }

        // Lấy dữ liệu
        $citizenServices = $query->orderBy('appointment_date')->get();

        // Trả về kết quả dưới dạng view nếu là yêu cầu AJAX
        if ($request->ajax()) {
            return view('partials._citizen_service_list', compact('citizenServices'))->render();
        }

        // Trả về kết quả đầy đủ với danh sách dịch vụ
        return view('dashboard', compact('citizenServices', 'services', 'request', 'statusOptions'));
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
        $services = Service::all()->sortBy('order');

        $query = CitizenService::with(['citizen', 'service'])->where('id', $id);

        $citizenService = $query->firstOrFail();

        // Trả về kết quả đầy đủ với danh sách dịch vụ
        return view('dashboard', compact('citizenService', 'services'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $cs = CitizenService::with(['citizen', 'service'])->find($id);

        if (!$cs) {
            return response()->json(['error' => 'Not found'], 404);
        }

        // if ($cs->status == 0) {
        //     $cs->update(['status' => 1]);
        // }



        return response()->json([
            'id' => $cs->id,
            'name' => $cs->citizen->name,
            'address' => $cs->citizen->address,
            'phone' => $cs->citizen->phone_number,
            'service' => $cs->service->name,
            'phone' => $cs->citizen->phone_number,
            'identity_number' => $cs->citizen->identity_number,
            'citizen_note' => $cs->citizen_note,
            'created_date' => $cs->created_date,
            'sequence_number' => $cs->sequence_number,
            'status' => $cs->status

        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $cs = CitizenService::with('citizen')->findOrFail($id);

        // Cập nhật địa chỉ nếu có
        if ($request->filled('address') && $cs->citizen->identity_number !== '000000000000') {
            $cs->citizen->address = $request->input('address');
            $cs->citizen->save();
        }

        // Cập nhật ghi chú cho dịch vụ
        if ($request->filled('citizen_note')) {
            $cs->citizen_note = $request->input('citizen_note');
            $cs->save();
        }

        if ($request->status == 0 || $cs->status == 1) {
            $cs->update(['status' => 2]);
        }

        if ($request->status == 2 && $request->cf_completed == "on") {
            $cs->update(['status' => 3]);
        }

        if ($request->status == 3 && $request->cf_cancel == "on") {
            $cs->update(['status' => 4]);
        }

        // Lấy lại danh sách filter
        $serviceCode = $request->input('service_code', '');
        $citizenName = $request->input('citizen_name', '');

        $query = CitizenService::with(['citizen', 'service'])->whereIn('status', [
            Status::New->value,
            Status::Reviewing->value,
            Status::InProgress->value,
            Status::Done->value
        ]);

        if ($serviceCode) {
            $query->whereHas('service', function ($q) use ($serviceCode) {
                $q->where('code', $serviceCode);
            });
        }

        if ($citizenName) {
            $query->whereHas('citizen', function ($q) use ($citizenName) {
                $q->where('name', 'like', '%' . $citizenName . '%');
            });
        }

        $citizenServices = $query->orderBy('appointment_date')->get();

        // Render lại HTML của danh sách đã filter
        $updatedListHtml = view('partials._citizen_service_list', compact('citizenServices'))->render();

        // Trả về JSON cho AJAX
        return response()->json([
            'success' => true,
            'message' => 'Cập nhật thành công!',
            'alertType' => 'success',
            'updatedListHtml' => $updatedListHtml,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id, Request $request)
    {

        $service = CitizenService::find($id);

        if (!$service) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy dịch vụ cần hủy!',
                'alertType' => 'error'
            ]);
        };

        $service->status = 5;


        if ($request->filled('citizen_note')) {
            $service->citizen_note = $request->input('citizen_note');
        }

        $service->save();


        $query = CitizenService::with(['citizen', 'service'])->whereIn('status', [
            Status::New->value,
            Status::Reviewing->value,
            Status::InProgress->value,
            Status::Done->value
        ]);

        if ($request->filled('service_code')) {
            $query->whereHas('service', function ($q) use ($request) {
                $q->where('code', $request->service_code);
            });
        }

        if ($request->filled('citizen_name')) {
            $query->whereHas('citizen', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->citizen_name . '%');
            });
        }

        $citizenServices = $query->orderBy('appointment_date')->get();

        // Render lại HTML
        $updatedListHtml = view('partials._citizen_service_list', compact('citizenServices'))->render();

        return response()->json([
            'success' => true,
            'message' => 'Huỷ người dùng dịch vụ thành công!',
            'alertType' => 'success',
            'updatedListHtml' => $updatedListHtml
        ]);
    }

    public function updateStatus(Request $request)
    {
        // dd($request->all());
        $citizenService = CitizenService::find($request->id);

        if (!$citizenService) {
            return response()->json(['success' => false, 'message' => 'Không tìm thấy yêu cầu']);
        }


        // Cập nhật start_processing và status nếu có
        if (!is_null($request->start_processing)) {
            $citizenService->start_procesing = $request->start_processing;
        }

        if (!is_null($request->status)) {
            $citizenService->status = $request->status;
        }

        $citizenService->save();


        $query = CitizenService::with(['citizen', 'service'])->whereIn('status', [
            Status::New->value,
            Status::Reviewing->value,
            Status::InProgress->value,
            Status::Done->value
        ]);


        if ($request->filled('service_code')) {
            $query->whereHas('service', function ($q) use ($request) {
                $q->where('code', $request->service_code);
            });
        }


        if ($request->filled('citizen_name')) {
            $query->whereHas('citizen', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->citizen_name . '%');
            });
        }


        $citizenServices = $query->orderBy('appointment_date')->get();

        $updatedView = view('partials._citizen_service_list', compact('citizenServices'))->render();

        return response()->json(['success' => true, 'updatedView' => $updatedView]);
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

    public function cancelMultiple(Request $request)
    {
        $ids = $request->input('ids', []);

        if (empty($ids)) {
            return response()->json([
                'success' => false,
                'message' => 'Không có yêu cầu nào được chọn.',
            ]);
        }

        CitizenService::whereIn('id', $ids)->update(['status' => 6]);

        $citizenServices = CitizenService::with(['citizen', 'service'])
            ->whereIn('status', [ 
            Status::New->value,
            Status::Reviewing->value,
            Status::InProgress->value,
            Status::Done->value])
            ->orderBy('appointment_date')
            ->get();

            

        $updatedListHtml = view('partials._citizen_service_list', compact('citizenServices'))->render();

        return response()->json([
            'success' => true,
            'message' => 'Huỷ yêu cầu thành công!',
            'updatedListHtml' => $updatedListHtml
        ]);
    }
}
