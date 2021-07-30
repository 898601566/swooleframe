<?php

namespace Fastswoole\core;

class Controller
{
    /**
     * @var App
     */
    protected $app;
    /**
     * rend渲染的时候才加载
     * @var View
     */
    protected $_view;

    public function __construct(App $app)
    {
        $this->app = $app;
    }

    /**
     * 设置变量
     *
     * @param type $key
     * @param type $value
     */
    protected function assign($key, $value = [])
    {
        $this->_view = $this->_view ?? new View($this->app);
        $this->_view->assign($key, $value);
    }

    /**
     * 渲染视图
     *
     * @param array $source
     */
    protected function render($action_name = '', $variable = [])
    {
        $this->_view = $this->_view ?? new View($this->app);
        $view = $this->_view->render($action_name, $variable);
        return $view;
    }



}
