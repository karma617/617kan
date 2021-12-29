<?php
namespace app\banner\api;
use app\nb_api\api\ApiInit;
use app\banner\server\Banner as BannerService;

class Banner extends ApiInit
{
    public function initialize() {
        parent::initialize();
        $this->BannerService = new BannerService();
    }
    /**
     * 获取轮播图
     *
     * @return void
     * @author 617 <email：723875993@qq.com>
     */
    public function getBannerLists() {
        $data = $this->params;
        $result = $this->BannerService->getBannerLists($data);
        if (false === $result) {
            return $this->_error($this->BannerService->getError(),'',7000);
        }
        return $this->_success('获取成功',$result);
    }

}