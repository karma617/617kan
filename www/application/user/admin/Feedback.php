<?php
namespace app\user\admin;

use app\system\admin\Admin;
use app\user\model\Feedback as FeedbackModel;
/**
 * 会员控制器
 * @package app\user\admin
 */
class Feedback extends Admin
{
    /**
     * 会员管理
     * @author 橘子俊 <364666827@qq.com>
     * @return mixed
     */
    public function index()
    {
        if ($this->request->isAjax()) {
            $where      = [];
            $page       = $this->request->param('page/d', 1);
            $limit      = $this->request->param('limit/d', 15);
            $type    = $this->request->param('type/s');
            if ($type) {
                $where[] = ['type', 'eq', $type];
            }
            $data['data'] = FeedbackModel::where($where)
                            ->page($page)
                            ->limit($limit)
                            ->order('id desc')
                            ->select();
            foreach ($data['data'] as $key => $value) {
                $data['data'][$key]['imgs'] = $value['imgs'] ? explode(',' ,$value['imgs']) : [];
            }
            $data['count'] = FeedbackModel::where($where)->count('id');
            $data['code'] = 0;
            return json($data);
        }

        return $this->fetch();
    }

}