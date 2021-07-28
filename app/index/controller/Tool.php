<?php

namespace app\index\controller;

use app\index\model\Admin;
use app\exception\SystemException;
use common\Http;
use common\leetCode\Leaky;
use common\leetCode\LRUCache;
use common\leetCode\TokenBucket;
use common\template\Doclever;
use fastswoole\Config;
use fastswoole\Controller;
use fastswoole\App;
use fastswoole\Di;
use Helper\StringHelper;
use model\Model;
use model\PDOOBJ;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class Tool extends Controller
{
    /**
     * 转化url,让doclever可以使用
     *
     * @param string $source
     */
    public function urlToRaw()
    {
        $key_map_value = [];
        $source = $this->app->post('source', '');
        $result = urldecode($source);
        $print_arr = [];
        $raw_arr = [];
        $url = '';
        if ($result) {
            $raw_arr = explode('&', $result);
            //参数是数组的
            $arr_name_map_value = [];
            foreach ($raw_arr as $key => $value) {
                //找到问号,除去url
                if (!empty($value) && strpos($value, '?') !== FALSE) {
                    [$url, $value] = explode('?', $value);
                    $raw_arr[$key] = $value;
                }
                //祛除空数值的参数名
                if (!empty($value) && $value[strlen($value) - 1] === "=") {
                    unset($raw_arr[$key]);
                    continue;
                }
                //祛除回车
                $raw_arr[$key] = trim($raw_arr[$key], '\n');
                //提取参数是数组的
                $pos = strpos($value, '[]');
                if ($pos) {
                    unset($raw_arr[$key]);
                    $temp = substr($value, 0, $pos);
                    $temp = [$temp, explode("=", $value)[1]];
                    $arr_name_map_value[] = $temp;
                } else {
                    $print_arr[] = explode("=", urldecode($value));
                }
            }
            //参数是数组的,顺序key
            $arr_name_count = [];
            //导入参数数组
            foreach ($arr_name_map_value as $key => $value) {
                if (empty($arr_name_count[$value[0]])) {
                    $arr_name_count[$value[0]] = 0;
                }
                $raw_arr[] = sprintf('%s[%s]=%s', $value[0], $arr_name_count[$value[0]], $value[1]);
                $print_arr[] = [sprintf('%s[%s]', $value[0], $arr_name_count[$value[0]]), urldecode($value[1])];
                $arr_name_count[$value[0]]++;
            }
        }
        SystemException::throwException(SystemException::CONTROLLER_DOES_NOT_EXIST);
        $result = implode('&', $raw_arr);
        $this->assign('source', $source);
        $this->assign('result', !empty($url) ? sprintf("%s?%s", $url, $result) : $result);
        $this->assign('print_arr', $print_arr);
        $html = $this->render();
        $this->app->html($html);
    }

    /**
     * 解构表创建语句
     */
    public function deconstructCreateTable()
    {
        $source = App::instance()->post('source', '');
        $ret = [];
        if (!empty($source)) {
            $pattern_str = '.*`(\w+)`\s(.*?)[\s\(].*(\n?)';
            $replace_str = '$1,$2$3';
            $str = preg_replace("/(.*CREATE TABLE.*)/i", '', $source);
            $str = preg_replace("/(.*ENGINE.*)/", '', $str);
            $str = preg_replace("/(.*KEY.*)/", '', $str);
            $str = preg_replace("/(.*primary key.*)/i", '', $str);

            $str_arr = array_filter(explode("\n", $str), function ($child_str) {
                return strlen($child_str) > 3;
            });

            $java_vo = $apidoc = $hyperf_request_str = $hyperf_responce_str = $query_str = $java_entity = '';
            foreach ($str_arr as $key => $value) {
                $str = preg_replace("/{$pattern_str}/", '$1,$2$3', $value);
                $str = explode(",", $str);
                $field = $str[0];
                $type = $str[1];
                $php_type = StringHelper::sqlTypeToPhpType($type);
                $java_type = StringHelper::sqlTypeToJavaType($type);

                $value = strstr($value, 'COMMENT');
                $comment = preg_replace("/.*'(.*)'.*/", '$1', $value);
                $comment = !empty($comment) ? $comment : $field;

                //example
                switch ($php_type) {
                    case 'string':
                        if (in_array($field, ['created_at', 'updated_at']) ||
                            FALSE !== strpos($type, 'date')) {
                            $example = date("Y-m-d H:i:s");
                        } else {
                            $example = $comment;
                        }
                        break;
                    case 'float':
                        $example = 1.11;
                        break;
                    case 'int':
                        $example = 0;
                        break;
                }
                $ret[$key] = [
                    'field' => $field,
                    'type' => $type,
                    'php_type' => $php_type,
                    'java_type' => $java_type,
                    'comment' => $comment,
                    'example' => $example,
                ];
                if ($key == 0) {
                    $hyperf_request_str .= " * @ResponseParam(n=\"info\",t=\"object\",   e=\"无\", d=\"详情\")\n";
                    $hyperf_responce_str .= " * @ResponseParam(n=\"list\", t=\"array\",e=\"无\",  d=\"数组\")\n";
                    $hyperf_responce_str .= " * @ResponseParam(n=\"list.0\",t=\"map\",   e=\"无\", d=\"对象\")\n";
                }

                if (FALSE == in_array($field, ['created_at', 'updated_at'])) {
                    $hyperf_request_str .= " * @RequestParam(n=\"{$field}\",t=\"{$php_type}\",d=\"{$comment}\",e=\"{$example}\",r=false)\n";
                }
                $hyperf_responce_str .= " * @ResponseParam(n=\"list.0.{$field}\",t=\"{$php_type}\",d=\"{$comment}\",e=\"{$example}\",)\n";
                $apidoc .= "@Returned(\"$field\",type=\"$type\",default=\"$example\",desc=\"$comment\")\n";

                $query_str .= "&$field=$comment";
            }
            $doclever = $this->doclever($ret);
            $param_where = $this->getParamWhere($ret);
            $laravel_param_where = $this->getLaravelParamWhere($ret);
            $field_key_str = $this->getFieldParam($ret);
            $field_map_str = $this->getFieldMapParam($ret);
            $query_str = substr($query_str, 1);
            $javaHashMap = $this->javaHashMap($ret);
            $javaEntity = $this->javaEntity($ret);
            $javaVO = $this->javaVo($ret);
            $ret = compact('javaHashMap', 'javaVO', 'javaEntity', 'apidoc', 'field_key_str', 'field_map_str',
                'hyperf_request_str',
                'hyperf_responce_str', 'query_str', 'param_where', 'laravel_param_where');
        }
        $this->assign('source', $source);
        $this->assign('ret', $ret);
        $html = $this->render();
        $this->app->html($html);
    }

    /**
     * 解构变量词
     */
    public function deconstructParam()
    {
        $source = App::instance()->post('source', '');
        $ret = [];
        if (!empty($source)) {
            $ret['首字母大写驼峰'] = ucfirst(StringHelper::camelize($source));
            $ret['驼峰'] = StringHelper::camelize($source);
            $ret['大写驼峰'] = strtoupper(StringHelper::camelize($source));
            $ret['小写驼峰'] = strtolower(StringHelper::camelize($source));
            $ret['下划线'] = StringHelper::uncamelize($source);
            $ret['大写下划线'] = strtoupper(StringHelper::uncamelize($source));
            $ret['小写下划线'] = strtolower(StringHelper::uncamelize($source));
        }
        $this->assign('source', $source);
        $this->assign('ret', $ret);
        $html = $this->render();
        $this->app->html($html);
    }

    /**
     * 生成getFieldParam文档
     */
    protected function getFieldParam($source)
    {
        $str1 = '';
        $str2 = '';
        foreach ($source as $key => $value) {
            $str1 .= "'{$value['field']}',\n";
            $str2 .= "'{$value['field']}'=>'{$value['field']},'\n";
        }
        return $str1 . "\n" . $str2;
    }

    /**
     * 生成getFieldParam文档
     */
    protected function getFieldMapParam($source)
    {
        $str = '';
        foreach ($source as $key => $value) {
            $str .= "'{$value['field']}'=>'{$value['comment']}'\n";
        }
        return $str;
    }

    /**
     * 生成javaVo文档
     *
     * @param $source
     *
     * @return string
     */
    protected function javaVo($source)
    {
        $str = '';
        foreach ($source as $key => $value) {
            $comment = $value['comment'];
            $field = $value['field'];
            $java_type = $value['java_type'];
            $camelize_field = StringHelper::camelize($field);
            $str .= " @ApiModelProperty(value = \"$comment\", name = \"$camelize_field\")\n 
                private $java_type $camelize_field;\n\n";
        }
        return $str;
    }

    /**
     * 生成javaEntity文档
     *
     * @param $source
     *
     * @return string
     */
    protected function javaEntity($source)
    {
        $str = '';
        foreach ($source as $key => $value) {
            $comment = $value['comment'];
            $field = $value['field'];
            $java_type = $value['java_type'];
            $camelize_field = StringHelper::camelize($field);
            $str .= "private $java_type $camelize_field;\n";
        }
        return $str;
    }

    /**
     * 生成javaHashMap文档
     *
     * @param $source
     *
     * @return string
     */
    protected function javaHashMap($source)
    {
        $str = '';
        foreach ($source as $key => $value) {
            $field = $value['field'];
            $camelize_field = StringHelper::camelize($field);
            $first_up_camelize_field = ucfirst($camelize_field);
            $str .= "item.put(\"$camelize_field\",value.get$first_up_camelize_field())\n";
        }
        return $str;
    }


    /**
     * 生成ParamWhere文档
     *
     * @param $source
     *
     * @return string
     */
    protected function getParamWhere($source)
    {
        $str = '';
        foreach ($source as $key => $value) {
            switch (TRUE) {
                case $value['type'] == 'timestamp' || $value['type'] == 'datetime' ||
                     FALSE !== strpos($value['field'], 'date') ||
                     FALSE !== strpos($value['field'], 'time'):
                    $str .= <<<EOF
        if (!empty(\$params['{$value['field']}_start'])) {
                \$where[] = [\$alias . '{$value['field']}', '>=', \$params['{$value['field']}_start']];
        }
EOF;
                    $str .= <<<EOF
        if (!empty(\$params['{$value['field']}_end'])) {
                \$where[] = [\$alias . '{$value['field']}', '<=', \$params['{$value['field']}_end']];
        }
EOF;
                    break;
                case $value['php_type'] == 'string':
                    $str .= <<<EOF
        if (!empty(\$params['{$value['field']}'])) {
            if (is_array(\$params['{$value['field']}'])) {
                \$where[] = [\$alias . '{$value['field']}', 'in', \$params['{$value['field']}']];
            } else {
                \$where[] = [\$alias . '{$value['field']}', 'like', "%{\$params['{$value['field']}']}%"];
            }
        }
EOF;
                    break;
                default:
                    $str .= <<<EOF
        if (!empty(\$params['{$value['field']}'])) {
            if (is_array(\$params['{$value['field']}'])) {
                \$where[] = [\$alias . '{$value['field']}', 'in', \$params['{$value['field']}']];
            } else {
                \$where[] = [\$alias . '{$value['field']}', '=', \$params['{$value['field']}']];
            }
        }
EOF;
                    break;
            }
        }
        return $str;
    }


    /**
     * 生成LaravelParamWhere文档
     *
     * @param $source
     *
     * @return string
     */
    protected function getLaravelParamWhere($source)
    {
        $str = '';
        foreach ($source as $key => $value) {
            switch (TRUE) {
                case $value['type'] == 'timestamp' || $value['type'] == 'datetime' ||
                     FALSE !== strpos($value['field'], 'date') ||
                     FALSE !== strpos($value['field'], 'time'):
                    $str .= <<<EOF
        if (isset(\$params['{$value['field']}_start'])) {
                \$where[] = [\$alias . '{$value['field']}', '>=', \$params['{$value['field']}_start']];
        }
EOF;
                    $str .= <<<EOF
        if (isset(\$params['{$value['field']}_end'])) {
                \$where[] = [\$alias . '{$value['field']}', '<=', \$params['{$value['field']}_end']];
        }
EOF;
                    break;
                case $value['php_type'] == 'string':
                    $str .= <<<EOF
        if (isset(\$params['{$value['field']}'])) {
                \$where[] = [\$alias . '{$value['field']}', 'like', "%{\$params['{$value['field']}']}%"];
        }
EOF;
                    break;
                default:
                    $str .= <<<EOF
        if (isset(\$params['{$value['field']}'])) {
                \$where[] = [\$alias . '{$value['field']}', '=', \$params['{$value['field']}']];
        }
EOF;
                    break;
            }
        }
        return $str;
    }

    /**
     * 生成doclever文档
     */
    protected function doclever($source)
    {
        $ret = [];
        $doclever = new Doclever();
        $template = $doclever->template;
        $queryParam = $bodyParam = $outParam = '';
        foreach ($source as $key => $value) {
            $queryParam .= <<<EOF
            {
                    "name": "{$value['field']}",
                    "must": 1,
                    "remark": "{$value['comment']}",
                    "value": {
                    "type": 0,
                    "status": "",
                    "data": [
                        {
                            "value": "{$value['example']}",
                            "remark": ""
                        }
                    ]
                    }
            },
EOF;
            $bodyParam .= <<<EOF
            {
                  "name": "{$value['field']}",
                  "type": 0,
                  "must": 1,
                  "remark": "{$value['comment']}",
                  "value": {
                      "type": 0,
                      "data": [
                        {
                            "value": "{$value['example']}",
                            "remark": ""
                        }
                      ],
                      "status": ""
                  }
              },
EOF;
            $outParam .= <<<EOF
            {
                  "name": "{$value['field']}",
                  "type": 0,
                  "remark": "{$value['comment']}",
                  "must": 1,
                  "mock": "{$value['example']}"
            },
EOF;
        }
        $template = str_replace('<queryParam>', rtrim($queryParam, ','),
            $template);
        $template = str_replace('<bodyParam>', rtrim($bodyParam, ','),
            $template);
        $template = str_replace('<outParam>', rtrim($outParam, ','),
            $template);
        file_put_contents(APP_PATH . "public/doclever/table.json", $template);
        return TRUE;
    }

    /**
     * 解构数组
     */
    public function deconstructArray()
    {
        $source = App::instance()->post('source', '');

        $replace_str = App::instance()->post('replace_str', "'$1'=>'$2'");
        $pattern_str = <<<EOF
.*?['"](.+)['"].*?['"](.+)['"].*?
EOF;
        $key_to_key = preg_replace("/{$pattern_str}/", "'$1'=>'$1'", $source);
        $key_to_value = preg_replace("/{$pattern_str}/", "'$1'=>'$2'", $source);
        $value_to_value = preg_replace("/{$pattern_str}/", "'$2'=>'$2'", $source);
        $value_to_key = preg_replace("/{$pattern_str}/", "'$2'=>'$1'", $source);
        $query_str = preg_replace("/{$pattern_str}/", "&$1=$2", $source);
        //输出
        $ret = compact('key_to_key', 'key_to_value', 'value_to_value', 'value_to_key', 'query_str');
        $this->assign('source', $source);
        $this->assign('ret', $ret);
        $html = $this->render();
        $this->app->html($html);
    }


    /**
     * 抄底计算器
     */
    public function biExcel()
    {

        $config = [
            'path' => APP_PATH . '/public/' // xlsx文件保存路径
        ];
        $excel = new \Vtiful\Kernel\Excel($config);
// fileName 会自动创建一个工作表，你可以自定义该工作表名称，工作表名称为可选参数
        $filePath = $excel->fileName('trade.xlsx', '币币');
        $bi_map_high = [
            'BNB√' => '691',
            'BTC√' => '64854',
            'DOGE√' => '0.739',
            'ETH√' => '4370',
            'FIL√' => '238',
            'UNI√' => '45',
            'CAKE√' => '44.2',
            'MATIC√' => '2.7',
            'ETC√' => '179',
            'BCH' => '1650',
            'MANA' => '1.67',
            'DENT' => '0.022',
            'SHIB' => '5000',
        ];
        $data = [];
        $mergeCellrows = [1];
        $i = 1;
        foreach ($bi_map_high as $key => $value) {
            $data[] = ["$key($value)"];
            $i++;
            $biDate = $this->biDate($value, '0.618');
            foreach ($biDate['data'] as $key2 => $value2) {
                $temp = [];
                foreach ($biDate['title'] as $key3 => $value3) {
                    $temp[] = $value2[$key3];
                }
                $data[] = $temp;
            }
            $i += count($biDate['data']);
            $mergeCellrows[] = $i;
        }
        try {
            $excel->data($data);
            $i = 0;
            foreach ($bi_map_high as $key => $value) {
                $row = $mergeCellrows[$i];
                $excel->mergeCells("A{$row}:H{$row}", "$key($value)");
                $i++;
            }
            $excel->output();
        }
        catch (\Exception $exception) {
            sdump($exception);
        }
        sdump($data);
    }

    protected function biDate($base = "10000", $rate = "0.618")
    {
        $fib_rate = [0.191, 0.236, 0.382, 0.5, 0.618, 0.809];
        $fib_rate_count = count($fib_rate);
        $fib_rate = array_reverse($fib_rate);
        $pre_base = $base;
        $data = [];
        $title = [
            'fib_res' => '黄金分割结果'
            , 'fib_res1' => '黄金分割结果/1.191'
            , 'fib_res2' => '黄金分割结果/1.236'
            , 'fib_res3' => '黄金分割结果/1.382'
            , 'fib_res4' => '黄金分割结果*0.819'
            , 'fib_res5' => '黄金分割结果*0.618'
            , 'index_result' => '指数结果'
            , 'fib_rate' => '黄金分割率'
            , 'buy_in' => '买入'
            , 'profit' => '利润',
        ];
        for ($i = 0; $i < $fib_rate_count; $i++) {
            $data[] = [
                'index_result' => round($pre_base * $rate, 5)
                , 'fib_res' => round($base * $fib_rate[$i], 5)
                , 'fib_res1' => round($base * $fib_rate[$i] / 1.191, 5)
                , 'fib_res2' => round($base * $fib_rate[$i] / 1.236, 5)
                , 'fib_res3' => round($base * $fib_rate[$i] / 1.382, 5)
                , 'fib_res4' => round($base * $fib_rate[$i] * 0.809, 5)
                , 'fib_res5' => round($base * $fib_rate[$i] * 0.618, 5)
                , 'fib_rate' => $fib_rate[$i]
                , 'buy_in' => ($i + 1) * 25
                , 'profit' => (($i + 1) * 25 < 100) ? 1 : 2,
            ];
            $pre_base = $pre_base * $rate;
        }
        $ret['data'] = $data;
        $ret['title'] = $title;
        return $ret;
    }

    /**
     * 抄底计算器
     */
    public function bottomReadingCalculator()
    {
        $param = App::instance()->input_extract([
            'base' => 10000,
            'rate' => 0.8,
        ]);
        $ret = $this->biDate($param['base'], $param['rate']);
        //输出
        $this->assign('param', $param);
        $this->assign('ret', $ret);
        $html = $this->render();
        $this->app->html($html);
    }


    public function flex()
    {
        $html = $this->render();
        $this->app->html($html);
    }

    public function box()
    {
        $html = $this->render();
        $this->app->html($html);
    }

    public function sqlLog()
    {
        $file = '/www/logs/mysql/mysql.general.log';
        $clear = App::instance()->get('clear', '');
        if (!empty($clear)) {
            file_put_contents($file, '');
            $log[] = '已清空';
        } else {
            $log = $this->beautifySqlLog($file);
        }
        $this->assign('log', implode('', $log));
        $html = $this->render();
        $this->app->html($html);
    }

    protected function beautifySqlLog($filename)
    {
        $fp = @fopen($filename, "r") or exit("log文件打不开!");; //打开文件指针，创建文件

        $upstr = ''; //上一次匹配的值
        $ret = [];
        while (!feof($fp)) {
            $str = fgets($fp);
            if (strpos($str, 'SHOW')
                || strpos($str, 'Init')
                || strpos($str, 'PROFILING')) {
                continue;
            }
            $ret[] = $str = preg_replace("/(\d{4}\-\d{2}\-\d{2}).*Query/", "<br>$1", $str);
        }
        $ret = str_replace("\n", " ", $ret);
        @fclose($fp);  //关闭指针
        return $ret;
    }


    /**
     * 正则表达式示例
     */
    public function regularExpression()
    {

        $content = Config::instance()->load('regular_expression');
        $html = $this->render(__FUNCTION__, compact('content'));
        $this->app->html($html);
    }

    /**
     *正则表达式基础
     */
    public function regularExpressionSource()
    {
        $content = Config::instance()->load('regular_expression_source');
        $html = $this->render('regularExpression', compact('content'));
        $this->app->html($html);
    }

    /**
     *漏桶算法
     */
    public function leaky()
    {
        $leaky = new Leaky();
        $leaky->run();
        sdump(1);
//        $this->app->html($html);
    }

    /**
     *令牌桶算法
     */
    public function tokenBucket()
    {
        $tokenBucket = new TokenBucket();
        $tokenBucket->run();
    }

    /**
     * LRU算法
     */
    public function LRUCache()
    {
        echo "<pre>";
        $LRUCache = new LRUCache(3);
        $LRUCache->run();
        echo "</pre>";
    }

    /**
     * rabbitMQ
     */
    public function RabbitMQPublish()
    {
        $connection = new AMQPStreamConnection(
            'rabbitmq',
            5672,
            'rabbitmq',
            'Ra225851');
        // 创建通道
        $channel = $connection->channel();

// 创建队列
        $channel->queue_declare('hello', FALSE, FALSE, FALSE, FALSE);
        $msg_content = '666';
        $msg = new AMQPMessage($msg_content);
// 通过默认的交换机发送消息到队列 (消息内容, 默认交换机, 路由键);
        $channel->basic_publish($msg, '', 'hello');
        echo " [x] Sent '{$msg_content}'\n";

        $channel->close();
        $connection->close();
    }

    /**
     * rabbitMQ
     */
    public function RabbitMQConsume()
    {
        $connection = new AMQPStreamConnection(
            'rabbitmq',
            5672,
            'rabbitmq',
            'Ra225851');
        // 创建通道
        $channel = $connection->channel();

// 创建队列
        $channel->queue_declare('hello', FALSE, FALSE, FALSE, FALSE);
        echo " [*] Waiting for messages. To exit press CTRL+C\n";
        $callback = function ($msg) {
            echo ' [x] Received ', $msg->body, "\n";
        };
// 通过默认的交换机发送消息到队列 (消息内容, 默认交换机, 路由键);
        $channel->basic_consume('hello', '', FALSE, TRUE,
            FALSE, FALSE, $callback);
//        while ($channel->is_consuming()) {
//            $channel->wait();
//        }
        echo 666;
        $channel->close();
        $connection->close();
    }

    public function zSetFollowAndFans()
    {
        $this->_redis = new \Redis();
        $this->_redis->connect('redis');
//关注
        $this->_redis->zAdd("my_id:follow", time(), "other_id");
        $this->_redis->zAdd("other_id:fans", time(), "my_id");
//取关
        $this->_redis->zRem("my_id:follow", "other_id");
        $this->_redis->zRem("other_id:fans", "my_id");
//关注列表
        $this->_redis->zRange("my_id:follow", 0, -1);
        $this->_redis->zRange("other_id:follow", 0, -1);
//粉丝列表
        $this->_redis->zRevRange("my_id:fans", 0, -1, TRUE);//逆序
        $this->_redis->zRange("my_id:fans", 0, -1, TRUE);//正序
//是否互粉
        $this->_redis->zScore("my_id:fans", other);
        $this->_redis->zScore("my_id:follow", other);
//关注数
        $this->_redis->zCard("my_id:fans");
        $this->_redis->zCard("my_id:follow");
//关注数
        $this->_redis->zRangeByScore("my_id:follow");

    }

    //滑动窗口限流
    public function runCheckLimits($rules, $key, $tel)
    {
        /*滑动窗口短信发送限流算法
        1.有两条规则
         基于IP的限制和基于手机号的限制
         IP规则:

         1分钟限制5
         10分钟限制30
         1小时限制50

         手机号规则:
         1分钟限制1
         10分钟限制5
         1小时限制10
        */
//IP规则
        $ipRules = [
            60 => 5,
            600 => 30,
            3600 => 50,
        ];
//手机号规则
        $phoneRules = [
            60 => 1,
            //            600=>5,
            //            3600=>10
        ];
//
//        $r = checkLimits($ipRules,$_SERVER["REMOTE_ADDR"],$_GET['tel']);
//        var_dump($r);

        $r = checkLimits($phoneRules, $_GET['tel'], $_GET['tel']);
        var_dump($r);
    }

    /**
     * 滑动窗口限流
     * 滑动窗口就是随着时间的流动 , 进行动态的删减区间内的数据 , 限制时获取区间内的数据
     **/
    public function checkLimits($rules, $key, $tel)
    {
        $redis = new Redis();
        $redis->connect('115.159.28.111', 1991);
        foreach ($rules as $ruleTime => $rule) {
            //有序集合key
            $redisKey = $key . "_" . $ruleTime;
            $time = time();
            $member = $tel . '_' . $time;
            //事务
            $redis->multi();
            //移除窗口以外的数据
            $redis->zRemRangeByScore($redisKey, 0, $time - $ruleTime);
            $redis->zAdd($redisKey, $time, $member);
            //设置过期时间
            $redis->expire($redisKey, $ruleTime);
            $redis->zRange($redisKey, 0, -1, TRUE);
            //事务执行,
            $members = $redis->exec();
            //$members[3]是zrange的返回值
            if (empty($members[3])) {
                break;
            }
            $nums = count($members[3]);
            var_dump($nums);

            if ($nums > $rule) {
                return FALSE;
            }
        }
        return TRUE;
    }

}
