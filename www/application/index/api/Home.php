<?php
namespace app\index\api;
use app\nb_api\api\ApiInit;
use app\index\server\Home as HomeService;

class Home extends ApiInit
{
    public function initialize() {
        parent::initialize();
        $this->HomeService = new HomeService();
    }
    /**
     * 获取启动图广告
     *
     * @return void
     * @author 617 <email：723875993@qq.com>
     */
    public function getGuideImg() {
        $data = $this->params;
        $result = $this->HomeService->getGuideImg($data);
        if (false === $result) {
            return $this->_error($this->HomeService->getError(),'',7000);
        }
        return $this->_success('获取成功',$result);
    }
    /**
     * 首页广告列表
     *
     * @return void
     * @author 617 <email：723875993@qq.com>
     */
    public function getHomeAdList()
    {
        $data = $this->params;
        $result = $this->HomeService->getHomeAdList($data);
        if (false === $result) {
            return $this->_error($this->HomeService->getError(),'',7001);
        }
        return $this->_success('获取成功',$result);
    }
    /**
     * 播放页弹窗
     *
     * @return void
     * @author 617 <email：723875993@qq.com>
     */
    public function getPlayerAdList()
    {
        $data = $this->params;
        $result = $this->HomeService->getPlayerAdList($data);
        if (false === $result) {
            return $this->_error($this->HomeService->getError(),'',7002);
        }
        return $this->_success('获取成功',$result);
    }
    /**
     * 常驻广告
     *
     * @return void
     * @author 617 <email：723875993@qq.com>
     */
    public function getPublicAdList()
    {
        $data = $this->params;
        $result = $this->HomeService->getPublicAdList($data);
        if (false === $result) {
            return $this->_error($this->HomeService->getError(),'',7003);
        }
        return $this->_success('获取成功',$result);
    }

    /**
     * 关于我们
     *
     * @return void
     * @author 617 <email：723875993@qq.com>
     */
    public function about()
    {
        $data = $this->params;
        $result = $this->HomeService->about($data);
        if (false === $result) {
            return $this->_error($this->HomeService->getError(),'',7004);
        }
        return $this->_success('获取成功',$result);
    }
    /**
     * 隐私
     *
     * @return void
     * @author 617 <email：723875993@qq.com>
     */
    public function punch()
    {
        $data = $this->params;
        $result = $this->HomeService->punch($data);
        if (false === $result) {
            return $this->_error($this->HomeService->getError(),'',7005);
        }
        return $this->_success('获取成功',$result);
    }
    /**
     * 公告
     *
     * @param Type $var
     * @return void
     * @author 617 <email：723875993@qq.com>
     */
    public function getMsgLists()
    {
        $data = $this->params;
        $result = $this->HomeService->getMsgLists($data);
        if (false === $result) {
            return $this->_error($this->HomeService->getError(),'',7006);
        }
        return $this->_success('获取成功',$result);
    }

    /**
     * 图片检测
     *
     * @return void
     * @author 617 <email：723875993@qq.com>
     */
    public function checkImg()
    {
        $data= $this->params;
        $result = $this->HomeService->checkImg($data);
        if (false === $result) {
            return $this->_error($this->HomeService->getError(),'',1007);
        }
        return $this->_success("", $result);
    }
}