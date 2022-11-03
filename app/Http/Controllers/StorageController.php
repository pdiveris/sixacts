<?php
namespace App\Http\Controllers;

use Exception;
use Generator;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Redis as Redis;

/**
 * Class StorageController
 *
 * @category Utilities
 * @package  App\Http\Controllers
 * @author   Petros Diveris <petros@diveris.org>
 * @license  Apache 2.0
 * @link     https://www.diveris.org
 */
class StorageController extends Controller
{
    protected static array $redisCommands = [
      'flushdb',
      'addrandom',
    ];

    /**
     * Get Avatar
     *
     * @param string $email email
     * @param int    $size  Size
     *
     * @return ResponseFactory|Response|string
     */
    public static function getAvatar(string $email, int $size = 64): Response|string|ResponseFactory
    {
        $email = str_replace('@', '-at-', $email);

        try {
            $blob = self::getObject($email);
            if (null !== $blob) {
                return response($blob)
                    ->withHeaders(
                        [
                        'Content-Type' => 'image/jpeg',
                        ]
                    );
            }
        } catch (Exception $exception) {
            return \Gravatar::src($email, $size);
        }
    }

    /**
     * Get an Object from S3 type of Storage
     * In a later release it should be possible to specify the
     * bucket and the resource type
     *
     * @param string $id     id
     * @param string $bucket bucket
     * @param string $type   type
     *
     * @return string
     * @throws FileNotFoundException
     */
    public static function getObject(
        string $id,
        string $bucket = '',
        string $type = ''
    ): string
    {
        if (null !== $bucket && null !== $type) {
            //
        }
        return \Storage::disk('s3')->get($id);
    }

    /**
     * Old scan method (slow)
     *
     * @param string $pattern    pattern
     * @param null   $cursor     cursor
     * @param array $allResults all
     * @param null   $redis      redis
     *
     * @return array|Generator
     */
    public static function scanAllForMatchWas(
        string $pattern,
               $cursor = null,
        array  $allResults = array(),
               $redis = null
    ): array|Generator
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
     * New scan all
     *
     * @param string $pattern pattern
     * @param string $redis   redis
     *
     * @return Generator
     */
    protected static function scanAllForMatch(string $pattern, string $redis): Generator
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
     * Add some random data to Redis
     *
     * @param string $db     database
     * @param int    $number number
     *
     * @return string
     * @throws Exception
     */
    protected static function addRandom(string $db, int $number): string
    {
        $redis = Redis::connection($db);

        for ($i = 0; $i < $number; $i++) {
            $redis->set("petes_randomizer:$i", md5(random_bytes(30)));
        }
        return 'OK';
    }

    /**
     * Post to Redis
     *
     * @param Request $request request
     * @param string $store   store
     * @param string $key     key
     *
     * @return Application|RedirectResponse|Redirector
     * @throws Exception
     */
    public function postRedis(
        Request $request,
        string $store = '',
        string $key = ''
    ): Application|RedirectResponse|Redirector
    {
        $dbId = $request->input('dbId');
        $needle = $request->input('needle', '');

        if (-1 == $needle) { // add
            $key = $request->get('key');
            $ttl = $request->get('ttl', 3600);
            $value = $request->get('value');

            $dbId = $dbId == -1 ? 0 : $dbId;

            $tag = self::getDbTagById($dbId);

            $redis = Redis::connection($tag);
            $ret = $redis->set($key, $value, 'ex', $ttl);

            $msg = "Added kosher salt to db #$dbId ($ret)";

            if (isset($error) && '' !== $error) {
                return redirect($request->getUri())->with('error', $error);
            } else {
                return redirect($request->getUri())->with('message', $msg);
            }
        }

        if ($dbId == -1) {
            $dbId = self::findDatabaseKeyBelongsTo($needle);
            if (!$dbId) {
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
            return redirect($request->getUri())->with('error', $error);
        } else {
            return redirect($request->getUri())->with('message', $msg);
        }
    }

    /**
     * Get the Redis DB by ID
     *
     * @param string $dbId Database ID
     *
     * @return bool|int|string
     */
    protected static function getDbTagById(string $dbId): bool|int|string
    {
        foreach (self::getRedisDatabases() as $label => $id) {
            if ($dbId == $id) {
                return $label;
            }
        }
        return false;
    }

    /**
     * Check all DBs for key
     *
     * @param string $needle needle
     *
     * @return bool|mixed
     */
    public static function findDatabaseKeyBelongsTo(string $needle): mixed
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
     * List all Redis databases
     *
     * @return array
     */
    public static function getRedisDatabases(): array
    {
        $databases = [];
        foreach (\Config::get('database.redis') as $key => $stanza) {
            if (is_array($stanza)) {
                if (array_key_exists('database', $stanza)) {
                    $databases[$key] = $stanza['database'];
                }
            }
        }
        return $databases;
    }

    /**
     * Return the REDIS View
     *
     * @param Request $request request
     * @param string $store   store
     * @param string $key     the key
     *
     * @return View
     * @throws Exception
     */
    public function redis(Request $request, string $store = '', string $key = '')
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
                    } else {
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

        if (isset($outputs) && count($outputs)>0) {
            $flash = implode(',', $outputs);
            \Session::flash('message', "Operation(s) returned: ".$flash);
        }
        return \View::make(
            'storage.redis',
            [
                'dump' => $stuff,
                'databases' => $databases,
                'dbId' => $dbId,
            ]
        );
    }
}
