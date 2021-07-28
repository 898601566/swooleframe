<?php

//有一个业务,打印然后输出

abstract class AbstractClass
{ // 抽象模板角色

    public function my_dump($arr)
    { // 模板方法 调用基本方法组装顶层逻辑
        $arr = $this->my_sort($arr);
        echo "<pre>";
        foreach ($arr as $key => $value)
        {
            echo "$key=>$value<br/>";
        }
    }

    abstract protected function my_sort($arr); // 基本方法
}

class ConcreteClass extends AbstractClass
{ // 具体模板角色

    protected function my_sort($arr)
    {
        sort($arr);
        return $arr;
    }

}

