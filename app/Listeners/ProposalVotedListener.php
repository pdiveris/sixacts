<?php

namespace App\Providers;

use App\Events\ProposalVotedEvent;
use App\Jobs\SendServerEvent;
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
    public function handle(ProposalVotedEvent $event): void
    {
        $dispatchJob = new SendServerEvent($event->data, $event->channel, $event->user);
        dispatch($dispatchJob);

    }
}
