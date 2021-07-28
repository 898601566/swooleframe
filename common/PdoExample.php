<?php

namespace common;

use fastswoole\InstanceTrait;

class PdoExample
{
    use InstanceTrait;

    public function run()
    {
        $a = boolval(0);
        ++$a;
        sdump($a);
        $dsn = "mysql:host=mysql;dbname=fastswoole;charset=utf8mb4";
        $username='root';
        $password='Ro225851';
        $pdo_config=[
            \PDO::ATTR_DEFAULT_FETCH_MODE=>\PDO::FETCH_ASSOC
        ];
        $pdo_object = new \PDO($dsn,$username,$password,$pdo_config);
        $sql = 'select * from item where id =:id';
        $statement_obj = $pdo_object->prepare($sql);
        $statement_obj->bindValue(":id",1);
        $res = $statement_obj->execute();
        if ($res) {
            sdump($statement_obj->fetchAll());
        } else {
            sdump($statement_obj->errorInfo());
        }
    }
    public function run2()
    {
        echo '<pre>';
        $dsn = "mysql:host=mysql;dbname=fastswoole;charset=utf8mb4";
        $username = " root";
        $password = 'Ro225851';
        $pdo_config = [\PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC];
        $pdo_object = new \PDO($dsn, $username, $password, $pdo_config);
        $sql = "update test set test_time =:time where test_id = :id ";
        $stmnt = $pdo_object->prepare($sql);
        $stmnt->bindValue(":id", 11);
        $stmnt->bindValue(":time", date("Y-m-d"));
        $update_res = $stmnt->execute();
        if (!$update_res) {
            var_dump([$update_res, $stmnt->errorInfo()]);
        }
        $dsn = "mysql:host=mysql;dbname=fastswoole;charset=utf8mb4";
        $username = 'root';
        $password = 'Ro225851';
        $option = [\PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC];
        try {
            $pdo_object = new \PDO($dsn, $username, $password, $option);
//修改
            $sql = "update test set test_time =:time where test_id = :id ";
            $stmnt = $pdo_object->prepare($sql);
            $stmnt->bindValue(":id", 11);
            $stmnt->bindValue(":time", date("Y-m-d"));
            $update_res = $stmnt->execute();
            if (!$update_res) {
                var_dump([$update_res, $stmnt->errorInfo()]);
            }
//查询
            $sql = "select * from test where test_id =:id";
            $stmnt = $pdo_object->prepare($sql);
            $stmnt->bindValue(":id", 11);
            $stmnt->execute();
            $value = $stmnt->fetchAll();
            $error = $stmnt->errorInfo();
            echo "<pre>";
            var_dump([$value, $error, $update_res]);
        } catch (Exception $e) {
            print $e->getMessage();
        }
    }
}

