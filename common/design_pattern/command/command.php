<?php

interface Command
{ // 命令角色

    public function execute(); // 执行方法
}

//制作姜撞奶
class ConcreteCommand1 implements Command
{ // 具体命令方法 

    private $_receiver;

    public function __construct(Receiver $receiver)
    {
        $this->_receiver = $receiver;
    }

    public function execute()
    {
        $this->_receiver->action1();
    }

}

//制作双皮奶
class ConcreteCommand2 implements Command
{ // 具体命令方法 

    private $_receiver;

    public function __construct(Receiver $receiver)
    {
        $this->_receiver = $receiver;
    }

    public function execute()
    {
        $this->_receiver->action2();
    }

}

class Receiver
{ // 接收者角色

    public function action1()
    {
        echo '制作姜撞奶<br>';
    }

    public function action2()
    {
        echo '制作双皮奶<br>';
    }

}

//waiter
class Invoker
{ // 请求者角色

    private $_commands;

    public function bind(Command $command)
    {
        $this->_commands[] = $command;
    }

    public function action()
    {
        foreach ($this->_commands as $value)
        {
            $value->execute();
        }
    }

}
