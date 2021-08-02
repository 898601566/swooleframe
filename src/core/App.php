<?php
/**
 * Created by : PhpStorm
 * User: Sin Lee
 * Date: 2021/7/30
 * Time: 16:27
 */

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

        $this->host = $request->header['host'];
        $this->param = array_merge($request->get ?? [], $request->post ?? []);

        $request_uri_arr = explode('/', trim($request->server['request_uri'], '/'));
        $this->module = !empty($request_uri_arr[0]) ? $request_uri_arr[0] : env('app.default_module');
        $this->controller = !empty($request_uri_arr[1]) ? $request_uri_arr[1] : env('app.default_controller');
        $this->action = !empty($request_uri_arr[2]) ? $request_uri_arr[2] : env('app.default_action');
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

    /**
     * html格式返回
     * @param $data
     * @param int $code
     *
     * @return bool
     */
    public function html($data, $code = 200)
    {
        echo "response:" . date("Y-m-d H:i:s") . "\n";
        $this->response->header('Content-Type', 'text/html; charset=utf-8');
        $this->response->status($code);
        $this->response->end($data);
        return TRUE;
    }

    /**
     * json格式返回
     * @param $data
     * @param int $code
     * @param string $mgs
     *
     * @return bool
     */
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
}
