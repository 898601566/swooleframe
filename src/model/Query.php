<?php
/**
 * Created by : PhpStorm
 * User: Sin Lee
 * Date: 2021/7/30
 * Time: 16:27
 */

namespace Fastswoole\model;

use App\exception\SystemException;
use Fastswoole\core\Di;
use Helper\ArrayHelper;
use Helper\StringHelper;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Swoole\Database\PDOStatementProxy;
use \Swoole\Database\PDOPool;

class Query
{
    // 数据库表名
    protected $table;
    /**
     * @var \PDO|null
     */
    protected $pdo_object;

    private $sqls = [];
    private $source_sql = [];
    private $model;
    private $pk;
    // WHERE和ORDER拼装后的条件
    private $condition_str;
    private $join_str;
    private $alias_str;
    private $order_str;
    private $limit_str;
    private $group_str;
    private $bind;
    private $field;
    private $model_class_name;
    private $with_str;

    public function __construct(Model $model)
    {
        $this->model = $model;
        $this->pk = $model->pk;
        $rf = new \ReflectionObject($this->model);

        $this->model_class_name = $rf->name;
        $this->table = $model->table;
        $this->_init();
    }

    public function __isset($name)
    {
        return isset($this->$name);
    }

    public function __set($name, $value)
    {
        $this->$name = $value;
    }

    public function __get($name)
    {
        return $this->$name;
    }

    /**
     * 初始化
     */
    public function _init()
    {
        $this->bind = [];
        $this->field = "*";
        // 数据库主键
        $this->pk = 'id';
        // WHERE和ORDER拼装后的条件
        $this->condition_str = '';
        $this->join_str = '';
        $this->alias_str = '';
        $this->order_str = '';
        $this->limit_str = '';
        $this->group_str = '';
        $this->with_str = "";
        $this->bind = [];
        $this->field = "*";
    }

    /**
     * 清除搜索信息
     */
    public function clear()
    {
        $this->_init();
        PDOInstance::clear($this->pdo_object);
        $this->pdo_object = NULL;
    }

    /**
     * 清除搜索信息
     */
    public function getPDO()
    {
        if (empty($this->pdo_object)) {
            $this->pdo_object = PDOInstance::instance();
        }
        return $this->pdo_object;
    }

    /**
     * 分解where条件,然后写入condition属性
     *
     * @param $where
     * @param string $method
     * @param string $value
     *$field, $operate = null, $condition = null
     *
     * @return $this
     */
    public function where($field, $operate = '', $condition = NULL)
    {
        if (empty($field)) {
            return $this;
        }
//        两个参数的情况,视为=运算符
        if (empty($condition)) {
            $condition = $operate;
            $operate = '=';
        }
        switch (TRUE) {
            case $field instanceof \Closure:
                $this->whereSplicce(" ( ", '', 'AND');
                $field($this);
                $this->whereSplicce(" ) ");
                break;
            case is_array($field):
                foreach ($field as $value) {
                    $this->where($value[0], $value[1], $value[2]);
                }
                break;
            case !empty($operate) && isset($condition):
                $bindKey = $this->getBindKey($field);
                switch ($operate) {
                    case 'in':
                        //处理非数组的值
                        $condition = is_array($condition) ? $condition : [$condition];
                        foreach ($condition as $condition2) {
                            $bindKey = $this->getBindKey($field);
                            $bindKeys[] = $bindKey;
                            $binds[$bindKey] = $condition2;
                        }
                        $bindKeys = implode(',', $bindKeys);
                        $this->whereSplicce("$field $operate ($bindKeys)", $binds, ' AND ');
                        break;
                    default:
                        $this->whereSplicce("$field $operate $bindKey", ["$bindKey" => $condition], ' AND ');
                        break;
                }
                break;
            default:
                $this->whereSplicce($field, '', 'AND');
                break;
        }
        return $this;
    }

