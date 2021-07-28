<?php

namespace app\index\model;

use fastswoole\InstanceTrait;
use model\Model;

/**
 * 用户Model
 */
class Company extends Model
{
    use  InstanceTrait;

    /**
     * 自定义当前模型操作的数据库表名称，
     * 如果不指定，默认为类名称的小写字符串，
     * 这里就是 item 表
     * @var string
     */
    public $table = 'company';
    public $id;

    /**
     * 搜索功能，因为Sql父类里面没有现成的like搜索，
     * 所以需要自己写SQL语句，对数据库的操作应该都放
     * 在Model里面，然后提供给Controller直接调用
     *
     * @param $title string 查询的关键词
     *
     * @return array 返回的数据
     */
    public function search($keyword)
    {
        $res = static::where('item_name', "like", "%{$keyword}%")->select();
        return $res;
    }


    public function item2()
    {
        return [Item::class, 'id', 'id'];
    }

}