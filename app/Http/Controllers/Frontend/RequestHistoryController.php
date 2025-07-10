<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\CitizenService;
use App\Models\Service;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RequestHistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $services = Service::all();
        $query = CitizenService::with(['citizen', 'service'])->whereIn('status', [ 4, 5, 6]);

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

        if ($request->filled('created_date')) {
            try {
                // Lấy giá trị ngày giờ từ người dùng
                $localDate = Carbon::parse($request->created_date)->format('Y-m-d');
                
                // Truy vấn dữ liệu trong phạm vi thời gian (UTC)
                $query->whereDate(DB::raw("DATE_ADD(created_date, INTERVAL 7 HOUR)"), $localDate);
            } catch (\Exception $e) {
                // Log lỗi nếu có
             
            }
        }

        $citizenServices = $query->orderByDesc('created_date')
        ->paginate(10)
        ->appends($request->query());

        if ($request->ajax()) {
            return response()->json([
                'requestHistory' => view('frontend.request-history.components.request-history-item', compact('citizenServices'))->render(),
                'pagination' => view('frontend.request-history.components.custom-pagination', compact('citizenServices'))->render(),
            ]);
        }

        return view('frontend.request-history.index', compact('citizenServices', 'services', 'request'));
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
        //
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
}
