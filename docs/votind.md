## Voting
### A discussion on the architecture and implementation

* Things to consider

* Proposed architecture

* Queue
  * Am I queueing the object?
  * Pub/Sub
  * Redis
* Socket.io
* SSE
* Messaging hub
* Laravel broadcasts
* Laravel Echo
* Pusher.io

Laravel Echo server
laravel-echo-server.json

~/sixacts/

Howto AUTH https://medium.com/@dennissmink/laravel-echo-server-private-channels-267a9e57bae9

currently queues are using the database backend:
QUEUE_CONNECTION=database

It's OK at the moment s it allowes me to sse wjat's happening. This will eventually have to change to **redis**

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


        