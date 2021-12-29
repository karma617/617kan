<?php
namespace app\index\admin;
use app\common\admin\AdminCommon;
use app\index\model\Msg as MsgModel;
class Msg extends AdminCommon
{
    protected $hisiModel = 'Msg';//模型名称[通用添加、修改专用]
    public function initialize()
    {
        parent::initialize();
        $this->MsgModel = new MsgModel();
    }
    public function index()
    {
        if ($this->request->isAjax()) {
            $map    = $data = [];
            $data   = $this->params;
            $page   = isset($data['page']) ? $data['page'] : 1;
            $limit  = isset($data['limit']) ? $data['limit'] : 15;
            $data = $this->MsgModel->getList($map, $page, $limit);
            $data['code'] = 0;
            $data['data'] = $data['list'];
            return json($data);
        }
        return $this->fetch();
    }

}