<?php

include_once "factory_method.php";
//client 

$factory = new MyComputerFactory();
echo $factory->createComputer('Mac');
echo $factory->createComputer('Win');
