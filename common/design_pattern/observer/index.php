<?php

include_once "observer.php";
// client
$ul = new Software(); //被观察者
$computer = new Computer();//观察者
$phone = new Phone();//观察者
$ul->addObserver($computer); //增加观察者
$ul->addObserver($phone); //增加观察者
$ul->sendMsg("您的优惠卷即将到期"); //发送消息到观察者
