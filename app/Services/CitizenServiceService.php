<?php

namespace App\Services;

use App\Repositories\CitizenServiceRepository;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use stdClass;

class CitizenServiceService
{
    protected $citizenServiceRepository;
    public function __construct(CitizenServiceRepository $citizenServiceRepository)
    {
        $this->citizenServiceRepository = $citizenServiceRepository;
    }
    public function getAll()
    {
        return $this->citizenServiceRepository->all();
    }
    public function getById($id)
    {
        return $this->citizenServiceRepository->find($id);
    }
    // public function create(array $data) { return $this->citizenServiceRepository->create($data); }
    public function update($id, array $data)
    {
        return $this->citizenServiceRepository->update($id, $data);
    }
    public function delete($id)
    {
        return $this->citizenServiceRepository->delete($id);
    }

    public function create(array $data)
    {
        $sq = $this->generateSequenceNumber($data);
        $data['created_date'] = now();
        $data['sequence_number'] = $sq['sequence_number'];

        $record = $this->citizenServiceRepository->create($data);
        $result = new stdClass();

        $result->record = $record;
        $result->count_ahead = $sq['count'];

        return $result;
    }

    private function generateSequenceNumber($data): array
    {
        $serviceId = $data['service_id'];
        $order = $data['order'];
        $date = Carbon::parse($data['created_date'])->format('Y-m-d');

        $result = [
            'count' => 0,
            'sequence_number' => ''
        ];

        try {

            // Get the latest booking for this service on this date
            $allBooking = $this->citizenServiceRepository->findByCreatedDate($serviceId, $date);
            $result['count'] = count($allBooking);
            $latestBooking = $allBooking[0];

            // If no previous bookings exist, start with 1
            if (!$latestBooking) {
                $nextNumber = 1;
            } else {
                // Extract the numeric part from the sequence number (remove first number is line order)
                $nextNumber = intval(substr($latestBooking->sequence_number, 1)) + 1;
            }

            //'S' . $order . 
            // Format with leading zeros and 'S' prefix
            $result['sequence_number'] = $order . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
        } catch (\Exception $e) {
            // Log the error
            \Log::error('Error generating sequence number: ' . $e->getMessage());
            // Return a fallback sequence number
            $result['sequence_number'] = $order . str_pad(1, 3, '0', STR_PAD_LEFT);
        }

        return $result;
    }
}
