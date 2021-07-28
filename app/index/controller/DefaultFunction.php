<?php

namespace app\index\controller;

use app\models\Item;
use common\ArrayTest;
use common\Ob;
use common\PdoExample;
use common\Sort;
use common\StringTest;
use fastswoole\Controller;

class DefaultFunction extends Controller
{


    public function stringTest()
    {
        ob_start();
        $obj = StringTest::instance();
        $obj->run();
        $html_content = ob_get_clean();
        $html = $this->_view->loadTemplate($html_content);
        $this->app->html($html);
    }

    public function arrayTest()
    {
        ob_start();
        $obj = ArrayTest::instance();
        $obj->run();
        $html_content = ob_get_clean();
        $html = $this->_view->loadTemplate($html_content);
        $this->app->html($html);
    }

    public function pdo()
    {
        ob_start();
        $obj = PdoExample::instance();
        $obj->run();
        $html_content = ob_get_clean();
        $html = $this->_view->loadTemplate($html_content);
        $this->app->html($html);
    }

    public function sort()
    {
        ob_start();
        $obj = Sort::instance();
        $obj->run();
        $html_content = ob_get_clean();
        $html = $this->_view->loadTemplate($html_content);
        $this->app->html($html);
    }

    public function ob()
    {
        $html_content = "框架要用到ob,这方面直接看源码";
        $obj = Ob::instance();
        $obj->run();
        $html = $this->_view->loadTemplate($html_content);
        $this->app->html($html);
    }

    public function ifelse()
    {
        $html_content = "if,else,label";
        $html = $this->_view->loadTemplate($html_content);
        $this->app->html($html);
    }

    public function quote()
    {
        $html_content = "quote";
        ob_start();
        $code = '
        $obj = new common\StringTest();
        $obj2 = $obj;
        $obj2->aaa = 111;
        unset($obj2);
        the_print($obj->aaa);
        ';
        start_pre();
        the_print($code, 0);
        end_pre();
        start_pre();
        eval($code);
        end_pre();
        $html_content = ob_get_clean();
        $html = $this->_view->loadTemplate($html_content);
        $this->app->html($html);
    }

    public function csv2()
    {
        ini_set('memory_limit', '2048M');
        set_time_limit(0);
        $dsn = "mysql:host=192.168.200.1;dbname=fang;charset=utf8mb4;port=33061";
        $username = 'fang';
        $password = 'iK2JMYPnUJ3j61Lj';
        $pdo_config = [
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC
        ];
        $pdo_object = new \PDO($dsn, $username, $password, $pdo_config);
        $sql = 'select order_id,medium_truename from e_order where order_id >:id limit 500000';
        $statement_obj = $pdo_object->prepare($sql);
        $statement_obj->bindValue(":id", 0);
        $res = $statement_obj->execute();
        if ($res) {
            $users = $statement_obj->fetchAll();
            foreach ($users as $key => $value) {
                $users[$key] = array_values($value);
            }
            $config = ['path' => APP_PATH . "/public/"];
            $excel = new \Vtiful\Kernel\Excel($config);
// fileName 会自动创建一个工作表，你可以自定义该工作表名称，工作表名称为可选参数
            $filePath = $excel->fileName('tutorial03.xlsx', 'sheet1')
                              ->header(['order_id', 'medium_truename'])
                              ->data($users)
                              ->output();
        }
    }

    public function csv()
    {
        ini_set('memory_limit', '2048M');
        set_time_limit(0);
        function export_csv($filename, $data, $columns, $chunk = 1000000)
        {
            header('Content-Type: application/csv; charset=UTF-8');
            header('Content-Disposition: attachment; filename="' . $filename . '.csv"');
            header('Cache-Control: max-age=0');

            //随机字符串
            $prefix = "aaa";

            $fileList = []; // 文件集合
            $file = APP_PATH . "/public/${prefix}_${filename}_1.csv";
            touch($file);
            $fileList[] = $file;

            $fp = fopen($file, 'w');
            fwrite($fp, chr(0xEF) . chr(0xBB) . chr(0xBF));
            fputcsv($fp, array_column($columns, 'title'));

            // 计数器
            $i = 0;
            // 每隔$limit行刷新一下输出buffer，不要太大，也不要太小
            $limit = 10000;
            // 行上限
            $maxLimit = 100000000;
            foreach ($data as $item) {
                if ($i >= $maxLimit) {
                    break;
                }
                if ($i > 0 && $i % $chunk == 0) {
                    fclose($fp);  // 关闭上一个文件
                    $j = $i / $chunk + 1;
                    $file = "app/public/${prefix}_${filename}_$j.csv";
                    touch($file);
                    $fileList[] = $file;
                    $fp = fopen($file, 'w');
                    fwrite($fp, chr(0xEF) . chr(0xBB) . chr(0xBF));
                    fputcsv($fp, array_column($columns, 'title'));
                }

                $i++;

                if ($i % $limit == 0) {
                    ob_flush();
                    flush();
                }

                $row = [];

                foreach ($columns as $column) {
                    $value = isset($column['index']) ? $item[$column['index']] : null;
                    if (is_numeric($value) && strlen($value) > 10) {
                        $value .= "\t";
                    }
                    $row[] = $value;
                }

                fputcsv($fp, $row);
                unset($row);
            }
            fclose($fp);
            if (count($fileList) > 1) {
                $zip = new \ZipArchive();
                $oldFilename = $filename;
                $filename = "app/public/${prefix}_${filename}.zip";
                touch($filename);
                $zip->open($filename, ZipArchive::CREATE); // 打开压缩包

                foreach ($fileList as $file) {
                    $zip->addFile($file, str_replace("${prefix}_", '', basename($file)));   // 向压缩包中添加文件
                }
                $zip->close(); // 关闭压缩包

                foreach ($fileList as $file) {
                    @unlink($file); // 删除csv临时文件
                }

                // 输出压缩文件提供下载
                header("Cache-Control: max-age=0");
                header("Content-Description: File Transfer");
                header('Content-disposition: attachment; filename=' . $oldFilename . '.zip');
                header("Content-Type: application/zip"); // zip格式的
                header("Content-Transfer-Encoding: binary");
                header('Content-Length: ' . filesize($filename));
            } else {
                $filename = $fileList[0];
            }
            @readfile($filename); // 输出文件;
            @unlink($filename); // 删除压缩包临时文件
        }

        $dsn = "mysql:host=192.168.200.1;dbname=fang;charset=utf8mb4;port=33061";
        $username = 'fang';
        $password = 'iK2JMYPnUJ3j61Lj';
        $pdo_config = [
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC
        ];
        $pdo_object = new \PDO($dsn, $username, $password, $pdo_config);
        $sql = 'select order_id,medium_truename from e_order where order_id >:id';
        $filename = APP_PATH . "/public/1.csv";
        $sql2 = "SELECT order_id,medium_truename FROM e_order where order_id >:id
INTO OUTFILE {$filename}' 
FIELDS TERMINATED BY ',' 
OPTIONALLY ENCLOSED BY '\"' 
LINES TERMINATED BY '\r\n';";

        $statement_obj = $pdo_object->prepare($sql2);
        $statement_obj->bindValue(":id", 0);
        $res = $statement_obj->execute();
        if ($res) {
            $users = $statement_obj->fetchAll();
            $columns = [
                [
                    'title' => '用户ID',
                    'index' => 'order_id',
                ],
                [
                    'title' => '用户名称',
                    'index' => 'medium_truename',
                ],
            ];
            export_csv('bbb', $users, $columns);
        }
        exit;
    }
}
