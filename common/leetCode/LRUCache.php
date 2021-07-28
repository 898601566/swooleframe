<?php

namespace common\leetCode;
class LRUCache
{

    /**
     * @var int 容量
     */
    private $capacity;

    /**
     * @var HashList Hash双向链表
     */
    private $list;

    /**
     * @param Integer $capacity
     */

    function __construct($capacity)
    {

        $this->capacity = $capacity;

        $this->list = new HashList();

    }

    /**
     * 获取数据
     *
     * @param Integer $key
     *
     * @return Integer
     */

    function getByKey($key)
    {

        if ($key < 0) {
            return -1;
        }

        return $this->list->getAndMove($key);

    }

    /**
     * 写入数据(数据存于头节点)
     *  先看数据原本在不在哈希表里,
     *  在里面移动到头节点
     *  不在里面插入到头节点
     *
     * @param Integer $key
     * @param Integer $value
     *
     * @return NULL
     */

    function put($key, $value)
    {

        $size = $this->list->size;

        $res = $this->list->hashGet($key);

        if ($res) {
            //如果在hashtable里面就直接移到头节点
            $this->list->moveToHead($res);
        } else {
            //如果不在hashtable里面就新增到头节点
            if ($size + 1 > $this->capacity) {
                //如果容量超过上限,移除尾节点
                $this->list->removeNode();
            }
            $this->list->addAsHead($key, $value);
        }

    }

    public function run()
    {

        for ($i = 0; $i < 5; $i++) {
            $res = $this->put($i, $i);
        }
        $this->list->printList();
        $this->getByKey(3);
        $this->getByKey(2);
        $this->list->printList();
    }

}
