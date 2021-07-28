<?php
include_once "adapter.php";
// client
//对象适配器
 $adapter = new Adapter();
$adapter->set_source(new  Adaptee());
$adapter->pay();
//类适配器
$adapter = new Adapter2();
$adapter->pay();
