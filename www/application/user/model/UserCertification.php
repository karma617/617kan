<?php
namespace app\user\model;

use think\Model;
use app\user\model\UserToken;

class UserCertification extends Model
{
     // 定义时间戳字段名
     protected $createTime = 'create_time';
     protected $updateTime = 'update_time';
 
     // 自动写入时间戳
     protected $autoWriteTimestamp = true;

    /**
     * 是否实名认证并审核通过
     *
     * @param string $token
     * @return void
     * @author 617 <email：723875993@qq.com>
     */
     public function checkAuth($uid = '', $info = false) {
          $UserTokenModel = new UserToken();
          $result = [];
          if (!is_numeric($uid)) {
               $uid = $UserTokenModel->where('token', $uid)->value('uid');
          }
          if ($uid) {
               if (($authinfo = $this->where(['uid' => $uid, 'status' => 2])->find())) {
                    $result = $authinfo->toArray();
               }
          }
          return $info ? (!empty($result) ? $result : []) : (!empty($result) ? 1 : 0);
     }
     /**
      * 实名认证
      *
      * @param [type] $data
      * @return void
      * @author 617 <email：723875993@qq.com>
      */
     public function userVerify($data) {
          $this->create($data);
     }
}