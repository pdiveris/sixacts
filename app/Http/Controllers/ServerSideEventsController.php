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
     * Fire message over the pipes
     *
     * @param $payload
     * @param string $channel
     * @param $user
     * @return \Psr\Http\Message\ResponseInterface
     */
    public static function fire($payload, string $channel = 'asty', $user)
    {
        $client = new \GuzzleHttp\Client(["base_uri" => env('APP_URL'), 'verify' => false ]);
        
        $options = [
            'json' => $payload
        ];
        $response = $client->post('/pub?id='.$channel, $options);
        return $response;
    }
}
