<?php

interface Visitor
{ // 抽象访问者角色

    public function viewConsumer(ConsumerBill $consumerBill);

    public function viewIncome(IncomeBill $incomeBill);
}

interface Element
{ // 抽象节点角色

    public function accept(Visitor $visitor);
}

class Boss implements Visitor
{ // 具体的访问者1

    private $totalConsumer = 0;
    private $totalIncome = 0;

    public function viewConsumer(ConsumerBill $consumerBill)
    {
        $this->totalConsumer += $consumerBill->getAmount();
//        echo __CLASS__, "调用了", consumerBill->getName(),"<br/>";
        echo __CLASS__, "单笔支出", $consumerBill->getAmount(), "<br>";
    }

    public function viewIncome(IncomeBill $incomeBill)
    {
        $this->totalIncome += $incomeBill->getAmount();
        echo __CLASS__, "单笔收入", $incomeBill->getAmount(), "<br>";
    }

    public function getTotalConsumer()
    {
        echo __CLASS__, "一共支出了", $this->totalConsumer, "<br>";
    }

    public function getTotalIncome()
    {
        echo __CLASS__, "一共收入了", $this->totalIncome, "<br>";
    }

}

class CPA implements Visitor
{ // 具体的访问者2

    private $_income_count = 0;
    private $_consumer_count = 0;

    public function viewConsumer(ConsumerBill $consumerBill)
    {
            $this->_consumer_count++;
//            $this->totalConsumer += $consumerBill->getAmount();
            echo "第{$this->_consumer_count}单消费了：", $consumerBill->getAmount(), "<br>";
    }

    public function viewIncome(IncomeBill $incomeBill)
    {
            $this->_income_count++;
//            $this->totalConsumer += $incomeBill->getAmount();
            echo "第{$this->_income_count}单支出：", $incomeBill->getAmount(), "<br>";
    }

}

//收入
class ConsumerBill implements Element
{ // 具体元素A

    private $_name;
    private $_amount;

    public function __construct($amount)
    {
        $this->_name = '支出';
        $this->_amount = $amount;
    }

    public function getName()
    {
        return $this->_name;
    }

    public function getAmount()
    {
        return $this->_amount;
    }

    public function accept(Visitor $visitor)
    { // 接受访问者调用它针对该元素的新方法
        $visitor->viewConsumer($this);
    }

}

//支出
class IncomeBill implements Element
{ // 具体元素B

    private $_name;
    private $_amount;

    public function __construct($amount)
    {
        $this->_name = '收入';
        $this->_amount = $amount;
    }

    public function getName()
    {
        return $this->_name;
    }

    public function getAmount()
    {
        return $this->_amount;
    }

    public function accept(Visitor $visitor)
    {
// 接受访问者调用它针对该元素的新方法
        $visitor->viewIncome($this);
    }

}

class ObjectStructure
{ // 对象结构 即元素的集合

    private $_collection;

    public function __construct()
    {
        $this->_collection = array();
    }

    public function attach(Element $element)
    {
        return array_push($this->_collection, $element);
    }

    public function detach(Element $element)
    {
        $index = array_search($element, $this->_collection);
        if ($index !== FALSE)
        {
            unset($this->_collection[$index]);
        }
        return $index;
    }

    public function accept(Visitor $visitor)
    {
        foreach ($this->_collection as $element)
        {
            $element->accept($visitor);
        }
    }

}
