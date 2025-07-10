<?php

namespace App\Helpers;

class GroupStatus
{
    public static function statusGroups(): array
    {
        return [
            '0' => [0, 1, 2], //Đang xử lý
            '1' => [0, 1, 2, 3, 4, 5, 6],//Đã gửi
            '2' => [3, 4], //Hoàn thành
        ];
    }
}