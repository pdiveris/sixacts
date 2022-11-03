<?php
/**
 * VerifyUser Model
 *
 * User Verifications data model
 *
 * PHP version 7.2
 *
 * LICENSE: This source file is subject to version 2.0 of the Apache License
 * that is available through the world-wide-web at the following URI:
 * https://www.apache.org/licenses/LICENSE-2.0.
 *
 * @category  Model
 * @package   Auth
 * @author    Petros Diveris <petros@diveris.org>
 * @copyright 2019 Bentleyworks
 * @license   http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version   GIT: $Id$
 * @link      https://github.com/pdiveris/sixproposals/blob/master/app/Http/Controllers/Auth/LoginController.php
 * @see       Six Acts
 */
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/**
 * Class VerifyMail
 * @package App\Mail
 */
class VerifyMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var \App\Models\User user
     */
    public $user;

    /**
     * @var string token
     */
    public $token;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(\App\Models\User $user, string $token)
    {
        $this->user = $user;
        $this->token = $token;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $data = [
            'email'=>$this->user->email,
            'name'=>$this->user->name,
            'token' => $this->token,
        ];
        return $this->view('emails.users.user_verify', $data);
    }
}
