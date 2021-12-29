<?php
namespace app\user\api;
use app\nb_api\api\ApiInit;
use app\user\server\Wechat as WechatServer;
use app\user\model\User;

class Wechat extends ApiInit
{
    public function initialize() {
        parent::initialize();
        $this->WechatServer = new WechatServer();
    }

    /**
     * 小程序登录
     *
     * @return void
     * @author 617 <email：723875993@qq.com>
     */
    public function mplogin()
    {
        $data= $this->params;
        $result = $this->WechatServer->mplogin($data);
        if (false === $result) {
            return $this->_error($this->WechatServer->getError(),'', 9000);
        }
        return $this->_success("操作成功", $result);
    }
    /**
     * 绑定手机号
     *
     * @return void
     * @author 617 <email：723875993@qq.com>
     */
    public function bindMobile()
    {
        $data= $this->params;
        $result = $this->WechatServer->bindMobile($data);
        if (false === $result) {
            return $this->_error($this->WechatServer->getError(),'', 9001);
        }
        return $this->_success("操作成功", $result);
    }
}