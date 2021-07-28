<?php

namespace common\leetCode;
class Node
{
    public $key;
    public $val;
    public $next;
    public $pre;

    public function __construct($val)
    {
        $this->val = $val;
    }
}
