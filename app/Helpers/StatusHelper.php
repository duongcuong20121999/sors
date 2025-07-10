<?php

use App\Enums\Status;

if (!function_exists('status_label')) {
    function status_label(int $status): string
    {
        try {
            return Status::from($status)->label();
        } catch (ValueError) {
            return 'Unknown';
        }
    }
}

if (!function_exists('status_options')) {
    function status_options(): array
    {
        return collect(Status::cases())
            ->mapWithKeys(fn ($case) => [$case->value => $case->label()])
            ->toArray();
    }
}

function checkStatusClass($status) {
    return match($status) {
        0 => 'new',
        1 => 'reviewing',
        2 => 'inprogress',
        3 => 'done' ,    
        4 => 'closed',
        5 => 'rejected',
        6 => 'cancelled'  

    };
}

function listStatus($status) {
    return match($status) {
        0 => 'new',
        1 => 'reviewing',
        2 => 'inprogress',
        3 => 'done' ,    
        4 => 'closed',
        5 => 'rejected',
        6 => 'cancelled'  

    };
}