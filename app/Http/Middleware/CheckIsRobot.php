<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use function GuzzleHttp\Psr7\str;

class CheckIsRobot
{
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next): mixed
    {
        $headers = $request->headers;
        if ($headers->has('user-agent')) {
            $agent = $headers->get('user-agent');
            if (strpos($agent, 'bot') === true) {
            }
        }
        return $next($request);
    }
}
