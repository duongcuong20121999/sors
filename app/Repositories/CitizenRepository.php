<?php

// app/Repositories/CitizenRepository.php
namespace App\Repositories;

use App\Models\Citizen;

class CitizenRepository
{
    public function all()
    {
        return Citizen::all();
    }
    public function find($id)
    {
        return Citizen::findOrFail($id);
    }
    public function findByZaloId($zaloId) {
        return Citizen::where('zalo_id', $zaloId)->first();
    }
    public function create(array $data)
    {
        return Citizen::create($data);
    }
    public function update($id, array $data)
    {
        $citizen = Citizen::findOrFail($id);
        $citizen->update($data);
        return $citizen;
    }
    public function delete($id)
    {
        return Citizen::destroy($id);
    }
}
