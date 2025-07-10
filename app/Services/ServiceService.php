<?php


// app/Services/ServiceService.php
namespace App\Services;
use App\Repositories\ServiceRepository;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


class ServiceService {
    protected $serviceRepository;
    public function __construct(ServiceRepository $serviceRepository) { $this->serviceRepository = $serviceRepository; }
    public function getAll() { return $this->serviceRepository->all(); }
    public function getById($id) { return $this->serviceRepository->find($id); }
    public function create(array $data) { return $this->serviceRepository->create($data); }
    public function update($id, array $data) { return $this->serviceRepository->update($id, $data); }
    public function delete($id) { return $this->serviceRepository->delete($id); }

    
    private function generateSequenceNumber($serviceId): string
    {
        $today = Carbon::today()->toDateString();

        $last = CitizenService::where('service_id', $serviceId)
            ->whereDate('created_date', $today)
            ->orderByDesc('sequence_number')
            ->first();

        if ($last) {
            $lastNumber = intval(substr($last->sequence_number, 1));
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1;
        }

        // Format: A001, A002, ..., A999
        return 'S' . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);
    }
}
