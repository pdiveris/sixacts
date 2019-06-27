<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * Class UserCreated
 * @package App\Mail
 */
class UserCreated extends Mailable
{
    use Queueable, SerializesModels;

    protected $user;
    
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(\App\User $user)
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
        // return $this->view('view.name');
        return $this->from('mario@sixacts.org', 'Mario Vargas Llosa')
            ->text('emails.users.welcome_plain')
            ->view('emails.users.user_welcome')
            ->with([
                'name' => $this->user->name,
                'email' => $this->user->email,
            ]);
    }
}
