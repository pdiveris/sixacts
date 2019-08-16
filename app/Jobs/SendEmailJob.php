<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Mail;

/**
 * Class SendEmailJob
 *
 * @category Job
 * @package  App\Jobs
 * @author   Petros Diveris <petros@diveris.org>
 * @license  Apache 2.0
 * @link     https://www.diveris.org *
 * @see      https://itsolutionstuff.com/post/how-to-send-mail-using-queue-in-laravel-57example.html
 */
class SendEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $mailShot;
    
    private $destination;
    
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($mailShot, $destination = '')
    {
        //
        $this->mailShot = $mailShot;
        $this->destination = $destination;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->destination !== '') {
            Mail::to($this->destination)
                ->cc('q@diveris.org')
                ->send($this->mailShot)
            ;
        } else {
            Mail::to('q@diveris.org')
                ->send($this->mailShot)
            ;
        }
    }
}
