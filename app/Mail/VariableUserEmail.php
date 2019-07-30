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
    
    private $extraData = [];
    
    /**
     * VariableUserEmail constructor.
     * Create a new message instance.
     *
     * @param \App\User $user
     * @param string $template
     * @param array $extraData
     * @return void
     */
    public function __construct(User $user, string $template, array $extraData = [])
    {
        $this->user = $user;
        $this->extraData = $extraData;
        $this->template = $template;    // e.g. user_welcome
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $data = $this->extraData;
        $data['email'] = $this->user->email;
        $data['name'] = $this->user->name;
        
        return $this->to($this->user->email)
            ->text('emails.users.'.$this->template.'_plain')
            ->subject($data['subject'] ?? 'Welcome to Six Acts')
            ->view('emails.users.'.$this->template)
            ->with($data);
    }
}
