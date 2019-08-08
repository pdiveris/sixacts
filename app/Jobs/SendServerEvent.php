<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use GuzzleHttp\Client;

class SendServerEvent implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $payload;
    
    private $channel;
    
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($payload, $channel)
    {
        $this->payload = $payload;
        
        $this->channel = $channel;
    }

    /**
     * Execute the job.
     *
     * @return void
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function handle()
    {
        \App\Http\Controllers\ServerSideEventsController::fire($this->payload, $this->channel);
//        \App\Http\Controllers\ServerSideEventsController::fire($this->payload, 'messages');
    }
}
