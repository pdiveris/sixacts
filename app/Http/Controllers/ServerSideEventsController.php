<?php

namespace App\Http\Controllers;

use App\Models\RandomNames;
use GuzzleHttp\Client;
use Illuminate\Http\Request as Request;
use Psr\Http\Message\ResponseInterface;

class ServerSideEventsController extends Controller
{
    protected Request $request;

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
    public static function randomData(int $limit = 15): array
    {
        return RandomNames::where('id', '>', 0)
            ->limit($limit)
            ->get();
    }

    /**
     * Fire message over the pipes
     *
     * @param $payload
     * @param string $channel
     * @param $user
     * @return ResponseInterface
     */
    public static function fire($payload, string $channel = 'asty', $user)
    {
        $client = new Client(["base_uri" => env('APP_URL'), 'verify' => false ]);

        $options = [
            'json' => $payload
        ];
        return $client->post('/pub?id='.$channel, $options);
    }
}
