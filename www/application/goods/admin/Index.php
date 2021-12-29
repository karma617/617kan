<?php
namespace app\goods\admin;
use app\common\admin\AdminCommon;
use app\goods\model\Goods as GoodsModel;
use app\goods\model\GoodsCategory as GoodsCategoryModel;
class Index extends AdminCommon
{
    protected $hisiModel = 'Goods';//模型名称[通用添加、修改专用]
    public function initialize()
    {
        parent::initialize();
        $this->GoodsModel = new GoodsModel();
        $this->GoodsCategoryModel = new GoodsCategoryModel();
    }
    public function index()
    {
        if ($this->request->isAjax()) {
            $map    = $data = [];
            $data = $this->params;
            $page   = isset($data['page']) ? $data['page'] : 1;
            $limit  = isset($data['limit']) ? $data['limit'] : 15;
            $data = $this->GoodsModel->getList($map, $page, $limit);
            foreach ($data['list'] as $key => $value) {
                $data['list'][$key]['cname'] = $this->GoodsCategoryModel->where('id', $value['cid'])->value('name');
            }
            $data['code'] = 0;
            $data['data'] = $data['list'];
            return json($data);
        }
        return $this->fetch();
    }

    public function categoryList()
    {
        if ($this->request->isAjax()) {
            $map    = $data = [];
            $data = $this->params;
            $page   = isset($data['page']) ? $data['page'] : 1;
            $limit  = isset($data['limit']) ? $data['limit'] : 15;
            $data = $this->GoodsCategoryModel->getList($map, $page, $limit);
            $data['data'] = $data['list'];
            $data['code'] = 0;
            return json($data);
        }
        return $this->fetch();
    }
}