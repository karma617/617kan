<?php
namespace app\codekey\api;
use app\nb_api\api\UserInit;
use app\codekey\server\Codekey as CodekeyService;

class Codekey extends UserInit
{
    public function initialize() {
        parent::initialize();
        $this->CodekeyService = new CodekeyService();
    }
    /**
     * 使用卡密
     *
     * @return void
     * @author 617 <email：723875993@qq.com>
     */
    public function useCodekey() {
        $data = $this->params;
        $result = $this->CodekeyService->useCodekey($data, $this->user);
        if (false === $result) {
            return $this->_error($this->CodekeyService->getError(),'',9000);
        }
        return $this->_success('兑换成功！',$result);
    }

}