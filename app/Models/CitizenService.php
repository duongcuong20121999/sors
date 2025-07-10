<?php

// app/Models/CitizenService.php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class CitizenService extends Model {
    protected $fillable = ['citizen_id', 'service_id', 'sequence_number', 'citizen_note', 
    'staff_node', 'status', 'qr_code', 'created_date', 'appointment_start_date', 
    'appointment_date', 'updated_date', 'source', 'read'];

    public function citizen() {
        return $this->belongsTo(Citizen::class);
    }

    public function service() {
        return $this->belongsTo(Service::class);
    }

}
