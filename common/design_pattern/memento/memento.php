<?php

class Originator
{ // 发起人(Originator)角色

    private $_state;

    public function __construct()
    {
        $this->_state = '';
    }

    public function createMemento()
    { // 创建备忘录
        return new Memento($this->_state);
    }

    public function restoreMemento(Memento $memento)
    { // 将发起人恢复到备忘录对象记录的状态上
        $this->_state = $memento->getState();
    }

    public function setState($state)
    {
        $this->_state = $state;
    }

    public function getState()
    {
        return $this->_state;
    }

    public function showState()
    {
        echo "<pre>";
        echo $this->_state;
        echo "<br>";
    }

}

class Memento
{ // 备忘录(Memento)角色 

    private $_state;

    public function __construct($state)
    {
        $this->setState($state);
    }

    public function getState()
    {
        return $this->_state;
    }

    public function setState($state)
    {
        $this->_state = $state;
    }

}

class Caretaker
{ // 负责人(Caretaker)角色 

    private $_memento;

    public function getMemento()
    {
        return $this->_memento;
    }

    public function setMemento(Memento $memento)
    {
        $this->_memento = $memento;
    }

}

