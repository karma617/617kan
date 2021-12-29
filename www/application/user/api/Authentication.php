<?php
namespace app\user\api;

use app\nb_api\api\UserInit;
use app\user\server\Authentication as AuthenticationServer;

class Authentication extends UserInit{

    public function initialize() {
        $this->check_auth = false;
        parent::initialize();
        $this->AuthenticationServer = new AuthenticationServer();
    }
    /**
     * 实名认证
     *
     * @return void
     * @author 617 <email：723875993@qq.com>
     */
    public function userVerify() {
        $data = $this->params;
        $result = $this->AuthenticationServer->userVerify($data, $this->user);
        if (false === $result) {
            return $this->_error($this->AuthenticationServer->getError(),'',1500);
        }
        return $this->_success("操作成功", $result);
    }
}