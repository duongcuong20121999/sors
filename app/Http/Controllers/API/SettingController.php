<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
class SettingController extends Controller
{
     public function getTimeUpdate(): JsonResponse
    {
        $setting = Setting::first(); 
        return response()->json([
            'time_update' => $setting->time_update ?? 5, 
        ]);
    }
}
