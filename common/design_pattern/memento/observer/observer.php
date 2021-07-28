
<?php

//观察者
interface IObserver
{

    function update(Subject $sender);

}

//被观察者
interface Subject
{

    function addObserver($observer);
    function setContent($msg);
    function getContent();
}

class Software implements Subject
{

    private $_observers = array();
    private $_content = array();

    public function setContent($msg)
    {
        $this->_content = $msg;
       $this->notify();
    }
    public function getContent()
    {
       return $this->_content;
    }
    
    public function notify()
    {
        foreach ($this->_observers as $obs)
        {
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
        if ($index)
        {
            unset($this->_observers[$index]);
            return;
        }
    }

}

class Computer implements IObserver
{

    public function update(Subject $sender)
    {
        echo get_class($this),"收到一条推送,",$sender->getContent(),'<br>' ;
    }


}

class Phone implements IObserver
{

    public function update(Subject $sender)
    {
         echo get_class($this),"收到一条推送,",$sender->getContent(),'<br>' ;
    }


}
