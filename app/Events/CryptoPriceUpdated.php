<?php

namespace App\Events;

use Illuminate\Log\Logger;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class CryptoPriceUpdated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $data;

    /**
     * Create a new event instance.
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('prices'),
        ];
    }

    /**
     * Get the data to broadcast.
     * 
     * @return array<string, mixed>
     */
    public function broadcastWith(): array
    {
        return [
            'pair' => $this->data['pair'],
            'exchange' => $this->data['exchange'],
            'average_price' => $this->data['average_price'],
            'change_percentage' => $this->data['change_percentage'],
            'change_direction' => $this->data['change_direction'],
            'timestamp' => now()->toDateTimeString()
        ];
    }
}