    /**
     * 分解whereOr条件,然后写入condition属性
     *
     * @param $where
     * @param string $method
     * @param string $value
     *$field, $operate = null, $condition = null
     *
     * @return $this
     */
    public function whereOr($field, $operate = '', $condition = NULL)
    {
        if (empty($field)) {
            return $this;
        }
//        两个参数的情况,视为=运算符
        if (empty($condition)) {
            $condition = $operate;
            $operate = '=';
        }
        switch (TRUE) {
            case $field instanceof \Closure:
                $this->whereSplicce(" ( ", '', 'OR');
                $field($this);
                $this->whereSplicce(" ) ");
                break;
            case is_array($field):
                foreach ($field as $value) {
                    $this->whereOr($value[0], $value[1], $value[2]);
                }
                break;
            case !empty($operate) && isset($condition):
                $bindKey = $this->getBindKey($field);
                switch ($operate) {
                    case 'in':
                        //处理非数组的值
                        $condition = is_array($condition) ? $condition : [$condition];
                        foreach ($condition as $condition2) {
                            $bindKey = $this->getBindKey($field);
                            $bindKeys[] = $bindKey;
                            $binds[$bindKey] = $condition2;
                        }
                        $bindKeys = implode(',', $bindKeys);
                        $this->whereSplicce("$field $operate ($bindKeys)", $binds, ' OR ');
                        break;
                    default:
                        $this->whereSplicce("$field $operate $bindKey", ["$bindKey" => $condition], ' OR ');
                        break;
                }
                break;
            default:
                $this->whereSplicce($field, '', 'OR');
                break;
        }
        return $this;
    }

    /**
     * 设置join条件
     *
     * @param string $join
     * @param null $condition
     * @param string $type
     *
     * @return $this
     */
    public function join(string $join, $condition = NULL, $type = 'INNER'): self
    {
        $this->join_str = sprintf("%s %s %s %s %s ", $type, 'join', $join, 'on', $condition);
        return $this;
    }

    /**
     * 别名
     *
     * @param $alias
     *
     * @return $this
     */
    public function alias($alias): self
    {
        $this->alias_str = $alias;
        return $this;
    }

    /**
     * bind差异化
     *
     * @param $key
     * @param string $prefix
     *
     * @return string
     */
    public function getBindKey($key, $prefix = 'bind'): string
    {
        $bindKey = str_replace('.', '__', "$key");
        $bindKey = str_replace('%', '_', $bindKey);
        $retKey = ":{$prefix}_{$bindKey}";
        if (array_key_exists($retKey, $this->bind)) {
            $retKey .= "_" . count($this->bind);
        }
        $this->bind[$retKey] = NULL;
        return $retKey;
    }

    /**
     * 拼接where
     *
     * @param $condition_str
     * @param array $bind
     * @param string $type AND OR 或者不填
     *
     * @return $this
     */
    protected function whereSplicce($condition_str, $bind = [], $type = ""): self
    {

        $trim_condition_str = trim($this->condition_str);
        //没有where填充where
        if (FALSE === strpos($trim_condition_str, 'WHERE')) {
            $this->condition_str .= " WHERE ";
            $trim_condition_str = trim($this->condition_str);
        }
        //写入实例condition_str属性
        if ($condition_str) {
            //判断是否需要AND OR
            if (StringHelper::endsWith($trim_condition_str, 'WHERE')
                || StringHelper::endsWith($trim_condition_str, '(')) {
                $this->condition_str .= " $condition_str ";
            } else {
                $this->condition_str .= " {$type} $condition_str ";
            }
            if (!empty($bind)) {
                $this->addBind($bind);
            }
        }
        return $this;
    }

    /**
     *
     * @param $wherestr
     * @param array $bind
     *
     * @return $this
     */
    protected function addBind($bind = [], $prefix = '')
    {
        $prefix = $prefix ? "{$prefix}_" : $prefix;
        foreach ($bind as $key => $value) {
            $this->bind["{$prefix}{$key}"] = $value;
        }
        return $this;
    }

    /**
     * 绑定field
     *
     * @param $field 支持key-value的数组或原生字符串
     *
     * @return $this
     */
    public function field($field = [])
    {
        if (is_array($field)) {
            $this->field = "";
            foreach ($field as $key => $value) {
                $key = is_int($key) ? $value : $key;
                if (!empty($this->field)) {
                    $this->field .= ",$key as $value";
                } else {
                    $this->field .= "$key as $value";
                }
            }
        }
        if (is_string($field)) {
            $this->field = $field;
        }
        return $this;
    }

    /**
     * 拼装排序条件，使用方式：
     * $this->order(['id DESC', 'title ASC', ...])->fetch();
     *
     * @param array $order 排序条件
     *
     * @return $this
     */
    public function order($order = [])
    {
        if ($order) {
            $this->order_str .= ' ORDER BY ';
            $this->order_str .= implode(',', $order);
        }

        return $this;
    }

    /**
     * 分组
     *
     * @param $group
     *
     * @return $this
     */
    public function group($group)
    {
        if (!empty($group)) {
            $this->group_str .= " GROUP BY $group ";
        }
        return $this;
    }

