<?php
/**
 * User: zhengze
 * Date: 2019/11/13
 * Time: 15:34
 */

define("ROOT_PATH", dirname(__DIR__) . "/");

/**
 * @param $class
 */
function __autoload($class)
{
    $file = $class . '.php';
    if (is_file($file)) {
        include_once $file;
    }
    if (is_file($class)) {
        include_once $class;
    }
}

spl_autoload_register('__autoload');
