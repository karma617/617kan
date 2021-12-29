<?php
namespace app\user\server;
use app\common\server\Service;
use app\user\model\User;
use app\user\model\UserWalletLog;

class Commission extends Service{

    public $scale = [];
    public $User = '';
    public $UserWalletLog = '';

    public function initialize() {
        parent::initialize();
        $this->User = new User;
        $this->UserWalletLog = new UserWalletLog;
        //佣金比例 单级 100% 二级 50%，50% 三级 50%，30%，20；
        $this->scale = [[0],[1],[0.5,0.5],[0.5,0.3,0.2]];
    }

    public function commission($uid, $money) {
        $parentList = $this->getParent($uid);
        if(count($parentList) > 0){
            $scale = $this->scale[count($parentList)];
            foreach ($parentList as $k => $v) {
                $this->UserWalletLog->log($v, $money*$scale[$k], $uid, 2, '佣金分红');
            }
        }
    }

    public function getParent($id, $arr = []){
        $pid = $this->User->where(['id' => $id])->value('pid');
        if($pid && $pid !=0 ){
            $arr[] = $pid;
            if(count($arr) < 3){
                return $this->getParent($pid, $arr);
            }
        }
        return $arr;
    }
    /**
     * 我的全部下级
     *
     * @param [type] $id
     * @param array $arr
     * @return void
     * @author 617 <email：723875993@qq.com>
     */
    public function getChild($id, $arr = []){
        $pid = $this->User->where(['pid' => $id])->value('id');
        if($pid && $pid !=0 ){
            $arr[] = $pid;
            // if(count($arr) < 3){
                return $this->getChild($pid, $arr);
            // }
        }
        return $arr;
    }
}