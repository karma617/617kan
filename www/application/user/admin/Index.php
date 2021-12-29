<?php
// +----------------------------------------------------------------------
// | HisiPHP框架[基于ThinkPHP5开发]
// +----------------------------------------------------------------------
// | Copyright (c) 2016-2021 http://www.hisiphp.com
// +----------------------------------------------------------------------
// | HisiPHP承诺基础框架永久免费开源，您可用于学习和商用，但必须保留软件版权信息。
// +----------------------------------------------------------------------
// | Author: 橘子俊 <364666827@qq.com>，开发者QQ群：50304283
// +----------------------------------------------------------------------
namespace app\user\admin;

use app\system\admin\Admin;
use app\user\model\User as UserModel;
use app\user\model\UserGroup as GroupModel;
use app\user\model\UserCertification as UserCertificationModel;
use app\messages\model\Messages;

/**
 * 会员控制器
 * @package app\user\admin
 */
class Index extends Admin
{
    protected $hisiModel = 'User';//模型名称[通用添加、修改专用]
    protected $hisiAddScene = 'adminCreate';//添加数据验证场景名
    protected $hisiEditScene = '';//更新数据验证场景名

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
            $keyword    = $this->request->param('keyword/s');
            $groupId    = $this->request->param('group_id/d');

            if ($keyword) {

                if (is_email($keyword)) {// 邮箱

                    $where[] = ['email', 'eq', $keyword];

                } elseif (is_mobile($keyword)) {// 手机号

                    $where[] = ['mobile', 'eq', $keyword];

                } elseif (is_numeric($keyword)) {// ID

                    $where[] = ['id', 'eq', $keyword];

                } else {// 用户名、昵称

                    $where[] = ['username', 'like', '%'.$keyword.'%'];

                }

            }

            if ($groupId) {
                $where[] = ['group_id', 'eq', $groupId];
            }

            $data['data'] = UserModel::with(['hasGroup','hasCertification'])
                            ->where($where)
                            ->page($page)
                            ->limit($limit)
                            ->order('id desc')
                            ->select();

            $data['count'] = UserModel::where($where)->count('id');
            $data['code'] = 0;

            return json($data);

        }

        $groups = GroupModel::cache(600)->column('id,name');
        $this->assign('groups', $groups);
        
        return $this->fetch();
    }

    /**
     * 重置密码
     * @author 橘子俊 <364666827@qq.com>
     * @return mixed
     */
    public function resetPwd()
    {
        $id = $this->request->param('id/a');

        $model = new UserModel;

        if (!$model->adminResetPassword($id)) {
            return $this->error('密码重置失败');
        }

        return $this->success('密码重置成功');
    }

    public function certification() {
        $id = $this->request->param('id/a');
        $where = [];
        $where['id'] = $id;
        $user = UserModel::with(['hasGroup','hasCertification'])
                        ->where($where)
                        ->find()->toArray();
        if ($this->request->isAjax()) {
            $param = input();
            if (3 == $param['status']) {
                if (empty($param['overrule_msg'])) {
                    return $this->error('请填写驳回理由');
                }
            }

            $data = [];
            $data['status'] = $param['status'];
            $data['overrule_msg'] = $param['overrule_msg'];

            (new UserCertificationModel)->update($data, ['id'=>$user['has_certification']['id']]);
            return $this->success('审核成功');
        }
        foreach ($user['has_certification'] as $key => $value) {
            $key == 'man_id_img' && $user['has_certification']['man_id_img'] = explode(',', $value);
            $key == 'car_id_img' && $user['has_certification']['car_id_img'] = explode(',', $value);
            $key == 'car_drever_img' && $user['has_certification']['car_drever_img'] = explode(',', $value);
            $key == 'car_img' && $user['has_certification']['car_img'] = explode(',', $value);
        }
        $this->assign('user', $user);
        return $this->fetch();
    }

    public function sendMsg()
    {
        if ($this->request->isAjax()) {
            $title = $this->request->param('title');
            $msg = $this->request->param('msg');
            $to_uid = $this->request->param('to_uid');
            $data = [
                'type' => 0,
                'title' => $title,
                'msg' => $msg
            ];
            if ((new Messages)->log($data, $to_uid)) {
                return $this->success('发送成功');
            }
            return $this->error('发送失败');
        }
    }
}