<?php

namespace App\Http\Controllers;

use App\Proposal;
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
     * SSE "server" implementatio.
     *
     * @return string
     */
    public function server()
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