    /**
     * 分页
     *
     * @param int $page
     * @param int $limit
     *
     * @return $this
     */
    public function limit($page = 1, $limit = 20)
    {
        if (!empty($page)) {
            $page = $page - 1;
            $page = $page * $limit;
            $this->limit_str .= " LIMIT $page,$limit ";
        } else {
            $this->limit_str .= " LIMIT $limit ";
        }
        return $this;
    }

    /**
     * 查询数组(单个查询最后还是会用到它)
     * @return Collection
     */
    public function select()
    {
//        拼接各个模块
        $condition = $this->condition_str . $this->group_str . $this->order_str . $this->limit_str;
//        构造sql查询语句
        $sql = sprintf('select %s from `%s` %s', $this->field, $this->table, $condition);
//        从PDO获取实例
        $sttmnt = $this->getPDO()->prepare($sql);
        $sttmnt = $this->formatBind($sttmnt, $sql, $this->bind);
        $sttmnt->execute();
        $res = $sttmnt->fetchAll();
//        构造模型类再塞入集合
        $model_class_name = $this->model_class_name;
        $ret = new Collection();
        foreach ($res as $key => $value) {
            /**
             * @var Model $model
             */
            $model = new $model_class_name();
            $ret->push($model->resultSet($value));
        }
        if (!empty($this->with_str)) {
            $ret->load($this->with_str);
        }
        $this->clear();
        return $ret;
    }

    /**
     *  直接查询sql
     */
    public function sql($sql)
    {
        $sttmnt = $this->getPDO()->prepare($sql);
        $sttmnt->execute();
        $res = $sttmnt->fetchAll();
        return $res;
    }

    /**
     *  直接查询sql
     */
    public function with($with)
    {
        $this->with_str = $with;
        return $this;
    }

    /**
     * 查询一条记录
     * @param array $id
     *
     * @return Model
     * @todo 主键查询
     */
    public function find($id = 0)
    {
        if (!empty($id)) {
            $this->where($this->model->pk, '=', $id);
        }
        $res = $this->limit(1)->select();
        if (FALSE == $res->isEmpty()) {
            return $res[0];
        }
        return NULL;
    }

    /**
     * 统计数量
     * @param string $field
     *
     * @return int|mixed|null
     */
    public function count($field = '*')
    {
        $one = $this->field(["count($field)" => 'count'])->find();
        return !empty($one) ? $one['count'] : 0;
    }

    /**
     * 求最小值
     * @param $field
     *
     * @return int|mixed|null
     */
    public function min($field)
    {
        $one = $this->field(["min($field)" => 'min'])->find();
        return !empty($one) ? $one['min'] : 0;
    }

    /**
     * 求最大值
     * @param $field
     *
     * @return int|mixed|null
     */
    public function max($field)
    {
        $one = $this->field(["max($field)" => 'max'])->find();
        return !empty($one) ? $one['max'] : 0;
    }

    /**
     * 求和
     * @param $field
     *
     * @return int|mixed|null
     */
    public function sum($field)
    {
        $one = $this->field(["sum($field)" => 'sum'])->find();
        return !empty($one) ? $one['sum'] : 0;
    }

    /**
     * 求平均值
     * @param $field
     *
     * @return int|mixed|null
     */
    public function avg($field)
    {
        $one = $this->field(["avg($field)" => 'avg'])->find();
        return !empty($one) ? $one['avg'] : 0;
    }

    /**
     * 占位符绑定具体的变量值
     *
     * @param PDOStatementProxy $sttmnt 要绑定PDOStatementProxy对象
     * @param array $binds 参数
     *
     * @return PDOStatementProxy
     */
    public function formatBind($sttmnt, $queryString, $binds = [])
    {
        if (empty($binds)) {
            $binds = $this->bind;
        }
        foreach ($binds as $bind => $value) {
            $bind = is_int($bind) ? $bind + 1 : ':' . trim($bind, ':');
            $sttmnt->bindValue($bind, $value);
            $binds[$bind] = "'$value'";
        }
        $this->sqls[] = strtr($queryString, $binds);
        $this->source_sql[] = [
            $queryString,
            $this->bind,
        ];
        if (env('app.debug')) {
            static::recordLog($queryString);
            static::recordLog(strtr($queryString, $binds));
        }
        return $sttmnt;
    }

    public function getLastSql()
    {

        return ArrayHelper::last($this->sqls);
    }

