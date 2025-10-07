<?php

namespace App\Events;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class RequestsCountChanged implements ShouldBroadcast
{
    public function __construct(public int $count) {}

    public function broadcastOn()
    {
        return new PrivateChannel('signatories');
    }
    public function broadcastAs()
    {
        return 'RequestsCountChanged';
    }
    public function broadcastWith(): array
    {
        return ['count' => $this->count];
    }
}