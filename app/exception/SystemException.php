<?php

namespace app\exception;

use fastswoole\BaseException;

/**
 * 100-999
 * Class SystemException
 * @package app\exception
 */
class SystemException extends BaseException
{
    const SYSTEM_ERROR = ['msg' => '系统错误', 'code' => 10000,];
    const MODULE_DOES_NOT_EXIST = ['msg' => '模块不存在', 'code' => 10001,];
    const CONTROLLER_DOES_NOT_EXIST = ['msg' => '控制器不存在', 'code' => 10002,];
    const METHOD_DOES_NOT_EXIST = ['msg' => '方法不存在', 'code' => 10003,];
}
