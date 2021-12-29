<?php
namespace app\banner\server;

use app\common\server\Service;
use app\banner\model\Banner as BannerModel;

class Banner extends Service{

    public function initialize() {
        parent::initialize();
        $this->BannerModel = new BannerModel();
    }

    public function getBannerLists($data) {
        $map = [];
        $map[] = ['status', 'eq', 1];
        return $this->BannerModel->getList($map, 1, 8);
    }

}