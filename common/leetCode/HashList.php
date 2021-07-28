<?php

namespace common\leetCode;
class HashList
{

    public $head;

    public $tail;

    public $size;

    public $hashTable = [];

    public function __construct(Node $head = NULL, Node $tail = NULL)
    {

        $this->head = $head;

        $this->tail = $tail;

        $this->size = 0;

    }

    /**
     * 通过一个hash表
     **/
    public function hashGet($key)
    {

        if (!empty($this->hashTable[$key])) {
            return $this->hashTable[$key];
        }
        return NULL;
    }

    /**
     * 获取数据
     *
     * @param $key
     *
     * @return int
     */
    public function getAndMove($key)
    {

        //先查hash表,没有就直接返回NULL
        $res = $this->hashGet($key);

        if (empty($res)) {
            return NULL;
        }

        $this->moveToHead($res);

        return $res->val;

    }

    /**
     * 插入新节点
     *
     * @param $key
     * @param $val
     */
    public function addAsHead($key, $val)
    {

        $node = new Node($val);
//      设置key
        $node->key = $key;
//      hashtable存入数据
        $this->hashTable[$key] = $node;
//      设置头节点pre指向$node
        if ($this->head) {
            $this->head->pre = $node;
        }
//      设置新节点next指向
        $node->next = $this->head;
//      头结点指向新节点,并把头结点pre指向NULL
        $this->head = $node;
        $this->head->pre = NULL;

        if (NULL == $this->tail) {
            $this->tail = $node;
            $this->tail->next = NULL;
        }

        $this->size++;


    }

    /**
     * 移除指针(已存在的键值对或者删除最近最少使用原则)
     *
     */
    public function removeNode()
    {
        $current = $this->tail;
        if ($current->pre) {
//          前节点的next指向NULL
            $current->pre->next = NULL;
        }

        if ($this->tail) {
//          尾节点指向尾节点的前节点
            $this->tail = $this->tail->pre;
//          清理hashTable和当前节点
            unset($this->hashTable[$current->key]);
            unset($current);
            $this->size--;
        }
    }

    //长度--


    /**
     * 把对应的节点应到链表头部(最近get或者刚刚put进去的node节点)
     *
     * @param Node $node
     */
    public function moveToHead(Node $node)
    {
        //如果已经是头结点跳过
        if ($node == $this->head) {
            return;
        }

        if ($node->pre) {
            //前节点next指向后节点
            $node->pre->next = $node->next;
        }
        if ($node->next) {
            //后节点pre指向前节点
            $node->next->pre = $node->pre;
        }
        //该节点next指向头节点
        $node->next = $this->head;
        //头节点pre指向该节点
        $this->head->pre = $node;

        //头节点指向当前节点,并把头结点pre指向NULL
        $this->head = $node;
        $this->head->pre = NULL;
    }

    public function printList()
    {
        echo "start" . "<br>";
        echo $this->size . "<br>";
        $cur = $this->head;
        while ($cur) {
            echo "val:" . $cur->val . "<br>";
            $cur = $cur->next;
        }
//        echo "hashtable" . "<br>";
//        foreach ($this->hashTable as $key => $value) {
//            echo "key:" . $key . "<br>";
//            echo "val:" . $value->val . "<br>";
//        }
        echo "end" . "<br>";
    }

}
