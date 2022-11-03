<?php

namespace App\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Events\UserCreatedEvent as UserCreatedEvent;

/**
 * Class UserCreatedListener
 *
 * @category Listener
 * @package  App\Listeners
 * @author   Petros Diveris <petros@diveris.org>
 * @license  Apache 2.0
 * @link     https://www.diveris.org
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
     * @param UserCreatedEvent $event event
     *
     * @return void
     */
    public function handle($event): void
    {
        //
    }
}
