<?php

$config = [
    [
        'pattern' => "*",
        'function' => "0次或多次"
    ], [
        'pattern' => "+",
        'function' => "1次或多次"
    ], [
        'pattern' => "{}",
        'function' => "指定内容重复次数"
    ], [
        'pattern' => "[]",
        'function' => "字符集合"
    ], [
        'pattern' => "^",
        'function' => "开始"
    ], [
        'pattern' => "$",
        'function' => "末尾"
    ], [
        'pattern' => "|",
        'function' => "或"
    ], [
        'pattern' => "()",
        'function' => "提权"
    ], [
        'pattern' => ".",
        'function' => '匹配换行符(\n)之外的字符'
    ], [
        'pattern' => "-",
        'function' => '指明字符范围'
    ], [
        'pattern' => "?",
        'function' => '重复0次或1次'
    ], [
        'pattern' => '\w',
        'function' => "字母|数字|下划线|汉字"
    ], [
        'pattern' => '\s',
        'function' => '匹配任意空白符'
    ], [
        'pattern' => '\d',
        'function' => '匹配数字'
    ], [
        'pattern' => '\b',
        'function' => '匹配单词的开始或结束'
    ], [
        'pattern' => '\n',
        'function' => '换行符'
    ], [
        'pattern' => '(exp)',
        'function' => '匹配exp,放到自动命名的组里'
    ], [
        'pattern' => '(?<name>exp)',
        'function' => "匹配exp,放到自动命名的组里,用\k<name>替代"
    ], [
        'pattern' => '(?:exp)',
        'function' => '匹配exp,不捕获,不分组'
    ], [
        'pattern' => '(?=exp)',
        'function' => '匹配exp前面位置,后面是exp'
    ], [
        'pattern' => '(?<=exp)',
        'function' => '匹配exp后面位置,前面是exp'
    ], [
        'pattern' => '(?!exp)',
        'function' => '匹配后面不是exp的位置'
    ], [
        'pattern' => '(?<!exp)',
        'function' => '匹配前面不是exp的位置'
    ],
];

return $config;