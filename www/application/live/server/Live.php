<?php
namespace app\live\server;
use app\common\server\Service;
use app\live\model\Live as LiveModel;
use app\live\model\LiveCategory as LiveCategoryModel;

class Live extends Service
{

    public function initialize() 
    {
        parent::initialize();
        $this->LiveModel = new LiveModel();
        $this->LiveCategoryModel = new LiveCategoryModel();
    }

    public function getLiveLists($data, $user)
    {
        $catList = $this->LiveCategoryModel->listToTree();
        if ($catList) {
            foreach ($catList as $key => $value) {
                $map = [];
                $map[] = ['cid', 'eq', $value['id']];
                $map[] = ['status', 'eq', 1];
                $list = $this->LiveModel->getList($map);
                $catList[$key]['live_info'] = $list['list'];
            }
            return $catList;
        }
        $this->error = '暂无信息';
        return false;
    }

}