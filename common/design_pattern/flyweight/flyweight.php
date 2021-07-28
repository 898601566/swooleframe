<?php

abstract class Resources
{

    public $id;

    abstract public function operate();
}

class UnShareFlyWeight extends Resources
{

    public function __construct($id)
    {
        echo $id, '号树苗领养成功<br>';
        $this->id = $id;
    }

    public function operate()
    {
        echo $this->id, '号树苗浇水<br>';
    }

}

class FlyWeightFactory
{

    private static $fly_weight_arr = array();

    public static function get_fly_weight($id)
    {
        if (isset(self::$fly_weight_arr[$id]))
        {
            echo "这是您领养过的", $id, '号树苗<br>';
            return self::$fly_weight_arr[$id];
        }
        else
        {
            echo $id, "号第一次被领养<br>";
            return self::$fly_weight_arr[$id] = new ShareFlyWeight($id);
        }
    }

}

class ShareFlyWeight extends Resources
{

    public function __construct($id)
    {
        echo $id, '号树苗领养成功<br>';
        $this->id = $id;
    }

    public function operate()
    {
        echo $this->id, '号树苗浇水<br>';
    }

}
