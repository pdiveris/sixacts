<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/**
 * Class SendUserEmail
 * @package App\Mail
 *
 * @see https://itsolutionstuff.com/post/how-to-send-mail-using-queue-in-laravel-57example.html
 */
class SendUserEmail extends Mailable
{
    use Queueable, SerializesModels;

    private User $user;

    private string $template;

    /**
     * SendUserEmail constructor.
     * Create a new message instance.
     *
     * @param User $user
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
            ->text('emails.users.' . $this->template . '_plain')
            ->view('emails.users.' . $this->template)
//            ->view('emails.users.user_welcome')
            ->with([
                'name' => $this->user->name,
                'email' => $this->user->email,
                'var' => 'Blurb..',
            ]);
    }
}
