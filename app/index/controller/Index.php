<?php

namespace app\index\controller;

use app\models\Item;
use common\ArrayTest;
use fastswoole\Controller;


class Index extends Controller
{
    public function exception_handler($exception)
    {
        echo "Uncaught exception 1: ", $exception->getMessage(), "\n";
    }

    public function index($str = "hello world")
    {
       return $this->navigate();
    }

    public function navigate()
    {
        $view = $this->render(__FUNCTION__);
        return $view;
    }

    public function arrayTest()
    {

        $obj = new ArrayTest();
        $obj->run();

    }

}
