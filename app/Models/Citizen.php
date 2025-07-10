<?php

// app/Models/Citizen.php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Citizen extends Model {
    use HasUuids;
    protected $fillable = ['name', 'first_name', 'avatar', 'address', 'identity_number',  'dob', 'dop', 'phone_number', 'created_date', 'updated_date', 'last_time_login', 'zalo_id'];


    public function citizenServices() {
        return $this->hasMany(CitizenService::class);
    }

    public function services()
{
    return $this->hasMany(CitizenService::class, 'citizen_id');
}
}