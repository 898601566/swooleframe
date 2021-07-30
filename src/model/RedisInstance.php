<?php
/**
 * Created by : PhpStorm
 * User: Sin Lee
 * Date: 2021/7/30
 * Time: 19:28
 */

namespace Fastswoole\model;

use Fastswoole\core\Di;
use \PDO;
use \PDOException;
use Swoole\Database\PDOPool;
use Swoole\Database\RedisPool;

/**
 * Class RedisInstance
 * @package Fastswoole\model
 */
class RedisInstance
{

    /**
     * 单例模式实例
     * @var null
     */
    protected static $redis = NULL;

    /**
     * 单例,获取pdo实例
     * @return \Redis
     */
    public static function instance()
    {
        //连接池
        if (!empty(env('redis.pool'))) {
            var_dump('redis pool get');
            return Di::instance()->get(RedisPool::class)->get();
        }
        //单例模式
        if (!empty(static::$redis)) {
            return static::$redis;
        } else {
            //外层有个trycath,这里就不加了
            static::$redis = new \Redis();
            static::$redis->connect(env('redis.host'),
                env('redis.port'),
                env('redis.timeout'));
            //redis连接失败不报异常，而是打印Redis server went away
            return static::$redis;
        }
    }

    public static function clear($pdoObj)
    {
        if (!empty(env('redis.pool'))) {
            var_dump('redis pool put');
            Di::instance()->get(RedisPool::class)->put($pdoObj);
        }
    }
}
