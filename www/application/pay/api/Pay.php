<?php
namespace app\pay\api;
use app\nb_api\api\UserInit;
use app\pay\server\Pay as ParService;

class Pay extends UserInit{

    public function initialize() {
        parent::initialize();
        $this->ParService = new ParService();
    }

    public function gopay() {
        $data = $this->params;
        $result = $this->ParService->gopay($data, $this->user);
        if (false === $result) {
            return $this->_error($this->ParService->getError(),'',2000);
        }
        return $this->_success('正在支付',$result);
    }
}