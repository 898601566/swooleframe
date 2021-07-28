<?php

abstract class Mediator
{ // 中介者角色

    abstract public function deliver($message, $colleague);
}

abstract class Colleague
{ // 抽象对象

    private $_mediator = null;
    public $cid = null;

    public function __construct($mediator,$cid)
    {
        $this->_mediator = $mediator;
        $this->cid = $cid;
    }

    public function send_msg($message, $cid)
    {
         echo $this->cid,"发送信件: ", $message,"给",$cid,'<br>';
        $this->_mediator->deliver($message, $cid);
    }

    public function receive_msg($message)
    {
        echo $this->cid,"收到信了 ", $message,'<br>';
    }
    
}

//媒婆
class ConcreteMediator extends Mediator
{ // 具体中介者角色

    private $colleague_arr = [];

    public function deliver($message, $cid)
    {
        if (!empty($this->colleague_arr[$cid]))
        {
            $this->colleague_arr[$cid]->receive_msg($message);
        }
        else
        {
            echo "收信地址有误<br>";
        }
    }

    public function bind($cid, Colleague $colleague)
    {
        $this->colleague_arr[$cid] = $colleague;
    }

}

class Person extends Colleague
{ // 具体对象角色

}
