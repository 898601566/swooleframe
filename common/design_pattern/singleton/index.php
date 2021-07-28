<?php
include_once "singleton.php";
// client
$print = Prints::getInstance();
echo $print->name,"<br/>";
$print->name = "print1";
echo $print->name,"<br/>";
$print = Prints::getInstance();
echo $print->name,"<br/>";
