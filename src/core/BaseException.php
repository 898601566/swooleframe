<?php

namespace Fastswoole\core;

/**
 * Class BaseException
 * 自定义异常类的基类
 */
class BaseException extends \Exception
{

    /**
     * 构造函数，设置异常的信息(用于自定义)
     *
     * @param array $params 关联数组应包含code、msg和data，且不应该是空值
     */
    public function __construct($message = [], $code = '', \Throwable $previous = null)
    {
        parent::__construct($message,$code,$previous);
    }

    /**
     * 获取异常实例
     * @param array $exception_config
     * @param string $msg
     * @param string $code
     */
    public static function getInstance(array $exception_config, $msg = '', $code = '')
    {
        $the_msg = '';
        $the_code = '';
        if (!empty($exception_config)) {
            if (!empty($exception_config['code'])) {
                $the_code = $exception_config['code'];
            }
            if (!empty($exception_config['msg'])) {
                $the_msg = $exception_config['msg'];
            }
        }

        if (!empty($msg)) {
            $the_msg = $msg;
        }

        if (!empty($code)) {
            $the_code = $code;
        }
        $exception = new static($the_msg,$the_code);
        return $exception;
    }


    /**
     * 设置异常信息并抛出
     *
     * @param array $exception_config
     *
     */
    public static function throwException(array $exception_config, $msg = '', $code = '')
    {
        throw static::getInstance($exception_config,$msg,$code);
    }
}


