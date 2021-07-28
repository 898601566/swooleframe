<?php

include_once "template_method.php";
// client
$class = new ConcreteClass();
$keys = [
  1,5,6,2,7,89,12,543,74  
];
$class->my_dump(array_combine($keys, $keys));
