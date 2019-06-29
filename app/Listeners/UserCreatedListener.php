<?php

namespace App\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Events\UserCreatedEvent as UserCreatedEvent;

/**
 * Class UserCreatedListener
 * @package App\Listeners
 *
 * @see https://laravel-news.com/laravel-model-events-getting-started
 */
class UserCreatedListener
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
     * @param  UserCreatedEvent $event
     * @return void
     */
    public function handle($event)
    {
        //
    }
}
