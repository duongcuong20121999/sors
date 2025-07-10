<?php

namespace App\Services;

use App\Repositories\CitizenServiceRepository;
use Carbon\Carbon;
use stdClass;
use App\Models\Service;
use App\Helpers\GlobalSettings;
use App\Helpers\WorkingTimes;

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

        $service = Service::where('id', $data['service_id'])->first();

        // Calculate appointment date based on service process time        
        $duration = 1 * 24 * 60; // 1 day

        if (!$service->unlimited_duration || $service->unlimited_duration == 0) {
            // Add process hours and minutes
            $hours = intval(!$service->process_hours || $service->process_hours < 0 ? 0 : $service->process_hours);
            $minutes = intval(!$service->process_minutes || $service->process_minutes < 0 ? 0 : $service->process_minutes);
            $duration = $hours * 60 + $minutes;
        }

        $data['duration'] = $duration;

        $sq = $this->generateSequenceNumber($data);
        $data['created_date'] = now();
        $data['updated_date'] = now();
        $data['sequence_number'] = $sq['sequence_number'];
        $data['appointment_date'] = $sq['accepted_date'];

        $record = $this->citizenServiceRepository->create($data);
        $result = new stdClass();

        $result->record = $record;
        $result->count_ahead = $sq['count'];

        return $result;
    }

    public function getTimeSlotsOfDate($serviceId, $date, $duration)
    {
        $workingTimes = WorkingTimes::get();
        $startAmTime = $date->copy()->setTimeFromTimeString($workingTimes[0]['start']);
        $endAmTime = $date->copy()->setTimeFromTimeString($workingTimes[0]['end']);
        $startPmTime = $date->copy()->setTimeFromTimeString($workingTimes[1]['start']);
        $endPmTime = $date->copy()->setTimeFromTimeString($workingTimes[1]['end']);

        $slots = [];
        while ($startAmTime->isBefore($endAmTime)) {
            $slots[] = [$startAmTime->copy()->addMinutes($duration)->format('H:i'), 'picked' => 0, 'code' => ''];
            $startAmTime->addMinutes($duration);
        }
        while ($startPmTime->isBefore($endPmTime)) {
            $slots[] = [$startPmTime->copy()->addMinutes($duration)->format('H:i'), 'picked' => 0, 'code' => ''];
            $startPmTime->addMinutes($duration);
        }
        // echo "<pre> comparing of date $date";
        //Fill the slots with the bookings
        $allBooking = $this->citizenServiceRepository->findByAppointmentDate($serviceId, $date->format('Y-m-d'));
        foreach ($allBooking as $booking) {
            $bookingTime = Carbon::parse($booking->appointment_date)->setTimezone('Asia/Ho_Chi_Minh')->format('H:i');

            // print_r($bookingTime);

            foreach ($slots as &$slot) {

                // print_r($slot[0]);

                if ($slot[0] == $bookingTime) {
                    $slot['picked'] = 1;
                    $slot['code'] = $booking->sequence_number;

                    // echo "<pre> slot == bookingTime";
                    // print_r($slot);
                    // echo "</pre>";
                    break;
                }
            }
        }

        // print_r($slots);

        return [
            'slots' => $slots,
            'allBooking' => $allBooking->toArray()
        ];
    }

    public function cancelledBooking($id)
    {
        //TODO: update sequence number for all bookings
        $citizenService = $this->citizenServiceRepository->findFromAppointmentDate($id);
        $citizenService->status = 5;
        $citizenService->save();

        return $citizenService;
    }

    private function generateSequenceNumber($data): array
    {
        $serviceId = $data['service_id'];
        $order = $data['order'];
        $appointment_date = Carbon::parse($data['appointment_date']);
        $duration = $data['duration'];
        $globalSettings = GlobalSettings::get();
        $eta_start_appointment_minutes = $data['source'] == 'zalo' ? $duration + $globalSettings['moving_time'] : 0;

        //Initialize slots 
        $result = $this->getTimeSlotsOfDate($serviceId, $appointment_date, $duration);

        $slots = $result['slots'];
        $allBooking = $result['allBooking'];
        // echo "<pre> getTimeSlotsOf Date $appointment_date";
        // print_r($slots);

        //Validate the booking slot
        $availableSlot = $this->validateBookingSlot($serviceId, $slots, $duration, $appointment_date, $eta_start_appointment_minutes);

        if (!$availableSlot['success']) {
            return [
                'count' => 0,
                'sequence_number' => '',
                'accepted_time' => $availableSlot['available_slot'],
                'accepted_date' => $availableSlot['slot_date'],
                'success' => false,
                'message' => $availableSlot['message']
            ];
        }

        $date1 = Carbon::parse($appointment_date);
        $date2 = Carbon::parse($availableSlot['slot_date']);

        if (!$date1->isSameDay($date2)) {
            $allBooking = $this->citizenServiceRepository->findByAppointmentDate($serviceId, $date2->format('Y-m-d'));
        }

        $latestBooking = $allBooking[0] ?? null;

        // If no previous bookings exist, start with 1
        if (!$latestBooking) {
            $nextNumber = 1;
        } else {
            // Extract the numeric part from the sequence number (remove first number is line order)
            $nextNumber = intval(substr($latestBooking['sequence_number'], 1)) + 1;
        }

        $result['sequence_number'] = $order . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
        $result['accepted_date'] = $availableSlot['slot_date']->copy()->setTimeFromTimeString($availableSlot['available_slot'])->utc();
        $result['success'] = true;
        $result['message'] = 'Success';
        $result['count'] = count($allBooking);
        return $result;
    }

    private function validateBookingSlot($serviceId, $slots, $duration, $appointment_date, $eta_start_appointment_minutes)
    {

        // echo "<pre> Validating";
        // print_r(count($slots));
        // echo "</pre>";
        // print_r($duration);
        // print_r($appointment_date->format('Y-m-d H:i:s'));
        // print_r($eta_start_appointment_minutes);
        // echo "</pre>";
        $slots = collect($slots)->sortBy('0', SORT_DESC);

        $workingTimes = WorkingTimes::get();
        $startAmTime = $appointment_date->copy()->setTimeFromTimeString($workingTimes[0]['start']);
        $endAmTime = $appointment_date->copy()->setTimeFromTimeString($workingTimes[0]['end']);
        $startPmTime = $appointment_date->copy()->setTimeFromTimeString($workingTimes[1]['start']);
        $endPmTime = $appointment_date->copy()->setTimeFromTimeString($workingTimes[1]['end']);

        // Find an available slot
        foreach ($slots as $t => $slot) {

            $time  = $slot[0];
            $slotTime = $appointment_date->copy()->setTimeFromTimeString($time);
            $proposedTime = $appointment_date->copy()->setTimeFromTimeString($time)->addMinutes($eta_start_appointment_minutes);
            $now = now()->setTimezone('Asia/Ho_Chi_Minh');

            // echo "<pre> VERIFYING $time $t";
            // print_r($slot);
            // print_r($slotTime->isAfter($now));
            // print_r($proposedTime->isAfter($startAmTime));
            // print_r($proposedTime->isBefore($endPmTime));
            // print_r($proposedTime->isAfter($slotTime));
            // print_r($slot['picked'] == 0);
            // echo "</pre>";

            if (
                $slotTime->isAfter($now) &&
                $proposedTime->isAfter($startAmTime) &&
                $proposedTime->isBefore($endPmTime) &&
                $proposedTime->gte($slotTime) &&
                $slot['picked'] == 0
            ) {

                // echo "<pre> OK FOUND $time $slotTime $proposedTime $now";
                // print_r($slotTime);
                // print_r($proposedTime);
                // print_r($slot);
                // echo "</pre>";
                // die();

                return [
                    'success' => true,
                    'message' => 'Time slot is available',
                    'available_slot' => $time,
                    'slot_date' => $slotTime
                ];
            }
        }

        // Find an available slot in the future, Start of next day
        $nextDay = $appointment_date->copy()->addDay()->setTimeFromTimeString($workingTimes[0]['start'])->addMinutes($duration + 11); // 15 minutes to avoid the same time slot
        $result = $this->getTimeSlotsOfDate($serviceId, $nextDay, $duration);
        $slots = $result['slots'];

        // echo "<pre> Second call getTimeSlotsOfDate $nextDay";
        // print_r($nextDay);
        // print_r($slots);
        // echo "</pre>";
        // die();
        return $this->validateBookingSlot($serviceId, $slots, $duration, $nextDay, $eta_start_appointment_minutes);
    }
}
