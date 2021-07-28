<?php

/**
 * 环境角色类
 */
class Output
{

    private $outputStrategy;

    // 传入的参数必须是策略接口的子类或子类的实例
    public function __construct(OutputStrategy $outputStrategy)
    {
        $this->outputStrategy = $outputStrategy;
    }

    public function renderOutput($array)
    {
        return $this->outputStrategy->render($array);
    }

}

/**
 * 策略接口
 */
interface OutputStrategy
{

    public function render($array);
}

/**
 * 策略类1：返回序列化字符串
 */
class SerializeStrategy implements OutputStrategy
{

    public function render($array)
    {
        return serialize($array);
    }

}

/**
 * 策略类2：返回JSON编码后的字符串
 */
class JsonStrategy implements OutputStrategy
{

    public function render($array)
    {
        return json_encode($array);
    }

}

/**
 * 策略类3：直接返回数组
 */
class ArrayStrategy implements OutputStrategy
{

    public function render($array)
    {
        return $array;
    }

}
