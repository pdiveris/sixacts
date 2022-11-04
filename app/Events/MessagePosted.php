<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessagePosted implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    protected $user;

    protected $message;

    /**
     * Create a new event instance.
     * @param User $user
     * @param $message
     *
     * @return void
     */
    public function __construct(User $user, $message)
    {
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
    public function broadcastWith(): array
    {
        // This must always be an array. Since it will be parsed with json_encode()
        return [
            'user' => $this->user->name,
            'message' => $this->message,
        ];
    }

    /**
     * The event name.
     * Prefix with a dot on the client
     * i.e. NewMessage
     *
     * @return string
     */
    public function broadcastAs(): string
    {
        return 'NewMessage';
    }

    /**
     * Get the channels the event should broadcast on.
     * It's not respected.
     *
     * @see (https://stackoverflow.com/questions/43066633/laravel-echo-does-not-listen-to-channel-and-events
     *
     * @return Channel|array
     */
    public function broadcastOn(): Channel|array
    {
        return new Channel('messages');
    }
}
