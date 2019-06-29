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
 * @package App\Jobs
 *
 * @see https://itsolutionstuff.com/post/how-to-send-mail-using-queue-in-laravel-57example.html
 */
class SendEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $mailShot;
    
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($mailShot)
    {
        //
        $this->mailShot = $mailShot;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Mail::to('q@diveris.org')->send($this->mailShot);
    }
}
