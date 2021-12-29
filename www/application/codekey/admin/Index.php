<?php

namespace app\codekey\admin;

use app\common\admin\AdminCommon;
use app\codekey\model\UserGroupCodekey as UserGroupCodekeyModel;
use app\user\model\User as UserModel;

class Index extends AdminCommon
{
    protected $hisiModel = 'UserGroupCodekey'; //模型名称[通用添加、修改专用]
    protected $hisiTable = ''; //表名称[通用添加、修改专用]
    protected $hisiAddScene = ''; //添加数据验证场景名
    protected $hisiEditScene = ''; //更新数据验证场景名

    public function initialize()
    {
        parent::initialize();
        $this->UserGroupCodekeyModel = new UserGroupCodekeyModel();
        $this->UserModel = new UserModel();
    }

    public function index()
    {
        if ($this->request->isAjax()) {
            $map    = $data = [];
            $data   = $this->params;
            if (isset($data['group_id']) && !empty($data['group_id'])) {
                $map[] = ['group_id', 'eq', $data['group_id']];
            }
            if (isset($data['mobile']) && !empty($data['mobile'])) {
                $mobile[] = ['mobile', 'eq', $data['mobile']];
                $uid = $this->UserModel->where($mobile)->value('id');
                $map[] = ['user_id', 'eq', $uid];
            }
            if (isset($data['status']) && $data['status'] != '') {
                $map[] = ['status', 'eq', $data['status']];
            }
            $page   = isset($data['page']) ? $data['page'] : 1;
            $limit  = isset($data['limit']) ? $data['limit'] : 15;
            $data = $this->UserGroupCodekeyModel->getList($map, $page, $limit);
            $data['code'] = 0;
            $data['data'] = $data['list'];
            return json($data);
        }
        return $this->fetch();
    }

    public function createCodekey()
    {
        $data   = $this->params;
        if ($data['number'] > 100) {
            return $this->error("为降低服务器压力，每次生成的数量不可超过100个");
        }
        $i = 1;
        $sql = 'INSERT INTO ' . config('database.prefix') . 'user_group_codekey VALUES';
        $time = time();
        while ($i <= $data['number']) {
            $uniqid = random(8,0);
            $sql .= '(0, 0, ' . $data['group_id'] . ',"' . $uniqid . '", 0, ' . $time . ', ' . $time . ')'
                . (($i == $data['number']) ? ';' : ',');
            $i++;
        }
        $this->UserGroupCodekeyModel->query($sql);
        return $this->success('生成成功');
    }
}
