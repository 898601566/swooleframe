<?php
/**
 * User: zhengze
 * Date: 2019/11/13
 * Time: 9:56
 */

//namespace app\tests;

//use app\tool\Sort;
use PHPUnit\Framework\TestCase;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
//include_once "autoload.php";
//include('ROOT_PATH/../Sort.php');
class StackTest extends TestCase
{
    public function testPushAndPop()
    {

        $stack = [];
        $this->assertEquals(0, count($stack));
        $sort_obj = new Sort();
        array_push($stack, 'foo');

        // 添加日志文件,如果没有安装monolog，则有关monolog的代码都可以注释掉
        $this->Log()->error('hello', $stack);

        $this->assertEquals('foo', $stack[count($stack) - 1]);
        $this->assertEquals(1, count($stack));

        $this->assertEquals('foo', array_pop($stack));
        $this->assertEquals(0, count($stack));
        $this->assertEquals(6, 6);
        $this->assertIsArray($sort_obj->createArr(10, 100));
    }

    public function Log()
    {
        // create a log channel
        $log = new Logger('Tester');
        $log->pushHandler(new StreamHandler(ROOT_PATH . 'storage/logs/app.log', Logger::WARNING));
        $log->error("Error");
        return $log;
    }
}
