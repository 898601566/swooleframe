<?php
/**
 * Created by PhpStorm.
 * User: zhengze
 * Date: 18/2/28
 * Time: 上午1:39
 */

/**
 * 写日志
 * @param $request
 */
function write_log($request)
{
    $content = [
        'date' => date("Ymd H:i:s"),
        'get' => $request->get,
        'post' => $request->post,
        'header' => $request->header,
    ];
    \Swoole\Async::writeFile(__DIR__ . "/access.log", json_encode($content) . PHP_EOL, function ($filename) {

    }, FILE_APPEND);
}

/**
 * 设置超全局变量
 * @param $request
 */
function init_global_value($request)
{
    $_SERVER = [];
    if (isset($request->server)) {
        foreach ($request->server as $key => $value) {
            $_SERVER[strtoupper($key)] = $value;
        }
    }
    if (isset($request->header)) {
        foreach ($request->header as $key => $value) {
            $_SERVER[strtoupper($key)] = $value;
        }
    }
    //PATH_INFO
    $_SERVER['argv'][1] = substr_count($_SERVER['PATH_INFO'], '/') > 2 ? $_SERVER['PATH_INFO'] : 0;
    $_SERVER['HTTP_HOST'] = empty($_SERVER['HTTP_HOST']) ? $_SERVER['HOST'] : $_SERVER['HTTP_HOST'];
    $_GET = [];
    if (isset($request->get)) {
        foreach ($request->get as $key => $value) {
            $_GET[$key] = $value;
        }
    }
    $_POST = [];
    if (isset($request->post)) {
        foreach ($request->post as $key => $value) {
            $_POST[$key] = $value;
        }
    }
}

$http = new swoole_http_server("0.0.0.0", 9501);

$http->set(
    [
        'enable_static_handler' => true,
        'document_root' => __DIR__ . '/../public/static',
        'worker_num' => 5,
    ]
);
//WorkerStart
$http->on('WorkerStart', function ($server, $worker_id) {
    require __DIR__ . '/../fastswoole/fastswoole.php';
    echo "Work\n";
});

//request
$http->on('request', function ($request, $response) use ($http) {
//    print_r($request->get);
    echo "connect\n";
//    写日志
//    write_log($request);
//    设置超全局变量
    init_global_value($request);
//    写cookie
    $response->cookie("singwa", "xsssss", time() + 1800);
    ob_start();
    echo (new fastswoole\fastswoole())->run();
    $res = ob_get_clean();
    $response->end($res);
//    $http->close();
});

$http->start();
