<?php
    /**
     * Twitter feed cache updater
     *
     * Handles aspects of the login process,
     * including syncing with OAuth data from external providers
     *
     * PHP version 7.2
     *
     * LICENSE: This source file is subject to version 2.0 of the Apache License
     * that is available through the world-wide-web at the following URI:
     * https://www.apache.org/licenses/LICENSE-2.0.
     *
     * @category
     * @package
     * @author    Petros Diveris <petros@diveris.org>
     * @copyright 2019 Bentleyworks
     * @license   http://www.php.net/license/3_01.txt  PHP License 3.01
     * @version   GIT:
     * @link      https://github.com/pdiveris/sixproposals/blob/master/app/Http/Controllers/Auth/LoginController.php
     * @see       Six Acts
     */
    
    namespace App\Invokable;
    
    use GuzzleHttp\Client;

    /**
     * Class FetchTwitter
     *
     * @package App\Invokable
     */
    class FetchTwitter
    {
        public function __invoke()
        {
            // Create a client with a base URI
            $client = new Client(['base_uri' => env('APP_URL'), 'verify' => false ]);
            // Send a request
            $response = $client->request('GET', 'twitter');
        }
    }
