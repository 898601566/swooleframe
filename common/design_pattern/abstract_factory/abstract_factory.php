<?php

namespace common\design_pattern\abstract_factory;

abstract class Computer
{

}

abstract class Pad
{

}

class MacComputer extends Computer
{

    public function __toString()
    {
        return __CLASS__ . "<br>";
    }

}

class WinComputer extends Computer
{

    public function __toString()
    {
        return __CLASS__ . "<br>";
    }

}

class MacPad extends Pad
{

    public function __toString()
    {
        return __CLASS__ . "<br>";
    }

}

class WinPad extends Pad
{

    public function __toString()
    {
        return __CLASS__ . "<br>";
    }

}

interface AbstractFactory
{

    public function CreateComputer();

    public function CreatePad();
}

class MacFactory implements AbstractFactory
{

    public function CreateComputer()
    {
        return new MacComputer();
    }

    public function CreatePad()
    {
        return new MacPad();
    }

}

class WinFactory implements AbstractFactory
{

    public function CreateComputer()
    {
        return new WinComputer();
    }

    public function CreatePad()
    {
        return new WinPad();
    }

}

?>