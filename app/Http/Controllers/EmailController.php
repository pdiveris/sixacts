<?php

namespace App\Http\Controllers;

/**
 * Class EmailController
 *
 * @category Email
 * @package  App\Http\Controllers
 * @author   Petros Diveris <petros@diveris.org>
 * @license  Apache 2.0
 * @link     https://www.diveris.org
 */
class EmailController extends Controller
{
    /**
     * Queue an Email job
     *
     * @link http://tiny.cc/o89l9y
     * @see  QUEUE_CONNECTION=database in dotEnv
     *
     * @return void
     */
    public function sendEmail()
    {
        echo 'email sent';
    }
}
