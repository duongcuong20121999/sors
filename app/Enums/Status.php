<?php

namespace App\Enums;

enum Status: int
{
    case New = 0;
    case Reviewing = 1;
    case InProgress = 2;
    case Done = 3;
    case Closed = 4;
    case Rejected = 5;
    case Cancelled = 6;

    public function label(): string
    {
        return match($this) {
            self::New => 'New',
            self::Reviewing => 'Reviewing',
            self::InProgress => 'In Progress',
            self::Done => 'Done',
            self::Closed => 'Closed',
            self::Rejected => 'Rejected',
            self::Cancelled => 'Cancelled',
        };
    }
}