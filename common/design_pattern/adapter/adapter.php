<?php

//对象适配器
interface Target
{

    public function pay();

    public function set_source($adaptee);
}

class Adaptee
{

    public function special_pay()
    {
        echo __CLASS__, "payed<br>";
    }

}

class Adapter implements Target
{

    private $adaptee;

    public function __construct()
    {
        
    }

    public function set_source($adaptee)
    {
        $this->adaptee = $adaptee;
    }

    public function pay()
    {
        echo $this->adaptee->special_pay();
    }

}

//类适配器
interface Target2
{

    public function pay();

}


class Adapter2 extends Adaptee implements Target2
{ // 适配后角色

    private $adaptee;

    public function __construct()
    {
        
    }

    public function pay()
    {
        echo $this->special_pay();
    }

}

?>