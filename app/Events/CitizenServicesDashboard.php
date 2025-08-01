<?php

namespace App\Events;

use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\Channel;

class CitizenServicesDashboard implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $serviceId;
    public $citizens;
    public $remaining;

    public function __construct($serviceId, $citizens, $remaining)
    {
        $this->serviceId = $serviceId;
        $this->citizens = $citizens;
        $this->remaining = $remaining;
    }

    public function broadcastOn()
    {
        return new Channel('citizen-services'); // Public channel
    }

    public function broadcastAs()
    {
        return 'citizen.services';
    }

    public function broadcastWith()
    {
        return [
            'serviceId' => $this->serviceId,
            'citizens' => $this->citizens,
            'remaining' => $this->remaining,
            
        ];
    }

}
