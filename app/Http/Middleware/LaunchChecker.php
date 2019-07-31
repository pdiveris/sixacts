<?php

namespace App\Http\Middleware;

use Closure;

class LaunchChecker
{
    /**
     * Handle an incoming request.
     * Disable access to endpoints only accessible after launching.
     * Simply throw a 404.
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
