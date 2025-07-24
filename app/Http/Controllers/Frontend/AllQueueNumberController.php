<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AllQueueNumberController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index()
{
    $services = Service::where('is_active', true)
        ->where('code', '!=', 'HDDV')
        ->orderBy('order')
        ->get()
        ->map(function ($service) {
            $prefix = str_pad($service->order, 1, '0', STR_PAD_LEFT) . '00';
         
            $citizens = $service->citizenServices()
                ->whereIn('status', [0, 1])
                ->where('sequence_number', 'like', $prefix . '%')
                ->whereDate('appointment_date', Carbon::today())
                ->orderBy('appointment_date')
                ->limit(3)
                ->get()
                ->values(); // reset index

            $remaining = $service->citizenServices()
                ->where('status', 0)
                ->where('sequence_number', 'like', $prefix . '%')
                ->whereDate('appointment_date', Carbon::today())
                ->count();

            return [
                'id' => $service->id,
                'name' => $service->name,
                'order' => $service->order,
                'queue' => $citizens, // Chứa cả status = 1 và 0
                'remaining' => $remaining,
            ];
        });
    

    return view('frontend.service-queue-number.index', compact('services'));
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
