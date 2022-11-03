<?php

namespace App\Jobs;

use App\Mail\SendMailable;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UserCreatedJob extends SendMailable implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('mario@sixacts.org', 'Λάκης Μποθγάτσας')
            ->to($this->user->email)
            ->cc('pdiveris@gmail.com')
            ->text('emails.users.welcome_plain')
            ->view('emails.users.user_welcome')
            ->with([
                'name' => $this->user->name,
                'email' => $this->user->email,
            ]);
    }
}
