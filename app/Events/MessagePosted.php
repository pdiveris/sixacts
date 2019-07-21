<?php

namespace App\Events;

use App\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class MessagePosted implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    protected $user;
    
    protected $message;
    
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(User $user, $message)
    {
        dump('MessagePosted constructor');
        dump($message);
        $this->user = $user;
        $this->message = $message;
    }
    
    /**
     * Broadcast with data
     * This must always be an array.
     * It will be parsed with JSON.parse()
     *
     * @return array
     */
    public function broadcastWith()
    {
        // This must always be an array. Since it will be parsed with json_encode()
        return [
            'user' => $this->user->name,
            'message' => $this->message,
        ];
    }
    
    public function broadcastAs()
    {
        return 'NewMessage';
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('messages');
    }
}
