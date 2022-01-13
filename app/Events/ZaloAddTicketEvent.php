<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ZaloAddTicketEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $user;
    public $ticket;
    public $event_data;
    public $is_promotion;
    public $operation;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($user, $ticket, $event_data, $is_promotion)
    {
        $this->user = $user;
        $this->ticket = $ticket;
        $this->event_data = $event_data;
        $this->is_promotion = $is_promotion;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
