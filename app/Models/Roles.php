<?php

namespace App\Models;
use Spatie\Permission\Models\Role as SpatieRole;
use Illuminate\Database\Eloquent\Model;

class Roles extends SpatieRole
{
    protected $fillable = [
        'name',
        'description',
        'is_active',
        'guard_name',
    ];
}
