<?php

namespace common\leetCode;

class Leaky
{
    public const contain = 50;
    public const leakSpeed = 1;
    public static $water = 0;
    public static $preTime = 0;

    /**
     * [leaky php实现漏桶算法]
     * @Author
     * @DateTime
     *
     * @param    [type]     $addNum             [int 每次注入桶中的水量]
     *
     * @return   [type]                         [bool,返回可否继续注入true/false]
     */
    public function leaky($addNum)
    {
        $curTime = time();
        //上次结束到本次开始，流出去的水
        $leakWater = ($curTime - static::$preTime) * static::leakSpeed;
        //上次结束时候的水量减去流出去的水，也就是本次初始水量
        echo "water:" . static::$water.",leakWater:".$leakWater."<br>";
        static::$water = static::$water - $leakWater;
        //水量不可能为负，漏出大于进入则水量为0
        static::$water = (static::$water >= 0) ? static::$water : 0;
        //更新本次漏完水的时间
        static::$preTime = $curTime;
        //水小于总容量则可注入，否则不可注入
        if ((static::$water + $addNum) <= static::contain) {
            static::$water += $addNum;
            echo "水没满继续浪" . static::$water."<br>";
            return TRUE;
        } else {
            static::$water = static::contain;
            echo "水满了" . static::$water."<br>";
            return FALSE;
        }
    }

    public function run()
    {

        for ($i = 0; $i < 50; $i++) {
            $res = $this->leaky($i);
//            var_dump($res);
            usleep(500000);
        }

    }
}
