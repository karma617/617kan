<?php
namespace app\user\api;
use app\nb_api\api\UserInit;
use app\user\server\User as UserService;

class User extends UserInit
{
    public function initialize() {
    	if (request()->action() == 'feedback') {
    		$this->check_login = false;
    	}
        parent::initialize();
        $this->UserService = new UserService();
    }
    
    public function changeUsi()
    {
        $data = $this->params;
        $result = $this->UserService->changeUsi($data, $this->user);
        if (false === $result) {
            return $this->_error($this->UserService->getError(),'',3000);
        }
        return $this->_success('修改成功');
    }

    public function feedback() {
        $data = $this->params;
        $result = $this->UserService->feedback($data, $this->user);
        if (false === $result) {
            return $this->_error($this->UserService->getError(),'',3000);
        }
        return $this->_success('反馈成功');
    }
    /**
     * 修改手机号
     *
     * @return void
     * @author 617 <email：723875993@qq.com>
     */
    public function changeMobile() {
        $data = $this->params;
        $result = $this->UserService->changeMobile($data, $this->user);
        if (false === $result) {
            return $this->_error($this->UserService->getError(),'',3000);
        }
        return $this->_success('修改成功');
    }
    /**
     * 修改密码
     *
     * @return void
     * @author 617 <email：723875993@qq.com>
     */
    public function changePassword() {
        $data = $this->params;
        $result = $this->UserService->changePassword($data, $this->user);
        if (false === $result) {
            return $this->_error($this->UserService->getError(),'',3000);
        }
        return $this->_success('修改成功');
    }
    /**
     * 用户中心首页
     *
     * @return void
     * @author 617 <email：723875993@qq.com>
     */
    public function home() {
        $data = $this->params;
        $result = $this->UserService->home($data, $this->user);
        if (false === $result) {
            return $this->_error($this->UserService->getError(),'',3000);
        }
        return $this->_success('获取成功', $result);
    }
    
    public function getNumbers()
    {
        $data = $this->params;
        $result = $this->UserService->getNumbers($data, $this->user);
        if (false === $result) {
            return $this->_error($this->UserService->getError(),'',3001);
        }
        return $this->_success('获取成功', $result);
    }
}