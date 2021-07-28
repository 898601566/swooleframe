<?php

namespace app\index\model;

use model\Model;

/**
 * 用户Model
 */
class Admin extends Model
{
    /**
     * 自定义当前模型操作的数据库表名称，
     * 如果不指定，默认为类名称的小写字符串，
     * 这里就是 item 表
     * @var string
     */
    public $table = 'challenge_admin';
    public $pk = 'admin_id';


}
