<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StaticController extends Controller
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Contracts\View\View
     */
    public function content(Request $request)
    {
        $view = $request->path();
        return \View::make('static.'.$view, []);
    }
    
    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function gladdys()
    {
        return \View::make('static.gladdys', []);
    }
    
    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function signIn()
    {
        return \View::make('auth.signin', []);
    }
    
    public function userRegistration()
    {
        return \View::make('static.registration', []);
    }
}
