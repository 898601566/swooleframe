<?php

namespace common;

use fastswoole\InstanceTrait;

class StringTest
{
    use InstanceTrait;

    public function run()
    {
        start_pre();
        the_print('strcmp("a","bsd")');
        the_print(strcmp("a", "z"), 0);
        the_print('strcmp("basd", "ba")');
        the_print(strcmp("basd", "ba"), 0);
        the_print('strcmp("basd", "bq")');
        the_print(strcmp("basd", "bq"), 0);
        end_pre();
        the_print('\反斜杠在字符串的区别');
        start_pre();
        the_print('strlen("123\0456")', 0);
        the_print("123\0456", 0);
        the_print("strlen('123\\0456')", 0);
        the_print('123\0456', 0);
        end_pre();
        the_print('count("abc")报错');
//        the_print(count("abc"));
        the_print('is_string');
        start_pre();
        the_print('is_string(3)');
        the_print(is_string(3), 0);
        the_print('is_string("3")');
        the_print(is_string("3"), 0);
        end_pre();
    }
}
