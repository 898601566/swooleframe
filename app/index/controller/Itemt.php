<?php

namespace app\index\controller;

use app\index\model\Company;
use app\index\model\Item;
use app\index\model\Tcompany;
use common\exception\BaseException;
use fastswoole\Controller;
use fastswoole\db\Query;
use Swoole\Client\Exception;

class Itemt extends Controller
{

    // 首页方法，测试框架自定义DB查询
    public function index()
    {
        $a = ["new string"];
        $c = $b = $a;
        xdebug_debug_zval( 'a' );
        unset( $b, $c );
        xdebug_debug_zval( 'a' );
        sdump(env("app.return_type"));
        throw new Exception('dfgdfgdfg', 1);
        BaseException::throwException(new Exception('sdf', 1));

        $tcompany = (new Tcompany())->select()->toArray();
        $company = (new Company())->select()->toArray();
        $tcompany = array_column($tcompany, "name","id");
        $company = array_column($company, "company_name","id");
        $tcompany_map = $tcompany;
        $str = "case company_id ";
        foreach ($tcompany as $key => $value) {
            if (array_search(trim($value), $company)) {
                $tcompany_map[$key] = array_search(trim($value), $company);
            $str .=  " when $key then $tcompany_map[$key] ";
            } else {
                $tcompany_map[$key] = 1;
                unset($tcompany_map[$key]);
            }
        }
        $str .="  else 1 end ";
        sdump($str,$tcompany_map,$tcompany,
            $company);

//        return $this->app->json(['start_time'=>time(),'end_time'=>time()]);
        $keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';
        if ($keyword) {
            $items = (new Item())->search($keyword);
        } else {
            // 查询所有内容，并按倒序排列输出
            // where()方法可不传入参数，或者省略
            sdump((new Item())->select());
            $items = (new Item())->field(["item_name", "id"])
                                 ->order(['id DESC'])
                                 ->whereOr('id', '=', 1)
                                 ->whereOr('id', '=', 2)
                                 ->where(function (Query $query){
                                     $query->where('id', '=', 2);
                                     $query->where('id', '=', 1);
                                 })
                                 ->group('id')
                                 ->select();
            sdump($items->toArray(),(new Item())->getLastSql());
        }
        $items->load([
                'item2' => function ($query) {
                    $query->where('id', '=', 1);
                },
            ]
        );
        $this->assign('title', '全部条目');
        $this->assign('keyword', $keyword);
        $this->assign('items', $items);
        $view = $this->render();
        return $view;
    }

    // 查看单条记录详情
    public function detail($id)
    {
        // 通过?占位符传入$id参数
        $item = (new Item())->where('id', '=', $id)->fetch();

        $this->assign('title', '条目详情');
        $this->assign('item', $item);
        $view = $this->render();
        echo $view;
    }

    // 添加记录，测试框架DB记录创建（Create）
    public function add()
    {
        $data['item_name'] = $_POST['value'];
        $count = (new Item)->add($data);

        $this->assign('title', '添加成功');
        $this->assign('count', $count);
        $view = $this->render();
        echo $view;
    }

    // 操作管理
    public function manage($id = 0)
    {
        $item = [];
        if ($id) {
            // 通过名称占位符传入参数
            $item = (new Item())->where('item.id', '=', $id)->fetch();
        }
        $this->assign('title', '管理条目');
        $this->assign('item', $item);
        $view = $this->render();
        echo $view;
    }

    // 更新记录，测试框架DB记录更新（Update）
    public function update()
    {
        $data = ['id' => $_POST['id'], 'item_name' => $_POST['value']];
        $count = (new Item)->where('item.id', '=', $_POST['id'])->update($data);
        $this->assign('title', '修改成功');
        $this->assign('count', $count);
        $view = $this->render();
        echo $view;
    }

    // 删除记录，测试框架DB记录删除（Delete）
    public function delete($id = NULL)
    {
        $count = (new Item)->delete($id);

        $this->assign('title', '删除成功');
        $this->assign('count', $count);
        $view = $this->render();
        echo $view;
    }

}
