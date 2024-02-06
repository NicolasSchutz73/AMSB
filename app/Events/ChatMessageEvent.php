<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ChatMessageEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $groupId; // Ajout d'une propriété pour stocker l'ID du groupe
    public $message;

    /**
     * Create a new event instance.
     */
    public function __construct(string $groupId, string $message)
    {
        $this->groupId = $groupId; // Stocke l'ID du groupe
        $this->message = $message;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        // Utilise un canal privé pour chaque groupe en utilisant l'ID du groupe
        return [new PrivateChannel('group.' . $this->groupId)];
    }

    public function broadcastAs()
    {
        return 'chat-message';
    }
}
