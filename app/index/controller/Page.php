<?php
/**
 * User: zhengze
 * Date: 2020/1/16
 * Time: 11:04
 */

namespace app\index\controller;

use app\index\model\Page as PageModel;
use Elasticsearch\ClientBuilder;
use fastswoole\Controller;

class Page extends Controller
{
    public $type = 'ccc';
    public $index = 'ccc';

    public function index()
    {
        $client = ClientBuilder::create()->setHosts(['elasticsearch:9200'])->build();
        $PageModel = PageModel::instance();
        $title_arr = [
            "美国留给伊拉克的是个烂摊子吗",
            "公安部：各地校车将享最高路权",
            "中韩渔警冲突调查：韩警平均每天扣1艘中国渔船",
            "中国驻洛杉矶领事馆遭亚裔男子枪击 嫌犯已自首",
        ];
        foreach ($title_arr as $key => $value) {
            $data = ['title' => $value, 'content' => $value];
            $page_id = $PageModel->insert($data);
            $params = [
                'index' => $this->index,
                'type' => $this->type,
            ];
            $params['body'] = array(
                'page_id' => $page_id,
                'test_title' => $value,
            );
            $client->index($params);
        }
        the_print('over');
        exit;
    }

    public function search()
    {
        $client = ClientBuilder::create()->setHosts(['elasticsearch:9200'])->build();
        //查询数据的拼装
        $params = [
            'index' => 'ccc',
            'type' => 'ccc',
            'from' => 0,
            'size' => 3,
            'body' => [
                'query' => [
                    'match' => [
                        'test_title' => '国',
                    ]
                ]
            ],
        ];
        //执行查询
        $rtn = $client->search($params);
        the_print($rtn);
        exit;
    }

    /**
     * searchByName
     * @auth   singwa
     * @param  [string] $name [description]
     * @param  [int] $from [description]
     * @param  [int] $size [description]
     * @param string $type [description]
     * @return [type]       [description]
     */
    public function searchByName($name, $from = 0, $size = 10, $type = "match")
    {
        $client = ClientBuilder::create()->setHosts(['elasticsearch:9200'])->build();
        $name = trim($name);
        if (empty($name)) {
            return [];
        }
        $params = [
            "index" => $this->index,
            "type" => $this->type,
            'body' => [
                'query' => [
                    $type => [
                        'name' => $name
                    ],
                ],
                'from' => $from,
                'size' => $size
            ],
        ];

        $result = $client->search($params);
        the_print($result);
        exit;
    }
}
