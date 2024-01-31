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
    public $firstname;
    public $lastname;


    public function __construct($groupId, $message, $firstname,$lastname)
    {
        $this->groupId = $groupId;
        $this->message = $message;
        $this->firstname = $firstname;
        $this->lastname = $lastname;  }

    public function broadcastWith()
    {
        return [
            'message' => [
                'id' => $this->message->id,
                'content' => $this->message->content,
                'firstname' => $this->firstname,
                'lastname' => $this->lastname,],

        ];
    }


    public function broadcastOn()
    {
        return new PrivateChannel('group.' . $this->groupId);
    }
}
