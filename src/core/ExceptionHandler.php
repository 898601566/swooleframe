<?php

namespace Fastswoole\core;

use App\exception\SystemException;
use Exception;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

/*
 * 重写Handle的render方法，实现自定义异常消息
 */

class ExceptionHandler
{

    /**
     * 渲染自定义异常
     *
     * @param Exception $e
     *
     */
    public static function render(\Throwable $e,$app)
    {
        //如果是自定义异常则直接打印
        if (env('app.debug')) {
            static::recordErrorLog($e);
        }
        //默认的系统错误
        if (!$e instanceof BaseException) {
            BaseException::getInstance(SystemException::SYSTEM_ERROR);
        }
        $app->json([], $e->getCode(), $e->getMessage());

        return TRUE;
    }

    /**
     * 将异常写入日志
     *
     * @param Exception $e
     */
    private static function recordErrorLog(\Throwable $e, $level = Logger::DEBUG)
    {
        $log = sprintf("<p style='font-size: 36px;'>%s</p>", $e->getMessage());
        $log .= sprintf("<p style='font-size: 20px;'>%s (%s) %s</p>", $e->getFile(), $e->getLine(), "\n<br>");
        $trace = $e->getTrace();
        foreach ($trace as $key => $value) {
            if (!empty($value['file']) && !empty($value['line'])) {
                $log .= sprintf("<p style='font-size: 20px;'>%s (%s) %s</p>", $value['file'], $value['line'], "\n");
            }
        }
        $Logger = new Logger('Exception');
        $Logger->pushHandler(new StreamHandler(APP_PATH . 'logs/app.log', $level));
        $Logger->error($log);
        return $log;
    }


}
