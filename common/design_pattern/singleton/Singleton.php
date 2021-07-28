<?php
namespace common\design_pattern\singleton;
//singleton
use fastswoole\InstanceTrait;

class Singleton
{
    private static $_subject;
    public $name = 'default';
    
    public function getSubject($value)
    {
        return $this->_subject;
    }

    public function setSubject($value)
    {
        $this->_subject = $value;
    }

    public static function getInstance()
    {
        if (!(self::$_subject instanceof self))
        {
            self::$_subject = new self;
        }
        return self::$_subject;
    }

//防止对象被复制
    public function __clone()
    {
        trigger_error('Clone is not allowed !');
    }

}

