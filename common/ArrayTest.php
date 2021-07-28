<?php

namespace common;

use fastswoole\InstanceTrait;

class ArrayTest
{

    use InstanceTrait;

    public function run()
    {
        $this->test();
    }

    function test()
    {

        the_print('+数组操作,以第一个数组为模板,相同索引只保留第一个,剩下的索引加在数组后面');
        the_print('array_merge,索引数组会合并在一起并重新索引,关联索引相同时后面覆盖前面');
        $array1 = array(0, 1, 2, 3, 4);
        $array2 = array(5, 6, 7, 8, 9);
        $array3 = array("a" => "1", "b" => "2", "c" => "3", "d" => "4", "f" => "5");
        $array4 = array(0, "a" => "2", 2, "b" => "1", 4);
        the_print('$array1');
        the_print($array1);
        the_print('$array2');
        the_print($array2);
        the_print('$array1+$array2');
        the_print($array1 + $array2);
        the_print('array_merge($array1, $array2)');
        the_print(array_merge($array1, $array2));
        the_print('$array3');
        the_print($array3);
        the_print('$array4');
        the_print($array4);
        the_print('$array3 + $array4');
        the_print($array3 + $array4);
        the_print('array_merge($array3, $array4)');
        the_print(array_merge($array3, $array4));
        the_print('array_filter($array3),返回1,引用参数');
        shuffle($array3);
        the_print($array3);
        $array5 = array(null, "", 0, false, true, 1, "1");
        the_print('$array5');
        the_print($array5);
        the_print('array_filter($array5)');
        the_print(array_filter($array5));

    }

}

