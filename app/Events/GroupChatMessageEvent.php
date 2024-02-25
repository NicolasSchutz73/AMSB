<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;
use App\Models\Group;
use Illuminate\Support\Facades\Log;

class GroupChatMessageEvent implements ShouldBroadcast
{
    use SerializesModels;

    public $groupId;
    public $message;
    public $firstname;
    public $lastname;
    public $filePath; // Ajouté
    public $fileType; // Ajouté


    public function __construct($groupId, $message, $firstname,$lastname, $filePath = null, $fileType = null)
    {
        $this->groupId = $groupId;
        $this->message = $message;
        $this->firstname = $firstname;
        $this->lastname = $lastname;
        $this->filePath = $filePath ;
        $this->fileType = $fileType; }

    public function broadcastWith()
    {
        return [
            'message' => [
                'id' => $this->message->id,
                'content' => $this->message->content,
                'firstname' => $this->firstname,
                'lastname' => $this->lastname,
                'filePath' => $this->filePath,
                'fileType' => $this->fileType,]

        ];
    }


    public function broadcastOn()
    {
        return new PrivateChannel('group.' . $this->groupId);
    }
}
