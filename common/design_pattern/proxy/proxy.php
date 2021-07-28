<?php

abstract class Subject
{ // 抽象主题角色

    abstract public function action();
}

class RealSubject extends Subject
{ // 真实主题角色

    public function __construct()
    {
        
    }

    public function action()
    {
        echo "代理人购买商品<br>";
        return $goods = "买到的商品";
    }

}

class ProxySubject extends Subject
{ // 代理主题角色

    private $_real_subject = NULL;
    private $goods;

    public function __construct()
    {
        
    }

    public function action()
    {
        $this->_beforeAction();
        if (is_null($this->_real_subject))
        {
            $this->_real_subject = new RealSubject();
        }
        $this->goods = $this->_real_subject->action();
        $this->_afterAction();
        return $this->goods;
    }

    private function _beforeAction()
    {
        echo '代购向海外代理人发出购买请求<br>';
    }

    private function _afterAction()
    {
        echo '代购分配商品<br>';
    }

}
