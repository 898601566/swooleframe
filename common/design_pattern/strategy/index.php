<?php

include_once "strategy.php";
// client
$test = ['a', 'b', 'c'];
$test = array_combine($test, $test);
// 需要返回数组
$output = new Output(new ArrayStrategy());
$data1 = $output->renderOutput($test);

// 需要返回JSON
$output = new Output(new JsonStrategy());
$data2 = $output->renderOutput($test);
echo "<pre>";
var_dump($data1);
var_dump($data2);
