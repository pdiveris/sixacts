<?php

namespace App\Mail;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * Class VariableUserEmail
 * @package App\Mail
 *
 * @see https://itsolutionstuff.com/post/how-to-send-mail-using-queue-in-laravel-57example.html
 */
class VariableUserEmail extends Mailable
{
    use Queueable, SerializesModels;
    
    /**
     * @var \App\User
     */
    private $user;
    
    private $template;
    
    /**
     * VariableUserEmail constructor.
     * Create a new message instance.
     *
     * @param \App\User $user
     * @param string $template
     * @return void
     */
    public function __construct(User $user, string $template)
    {
        $this->user = $user;
        $this->template = $template;    // e.g. user_welcome
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->to($this->user->email)
            ->text('emails.users.'.$this->template.'_plain')
            ->view('emails.users.'.$this->template)
            ->with([
                'name' => $this->user->name,
                'email' => $this->user->email,
                'var' => 'Blurb..',
            ]);
    }
}
