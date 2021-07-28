<?php

include_once "composite_safe.php";
// client
$leaf1 = new Leaf('first');
$leaf2 = new Leaf('second');

$composite = new Composite();
$composite->add($leaf1);
$composite->add($leaf2);
$composite->operation();

$composite->remove($leaf2);
$composite->operation();

