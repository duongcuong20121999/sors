<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'system_name',
        'ward_name',
        'logo',
        'qr_code',
        'instruction',
    ];
}