    public function getSqls()
    {
        return $this->sqls;
    }

    public function getSourceSql()
    {
        return $this->source_sql;
    }

    /**
     * 新增数据
     *
     * @param type $data
     */
    public function add($data)
    {
        $sql = sprintf('insert into `%s` %s', $this->table, $this->formatInsert($data));
        $sttmnt = $this->getPDO()->prepare($sql);
        $sttmnt = $this->formatBind($sttmnt, $sql);

        $lastInsertId = NULL;
        if ($sttmnt->execute()) {
            $lastInsertId = $this->pdo_object->lastInsertId();
        }
        $this->clear();
        return $lastInsertId;
    }

    public function create($data)
    {
        return $this->add($data);
    }

    public function insert($data)
    {
        return $this->add($data);
    }

    /**
     * 更新数据
     *
     * @param type $data
     */
    public function update($data, $where = [])
    {
        if (!empty($where)) {
            $this->where($where);
        }
        $sql = sprintf('update `%s` set %s %s', $this->table, $this->formatUpdate($data), $this->condition_str);
        var_dump($this->condition_str);
        var_dump($this->bind);
        $sttmnt = $this->getPDO()->prepare($sql);
        $sttmnt = $this->formatBind($sttmnt, $sql, $this->bind);
        $sttmnt->execute();
        $count = $sttmnt->rowCount();
        $this->clear();
        return $count;
    }

    /**
     * 将数组转换成插入格式的sql语句
     *
     * @param Array $data
     */
    private function formatInsert(array $data)
    {
        $field_arr = [];
        $bind_name_arr = [];
        $bind_data = [];
        foreach ($data as $key => $value) {
            $field_arr[] = sprintf('`%s`', $key);
            $bindKey = $this->getBindKey($key);
            $bind_name_arr[] = $bindKey;
            $bind_data[$bindKey] = $value;
        }
        $this->addBind($bind_data);
        $field = implode(',', $field_arr);
        $bind_name = implode(',', $bind_name_arr);
        $ret = sprintf('( %s ) values ( %s )', $field, $bind_name);
        return $ret;
    }

    /**
     * 将数组转换成更新格式的sql语句
     *
     * @param type $data
     *
     * @return type
     */
    private function formatUpdate($data)
    {
        $field_arr = [];
        $bind_data = [];
        foreach ($data as $key => $value) {
            $bindKey = $this->getBindKey($key);
            $field_arr[] = sprintf(' `%s` = %s ', $key, $bindKey);
            $bind_data[$bindKey] = $value;
        }
        $this->addBind($bind_data);
        $field = implode(',', $field_arr);
        return $field;
    }

    /**
     * 根据条件主键删除
     *
     * @param integer|array $where
     *
     * @return type
     */
    public function delete($where = [])
    {
        if (!empty($where)) {
            if (is_int($where)) {
                $this->where($this->pk, $where);
            } else {
                $this->where($where);
            }
        }
        $sql = sprintf("delete from `%s` %s", $this->table, $this->condition_str);
        if (FALSE == strpos($sql, "WHERE")) {
            var_dump($sql);
            SystemException::throwException(SystemException::SYSTEM_ERROR);
        }
        $sttmnt = $this->getPDO()->prepare($sql);
        $sttmnt = $this->formatBind($sttmnt, $sql, $this->bind);
        $sttmnt->execute();
        $rowCount = $sttmnt->rowCount();
        $this->clear();
        return $rowCount;
    }

    /**
     * 开始事务
     */
    public function transaction(callable $callback)
    {
        $this->pdo_object->beginTransaction();
        try {
            call_user_func($callback);
        }
        catch (\Exception $exception) {
            $this->pdo_object->rollBack();
            throw $exception;
        }
        $this->pdo_object->commit();
    }

    /**
     * 开始事务
     */
    public function beginTransaction()
    {
        $this->pdo_object->beginTransaction();
    }

    /**
     * 提交事务
     */
    public function commit()
    {
        $this->pdo_object->commit();
    }

    /**
     * 回滚
     */
    public function roolback()
    {
        $this->pdo_object->rollBack();
    }

    /**
     * 将异常写入日志
     *
     * @param \String $sql
     */
    private static function recordLog($sql, $level = Logger::DEBUG)
    {
        $Logger = new Logger('Sql');
        $Logger->pushHandler(new StreamHandler(APP_PATH . '/runtime/logs/sql.log', $level));
        $Logger->debug($sql);
        return TRUE;
    }

}
