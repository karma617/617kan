<?php
namespace app\user\server;
use app\common\server\Service;
use app\user\validate\User as UserValidate;
use app\user\model\Feedback as FeedbackModel;
use app\user\model\User as UserModel;
// use app\sms\model\SmsVerify as SmsVerifyModel;

class User extends Service{

    public function initialize() {
        parent::initialize();
        $this->UserValidate = new UserValidate();
        $this->FeedbackModel = new FeedbackModel();
        $this->UserModel = new UserModel();
        // $this->SmsVerifyModel = new SmsVerifyModel();
    }

    public function feedback($data, $user) {
        if($this->UserValidate->scene('feedback')->check($data) !== true) {
            $this->error = $this->UserValidate->getError();
            return false;
        }
        if ($user) {
            $data['contact'] = $user['id'];
        }
        if ($this->FeedbackModel->isUpdate(false)->save($data)) {
            return true;
        }
        return false;
    }

    public function changeMobile($data, $user) {
        // $result = $this->SmsVerifyModel->verifyCode($user['mobile'], $data['code'], 'mobile');
        // if (false === $result) {
        //     $this->error = "验证码不正确";
        //     return false;
        // }
        if ($this->UserModel->isUpdate(true)->save($data, ['id'=>$user['id']])) {
            return true;
        }
        return false;
    }

    public function changePassword($data, $user) {
        if ($data['password'] != $data['repassword']) {
            $this->error = "两次密码不一致";
            return false;
        }
        // $result = $this->SmsVerifyModel->verifyCode($user['mobile'], $data['code'], 'password');
        // if (false === $result) {
        //     $this->error = "验证码不正确";
        //     return false;
        // }
        $data['salt'] = $this->UserModel->where('id', $user['id'])->value('salt');
        $old_password = $this->UserModel->where('id', $user['id'])->value('password');
        if ($old_password != md5(md5($data['oldpassword']).$data['salt'])) {
            $this->error = "旧密码不正确";
            return false;
        }
        if ($old_password == md5(md5($data['password']).$data['salt'])) {
            $this->error = "新密码不可与旧密码一样";
            return false;
        }
        unset($data['repassword']);
        unset($data['oldpassword']);
        if ($this->UserModel->isUpdate(true)->save($data, ['id'=>$user['id']])) {
            return true;
        }
        return false;
    }
    
    public function changeUsi($data, $user)
    {
        if (empty($data['nick']) && empty($data['avatar'])) {
            return true;
        }
        if ($this->UserModel->isUpdate(true)->save($data, ['id'=>$user['id']])) {
            return true;
        }
        return false;
    }
    
    public function getNumbers($data, $user)
    {
        return $this->UserModel->where('id', $user['id'])->value('number');
    }
}