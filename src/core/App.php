<?php

namespace Fastswoole\core;

use App\exception\SystemException;
use Swoole\Http\Request;
use Swoole\Http\Response;
use Swoole\Http\Server;

/**
 * Class App
 * @package core
 */
class App
{
    use InstanceTrait;

    /**
     * @var Response
     */
    public Response $response;
    /**
     * @var Request
     */
    public Request $request;
    /**
     * @var Server
     */
    public Server $http_server;

    /**
     * view类里面才加载
     * @var Url
     */
    public Url $url;

    public $host;
    public $module;
    public $controller;
    public $action;
    public $param;


    public function __construct(Request $request, Response $response, Server $http_server)
    {
        $this->request = $request;
        $this->http_server = $http_server;
        $this->response = $response;

        $this->module = env('app.default_module');
        $this->controller = env('app.default_controller');
        $this->action = env('app.default_action');
        $this->host = $request->header['host'];

        $this->analysisUrl($request->server['request_uri']);
    }


    /**
     * 按照规则(默认值,部分提取)返回request数组
     *
     * @param Array $rule 数组
     * <br>['field'=>'default_value']
     * @param string $request_method 获取方式 get,post,param(默认)
     *
     * @return Array
     */
    public function input_extract($rule, $request_method = '')
    {
        $ret = [];
        $input = $this->input(empty($request_method) ? '' : $request_method . '.');
        foreach ($rule as $key => $value) {
            if (!is_numeric($key) && isset($input[$key])) {
                $ret[$key] = $input[$key];
            } else {
                $ret[$key] = $value;
            }
        }
        return $ret;
    }

    /**
     * 获取输入数据
     *
     * @param string $key
     * @param null $default
     */
    public function input($key = '', $default = NULL)
    {
        if ($pos = strpos($key, '.')) {
            // 指定参数来源
            $method = substr($key, 0, $pos);
            if (in_array($method, [
                'get', 'post', 'put', 'patch', 'delete', 'route', 'param', 'request', 'session', 'cookie', 'server',
                'env', 'path', 'file',
            ])) {
                $key = substr($key, $pos + 1);
            } else {
                $method = 'param';
            }
        } else {
            // 默认为自动判断
            $method = 'param';
        }
        $ret = $this->request->$method ?? $this->param;
        return $ret[$key] ?? $default;
    }

    /**
     * 获取输入数据
     *
     * @param string $key
     * @param null $default
     */
    public function request($key, $default = NULL)
    {
        $this->input($key, $default);
    }

    /**
     * 获取get数据
     *
     * @param string $key
     * @param null $default
     */
    public function get($key = '', $default = NULL)
    {
        return $this->input("get." . $key, $default);
    }

    /**
     * 获取post数据
     *
     * @param string $key
     * @param null $default
     */
    public function post($key = '', $default = NULL)
    {
        return $this->input("post." . $key, $default);
    }

    /**
     * 解析URL,分离 module  controller action
     * @get $url
     */
    public function analysisUrl($url)
    {
        //读取url "?"前面的部分
        $get_str = '';
        $url_arr = '';
        if (strpos($url, '?') !== FALSE) {
            $get_str = trim(strstr($url, '?'), '?');
            $url = strstr($url, '?', TRUE);
//            $this->url = $this->request->header['host'] . $url;
        }
        //读取url "?"前面的部分(忽略后缀)
        if (strpos($url, '.') !== FALSE) {
            $url = strstr($url, '.', TRUE);
        }
//          删除前后的“/”
        $url = trim($url, '/');
        if (!empty($url)) {
            if (strpos($url, "public") !== FALSE) {
                $url = substr($url, strpos($url, "public") + strlen("public"));
            }
//          转成数组
            $url_arr = explode('/', $url);
//            清除空值
            $url_arr = array_filter($url_arr);
            $value = array_shift($url_arr);
//            //swoole去除favicon
//            $value = $value == 'favicon' ? $this->module : $value;
            $this->module = $value ? $value : $this->module;
            $value = array_shift($url_arr);
            $this->controller = $value ? $value : $this->controller;
            $value = array_shift($url_arr);
            $this->action = $value ? $value : $this->action;
        }
        $request_uri_arr = explode('/', trim($this->request->server['request_uri'], '/'));
        $this->module = !empty($request_uri_arr[0]) ? $request_uri_arr[0] : $this->module;
        $this->controller = !empty($request_uri_arr[1]) ? $request_uri_arr[1] : $this->module;
        $this->action = !empty($request_uri_arr[2]) ? $request_uri_arr[2] : $this->module;

        $this->param = array_merge($this->request->get ?? [], $this->request->post ?? []);
    }

    /**
     * 运行控制器
     * @return null
     */
    public function run()
    {
        try {
            $controller = sprintf('App\%s\controller\%s', $this->module, $this->controller);
            $module = sprintf('%s/app/%s', APP_PATH, $this->module);
            if (!is_dir($module)) {
                BaseException::throwException(SystemException::MODULE_DOES_NOT_EXIST);
                return NULL;
            }
            if (!class_exists($controller)) {
                BaseException::throwException(SystemException::CONTROLLER_DOES_NOT_EXIST);
                return NULL;
            }
            if (!is_callable([$controller, $this->action])) {
                BaseException::throwException(SystemException::METHOD_DOES_NOT_EXIST);
                return NULL;
            }

            // 如果控制器和操作名存在，则实例化控制器，因为控制器对象里面
            // 还会用到控制器名和操作名，所以实例化的时候把他们俩的名称也
            // 传进去。结合Controller基类一起看
            $dispatch = new $controller($this, $this->controller, $this->action);
            // $dispatch保存控制器实例化后的对象，我们就可以调用它的方法，
            // 也可以像方法中传入参数，以下等同于：
            // $dispatch->$this->$this->getAction($param);
            call_user_func([$dispatch, $this->action]);
        }
        catch (\Exception $e) {
            ExceptionHandler::render($e,$this);
        }
    }

    public function html($data, $code = 200)
    {
        echo "response:" . date("Y-m-d H:i:s") . "\n";
        $this->response->header('Content-Type', 'text/html; charset=utf-8');
        $this->response->status($code);
        $this->response->end($data);
        return TRUE;
    }

    public function json($data, $code = 0, $mgs = 'success')
    {
        echo "response:" . date("Y-m-d H:i:s") . "\n";
        $this->response->header('Content-Type', 'application/json');
        $retJson = [
            'code' => $code,
            'msg' => $mgs,
            'data' => $data,
        ];
        $this->response->end(json_encode($retJson, JSON_UNESCAPED_UNICODE));
        return TRUE;
    }

    /**
     * 获取一个reids实例
     * @return \Redis
     * @throws \Throwable
     */
    public function getRedis(): \Redis
    {
        $redis = Di::instance()->get('redis');
        try {
            if (empty($redis)) {
                $redis = new \Redis();
                $res = $redis->connect(env('redis.host'), env('redis.port'), env('redis.timeout'));
                //redis连接失败不报异常，而是打印Redis server went away
                $redis->set('connect_status', $res . '-' . time(), 15);
                Di::instance()->set('redis', $redis);
            }
        }
        catch (\Exception $e) {
            echo "get_redis->" . $e->getMessage() . PHP_EOL;
            Di::instance()->delete('redis');
        }
        return $redis;
    }
}