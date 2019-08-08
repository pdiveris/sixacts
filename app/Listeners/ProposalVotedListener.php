<?php

namespace App\Providers;

use App\Events\ProposalVotedEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ProposalVotedListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  ProposalVotedEvent  $event
     * @return void
     */
    public function handle(ProposalVotedEvent $event)
    {
        $dispatchJob = new \App\Jobs\SendServerEvent($event->data, $event->channel, $event->user);
        dispatch($dispatchJob);
        
    }
}
