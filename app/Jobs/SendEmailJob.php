<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Mail;

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
        //
        // $email = new SendEmailTest();
        dump('SendEmailJob [handle]');
        dump(get_class($this->mailShot));
        Mail::to('q@diveris.org')->send($this->mailShot);
    }
}
