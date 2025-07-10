<?php

// app/Http/Controllers/ServiceController.php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\ServiceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ServiceController extends Controller
{
    protected $serviceService;
    public function __construct(ServiceService $serviceService)
    {
        $this->serviceService = $serviceService;
    }
    public function index()
    {
        return response()->json($this->serviceService->getAll());
    }

    public function show($id)
    {
        try {
            $service = $this->serviceService->getById($id);
            return response()->json($service);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Service not found'], 404);
        }
    }
    public function store(Request $request)
{
    $data = $request->validate([
        'name' => 'required|max:50',
        'code' => 'required|max:50',
        'icon' => 'required|max:2048',
        'order' => 'required|integer',
        'is_active' => 'boolean',
        'process_hours' => 'integer|min:0',
        'process_minutes' => 'integer|min:0|max:59',
        'unlimited_duration' => 'boolean',
    ]);

    if ($request->hasFile('icon')) {
        $imagePath = $request->file('icon')->store('icons', 'public');
        $data['icon'] = '/storage/' . $imagePath;
    }

    return response()->json($this->serviceService->create($data), 201);
}
    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'name' => 'max:50',
            'code' => 'required|max:50',
            'icon' => 'required|max:50',
            'order' => 'integer',
            'is_active' => 'boolean',
            'process_hours' => 'integer|min:0',
            'process_minutes' => 'integer|min:0|max:59',
            'unlimited_duration' => 'boolean',
        ]);
    
        $service = $this->serviceService->getById($id);
    
        if ($request->hasFile('icon')) {
            if ($service->icon) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $service->icon));
            }
            $imagePath = $request->file('icon')->store('icons', 'public');
            $data['icon'] = '/storage/' . $imagePath;
        }
    
        return response()->json($this->serviceService->update($id, $data));
    }
    public function destroy($id)
    {
        return response()->json(['deleted' => $this->serviceService->delete($id)]);
    }
}
