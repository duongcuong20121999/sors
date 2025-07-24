<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow; // dùng nếu muốn gửi ngay
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class QueueUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $serviceId;
    public $queueData;
    public $remaining;



    public function __construct($serviceId, $queueData, $remaining)
{
    $this->serviceId = $serviceId;
    $this->queueData = $queueData;
    $this->remaining = $remaining;
}

    public function broadcastOn()
    {
        return new Channel('service-queue');
    }

    public function broadcastAs()
    {
        return 'queue.updated';
    }

    public function broadcastWith()
{
    return [
        'serviceId' => $this->serviceId,
        'queueData' => $this->queueData,
        'remaining' => $this->remaining,
    ];
}
}
