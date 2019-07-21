<?php

namespace App\Http\Controllers;

use App\Proposal;
use App\RandomNames;
use Illuminate\Http\Request as Request;
use Symfony\Component\HttpFoundation\StreamedResponse as StreamedResponse;

class ServerSideEventsController extends Controller
{
    protected $request;
    
    public function __construct(Request $request)
    {
        $this->request = $request;
    }
    
    /**
     * Get a random bunch frin the names table
     *
     * @param int $limit
     * @return array
     */
    public static function randomData($limit = 15): array
    {
        $randmoms = RandomNames::where('id', '>', 0)
            ->limit($limit)
            ->get()
        ;
        return $randmoms;
    }
    
    /**
     * SSE "server" implementation.
     *
     * @return string
     */
    public function server()
    {
        $request = $this->request;
        $response = new StreamedResponse(function () use ($request) {
            while (true) {
                $randoms = RandomNames::where('id', '>', 0)
                            ->limit(15)
                            ->get();

                echo 'data: ' . json_encode($randoms) . "\n\n";
                ob_flush();
                flush();
                usleep(2000000);
            }
        });
        $response->headers->set('Content-Type', 'text/event-stream');
        $response->headers->set('X-Accel-Buffering', 'no');
        $response->headers->set('Cach-Control', 'no-cache');
        
        return $response;
    }
    
    public function test()
    {
        echo 'Setting keys..';
        \Cache::put('broadcast', 1, 1800);

        \Cache::rememberForever('broadcast_king', function () {
                return 1;
        });
        
        return '';
    }

    /**
     * SSE "server" implementation.
     *
     * @return string
     */
    public function semaphore()
    {
        $request = $this->request;
        
        
        $response = new StreamedResponse(function () use ($request) {
            while (true) {
                $data = '';
                $broadcast = \Cache::get('broadcast', null);
                if (1 == $broadcast) {
                    $randoms = RandomNames::where('id', '>', 0)
                        ->limit(15)
                        ->get()
                    ;
    
                    \Cache::forget('broadcast');
                    if (1===2) {
                        $data = json_encode($randoms);
                    }
                }
                echo 'data: ' . $data . "\n\n";
                dump('Falling asleep - broadcast: '. $broadcast. ' | flushed '.strlen($data) . ' bytes');

                ob_flush();
                flush();

                usleep(9000000);
            }
        });
        $response->headers->set('Content-Type', 'text/event-stream');
        $response->headers->set('X-Accel-Buffering', 'no');
        $response->headers->set('Cache-Control', 'no-cache');
        dump('Writing headers?');
        return $response;
    }
    
    /**
     * SSE "server" implementation.
     *
     * @return string
     */
    public function servere()
    {
        $request = $this->request;
        $response = new StreamedResponse(function () use ($request) {
            while (true) {
                echo 'data: ' . json_encode(Proposal::all()) . "\n\n";
                ob_flush();
                flush();
                usleep(2000000);
            }
        });
        $response->headers->set('Content-Type', 'text/event-stream');
        $response->headers->set('X-Accel-Buffering', 'no');
        $response->headers->set('Cach-Control', 'no-cache');
        
        return $response;
    }
    
    /**
     * Our poor vusto,err
     *
     * @return string
     */
    public function client()
    {
        return 'kongo';
    }
}
