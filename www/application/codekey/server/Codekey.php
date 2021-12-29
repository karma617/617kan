<?php
namespace app\codekey\server;

use think\Db;
use app\common\server\Service;
use app\codekey\model\UserGroupCodekey as UserGroupCodekeyModel;
use app\codekey\validate\Codekey as CodekeyValidate;
use app\user\model\User as UserModel;
use app\user\model\UserGroup as UserGroupModel;

class Codekey extends Service{

    public function initialize() {
        parent::initialize();
        $this->UserGroupCodekeyModel = new UserGroupCodekeyModel();
        $this->UserModel = new UserModel();
        $this->CodekeyValidate = new CodekeyValidate();
        $this->UserGroupModel = new UserGroupModel();
    }

    public function useCodekey($data, $user)
    {
        if($this->CodekeyValidate->check($data) !== true) {
            $this->error = $this->CodekeyValidate->getError();
            return false;
        }
        // 校验卡密
        if (
            !($code = $this->UserGroupCodekeyModel->where([
                'codekey' => $data['code'], 
                'status' => 0
            ])->find())
        ) {
            $this->error = "卡密无效或已被使用";
            return false;
        }
        // 获取该卡密会员组有效天数
        $expire = $this->UserGroupModel->where('id', $code['group_id'])->value('expire');
        // 默认从当前时间算起
        $start_time = date("Y-m-d H:i:s");
        // 如果已开通会员
        if ($user['expire_time'] > 0) {
            // 如果是同种类，会员计算时间由现有到期时间开始计算
            if($code['group_id'] == $user['group_id']) {
                $start_time = date("Y-m-d H:i:s", strtotime($user['expire_time']));
            }
        }
        $size = 1 * $expire;
        $todata = strtotime($start_time . '+' . $size . ' day');
        $update = [
            'group_id' => $code['group_id'],
            'expire_time' => $size > 0 ? $todata : 0
        ];
        $this->UserModel->startTrans();
        $this->UserGroupCodekeyModel->startTrans();
        if (false === $this->UserModel->where('id', $user['id'])->update($update)) {
            $this->UserModel->rollBack();
            $this->error = '开通失败，请联系管理员';
            return false;
        }
        $update = [
            'id' => $code['id'],
            'user_id' => $user['id'],
            'status' => 1
        ];
        if (false === $this->UserGroupCodekeyModel->isUpdate(true)->save($update)) {
            $this->UserGroupCodekeyModel->rollBack();
            $this->error = '开通失败，请联系管理员';
            return false;
        }
        $this->UserModel->commit();
        $this->UserGroupCodekeyModel->commit();
        return true;
    }

}