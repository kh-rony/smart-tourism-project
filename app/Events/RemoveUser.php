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

class RemoveUser implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $removedUsers;
    public $conversation_id;

    /**
     * Create a new event instance.
     *
     * @param $removedUsers
     * @param $conversation_id
     */
    public function __construct($removedUsers, $conversation_id)
    {
        $this->removedUsers = $removedUsers;
        $this->conversation_id = $conversation_id;
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
          'removedUsers' => $this->removedUsers,
          'conversation_id' => $this->conversation_id
        ];
    }
}
