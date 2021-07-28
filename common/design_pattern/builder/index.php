<?php

include_once "builder.php";
// client
$buidler1 = new ConcreteBuilder1();
$director = new Director();
$director->bind($buidler1);
$product = $director->build();
the_print($product);
echo "<br>";
$buidler2 = new ConcreteBuilder2();
$director->bind($buidler2);
$product = $director->build();
echo "<br>";
the_print($product);
