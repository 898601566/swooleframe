<?php

namespace app\index\controller;

use app\index\model\Item;
use common\design_pattern\abstract_factory\MacFactory;
use common\design_pattern\abstract_factory\WinFactory;
use common\Http;
use fastswoole\Controller;

class DesignPattern extends Controller
{
    public function index()
    {
        $html = $this->render();
        $this->app->html($html);
    }

    public function abstractFactory()
    {
        ob_start();
        ob_implicit_flush(false);
        $win_factory = new WinFactory();
        $mac_factory = new MacFactory();
        echo $win_factory->CreateComputer();
        echo $win_factory->CreatePad();
        echo $mac_factory->CreateComputer();
        echo $mac_factory->CreatePad();
        $html_content = ob_get_clean();
        $this->app->html($html_content);
    }


    public function showColumn()
    {
        $result = Item::instance()->find();
        echo "<pre>";
        var_dump([$result]);
        exit;
        $sql = 'show full COLUMNS from item';
        $result = Item::instance()->sql($sql);
        foreach ($result as $key => $value) {
            $define = sprintf('public $%s;', $value['Field']);
            the_print($define, 0);
        }
    }

}
