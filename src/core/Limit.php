<?php
/**
 * Created by : PhpStorm
 * User: Sin Lee
 * Date: 2021/8/3
 * Time: 16:47
 */

namespace Fastswoole\core;

use App\exception\SystemException;
use Fastswoole\model\RedisInstance;

class Limit
{

    private App $app;

    public function __construct(App $app)
    {
        $this->app = $app;
    }

    /**
     * 滑动窗口限流
     * 随着时间的流动 , 进行动态的删减区间内的数据 , 限制时获取区间内的数据
     *
     * @param array $rules
     * @param string $title
     *
     * @return bool
     */
    public function slideWindow(array $rules, string $title)
    {
        foreach ($rules as $expire => $limit) {
            $redis = RedisInstance::instance();
            //有序集合key
            $redisKey = "slideWindow" . ":{$title}" . ":$expire";
            $time = time();
            $value = microtime() . mt_rand(0, 10000);
            //事务
            $redis->multi();
            //根据现在时间和规则时间的差,移除过期(窗口以外)的数据
            $redis->zRemRangeByScore($redisKey, 0, $time - $expire);

            //设置过期时间
            $redis->expire($redisKey, $expire);
            //获取zsort排序结果
            $redis->zRange($redisKey, 0, -1, TRUE);
            //事务执行,获取exec结果
            $execRet = $redis->exec();
            //$execRet][3]是zrange的返回值
            if (!empty($execRet[2])) {
                $zrang = $execRet[2];
                $nums = count($zrang);
                if ($nums > $limit) {
                    RedisInstance::clear($redis);
                    return FALSE;
                }
            }
            //将数据加入zsort,第二个参数是score,第三个参数是value
            $redis->zAdd($redisKey, $time, $value);
            RedisInstance::clear($redis);
        }
        return TRUE;
    }
}
