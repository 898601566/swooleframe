<?php

include_once "mediator.php";
// client
$mediator = new ConcreteMediator();
$person1 = new Person($mediator,1);
$person2 = new Person($mediator,2);
$mediator->bind(1, $person1);
$mediator->bind(2, $person2);
$person1->send_msg("666",2);
$person2->send_msg("666",1);
?>
