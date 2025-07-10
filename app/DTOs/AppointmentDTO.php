<?php

namespace App\DTOs;

use Carbon\CarbonInterface;

class AppointmentDTO
{
    public readonly int $citizen_id;
    public readonly int $service_id;
    public readonly CarbonInterface $appointment_date;

    public function __construct(
        int $citizen_id,
        int $service_id,
        CarbonInterface $appointment_date
    ) {
        $this->citizen_id = $citizen_id;
        $this->service_id = $service_id;
        $this->appointment_date = $appointment_date;
    }

    public static function fromArray(array $data): self
    {
        return new self(
            (int) $data['citizen_id'],
            (int) $data['service_id'],
            \Carbon\Carbon::parse($data['appointment_date'])
        );
    }
}
