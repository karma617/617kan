<?php
namespace app\user\server;
use app\common\server\Service;
use app\user\model\User;
use app\user\model\UserCertification;
use app\user\validate\UserCertification as UserCertificationValidate;

class Authentication extends Service{

    public function initialize() {
        parent::initialize();
        $this->UserModel = new User();
        $this->UserCertification = new UserCertification();
        $this->UserCertificationValidate = new UserCertificationValidate();
    }
    /**
     * 实名认证
     *
     * @param [type] $data
     * @return void
     * @author 617 <email：723875993@qq.com>
     */
    public function userVerify($data, $user) {
        $scenes = $user['group_id'] == 1 ? 'drivers' : 'owner';
        if($this->UserCertificationValidate->scene($scenes)->check($data) !== true) {
            $this->error = $this->UserCertificationValidate->getError();
            return false;
        }
        // if ($this->UserCertification->where('man_id', $data['man_id'])->find()) {
        //     $this->error = '此身份证已被使用！';
        //     return false;
        // }
        $data['uid'] = $user['id'];
        // $map = "uid={$user['id']}";
        if (($status = $this->UserCertification->where('uid', $user['id'])->value('status'))) {
            switch ($status) {
                case 1:
                    $this->error = '您已提交过实名认证，请耐心等待审核';
                    return false;
                
                case 2:
                    $this->error = '您的实名认证已通过审核，若需修改，请联系平台管理员';
                    return false;
            }
        }
        if (isset($data['id']) && !empty($data['id'])) {
            if ($this->UserCertification->isUpdate(true)->save($data, ['id'=>$data['id']])) {
                return str_coding($data['id'], 'ENCODE');
            }
        }else{
            if (!config('sysconf.user_certification')) {
                $data['status'] = 2;
            }
            if ($this->UserCertification->isUpdate(false)->save($data)) {
                return str_coding($this->UserCertification->id, 'ENCODE');
            }
        }
        $this->error = '操作失败';
        return false;
    }

}