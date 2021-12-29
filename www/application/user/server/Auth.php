<?php
namespace app\user\server;
use app\common\server\Service;
use app\user\model\User;

class Auth extends Service{

    public function initialize() {
        parent::initialize();
        $this->UserModel = new User();
    }
    /**
     * 登录等统一入口
     *
     * @param [type] $data
     * @return void
     * @author 617 <email：723875993@qq.com>
     */
    public function dosth($data) {
        $mobile = isset($data['account']) ? $data['account'] : '';
        $password = isset($data['password']) ? $data['password'] : '';
        $code = isset($data['code']) ? $data['code'] : '';
        $remember = isset($data['remember']) ? ($data['remember'] == 1 ? true : false) : false;
        switch($data['type']){
            case 'reg':
                // $result = $this->SmsVerify->verifyCode($mobile, $code, 'reg');
                $result = true;
                if ($result === false) {
                    $this->error = '验证码错误';
                    return false;
                }else{
                    $return = $this->UserModel->register($data);
                }
                break;
            case 'login':
                // $result = $this->SmsVerify->verifyCode($mobile, $code, 'login');
                $result = true;
                if ($result === false && !$password) {
                    $this->error = '验证码错误';
                    return false;
                }else{
                    $return = $this->UserModel->login($mobile, $password, $code, $remember);
                }
                break;
            case 'forget':
                // $result = $this->SmsVerify->verifyCode($mobile, $code, 'forget');
                $result = true;
                if ($result === false) {
                    $this->error = '验证码错误';
                    return false;
                }else{
                    $return = $this->UserModel->forget($data);
                }
                break;
        }
        if (false === $return) {
        	$this->code = $this->UserModel->code ? $this->UserModel->code : '';
            $this->error = $this->UserModel->getError();
            return false;
        }
        return $return;
    }
}