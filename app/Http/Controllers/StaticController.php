<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\Gravatar;

class StaticController extends Controller
{
    /**
     * Temporary main proposal content result set
     *
     * @param \Illuminate\Http\Request $request Request
     *
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
     * Render the home view
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function home()
    {
        $proposals = \App\ProposalView::all();
        $categories = \App\Category::all();
        
        dump(\Cache::get('SSR', false));
        
        if (\Cache::get('SSR', false)) {
            return view(
                'static.ssr.welcome',
                ['proposals'=>$proposals, 'categories'=>$categories]
            );
        } else {
            return view(
                'static.welcome',
                ['proposals'=>$proposals, 'categories'=>$categories]
            );
        }
    }
    
    /**
     * Render the home view
     *
     * @return \Illuminate\View\View
     */
    public function homeRendered(): \Illuminate\View\View
    {
        $proposals = \App\ProposalView::all();
        $categories = \App\Category::all();
        echo \Cache::get('SSR');
        return view(
            'static.ssr.welcome',
            ['proposals'=>$proposals, 'categories'=>$categories]
        );
    }
    
    /**
     * Render the React view
     * It's the same as the home view usually but it serves as a testbed
     * for new features/fixes before they make their way to their actual place
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function react()
    {
        $proposals = \App\Proposal::all();
        $categories = \App\Category::all();
        $listData = json_encode(['Koko', 'Taylor', 'Tash', 'Μιχαλάκης', 'Lucia']);
        return view(
            'static.react',
            [
                'proposals'=>$proposals,
                'categories'=>$categories,
                'listData'=>$listData,
            ]
        );
    }
    
    /**
     * Compose the link to the author
     *
     * @param \App\Proposal $proposal proposal
     *
     * @return string
     */
    public static function authorLink(\App\ProposalView $proposal)
    {
        $displayName = $proposal->user->display_name;
        if (null !== $displayName && '' !== $displayName) {
            return '@'.$proposal->user->display_name;
        }
        $names = explode(' ', $proposal->user->name);
        if (count($names) >= 2) {
            return '@'.
                strtolower(substr($names[0], 0, 1)).
                strtolower(substr($names[1], 0));
        } else {
            return '@'.strtolower($proposal->user->name);
        }
    }
    
    /**
     * Try local avatar, social avatar, gravatar
     *
     * @param \App\User $user user
     *
     * @return string
     */
    public static function makeAvatar(\App\User $user): string
    {
        if (null !== $user->avatar && '' !== $user->avatar) {
            return url($user->avatar);
        } elseif (null !== $user->social_avatar && '' !== $user->social_avatar) {
            return $user->social_avatar;
        } else {
            return self::makeGravatar($user->email);
        }
    }
    
    /**
     * Get either a Gravatar URL or complete image tag for a specified email address.
     *
     * @param string $email The email address
     * @param int    $s     Size in pixels, defaults to 80px [ 1 - 2048 ]
     * @param string $d     Default imageset to use
     * @param string $r     Maximum rating (inclusive) [ g | pg | r | x ]
     * @param bool   $img   True to return a complete IMG tag False for just the URL
     * @param array  $atts  Optional, additional key/value attrs to include
     *
     * @return string String containing either just a URL or a complete image tag
     *
     * @see https://en.gravatar.com/site/implement/images/php/
     */
    public static function makeGravatar(
        string $email,
        $s = 80,
        $d = 'mp',
        $r = 'g',
        $img = false,
        $atts = []
    ): string {
        $url = 'https://www.gravatar.com/avatar/';
        $url .= md5(strtolower(trim($email)));
        $url .= "?s=$s&d=$d&r=$r";
        if ($img) {
            $url = '<img src="' . $url . '"';
            foreach ($atts as $key => $val) {
                $url .= ' ' . $key . '="' . $val . '"';
            }
            $url .= ' />';
        }
        return $url;
    }
    
    /**
     * Return forum view
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function forum()
    {
        $proposals = \App\Proposal::all();
        $categories = \App\Category::all();
        return view(
            'forum.home',
            ['proposals'=>$proposals, 'categories'=>$categories]
        );
    }
    
    /**
     * Return siginin view
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function signIn()
    {
        return \View::make('auth.signin', []);
    }
    
    /**
     * Return view User registration
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function userRegistration()
    {
        return \View::make('static.registration', []);
    }
}
