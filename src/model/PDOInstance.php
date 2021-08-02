<?php
/**
 * Created by : PhpStorm
 * User: Sin Lee
 * Date: 2021/7/30
 * Time: 16:27
 */

namespace Fastswoole\model;

use Fastswoole\core\Di;
use \PDO;
use \PDOException;
use Swoole\Database\PDOPool;

/**
 * Class PDOOBJ
 * @package Fastswoole\model
 */
class PDOInstance
{

    /**
     * 单例模式实例
     * @var null
     */
    protected static $pdo = NULL;

    /**
     * 单例,获取pdo实例
     * @return PDO
     */
    public static function instance()
    {
        //连接池
        if (!empty(env('database.pool'))) {
            var_dump('database pool get');
            return Di::instance()->get(PDOPool::class)->get();
        }
        //单例模式
        if (!empty(static::$pdo)) {
            return static::$pdo;
        } else {
            //外层有个trycath,这里就不加了
            $dsn = sprintf('mysql:host=%s;dbname=%s;charset=utf8mb4', env('database.host'), env('database.dbname'));
            $option = [
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
            ];
            static::$pdo = new \PDO($dsn, env('database.username'), env('database.password'), $option);
            return static::$pdo;
        }
    }

    public static function clear($pdoObj)
    {
        if (!empty(env('database.pool'))) {
            var_dump('database pool put');
            Di::instance()->get(PDOPool::class)->put($pdoObj);
        }
    }
}
