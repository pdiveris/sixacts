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
     * Get either a Gravatar URL or complete image tag for a specified email address.
     *
     * @param string $email The email address
     * @param int $s Size in pixels, defaults to 80px [ 1 - 2048 ]
     * @param string $d Default imageset to use [ 404 | mp | identicon | monsterid | wavatar ]
     * @param string $r Maximum rating (inclusive) [ g | pg | r | x ]
     * @param bool $img True to return a complete IMG tag False for just the URL
     * @param array $atts Optional, additional key/value attributes to include in the IMG tag
     * @return string String containing either just a URL or a complete image tag
     *
     * @see https://en.gravatar.com/site/implement/images/php/
     */
    public static function makeGravatar(string $email, $s = 80, $d = 'mp', $r = 'g', $img = false,  $atts = []): string
    {
        $url = 'https://www.gravatar.com/avatar/';
        $url .= md5( strtolower( trim( $email ) ) );
        $url .= "?s=$s&d=$d&r=$r";
        if ( $img ) {
            $url = '<img src="' . $url . '"';
            foreach ( $atts as $key => $val )
                $url .= ' ' . $key . '="' . $val . '"';
            $url .= ' />';
        }
        return $url;
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
