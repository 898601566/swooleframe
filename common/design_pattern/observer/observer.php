<?php

//观察者
interface IObserver
{

    public function update($sender);

    public function getName();
}

//被观察者
interface IObservable
{
    public function addObserver($observer);
}

class Software implements IObservable
{

    private $_observers = array();
    public $msg = '';

    public function sendMsg($msg)
    {
        $this->msg = $msg;
        $this->_notify();
    }


    protected function _notify()
    {
        foreach ($this->_observers as $obs) {
            $obs->update($this);
        }
    }

    public function addObserver($observer)
    {
        $this->_observers[] = $observer;
    }

    public function removeObserver(IObserver $observer_name)
    {
        $index = array_search($observer_name, $this->_observers);
        if ($index) {
            unset($this->_observers[$index]);
            return;
        }
    }

}

class Computer implements IObserver
{

    public function update($sender)
    {
        echo $this->getName(), "收到一条推送,", $sender->msg, '<br>';
    }

    public function getName()
    {
        return 'Computer';
    }

}

class Phone implements IObserver
{

    public function update($sender)
    {
        echo $this->getName(), "收到一条推送,", $sender->msg, '<br>';
    }

    public function getName()
    {
        return 'Phone';
    }

}
