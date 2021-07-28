<?php

abstract class Responsibility
{ // 抽象责任角色

    protected $next; // 下一个责任角色

    public function setNext(Responsibility $l)
    {
        $this->next = $l;
        return $this;
    }

    public function check($signal)
    {
        if ($signal == $this->getSignal())
        {
            $this->operate();
            return TRUE;
        }
        if(!empty($this->next))
        {
            return $this->next->check($signal);
        }
        else
        {
            echo '没有合适的处理方式' . "<br>";
            return FALSE;
        }
    }

    abstract public function operate(); // 操作方法

    abstract public function getSignal(); // 操作方法
}

class ResponsibilityA extends Responsibility
{

    public function getSignal()
    {
        return "A";
    }

    public function __construct()
    {
        
    }

    public function operate()
    {
        echo '采用A处理办法' . "<br>";
    }

}

class ResponsibilityB extends Responsibility
{

    public function getSignal()
    {
        return "B";
    }

    public function __construct()
    {
        
    }

    public function operate()
    {
        echo '采用B处理办法' . "<br>";
    }

}
