<?php

//透明式合成模式
interface Component
{ // 抽象组件角色

    public function getSelf(); // 返回自己的实例

    public function operation(); // 示例方法

    public function add(Component $component); // 聚集管理方法,添加一个子对象

    public function remove(Component $component); // 聚集管理方法 删除一个子对象

    public function getChild(); // 聚集管理方法 返回所有的子对象
}

class Branch implements Component
{ // 树枝组件角色

    private $_composites;
    private $_name;

    public function __construct($name)
    {
        $this->_composites = array();
        $this->_name = $name;
    }

    public function getSelf()
    {
        return $this;
    }

    public function operation()
    { // 示例方法，调用各个子对象的operation方法
         echo $this->_name . "<br>";
        foreach ($this->_composites as $composite)
        {
            $composite->operation();
        }
    }

    public function add(Component $component)
    { // 聚集管理方法 添加一个子对象
        $this->_composites[] = $component;
    }

    public function remove(Component $component)
    { // 聚集管理方法 删除一个子对象
        foreach ($this->_composites as $key => $row)
        {
            if ($component == $row)
            {
                unset($this->_composites[$key]);
                return TRUE;
            }
        }
        return FALSE;
    }

    public function getChild()
    { // 聚集管理方法 返回所有的子对象
        return $this->_composites;
    }

}

class Leaf implements Component
{

    private $_name;

    public function __construct($name)
    {
        $this->_name = $name;
    }

    public function operation()
    {
        echo $this->_name . "<br>";
    }

    public function getSelf()
    {
        return null;
    }

    public function add(Component $component)
    {
        return FALSE;
    }

    public function remove(Component $component)
    {
        return FALSE;
    }

    public function getChild()
    {
        return null;
    }

}
