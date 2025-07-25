<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CounterUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $serviceId;
    public $processing;
    public $waiting;
    public $remaining;

    public function __construct($serviceId, $processing, $waiting, $remaining)
    {
        $this->serviceId = $serviceId;
        $this->processing = $processing;
        $this->waiting = $waiting;
        $this->remaining = $remaining;
    }

    public function broadcastOn()
    {
        return new Channel('counter-channel');
    }

    public function broadcastAs()
    {
        return 'counter.updated';
    }

    public function broadcastWith()
    {
        return [
            'serviceId' => $this->serviceId,
            'processing' => $this->processing,
            'waiting' => $this->waiting,
            'remaining' => $this->remaining,
        ];
    }

}
