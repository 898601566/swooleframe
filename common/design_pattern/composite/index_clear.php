<?php

include_once "composite_clear.php";
// client 
$leaf1 = new Leaf('first');
$leaf2 = new Leaf('second');

$branch = new Branch('Branch1');
$branch2 = new Branch('Branch2');
$branch->add($leaf1);
$branch->add($leaf2);
$branch2->add($branch);
$branch2->operation();
//$branch->remove($leaf2);
//$branch->operation();
?>
