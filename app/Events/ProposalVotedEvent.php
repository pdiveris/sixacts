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

    public $channel;

    public $user;

    /**
     * Create a new event instance.
     *
     * @return void
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
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        // return new PrivateChannel('channel-name');
        return new Channel('messages');
    }
}
