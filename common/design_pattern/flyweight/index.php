<?php
include_once "flyweight.php";
// client
$a  = FlyWeightFactory::get_fly_weight('a');
$b  = FlyWeightFactory::get_fly_weight('b');
$a->operate();
$b->operate();
$c =  FlyWeightFactory::get_fly_weight('a');
$c->operate();
// 不共享的对象，单独调用
$uflyweight = new unShareFlyWeight('A');
$uflyweight->operate();

$uflyweight = new unShareFlyWeight('B');
$uflyweight->operate();
