<?php

// app/Models/Service.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Service extends Model
{
    use HasUuids;
    protected $fillable = ['name', 'icon','code','mission', 'description','order', 'is_active', 'process_hours', 'process_minutes', 'unlimited_duration'];


    public function citizenServices() {
        return $this->hasMany(CitizenService::class);
    }
}
