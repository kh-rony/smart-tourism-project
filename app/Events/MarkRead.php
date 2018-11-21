<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class MarkRead implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $conversation_id;
    public $reader;
    public $read_at;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($conversation_id, $reader, $read_at)
    {
        $this->conversation_id = $conversation_id;
        $this->reader = $reader;
        $this->read_at = $read_at;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('conversation.' . $this->conversation_id);
    }

    public function broadcastWith()
    {
        return [
          'conversation_id' => $this->conversation_id,
          'reader' => $this->reader,
          'read_at' => $this->read_at->toDateTimeString()
        ];
    }
}
