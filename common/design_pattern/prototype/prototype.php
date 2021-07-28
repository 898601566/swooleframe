<?php

interface Prototype
{

    public function copy();
}

class ConcretePrototype implements Prototype
{

    public $name;

    public function __construct($name)
    {
        $this->name = $name;
    }

    public function copy()
    {
        return clone $this;
    }

}

