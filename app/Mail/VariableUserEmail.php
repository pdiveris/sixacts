<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

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
     * @var User
     */
    private User $user;

    private string $template;

    private array $extraData = [];

    /**
     * VariableUserEmail constructor.
     * Create a new message instance.
     *
     * @param User $user
     * @param string $template
     * @param array $extraData
     * @return void
     */
    public function __construct(User $user, string $template, array $extraData = [])
    {
        $this->user = $user;

        $this->extraData = $extraData;

        // e.g. user_welcome
        $this->template = $template;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(): static
    {
        $data = $this->extraData;
        $data['email'] = $this->user->email;
        $data['name'] = $this->user->name;

        return $this->to($this->user->email)
            ->text('emails.users.'. $this->template . '_plain')
            ->subject($data['subject'] ?? 'Welcome to Six Acts')
            ->view('emails.users.'.  $this->template)
            ->with($data);
    }
}
