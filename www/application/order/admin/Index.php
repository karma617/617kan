<?php
namespace app\order\admin;
use app\common\admin\AdminCommon;

use app\order\model\Order as OrderModel;

class Index extends AdminCommon
{
    protected $hisiModel = 'Order';//模型名称[通用添加、修改专用]
    protected $hisiTable = '';//表名称[通用添加、修改专用]
    protected $hisiAddScene = '';//添加数据验证场景名
    protected $hisiEditScene = '';//更新数据验证场景名

    public function initialize()
    {
        parent::initialize();
        $this->OrderModel = new OrderModel();
    }

    public function index()
    {
        if ($this->request->isAjax()) {
            $map    = $data = [];
            $data   = $this->params;
            $page   = isset($data['page']) ? $data['page'] : 1;
            $limit  = isset($data['limit']) ? $data['limit'] : 15;
            if (isset($data['keyword'])) {
                $map[] = ['order_sn', 'eq', $data['keyword']];
            }
            $data = $this->OrderModel->getList($map, $page, $limit);
            $data['code'] = 0;
            $data['data'] = $data['list'];
            return json($data);
        }
        return $this->fetch();
    }
}