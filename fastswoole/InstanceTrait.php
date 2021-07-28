<?php
/**
 * Created by PhpStorm.
 * User: zhengze
 * Date: 2019/7/1
 * Time: 18:29
 */

namespace fastswoole;

/**
 * 获取类实例(单例)
 * @package app\common\traits
 */
trait InstanceTrait
{

    protected static $instance = null;

    /**
     * @param array $options
     * @return static
     */
    public static function instance($options = [])
    {
        if (is_null(static::$instance)) {
            try {
                static::$instance = new static($options);
            } catch (\Throwable $e) {
                ExceptionHandler::instance()->render($e);
            }
        }
        return static::$instance;
    }
}
