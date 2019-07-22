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

##### Laravel broadcasts with Echo server

The Laravel Echo server is a "chat" type of server. It has connectors for and receives message from Redis, over HTTP, over HTTP using the Pusher protocol.

The (separate from Laravel) project lives [here](https://github.com/tlaverdure/laravel-echo-server).

An `npm install -g laravel-echo-server` is required. After that, one is encouraged to run `laravel-echo-server init` and set some values (dev/non dev, SSL, CORS, hosts allows, type of connector etc.)

Additionally, one is encouraged to also run `laravel-echo-server client:add APP_ID` which will yield an APP_ID (generated for you of not specified) and a key, to be used for the management.halth status and direct push API endpoints, now included with the server.

The above actions will generate `laravel-echo-server.json` which will produce output like below
```
API Client added!
appId: sixacts
key: 
```

 * AUTH [Laravel Echo Server — Private Channels](https://medium.com/@dennissmink/laravel-echo-server-private-channels-267a9e57bae9)
 * [Starting with Laravel Echo and PusherJS](https://petericebear.github.io/starting-laravel-echo-20170303/)
 * **Dot prefix issue**: [https://github.com/tlaverdure/laravel-echo-server/issues/116](https://github.com/tlaverdure/laravel-echo-server/issues/116). Essentially, the channel name isn't set to what one expects it to be. The place where that gets set is the `broadcastOn()` in the Event class so the following should broadcast to a channel called 'messages' 

```
class MessagePosted implements ShouldBroadcast
{
    public function broadcastOn()
    {
        return new Channel('messages');
    }
    
}    
```    
 
never the less ends up in broadcasting to `Channel: 6_acts_database_messages`.

* Read about it [here](https://stackoverflow.com/questions/43066633/laravel-echo-does-not-listen-to-channel-and-events), thanks to the French guy who pointed out and who received no applause.


#### An attempt with SSE

*Server-Sent Events are real-time events emitted by the server and received by the browser. They’re similar to WebSockets in that they happen in real time, but they’re very much a one-way communication method from the server.
In theory this would be a nice testbed for using ServerSideEvents. Most browsers now support it and given, no external JS has to be imported, as it's the case with Socket.io. With SSE one would typically do something like this in the browser (this code actually exists in a certain branch)*

The main difference to polling is that we get only one connection and keep an event stream going through it. Long polling creates a new connection for every pull — which can creative a massive headers overhead and other issues.

A lengthy intro to Server Side Events [Using SSE Instead Of WebSockets For Unidirectional Data Flow Over HTTP/2](https://www.smashingmagazine.com/2018/02/sse-websockets-data-flow-http2/)" is probably worth reading.

```
let es = new EventSource('https://sixacts.div/sse/semaphore');

es.addEventListener('message', event => {
            let data = JSON.parse(event.data);
            this.stockData = data.stockData;
        }, false);
```
and have a server component that handles sessions/presence to broadcast a BufferedResponse. 

```
$response = new StreamedResponse(function () use ($request) {
            while (true) {
```

So far I've come across two sp;utions that claim to do SSE in PHP, which I haven't tried due to lack of time and the very sparse info they provide. One of them is a library, simply called PHP SSE: Server-sent Events [on Github](https://github.com/hhxsv5/php-sse) and the other one this guy's [notes](https://chrisblackwell.me/server-sent-events-using-laravel-vue/). Leaving PHP aside, one could use the Mercure SSE hub, written in go. 

##### PHP SSE: Server-sent Events
In the light of the failure with "this guy's notes," I now think I should have started with these guys. The documentation is laconic, so much so that it may actually work. A branch has been created to investigate.

##### This guy's notes
I spent considerable amount of time getting this to work and ended up realising that it can only serve one connection, in the instance the first ever made. All consequent new connections do not received the events. The headers sent at the end and out of the loop should have raised alarm bells, as it's obvious that they never really get sent. One possibility though is that the `$response = new StreamedResponse(function () use ($request) {` can be wrapped in a ReactPHP or other higher lever non stopping MUX/DEMUX

##### Mercure SSE 
Nice and lovely as it is, it's far too much for what I want to achieve. See notes about it in aginter (link will be provided.)

#### Unclassified notes
currently queues are using the database backend:
QUEUE_CONNECTION=database

It's OK at the moment as it allows me to see what's happening. 
This will eventually have to change to **redis**
