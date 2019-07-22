## Voting
### A discussion on the architecture and implementation

##### Things to consider

* Architecture. 
  * The problem
  * What we are trying to solve and how
* Socket.io
* SSE
* Laravel broadcasts with Echo server
* An attempt with SSE
* Unclassified notes

##### Architecture

##### Socket.io

##### Laravel Echo server

The Laravel Echo server is a "chat" type of server. It has connectors for and receives message from Redis, over HTTP, over HTTP using the Pusher protocol.

The (separate from Laravel) project lives [here](https://github.com/tlaverdure/laravel-echo-server).

An `npm install -g laravel-echo-server` is required. After that, one is encouraged to run `laravel-echo-server init` and set some values (dev/non dev, SSL, CORS, hosts allowes, type of connector etc.)

Additionally, one is encouraged to also run `laravel-echo-server client:add APP_ID` which will yield an APP_ID (generated for you of not specified) and a key, to be used for the management.halth status and direct push API endpoints, now included with the server.

The above actions will generate `laravel-echo-server.json` which will typically 
~/sixacts/

```
API Client added!
appId: sixacts
key: 
```

##### HOWTO

 * AUTH [Laravel Echo Server — Private Channels](https://medium.com/@dennissmink/laravel-echo-server-private-channels-267a9e57bae9)
 * [Starting with Laravel Echo and PusherJS](https://petericebear.github.io/starting-laravel-echo-20170303/)
 * dot Prefox issue: [https://github.com/tlaverdure/laravel-echo-server/issues/116](https://github.com/tlaverdure/laravel-echo-server/issues/116)
 * Jesus fucking Christ, this has been worjing since at least four hours ago. But the broadcastAs() setting of the channel name is not respected by Laravel. So this:

```
class MessagePosted implements ShouldBroadcast
{
    public function broadcastAs()
    {
        return 'NewMessage';
    }
    
}    
```    
 
never the less ends up in broadcasting to `Channel: 6_acts_database_messages`

* Read about it [here](https://stackoverflow.com/questions/43066633/laravel-echo-does-not-listen-to-channel-and-events), thanks to the French guy whom no one clapped,

currently queues are using the database backend:
QUEUE_CONNECTION=database

It's OK at the moment as it allows me to see what's happening. 
This will eventually have to change to **redis**

        'sftp' => [
            'driver'    => 'sftp',
            'host'      => env('VAULT'),
            'port'      => 22,
            'username'  => env('SFTP_UID'),
            'password'  => env('SFTP_PASS'),
            /*            'privateKey' => 'path/to/or/contents/of/privatekey',*/
            'root'      => '/sixacts',
            'timeout'   => 10,
        ],
        

```        


        
