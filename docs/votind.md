## Voting
### A discussion on the architecture and implementation

* Things to consider

* Proposed architecture

* Queue
  * Am I queueing the object?
  * Pub/Sub
  * Redis
* Socket.io
* Laravel broadcasts
* Laravel Echo

Laravel Echo server
laravel-echo-server.json

~/sixacts/

HOWTO

 * AUTH [Laravel Echo Server — Private Channels](https://medium.com/@dennissmink/laravel-echo-server-private-channels-267a9e57bae9)
 * [Starting with Laravel Echo and PusherJS](https://petericebear.github.io/starting-laravel-echo-20170303/)


currently queues are using the database backend:
QUEUE_CONNECTION=database

It's OK at the moment as it allowes me to sse what's happening. 
This will eventually have to change to **redis**

        'sftp' => [
            'driver'    => 'sftp',
            'host'      => 'u203732.your-storagebox.de',
            'port'      => 22,
            'username'  => env('SFTP_UID'),
            'password'  => env('SFTP_PASS'),
            /*            'privateKey' => 'path/to/or/contents/of/privatekey',*/
            'root'      => '/odysseas',
            'timeout'   => 10,
        ],
        

```        
VAULT=u203732.your-storagebox.de
SFTP_UID=u203732
SFTP_PASS=GeRuHPzY3tkn3uUp
```


        