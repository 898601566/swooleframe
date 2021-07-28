<?php

namespace common\leetCode;

use Swoole\Coroutine\Redis;

class TokenBucket
{
    private $_redis;
    private $_queueName;//令牌桶
    private $_max;//最大令牌数
    private $preTime = 0;

    public function __construct()
    {

        $this->_redis = new \Redis();
        $this->_redis->connect('redis');
        $this->_queueName = 'tokenbucket';
        $this->_max = 18;
    }

    /**
     * 加入令牌
     *
     * @param Int $num 加入的令牌数量
     *
     * @return Int 加入的数量
     */
    public function add($num = 0)
    {
        //当前剩余令牌数
        $cur_num = $this->_redis->lLen($this->_queueName);

        $num = $this->_max >= ($cur_num + $num) ? $num : ($this->_max - $cur_num);
        if ($num > 0) {
            $token = array_fill(0, $num, 1);
            $this->_redis->lPush($this->_queueName, ...$token);
        }
        return TRUE;
    }

    /**
     * 获取令牌
     */
    public function get()
    {
        return $this->_redis->rPop($this->_queueName) ? TRUE : FALSE;
    }

    /**
     * 重设令牌桶，填满令牌
     */
    public function reset()
    {
        $this->add($this->_max);
        $this->preTime = time();
    }

    public function run()
    {
        $this->reset();
        for ($i = 0; $i < 50; $i++) {
            $curTime = time();
            if ($this->get()) {
                echo "拿到令牌了,快跑" . "<br>";
            } else {
                echo "没拿到令牌了,快滚" . "<br>";
            }
            if ($curTime - $this->preTime >= 20) {
                $this->reset();
            }
            usleep(500000);
        }
    }
}
