<?php
namespace app\news\admin;
use app\common\admin\AdminCommon;

use app\news\model\News as NewsModel;

class Index extends AdminCommon
{
    protected $hisiModel = 'News';//模型名称[通用添加、修改专用]
    protected $hisiTable = '';//表名称[通用添加、修改专用]
    protected $hisiAddScene = '';//添加数据验证场景名
    protected $hisiEditScene = '';//更新数据验证场景名

    public function initialize()
    {
        parent::initialize();
        $this->NewsModel = new NewsModel();
    }
    public function index()
    {
        if ($this->request->isAjax()) {
            $map    = $data = [];
            $data = $this->params;
            $page   = isset($data['page']) ? $data['page'] : 1;
            $limit  = isset($data['limit']) ? $data['limit'] : 15;
            $data = $this->NewsModel->getList($map, $page, $limit);
            $data['code'] = 0;
            $data['data'] = $data['list'];
            return json($data);
        }
        return $this->fetch();
    }
}