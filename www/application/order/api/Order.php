<?php
namespace app\order\api;
use app\nb_api\api\UserInit;
use app\order\server\Order as OrderService;

class Order extends UserInit{

    public function initialize() {
        parent::initialize();
        $this->OrderService = new OrderService();
    }

    public function createOrder() {
        $data = $this->params;
        $result = $this->OrderService->createOrder($data, $this->user);
        if (false === $result) {
            return $this->_error($this->OrderService->getError(),'',2000);
        }
        return $this->_success('创建成功',$result);
    }

    public function getOrderStatus()
    {
        $data = $this->params;
        $result = $this->OrderService->getOrderStatus($data, $this->user);
        if (false === $result) {
            return $this->_error($this->OrderService->getError(),'',2001);
        }
        return $this->_success('查询成功',$result);
    }
}