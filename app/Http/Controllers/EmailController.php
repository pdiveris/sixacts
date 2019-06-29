<?php

namespace App\Http\Controllers;


class EmailController extends Controller
{
    /**
     * Queue an Email job
     * https://appdividend.com/2017/12/21/laravel-queues-tutorial-example-scratch/#Laravel_Queue_Send_Email_Example
     * @see QUEUE_CONNECTION=database in dotEnv
     */
    public function sendEmail()
    {
    
        echo 'email sent';
        
    }
}
