<?php
namespace app\live\admin;
use app\common\admin\AdminCommon;
use app\live\model\LiveCategory as LiveCategoryModel;
use app\live\model\Live as LiveModel;

class Index extends AdminCommon
{
    protected $hisiModel = 'LiveCategory';//模型名称[通用添加、修改专用]
    public function initialize()
    {
        parent::initialize();
        $this->LiveCategoryModel = new LiveCategoryModel();
        $this->LiveModel = new LiveModel();
    }
    public function categoryList()
    {
        if ($this->request->isAjax()) {
            $map    = $data = [];
            $data   = $this->params;
            $page   = isset($data['page']) ? $data['page'] : 1;
            $limit  = isset($data['limit']) ? $data['limit'] : 15;
            $data = $this->LiveCategoryModel->getList($map, $page, $limit);
            $data['code'] = 0;
            $data['data'] = $data['list'];
            return json($data);
        }
        return $this->fetch();
    }

    public function liveList()
    {
        if ($this->request->isAjax()) {
            $map    = $data = [];
            $data   = $this->params;
            if (isset($data['cid']) && !empty($data['cid'])) {
                $map[] = ['cid', 'eq', $data['cid']];
            }
            $page   = isset($data['page']) ? $data['page'] : 1;
            $limit  = isset($data['limit']) ? $data['limit'] : 15;
            $data = $this->LiveModel->getList($map, $page, $limit);
            foreach ($data['list'] as $key => $value) {
                $data['list'][$key]['cname'] = $this->LiveCategoryModel->where('id', $value['cid'])->value('name');
            }
            $data['code'] = 0;
            $data['data'] = $data['list'];
            return json($data);
        }
        return $this->fetch();
    }
}