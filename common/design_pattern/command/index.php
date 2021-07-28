<?php

include_once "command.php";
// client 

$receiver = new Receiver('hello world');
$command1 = new ConcreteCommand1($receiver);
$command2 = new ConcreteCommand2($receiver);
$invoker = new Invoker();
$invoker->bind($command1);
$invoker->bind($command2);
$invoker->action();
?>
