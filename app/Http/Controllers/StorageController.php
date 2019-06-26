<?php
  
  namespace App\Http\Controllers;
  
  use Illuminate\Http\Request;
  use Illuminate\Support\Facades\Redis as Redis;
  
  class StorageController extends Controller
  {
    private static $redisCommands = [
      'flushdb',
      'addrandom',
    
    ];
    
    /**
     * @param string $email
     * @param int $size
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response|string
     */
    public static function getAvatar(string $email, int $size = 64)
    {
      $email = str_replace('@', '-at-', $email);
      
      try {
        $blob = self::getObject($email);
        if (null !== $blob) {
          return response($blob)
            ->withHeaders([
              'Content-Type' => 'image/jpeg',
            ]);
        }
      } catch (\Exception $exception) {
        return \Gravatar::src($email, $size);
      }
      
    }
    
    /**
     * Get an Object from S3 type of Storage
     * In a later release it should be possible to specify the bucket and the resource type
     *
     * @param string $id
     * @param string $bucket
     * @param string $type
     * @return string
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public static function getObject(string $id, string $bucket = '', string $type = '')
    {
      if (null !== $bucket && null !== $type) {
      
      }
      $fileFromId = \Storage::disk('s3')->get($id);
      return $fileFromId;
    }
    
    /**
     * @param $pattern
     * @param null $cursor
     * @param array $allResults
     * @return array|\Generator
     */
    public static function scanAllForMatchWas($pattern, $cursor = null, $allResults = array(), $redis)
    {
      // Zero means full iteration
      if ($cursor === "0") {
        return $allResults;
      }
      
      // No $cursor means init
      if ($cursor === null) {
        $cursor = "0";
      }
      
      // The call
      $result = $redis->scan($cursor, 'match', $pattern);
      
      // Append results to array
      $allResults = array_merge($allResults, $result[1]);
      
      // Recursive call until cursor is 0
      return self::scanAllForMatch($pattern, $result[0], $allResults, $redis);
    }
    
    /**
     * @param $pattern
     * @param $redis
     * @return \Generator
     */
    private static function scanAllForMatch($pattern, $redis)
    {
      $cursor = 0;
      do {
        list($cursor, $keys) = $redis->scan($cursor, 'match', $pattern);
        foreach ($keys as $key) {
          yield $key;
        }
      } while ($cursor);
    }
    
    /**
     * @param string $db
     * @param int $number
     * @return string
     * @throws \Exception
     */
    private static function addRandom(string $db, int $number)
    {
      $redis = Redis::connection($db);
      
      for ($i = 0; $i < $number; $i++) {
        $redis->set("petes_randomizer:$i", md5(random_bytes(30)));
      }
      return 'OK';
    }
    
    /**
     * @param \Illuminate\Http\Request $request
     * @param string $store
     * @param string $key
     * @return \Illuminate\Contracts\View\View
     * @throws \Exception
     */
    public function postRedis(Request $request, $store = '', $key = '')
    {
      $dbId = $request->input('dbId');
      $needle = $request->input('needle', '');
      
      if (-1 == $needle) {  // add
        $key = $request->get('key');
        $ttl = $request->get('ttl', 3600);
        $value = $request->get('value');
        
        $dbId = $dbId == -1 ? 0 : $dbId;
        
        $tag = self::getDbTagById($dbId);
        
        $redis = Redis::connection($tag);
        $ret = $redis->set($key, $value, 'ex', $ttl);
        
        $msg = "Added kosher salt to db #$dbId ($ret)";
        
        if (isset($error) && '' !== $error) {
          return redirect($request->getUri())
            ->with('error', $error);
        }
        else {
          return redirect($request->getUri())
            ->with('message', $msg);
        }
        
      }
      
      if ($dbId == -1) {
        $dbId = self::findDatabaseKeyBelongsTo($needle);
        if (false == $dbId) {
          $error = "Problem: can't find this key ($needle)";
        }
      }
      
      $tag = self::getDbTagById($dbId);
      
      if ($tag) {
        $redis = Redis::connection($tag);
        $ret = $redis->del([$needle]);
        $msg = "Deleted $ret: $needle from #$dbId";
      }
      
      if (isset($error) && '' !== $error) {
        return redirect($request->getUri())
          ->with('error', $error);
      } else {
        return redirect($request->getUri())
          ->with('message', $msg);
      }
    }
    
    /**
     * @param string $dbId
     * @return bool|int|string
     */
    private static function getDbTagById(string $dbId)
    {
      foreach (self::getRedisDatabases() as $label => $id) {
        if ($dbId == $id) {
          return $label;
        }
      }
      return false;
    }
    
    /**
     * @param $needle
     * @return bool|mixed
     */
    public static function findDatabaseKeyBelongsTo($needle)
    {
      $databases = self::getRedisDatabases();
      foreach ($databases as $tag => $db) {
        $redis = Redis::connection($tag);
        $keys = self::scanAllForMatch('*', $redis, []);
        
        foreach ($keys as $key) {
          if ($key == $needle) {
            return $db;
          }
        }
      }
      return false;
    }
    
    /**
     * @return array
     */
    public static function getRedisDatabases()
    {
      $databases = [];
      foreach (\Config::get('database.redis') as $key => $stanza) {
        if (is_array($stanza)) {
          if (array_key_exists('database', $stanza))
            $databases[$key] = $stanza['database'];
        }
      }
      return $databases;
    }
    
    /**
     * @param \Illuminate\Http\Request $request
     * @param string $store
     * @param string $key
     * @return \Illuminate\Contracts\View\View
     * @throws \Exception
     */
    public function redis(Request $request, $store = '', $key = '')
    {
      $command = $request->get('command', '');
      
      $databases = self::getRedisDatabases();
      
      $stuff = [];
      
      $dbId = ('' === $store) ? -1 : intval($store);
      $outputs = [];
      
      foreach ($databases as $tag => $db) {
        if (-1 == $dbId || $dbId == $db) {
          $redis = Redis::connection($tag);
          
          if (in_array($command, self::$redisCommands)) {
            if ($command == 'addrandom') {
              $outputs[] = self::addRandom($tag, rand(1, 7));
            }
            else {
              $ret = $redis->command($command, []);
              $outputs[] = $ret->getPayload();
            }
          }
          
          $keys = self::scanAllForMatch('*', $redis, $stuff);
          foreach ($keys as $key) {
            $stuff[$key] = [
              'ttl'=>$redis->command('ttl', [$key]),
              'value'=>$redis->command('get', [$key])
            ];
          }
        }
      }
      
      if (isset($outputs) && count($outputs) > 0) {
        \Session::flash('message', "Operation(s) returned: " . implode(',', $outputs));
        
      }
      return \View::make('storage.redis',
        [
          'dump' => $stuff,
          'databases' => $databases,
          'dbId' => $dbId,
        ]);
      
    }
  }
