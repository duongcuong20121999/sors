<?php

use Carbon\Carbon;

define('STATUS_C_S_PENDING', 0);
define('STATUS_C_S_REVIEWED', 1);
define('STATUS_C_S_IN_PROGRESS', 2);
define('STATUS_C_S_COMPLETED', 3);
define('STATUS_C_S_CLOSE', 4);
define('STATUS_C_S_REJECTED', 5);
define('STATUS_C_S_CANCELLED', 6);

function getStatusCSName($status)
{
    switch ($status) {
        case STATUS_C_S_PENDING:
            return '<span class="badge bg-danger">Chờ xử lý</span>';
        case STATUS_C_S_REVIEWED:
            return '<span class="badge bg-info">Đã tiếp nhận</span>';
        case STATUS_C_S_IN_PROGRESS:
            return '<span class="badge bg-warning">Đang xử lý</span>';
        case STATUS_C_S_COMPLETED:
            return '<span class="badge bg-success">Hoàn thành</span>';
        case STATUS_C_S_CLOSE:
            return '<span class="badge bg-secondary">Đã đóng</span>';
        case STATUS_C_S_REJECTED:
            return '<span class="badge bg-danger">Từ chối</span>';
        case STATUS_C_S_CANCELLED:
            return '<span class="badge bg-dark">Đã hủy</span>';
        default:
            return 'Không xác định';
    }
}

if (!function_exists('getAllStatusCS')) {
    function getAllStatusCS()
    {
        return [
            STATUS_C_S_PENDING => 'Chờ xử lý',
            STATUS_C_S_REVIEWED => 'Đã tiếp nhận',
            STATUS_C_S_IN_PROGRESS => 'Đang xử lý',
            STATUS_C_S_COMPLETED => 'Hoàn thành',
            STATUS_C_S_CLOSE => 'Đã đóng',
            STATUS_C_S_REJECTED => 'Từ chối',
            STATUS_C_S_CANCELLED => 'Đã hủy',
        ];
    }
}

function getButtonNextStep($data)
{
    $status = $data->status ?? null;
    $id = $data->id ?? null;

    switch ($status) {
        case STATUS_C_S_PENDING:
            return '<button type="button" class="custom-btn-1" data-id="'.$id.'">Gọi xử lý</button>';
        case STATUS_C_S_REVIEWED:
            return '<button type="button" class="custom-btn-1" data-id="'.$id.'">Gọi xử lý</button>';
        case STATUS_C_S_IN_PROGRESS:
            return '<button type="button" class="custom-btn-1" data-id="'.$id.'">Hoàn thành</button>';
        case STATUS_C_S_COMPLETED:
            return '<button type="button" class="custom-btn-1" data-id="'.$id.'">Đóng hồ sơ</button>';
        case STATUS_C_S_CLOSE:
            return '<button type="button" class="custom-btn-1" data-id="'.$id.'">Đã đóng</button>';
        case STATUS_C_S_REJECTED:
            return '<button type="button" class="custom-btn-1" data-id="'.$id.'">Từ chối</button>';
        case STATUS_C_S_CANCELLED:
            return '<button type="button" class="custom-btn-1" data-id="'.$id.'">Đã hủy</button>';
        default:
            return '';
    }
}

function getNextStatusID($status){
    switch ($status) {
        case STATUS_C_S_PENDING:
            return STATUS_C_S_IN_PROGRESS;
        case STATUS_C_S_REVIEWED:
            return STATUS_C_S_IN_PROGRESS;
        case STATUS_C_S_IN_PROGRESS:
            return STATUS_C_S_COMPLETED;
        case STATUS_C_S_COMPLETED:
            return STATUS_C_S_CLOSE;
        case STATUS_C_S_CLOSE:
            return null;
        case STATUS_C_S_REJECTED:
            return null;
        case STATUS_C_S_CANCELLED:
            return null;
        default:
            return null;
    }
}

function convertDateToVn($dateTime, $format = 'H:s d/m/Y')
{
    // Chuyển đổi thời gian từ UTC sang giờ Việt Nam
    $vnTime = Carbon::parse($dateTime)->setTimezone('Asia/Ho_Chi_Minh');

    // Trả về kết quả với định dạng mong muốn
    return $vnTime->format($format);
}
