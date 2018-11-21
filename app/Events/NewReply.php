<?php

namespace App\Events;

use App\Model\Forum\Reply;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class NewReply implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $reply;
    public $parentReplyId;
    public $questionId;

    /**
     * Create a new event instance.
     *
     * @param Reply $reply
     * @param $parentReplyId
     * @param $questionId
     */
    public function __construct(Reply $reply, $parentReplyId, $questionId)
    {
        $this->reply = $reply;
        $this->parentReplyId = $parentReplyId;
        $this->questionId = $questionId;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('forum.' . $this->questionId);
    }

    public function broadcastWith()
    {
        return [
            'parentReplyId' => $this->parentReplyId,
            'id' => $this->reply->id,
            'body' => $this->reply->body,
            'created_at' => $this->reply->created_at->toDateTimeString(),
            'user' => [
                'id' => $this->reply->user->id,
                'name' => $this->reply->user->name,
            ],
            'photos' => $this->reply->photos
        ];
    }
}
