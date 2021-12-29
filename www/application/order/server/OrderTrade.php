<?php
namespace app\order\server;

use app\common\server\Service;
use app\order\model\OrderTrade as OrderTradeModel;
use app\order\validate\OrderTrade as OrderTradeValidate;

class OrderTrade extends Service{

    public function initialize() {
        parent::initialize();
        $this->OrderTradeModel = new OrderTradeModel();
        $this->OrderTradeValidate = new OrderTradeValidate();
    }

    public function trade($data) {
        if($this->OrderTradeValidate->scene('gopay')->check($data) !== true) {
            $this->error = $this->OrderTradeValidate->getError();
            return false;
        }
        $result = $this->OrderTradeModel->trade($data);
        if (false === $result) {
            $this->error = '订单创建失败！';
            return false;
        }
        return $result;
    }
}