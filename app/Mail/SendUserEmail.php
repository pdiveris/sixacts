<?php

namespace App\Mail;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendUserEmail extends Mailable
{
    use Queueable, SerializesModels;

    private $user;
    
    private $template;
    
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user,string $template)
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
        dump('SendUserEmail.build');
        // return $this->view('view.name');
        return $this->to($this->user->email)
//            ->text('emails.users.'.$this->template.'_plain')
//            ->view('emails.users.'.$this->template)
            ->view('emails.users.user_welcome')
            ->with([
                'name' => $this->user->name,
                'email' => $this->user->email,
            ]);
    }
}
