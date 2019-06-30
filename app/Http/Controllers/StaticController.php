<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\Gravatar;

class StaticController extends Controller
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Contracts\View\View
     */
    public function content(Request $request)
    {
        $proposals = \App\Proposal::all();
        $categories = \App\Category::all();
        
        $view = $request->path();
        return \View::make('static.'.$view, ['proposals'=>$proposals, 'categories'=>$categories]);
    }
    
    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function home()
    {
        $proposals = \App\Proposal::all();
        $categories = \App\Category::all();
        return view('static.welcome', ['proposals'=>$proposals, 'categories'=>$categories]);
    }

    public static function authorLink(\App\Proposal $proposal)
    {
        if (null !== $proposal->user->display_name && '' !== $proposal->user->display_name) {
            return '@'.$proposal->user->display_name;
        }
        
        $names = explode(' ',$proposal->user->name);
        if (count($names) >= 2 ) {
            return '@'.strtolower(substr($names[0],0,1)).strtolower(substr($names[1],1));
        } else {
            return '@'.strtolower($proposal->user->name);
        }
        
    }
    
    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function forum()
    {
        $proposals = \App\Proposal::all();
        $categories = \App\Category::all();
        return view('forum.home', ['proposals'=>$proposals, 'categories'=>$categories]);
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
