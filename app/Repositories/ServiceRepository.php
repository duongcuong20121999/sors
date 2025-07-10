<?php

// app/Repositories/ServiceRepository.php
namespace App\Repositories;

use App\Models\Service;

class ServiceRepository
{
    public function all()
    {
        return Service::select('id', 'name', 'icon','code','order','mission','description','is_active')->get();
    }
    public function find($id)
    {
        return Service::findOrFail($id);
    }
    public function create(array $data)
    {
        return Service::create($data);
    }
    public function update($id, array $data)
    {
        $service = Service::findOrFail($id);
        $service->update($data);
        return $service;
    }
    public function delete($id)
    {
        return Service::destroy($id);
    }
}
