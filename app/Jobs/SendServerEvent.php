<?php

namespace App\Jobs;

use App\Http\Controllers\ServerSideEventsController;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendServerEvent implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $payload;

    private $channel;

    private $user;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($payload, $channel, User $user)
    {
        $this->payload = $payload;
        $this->channel = $channel;
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        ServerSideEventsController::fire($this->payload, $this->channel, $this->user);
    }
}
