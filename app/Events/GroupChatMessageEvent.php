<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;
use App\Models\Group;

class GroupChatMessageEvent implements ShouldBroadcast
{
    use SerializesModels;

    public $groupId;
    public $message;
    public $authorName;

    public function __construct($groupId, $message, $authorName)
    {
        $this->groupId = $groupId;
        $this->message = $message;
        $this->authorName = $authorName;
    }

    public function broadcastWith()
    {
        return [
            'message' => [
                'id' => $this->message->id,
                'content' => $this->message->content,
                // autres attributs de message si nécessaire
            ],
            'authorName' => $this->authorName,
        ];
    }


    public function broadcastOn()
    {
        return new PrivateChannel('group.' . $this->groupId);
    }
}
