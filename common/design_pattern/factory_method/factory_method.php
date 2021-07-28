<?php

class Computer
{/* ... */
}

class WinComputer extends Computer
{/* ... */
        public function __toString()
    {
        return  "this is WinComputer<br>";
    }
}

class MacComputer extends Computer
{/* ... */

    public function __toString()
    {
        return "this is MacComputer<br>";
    }

}

interface ComputerFactory
{

    public function createComputer($type);
}

class MyComputerFactory implements ComputerFactory
{

    // 实现工厂方法
    public function createComputer($type)
    {
        switch ($type)
        {
            case 'Mac':
                return new MacComputer();
            case 'Win':
                return new WinComputer();
        }
    }

}
