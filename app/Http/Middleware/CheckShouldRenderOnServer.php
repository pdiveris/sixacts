<?php

namespace App\Http\Middleware;

use Closure;
use function GuzzleHttp\Psr7\str;

class CheckShouldRenderOnServer
{
    /* Google
      // Mozilla/5.0 (Linux; Android 6.0.1; Nexus 5X Build/MMB29P) AppleWebKit/537.36 (KHTML, like Gecko)
      // Chrome/41.0.2272.96 Mobile Safari/537.36 (compatible; Googlebot/2.1; +http://www.google.com/bot.html

     Microsoft
Mozilla/5.0 (Windows Phone 8.1; ARM; Trident/7.0; Touch; rv:11.0; IEMobile/11.0; NOKIA; Lumia 530) like Gecko
    (compatible; bingbot/2.0; +http://www.bing.com/bingbot.htm)

    Microsoft BingPreview
    Mozilla/5.0 (Windows Phone 8.1; ARM; Trident/7.0; Touch; rv:11.0; IEMobile/11.0; NOKIA; Lumia 530)
    like Gecko BingPreview/1.0b

    */

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        \Cache::set('ssr', false);
        return $next($request);
    }
}
