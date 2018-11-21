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

class AddUser implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $addedUsers;
    public $conversation_id;

    /**
     * Create a new event instance.
     *
     * @param $addedUsers
     * @param $conversation_id
     */
    public function __construct($addedUsers, $conversation_id)
    {
        $this->addedUsers = $addedUsers;
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
            'addedUsers' => $this->addedUsers,
            'conversation_id' => $this->conversation_id
        ];
    }
}
