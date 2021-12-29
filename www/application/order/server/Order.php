<?php
namespace app\order\server;

use app\common\server\Service;
use app\order\model\Order as OrderModel;
use app\goods\model\Goods as GoodsModel;
use app\order\validate\Order as OrderValidate;

class Order extends Service{

    public function initialize() {
        parent::initialize();
        $this->OrderModel = new OrderModel();
        $this->GoodsModel = new GoodsModel();
        $this->OrderValidate = new OrderValidate();
    }

    public function createOrder($data, $user)
    {
        if($this->OrderValidate->scene('createOrder')->check($data) !== true) {
            $this->error = $this->OrderValidate->getError();
            return false;
        }
        $goods_info = $this->GoodsModel->where('id', $data['goods_id'])->find();
        if (!$goods_info) {
            $this->error = '未找到此商品';
            return false;
        }
        $order_sn = unique_order_number($goods_info['id']);
        $_data = [
            'goods_id' => $goods_info['id'],
            'uid' => $user['id'],
            'order_sn' => $order_sn,
            'money' => $goods_info['money'],
            'cid' => $goods_info['cid'],
            'ext' => $goods_info['ext'],
        ];
        if ($this->OrderModel->isUpdate(false)->save($_data)) {
            return ['order_sn'=>$order_sn, 'money' => $goods_info['money'], 'order_id' => $this->OrderModel->id];
        }
        $this->error = '创建订单失败';
        return false;
    }

    public function getOrderStatus($data, $user)
    {
        $map = [];
        $map[] = ['order_sn', 'eq', $data['order_sn']];
        $map[] = ['uid', 'eq', $user['id']];
        return $this->OrderModel->where($map)->value('pay_status');
    }
}