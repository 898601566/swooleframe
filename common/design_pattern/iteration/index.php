<?php
include_once "iteration.php";
//client
 
// client
$data = array(1, 2, 3, 4, 5);
$sa = new sample($data);
echo "<pre>";
var_dump($sa);
foreach ($sa AS $key => $row) {
var_dump($key);
echo "<br />";
var_dump($row);
    echo $key, ' ', $row, '<br />';
}



$data = array('s1' => 11, 's2' => 22, 's3' => 33);
$it = new CMapIterator($data);
foreach ($it as $row) {
    echo $row, '<br />';
}
