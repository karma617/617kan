<?php
namespace app\index\api;
use app\nb_api\api\UserInit;
use app\index\server\Config as ConfigService;

class Config extends UserInit
{
    public function initialize() {
        $this->check_login = false;
        parent::initialize();
        $this->ConfigService = new ConfigService();
    }

    public function getConfig() {
        $data = $this->params;
        $result = $this->ConfigService->getConfig($data, $this->user);
        if (false === $result) {
            return $this->_error($this->ConfigService->getError(),'',7000);
        }
        return $this->_success('获取成功',$result);
    }
	public function updateInfo() {
        $result = $this->ConfigService->updateInfo();
        if (false === $result) {
            return $this->_error($this->ConfigService->getError(),'',9000);
        }
        return $this->_success('获取成功',$result);
    }
}