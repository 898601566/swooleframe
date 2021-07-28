<?php

interface Item
{ // 产品部分

    public function __construct();
}

class noodles implements Item
{

    public function __construct()
    {
        echo "准备面条<br>";
    }

}

class rices implements Item
{

    public function __construct()
    {
        echo "准备米饭<br>";
    }

}

class Juice implements Item
{

    public function __construct()
    {
        echo "准备果汁<br>";
    }

}

class Package
{ // 产品本身

    public $parts;

    public function __construct()
    {
        $this->parts = [];
    }

    public function add(Item $part)
    {
        $this->parts[] = $part;
        return $this->parts;
    }

}

abstract class Builder
{ // 建造者抽象类

    public abstract function buildDrink();

    public abstract function buildMeal();

    public abstract function getResult();
}

class ConcreteBuilder1 extends Builder
{ // 具体建造者

    private $product;

    public function __construct()
    {
        $this->product = new Package();
    }

    public function buildDrink()
    {
        $this->product->add(new Juice());
    }

    public function buildMeal()
    {
        $this->product->add(new noodles());
    }

    public function getResult()
    {
        return $this->product;
    }

}

class ConcreteBuilder2 extends Builder
{ // 具体建造者

    private $product;

    public function __construct()
    {
        $this->product = new Package();
    }

    public function buildDrink()
    {
        $this->product->add(new Juice());
    }

    public function buildMeal()
    {
        $this->product->add(new rices());
    }

    public function getResult()
    {
        return $this->product;
    }

}

class Director
{ //导演者

    private $builder;

    public function bind(Builder $builder)
    {
        $this->builder = $builder;
    }

    public function build()
    {
        try{

        $this->builder->build();
        return $this->builder->getResult();
        }catch (Exception $e){
            echo "<pre>";
            var_dump([$e]);
            exit;;
        }
    }

}
