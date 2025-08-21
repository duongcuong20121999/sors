<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    protected $fillable = ['citizen_service_id', 'rating', 'note', 'created_at'];

    public function citizenService()
    {
        return $this->belongsTo(CitizenService::class);
    }
}
