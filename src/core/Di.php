<?php
/**
 * Created by : PhpStorm
 * User: Sin Lee
 * Date: 2021/7/30
 * Time: 16:27
 */


namespace Fastswoole\core;

class Di
{
    use InstanceTrait;

    private $container = [];
    private $onKeyMiss = null;
    private $alias = [];

    /**
     * 别名
     * @param $alias
     * @param $key
     *
     * @return $this
     */
    public function alias($alias,$key): Di
    {
        if(!array_key_exists($alias,$this->container)){
            $this->alias[$alias] = $key;
            return $this;
        }else{
            throw new  \InvalidArgumentException("can not alias a real key: {$alias}");
        }
    }

    /**
     * 当key不存在的时候调用
     * @param callable $call
     *
     * @return $this
     */
    public function setOnKeyMiss(callable $call):Di
    {
        $this->onKeyMiss = $call;
        return $this;
    }

    /**
     * 输出别名
     * @param $alias
     *
     * @return $this
     */
    public function deleteAlias($alias): Di
    {
        unset($this->alias[$alias]);
        return $this;
    }

    /**
     * 设置容器
     * @param $key
     * @param $obj
     * @param ...$arg
     */
    public function set($key, $obj,...$arg):void
    {
        $this->container[$key] = [
            "obj"=>$obj,
            "params"=>$arg
        ];
    }

    /**
     * 根据key删除容器
     * @param $key
     *
     * @return $this
     */
    function delete($key):Di
    {
        unset($this->container[$key]);
        return $this;
    }

    /**
     * 清空容器
     * @return $this
     */
    function clear():Di
    {
        $this->container = [];
        return $this;
    }

    /**
     * 获取实例
     * @param $key
     * @return null
     * @throws \Throwable
     */
    function get($key)
    {
        if(isset($this->alias[$key])){
            $key = $this->alias[$key];
        }
        if(isset($this->container[$key])){
            $obj = $this->container[$key]['obj'];
            $params = $this->container[$key]['params'];
//          对象或者匿名函数直接返回
            if(is_object($obj) || is_callable($obj)){
                return $obj;
            }else if(is_string($obj) && class_exists($obj)){
//              字符串用反射调用
                try{
                    $ref = new \ReflectionClass($obj);
                    if(empty($params)){
                        $constructor = $ref->getConstructor();
                        if($constructor){
                            $list = $constructor->getParameters();
                            foreach ($list as $p){
                                $class = $p->getClass();
                                if($class){
                                    $temp = $this->get($class->getName());
                                }else{
                                    $temp = $this->get($p->getName()) ?? $p->getDefaultValue();
                                }
                                $params[] = $temp;
                            }
                        }
                    }
                    $this->container[$key]['obj'] = $ref->newInstanceArgs($params);
                    return $this->container[$key]['obj'];
                }catch (\Throwable $throwable){
                    throw $throwable;
                }
            }else{
                return $obj;
            }
        }else{
//          当key对应容器不存在,且$this->onKeyMiss可调用,就调用$this->onKeyMiss
            if(is_callable($this->onKeyMiss)){
                return call_user_func($this->onKeyMiss,$key);
            }
            return null;
        }
    }
}
