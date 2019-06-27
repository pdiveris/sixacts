<?php
    
    namespace App\Jobs;
    
    use App\User;
    use Illuminate\Bus\Queueable;
    use Illuminate\Queue\SerializesModels;
    use Illuminate\Queue\InteractsWithQueue;
    use Illuminate\Contracts\Queue\ShouldQueue;
    use Illuminate\Foundation\Bus\Dispatchable;
    use Illuminate\Support\Facades\Mail;
    use App\Mail\UserCreated;
    
    class SendEmailJob implements ShouldQueue
    {
        use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
        
        public $user;
        
        public $mailShot;
        
        /**
         * Create a new job instance.
         *
         * @return void
         */
        public function __construct($user, $mailShot)
        {
            // $this->user = $user;
            $this->mailShot = $mailShot;
            dump($mailShot);
        }
        
        /**
         * Execute the job.
         *
         * @return void
         */
        public function handle()
        {
            // Mail::to('petros@diveris.org')->send(new UserCreated($this->user));
            Mail::to('petros@diveris.org')->send($this->mailShot);
        }
}
