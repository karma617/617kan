<?php
namespace app\pay\hook;

use app\order\model\Order as OrderModel;
use app\order\model\OrderTrade as OrderTradeModel;
use app\user\model\User as UserModel;
use Env;

class Notify{
    public $OrderModel;
    public $UserModel;

    public function __construct() {
        $this->OrderModel = new OrderModel();
        $this->OrderTradeModel = new OrderTradeModel();
        $this->UserModel = new UserModel();
    }
    /**
     * 支付完成回调
     *
     * @param [type] $data
     * @return void
     * @author 617 <email：723875993@qq.com>
     */
    public function afterPayNotify($data)
    {
    	$trade_no = isset($data['payId']) ? $data['payId'] : $data['out_trade_no'];
        $map = [];
        $map[] = ['trade_no', 'eq', $trade_no];
        $map[] = ['status', 'eq', 2];
        if (($order_sn = $this->OrderTradeModel->where($map)->value('order_sn'))) {
            if (($order_info = $this->OrderModel->where('order_sn', $order_sn)->find())) {
                // 商品分类
                switch ($order_info['cid']) {
                    case '1':   //加次数
                        $this->UserModel->where('id', $order_info['uid'])->setInc('number', $order_info['ext']);
                        break;
                }
            }
        }
    }
}