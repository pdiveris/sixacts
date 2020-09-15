<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use App\User;

class ProposalVotedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $data;

    public $channel;

    public $user;

    /**
     * Create a new event instance.
     *
     * @param $data
     * @param  string  $channel
     * @param  User  $user
     */
    public function __construct($data, string $channel, User $user)
    {
        $this->data = $data;
        $this->channel = $channel;
        $this->user = $user;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        // return new PrivateChannel('channel-name');
        return new Channel('messages');
    }
}
