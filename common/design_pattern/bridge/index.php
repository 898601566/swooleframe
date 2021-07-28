<?php

include_once "bridge.php";
// client
$abstraction = new RefinedAbstraction(new ConcreteImplementorA());
echo $abstraction->operation();

$abstraction = new RefinedAbstraction(new ConcreteImplementorB());
echo $abstraction->operation();
