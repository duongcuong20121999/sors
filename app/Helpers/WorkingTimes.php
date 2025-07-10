<?php

namespace App\Helpers;

class WorkingTimes
{
    /**
     * Get predefined working times.
     *
     * @return array
     */
    public static function get(): array
    {
        return [
            ['start' => '07:00', 'end' => '11:00'],
            ['start' => '13:00', 'end' => '17:00'],
        ];
    }
}
