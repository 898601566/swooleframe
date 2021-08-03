<?php
/**
 * Created by : PhpStorm
 * User: Sin Lee
 * Date: 2021/8/3
 * Time: 14:47
 */

namespace Fastswoole\core;

use App\exception\SystemException;

class Sign
{

    private App $app;

    public function __construct(App $app)
    {
        $this->app = $app;
    }

    /**
     * 签名校验
     * @return bool|void
     * @throws BaseException
     */
    public function verifySign()
    {
        $param = $values = array_filter($this->app->param());
        unset($values['sign']);
        $salt = env("sign.salt");
        $sort = env("sign.sort");
        $encode = env("sign.encode");
        //排序
        if (!empty($sort)) {
            $sort($values);
        }
        //合并加盐
        $sign = implode(',', $values) . $salt;
        //时间戳
        if (!empty(env("sign.timestamp"))) {
            $timestamp_field = env("sign.timestamp_field");
            if (empty($param[$timestamp_field])) {
                SystemException::throwException(SystemException::TIMESTAMP_CANNOT_BE_EMPTY);
            } else {
                $timestamp = $param[$timestamp_field];
            }
            if (time() - $timestamp > env("sign.timeout")) {
                SystemException::throwException(SystemException::TIMESTAMP_OUT);
            }
            $sign .= $timestamp;
        }
        //加密方法
        if (!empty($encode)) {
            if (strpos($encode, ",")) {
                $encode = explode(",", $encode);
                foreach ($encode as $key => $value) {
                    $sign = $value($sign);
                }
            } else {
                $sign = $encode($sign);
            }
        }
        echo "sign:".$sign."\n";
        //签名检验
        $sign_field = env("sign.sign_field");
        if (!empty($sign_field) && !empty($sign) && $sign === $param[$sign_field]) {
            return TRUE;
        }
        SystemException::throwException(SystemException::SIGNATURE_ERROR);
    }
}
