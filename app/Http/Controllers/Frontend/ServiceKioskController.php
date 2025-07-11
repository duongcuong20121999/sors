<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use App\Models\Citizen;
//use App\Models\CitizenService;
use App\Models\Service;
use App\Models\Setting;
use App\Services\CitizenServiceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;
use Endroid\QrCode\Writer\PngWriter;

class ServiceKioskController extends Controller
{
    protected $citizenService;
    public function __construct(CitizenServiceService $citizenService)
    {
        $this->citizenService = $citizenService;
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

        // Calculate appointment date based on service process time
        $now = now();
        $appointmentDate = $now->copy();

        if (!$service->unlimited_duration || $service->unlimited_duration == 0) {
            // Add process hours and minutes
            $hours = intval(!$service->process_hours || $service->process_hours < 0 ? 0 : $service->process_hours);
            $minutes = intval(!$service->process_minutes || $service->process_minutes < 0 ? 0 : $service->process_minutes);
            $appointmentDate->addHours($hours)
                ->addMinutes($minutes);
                 // Add 60 minutes buffer
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
        $result = $this->citizenService->create($data);

        $citizenServiceRecord = $result->record;

        // Generate QR code
        $customLink = url("/dashboard/{$citizenServiceRecord->id}");
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

        $citizenServiceRecord->qr_code = 'storage/' . $fileName;

        $citizenServiceRecord->save();

        //$this->citizenService->update($citizenServiceRecord->id, $citizenServiceRecord);

        return response()->json([
            'success' => true,
            'service_name' => $service->name,
            'counter' => $service->order,
            'notes' => $service->description,
            'sequence_number' => $citizenServiceRecord->sequence_number,
            'appointment_date' => $appointmentDate->format('Y-m-d H:i:s'),
            'created_date' => $citizenServiceRecord->created_date->format('Y-m-d H:i:s'),
            'record_id' => $citizenServiceRecord->id,
            'qr_code_url' => asset('storage/' . $fileName),
            'count_ahead' =>  $result->count_ahead
        ]);
    }

}
