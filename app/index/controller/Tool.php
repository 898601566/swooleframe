<?php

namespace app\index\controller;

use app\exception\SystemException;
use fastswoole\App;
use fastswoole\Config;
use fastswoole\Controller;
use Helper\StringHelper;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class Tool extends Controller{
    /**
     * 转化url,让doclever可以使用
     *
     * @param string $source
     */
    public function urlToRaw()
    {
        $key_map_value = [];
        $source = $this->app->post('source', '');
        $result = urldecode($source);
        $print_arr = [];
        $raw_arr = [];
        $url = '';
        if ($result) {
            $raw_arr = explode('&', $result);
            //参数是数组的
            $arr_name_map_value = [];
            foreach ($raw_arr as $key => $value) {
                //找到问号,除去url
                if (!empty($value) && strpos($value, '?') !== FALSE) {
                    [$url, $value] = explode('?', $value);
                    $raw_arr[$key] = $value;
                }
                //祛除空数值的参数名
                if (!empty($value) && $value[strlen($value) - 1] === "=") {
                    unset($raw_arr[$key]);
                    continue;
                }
                //祛除回车
                $raw_arr[$key] = trim($raw_arr[$key], '\n');
                //提取参数是数组的
                $pos = strpos($value, '[]');
                if ($pos) {
                    unset($raw_arr[$key]);
                    $temp = substr($value, 0, $pos);
                    $temp = [$temp, explode("=", $value)[1]];
                    $arr_name_map_value[] = $temp;
                } else {
                    $print_arr[] = explode("=", urldecode($value));
                }
            }
            //参数是数组的,顺序key
            $arr_name_count = [];
            //导入参数数组
            foreach ($arr_name_map_value as $key => $value) {
                if (empty($arr_name_count[$value[0]])) {
                    $arr_name_count[$value[0]] = 0;
                }
                $raw_arr[] = sprintf('%s[%s]=%s', $value[0], $arr_name_count[$value[0]], $value[1]);
                $print_arr[] = [sprintf('%s[%s]', $value[0], $arr_name_count[$value[0]]), urldecode($value[1])];
                $arr_name_count[$value[0]]++;
            }
        }
        SystemException::throwException(SystemException::CONTROLLER_DOES_NOT_EXIST);
        $result = implode('&', $raw_arr);
        $this->assign('source', $source);
        $this->assign('result', !empty($url) ? sprintf("%s?%s", $url, $result) : $result);
        $this->assign('print_arr', $print_arr);
        $html = $this->render();
        $this->app->html($html);
    }
}
