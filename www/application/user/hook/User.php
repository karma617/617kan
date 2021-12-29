<?php
namespace app\user\hook;

use app\user\model\User as UserModel;
use app\user\model\UserToken as UserTokenModel;
use app\user\model\UserCertification as UserCertificationModel;

class User
{
    /**
     * 注册后操作
     *
     * @param [type] $data
     * @return void
     * @author 617 <email：723875993@qq.com>
     */
    public function userAfterRegister($data) {
        // 上级
        $UserModel = new UserModel();
        if (isset($data['pid']) && !empty($data['pid'])) {
            $UserModel->where('id', $data['id'])->setField('pid', $data['pid']);
        }
        
    }

    public function userUpdate($user) {
        // 更新后删除所有token
        $UserTokenModel = new UserTokenModel();
        $map[] = ['uid', 'eq', $user['id']];
        $UserTokenModel->where($map)->delete();
    }

    public function userDelete($user) {
        $UserCertificationModel = new UserCertificationModel();
        $UserCertificationModel->where('uid', $user['id'])->delete();
    }
    
    public function userAfterLogin($user) {
        // 登录后删除其他token
        $UserTokenModel = new UserTokenModel();
        $map[] = ['token', 'neq', $user['token']];
        $map[] = ['uid', 'eq', $user['id']];
        $UserTokenModel->where($map)->delete();
    }
}