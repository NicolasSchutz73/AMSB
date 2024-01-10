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

    public function __construct($groupId, $message)
    {
        $this->groupId = $groupId;
        $this->message = $message;
    }

    public function broadcastOn(): PrivateChannel
    {
        return new PrivateChannel('group.' . $this->groupId);
    }
}
