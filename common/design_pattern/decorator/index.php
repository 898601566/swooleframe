<?php

include_once "decorator.php";
// clients
$component = new ConcreteComponent();
$decoratorA = new ConcreteDecoratorA($component);
$decoratorB = new ConcreteDecoratorB($decoratorA);

//$decoratorA->operation();
//echo '<br>--------<br>';
$decoratorB->operation();
?>
