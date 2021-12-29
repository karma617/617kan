<?php
namespace app\goods\server;
use app\common\server\Service;
use app\goods\model\Goods as GoodsModel;

class Goods extends Service
{

    public function initialize() 
    {
        parent::initialize();
        $this->GoodsModel = new GoodsModel();
    }

    public function getGoodsLists($data, $user)
    {
        $map = [];
        $map[] = ['cid', 'eq', $data['c']];
        $map[] = ['status', 'eq', 1];
        $list = $this->GoodsModel->getList($map, 0);
        return $list['list'];
    }

}