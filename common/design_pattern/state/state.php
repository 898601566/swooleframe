<?php

interface State
{ // 抽象状态角色

    public function handle(Context $context); // 方法示例
}

//ConcreteStateA
class On implements State
{ // 具体状态角色A

    private static $_instance = null;

    private function __construct()
    {
        
    }

    public static function getInstance()
    { // 静态工厂方法，返还此类的唯一实例
        if (is_null(self::$_instance))
        {
            self::$_instance = new On();
        }
        return self::$_instance;
    }

    public function handle(Context $context)
    {
        echo '关灯' . "<br>";
        $context->setState(off::getInstance());
    }

}

//ConcreteStateB
class Off implements State
{ // 具体状态角色B

    private static $_instance = null;

    private function __construct()
    {
        
    }

    public static function getInstance()
    {
        if (is_null(self::$_instance))
        {
            self::$_instance = new Off();
        }
        return self::$_instance;
    }

    public function handle(Context $context)
    {
        echo '开灯' . "<br>";
        $context->setState(On::getInstance());
    }

}

class Context
{ // 环境角色 

    private $_state;

    public function __construct()
    { // 默认为stateA
        $this->_state = off::getInstance();
    }

    public function setState(State $state)
    {
        $this->_state = $state;
    }

    public function pressSwich()
    {
        $this->_state->handle($this);
    }

}
