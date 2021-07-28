<?php /** @noinspection ALL */

namespace fastswoole;

use app\index\controller\Tool;

class View
{
    use InstanceTrait;

    public $app = NULL;
    private $_variable = [];
    private $_load_template = 1;

    public function __construct(App $app)
    {
        $this->app = $app;
        $this->app->url = new Url($app);
    }

    /**
     * 设置变量
     *
     * @param type $key
     * @param type $value
     */
    public function assign($key, $value = '')
    {
        if (is_array($key)) {
            foreach ($key as $key1 => $value1) {
                $this->_variable[$key1] = $value1;
            }
        } else {
            $this->_variable[$key] = $value;
        }
    }

    /**
     * 渲染视图
     *
     * @param array $variable
     */
    public function render($action_name = '', $variable = [])
    {
        $action_name = $action_name ? $action_name : $this->app->action;
        foreach ($variable as $key1 => $value1) {
            $this->_variable[$key1] = $value1;
        }
        extract($this->_variable);
        $html_content_file =
            sprintf('%sapp/%s/view/%s/%s.php', APP_PATH, $this->app->module, strtolower($this->app->controller),
                $action_name);
        ob_start();
        ob_implicit_flush(FALSE);
        //判断视图文件是否存在
        if (is_file($html_content_file)) {
            include $html_content_file;
            $html_content = ob_get_clean();
        } else {
            $html_content = $action_name;
        }
        $view = $this->loadTemplate($html_content);
//        $view = $this->setTagLib($view);
        return $view;
    }


    public function setTagLib($view)
    {
//        (\|default="(.*)")?
        $pattern = '/\{\$(.*)\}/';
        preg_match_all($pattern, $view, $matches);
        foreach ($matches[0] as $key => $value) {
            $search = sprintf('{$%s}', $value);
            $replace = $matches[1][$key];
            $view = str_replace($search, $$replace, $view);
        }
        return $view;
    }

    /**
     * 加载模板
     *
     * @param $html_content
     */
    public function loadTemplate($html_content)
    {
        if ($this->_load_template) {
            ob_start();
            ob_implicit_flush(FALSE);

            $default_template_file = sprintf('%sapp/%s/view/template.php', APP_PATH, $this->app->module);
            $controller_template_file =
                sprintf('%sapp/%s/view/%s/template.php', APP_PATH, $this->app->module, strtolower($this->app->controller));
//      导入模板
            if (is_file($controller_template_file)) {
                include $controller_template_file;
            } else {
//               Tool类
                $rc = new \ReflectionClass(Tool::class);
                $tool_methods = $rc->getMethods(\ReflectionMethod::IS_PUBLIC);

                include $default_template_file;
            }
            $view = ob_get_clean();
            return $view;
        }
        return $html_content;
    }

    /**
     * 加载模板
     *
     * @param $html_content
     *
     * @return false|string
     */
    public function errorPage($url, $msg, $wait)
    {
        ob_start();
        ob_implicit_flush(FALSE);
        $default_template_file = sprintf('%sapp/index/view/404.php', APP_PATH);
//      导入模板
        include $default_template_file;
        $view = ob_get_clean();
        return $view;
    }
}
