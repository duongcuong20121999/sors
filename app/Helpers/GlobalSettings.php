<?php

namespace App\Helpers;

class GlobalSettings{

    public static function get(){
        return [
            'moving_time' => 60, // 60 minutes
        ];
    }
}