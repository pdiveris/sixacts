<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ProposalVotedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $data;

    public string $channel;

    public User $user;

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
    public function broadcastOn(): Channel|array
    {
        // return new PrivateChannel('channel-name');
        return new Channel('messages');
    }
}
