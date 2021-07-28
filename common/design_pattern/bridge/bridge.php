<?php

//    Abstraction：抽象类
//    RefinedAbstraction：扩充抽象类
//    Implementor：实现类接口
//    ConcreteImplementor：具体实现类


abstract class Abstraction
{ // 抽象化角色，抽象化给出的定义，并保存一个对实现化对象的引用。    

    public $itme;
    protected $imp; // 对实现化对象的引用

    public function operation()
    {
        $this->imp->operationImp();
    }

}

class RefinedAbstraction extends Abstraction
{ // 修正抽象化角色, 扩展抽象化角色，改变和修正父类对抽象化的定义。

    public $itme;

    public function __construct(Implementor $imp)
    {
        $this->imp = $imp;
    }

    public function operation()
    {
        $this->itme = "红色的" .   $this->imp->operationImp()."<br>";
        return $this->itme;
    }

}

abstract class Implementor
{ // 实现化角色, 给出实现化角色的接口，但不给出具体的实现。

    abstract public function operationImp();
}

class ConcreteImplementorA extends Implementor
{ // 具体化角色A

    public $itme;

    public function operationImp()
    {
        $this->itme = "圆形";
        return $this->itme;
    }

}

class ConcreteImplementorB extends Implementor
{ // 具体化角色B

    public $itme;

    public function operationImp()
    {
        $this->itme = "方形";
        return $this->itme;
    }

}

?>