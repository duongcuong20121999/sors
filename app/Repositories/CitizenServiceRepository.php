<?php

// app/Repositories/CitizenRepository.php
namespace App\Repositories;

use App\Models\CitizenService;
use Carbon\Carbon;

class CitizenServiceRepository
{
    public function all()
    {
        return CitizenService::all();
    }
    public function find($id)
    {
        return CitizenService::findOrFail($id);
    }

    public function findByCreatedDate($id, $create_date)
    {
        return CitizenService::where('service_id', $id)
            ->whereIn('status', [0, 1, 2])
            ->whereDate('created_date', $create_date)
            ->orderBy('sequence_number', 'desc')
            ->get();
    }

    public function findByAppointmentDate($id, $appointment_date)
    {
        return CitizenService::where('service_id', $id)
            ->whereIn('status', [0, 1, 2])
            ->whereDate('appointment_date', $appointment_date)
            ->orderBy('sequence_number', 'desc')
            ->get();
    }

    public function findFromAppointmentDate($appointment_date)
    {
        return CitizenService::whereDate('appointment_date', '>=', $appointment_date)
            ->whereIn('status', [0, 1, 2])
            ->where('source', '!=', 'zalo')
            ->orderBy('appointment_date', 'asc')
            ->get();
    }

    public function create(array $data)
    {
        return CitizenService::create($data);
    }
    public function update($id, array $data)
    {
        $citizenService = CitizenService::findOrFail($id);
        $citizenService->update($data);
        return $citizenService;
    }
    public function delete($id)
    {
        return CitizenService::destroy($id);
    }

    public function countAll()
    {
        return CitizenService::count();
    }
}
