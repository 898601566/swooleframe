<?php

include_once "prototype.php";
// client
$object1 = new ConcretePrototype("666");
$object2 = $object1->copy();
echo "<pre>";
var_dump($object1);
var_dump($object2);
$object1->name = 777;
$object3 = $object1->copy();
var_dump($object1);
var_dump($object3);
$object4 = clone $object1;
var_dump($object4);
