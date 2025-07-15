<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class CitizenServiceFile extends Model
{
    use HasFactory;

    protected $fillable = [
        'citizen_service_id',
        'title',
        'file_path',
    ];

    public function citizenService()
    {
        return $this->belongsTo(CitizenService::class);
    }
}
