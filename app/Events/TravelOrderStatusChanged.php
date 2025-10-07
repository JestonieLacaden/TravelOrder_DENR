<?php

namespace App\Events;

use App\Models\TravelOrder;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class TravelOrderStatusChanged implements ShouldBroadcast
{
    public function __construct(public TravelOrder $to) {}

    public function broadcastOn()
    {
        return new PrivateChannel('users.' . $this->to->employeeid);
    }

    public function broadcastAs()
    {
        return 'TravelOrderStatusChanged';
    }

    public function broadcastWith(): array
    {
        return [
            'id'           => $this->to->id,
            'is_approve1'  => (bool) $this->to->is_approve1,
            'is_approve2'  => (bool) $this->to->is_approve2,
            'approved_code' => optional($this->to->approved)->travelorderid,
        ];
    }
}