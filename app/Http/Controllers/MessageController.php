<?php

namespace App\Http\Controllers;

use App\Events\MessagePosted;
use Auth;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function showMessageForm(): View
    {
        return \View::make('messages.create', []);
    }

    public function post(Request $request)
    {
        // event(new MessagePosted($user, 'refresh'));

        event(new MessagePosted(Auth::user(), $request->get('message')));
        redirect('message');
    }
}
