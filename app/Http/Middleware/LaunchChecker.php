<?php

namespace App\Http\Middleware;

use Closure;

class LaunchChecker
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $hostPart = 'https://'.$request->getHost().'/';
        $url = str_replace($hostPart, '', $request->getUri());
        if (in_array($url, ['register', 'propose', 'profile', 'login', 'signin']) && !env('LAUNCH')) {
            abort(404);
        }
        return $next($request);
    }
}
