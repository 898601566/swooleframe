<?php

namespace Fastswoole\core;

use Helper\ArrayHelper;

class Url
{

    private App $app;

    public function __construct(App $app)
    {
        $this->app = $app;
    }

    protected $module_controller_action;

    /**
     * @param string $relative_path
     * @param string $param
     *
     * @return mixed|string
     */
    public function createUrl($relative_path = '', $param = '')
    {
        if (empty($relative_path)) {
            return $this->app->host;
        }
        $this->module_controller_action = $this->formatRelativePath($relative_path);
        $url = sprintf('//%s/%s', $this->app->host, $this->module_controller_action);
        if ($param) {
            $param_str = $this->formatParam($param);
            $url = sprintf("%s?%s", $url, $param_str);
        }

        return $url;
    }

    /**
     * @param $param
     *
     * @return string
     */
    public function formatParam($param)
    {
        $param_arr = [];
        $param_str = $param;
        if (ArrayHelper::isIndexedArray($param)) {
            foreach ($param as $key => $value) {
                $param_arr[] = sprintf('%s=%s', $value, 1);
            }
        } else {
            if (is_array($param)) {
                foreach ($param as $key => $value) {
                    $param_arr[] = sprintf('%s=%s', $key, $value);
                }
            }
        }

        $param_str = implode('&', $param_arr);
        return $param_str;
    }

    /**
     * @param $relative_path
     *
     * @return string
     */
    public function formatRelativePath($relative_path)
    {
        $arr = explode('/', $relative_path);
        $count = count($arr);
        switch ($count) {
            case 1:
                $module_controller_action =
                    sprintf("%s/%s/%s", $this->app->module, $this->app->controller, $arr[0]);
                break;
            case 2:
                $module_controller_action = sprintf("%s/%s/%s", $this->app->module, $arr[0], $arr[1]);
                break;
            case 3:
                $module_controller_action = sprintf("%s/%s/%s", $arr[0], $arr[1], $arr[2]);
                break;
            default:
                return '';
        }
        return $module_controller_action;
    }

    /**
     *  生成带该页面get的链接
     *
     * @param string $str 控制器/方法
     * @param type 需要设置的get参数
     *
     * @return url 生成的链接
     */
    function mergeUrl($str, $arr = [], $method = "get.")
    {
        return $this->createUrl($str, array_merge($this->app->input($method), $arr));
    }
}
