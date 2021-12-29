<?php
namespace app\user\api;
use app\nb_api\api\ApiInit;
use app\user\server\Auth as AuthServer;
use app\user\model\User;

class Auth extends ApiInit
{
    public function initialize() {
        parent::initialize();
        $this->AuthServer = new AuthServer();
        $this->UserModel = new User();
    }
    
    /**统一入口 */
    public function dosth () {
        $data= $this->params;
        $result = $this->AuthServer->dosth($data);
        if (false === $result) {
            return $this->_error($this->AuthServer->getError(),'', $this->AuthServer->code ? $this->AuthServer->code : 1400);
        }
        return $this->_success("操作成功", $result);
    }

    public function logout() {
        $this->UserModel->logout($this->token);
        return $this->_success('退出成功');
    }

    public function certification() {
        $result = $this->UserModel->isLogin($this->token, true);
        if (false === $result) {
            return $this->_error($this->AuthServer->getError(),'',1401);
        }
        return $this->_success("操作成功", $result);
    }
}
