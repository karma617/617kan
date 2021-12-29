<?php
// +----------------------------------------------------------------------
// | qdapi
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://qdapi.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 617 <723875993@qq.com>
// +----------------------------------------------------------------------
namespace app\banner\admin;
use app\common\admin\AdminCommon;
use app\banner\model\Banner as BannerModel;
use app\banner\model\Ad as AdModel;

class Banner extends AdminCommon
{
    protected $hisiModel = 'Banner';//模型名称[通用添加、修改专用]
    protected $hisiTable = '';//表名称[通用添加、修改专用]
    protected $hisiAddScene = '';//添加数据验证场景名
    protected $hisiEditScene = '';//更新数据验证场景名

    public function initialize() {
        parent::initialize();
        $this->BannerModel = new BannerModel();
        $this->AdModel = new AdModel();
    }
    /**
     * 轮播图
     *
     * @return void
     * @author 617 <email：723875993@qq.com>
     */
    public function index()
    {
        if ($this->request->isAjax()) {
            $map    = $data = [];
            $data   = $this->params;
            $page   = isset($data['page']) ? $data['page'] : 1;
            $limit  = isset($data['limit']) ? $data['limit'] : 15;

            $data = $this->BannerModel->getList($map, $page, $limit);
            
            $data['code'] = 0;
            $data['msg'] = '';
            $data['data'] = $data['list'];
            return json($data);
        }
        return $this->fetch();
    }
    public function add() 
    {
        $data = [];
        $data = $this->params;
        $id = isset($data['id']) && !empty($data['id']) ? $data['id'] : 0;
        if ($this->request->isAjax()) {
            $_data = [
                'title' => $data['title'],
                'img' => isset($data['img_url']) && !empty($data['img_url']) ? $data['img_url'] : $data['img'],
                'vid' => $data['vid'],
                'isad' => $data['isad'],
                'ad_url' => $data['ad_url'],
            ];
            if ($data['isad'] == 2) {
                $_data['type'] = 'mpad';
            }
            if ($id > 0) {
                $this->BannerModel->save($_data, ['id'=>$id]);
            }else{
                $this->BannerModel->save($_data);
            }
            
            $this->success('保存成功');
        }
        if ($id > 0) {
            $__data = $this->BannerModel->where('id', $id)->find()->toArray();
            $this->assign('formData', $__data);
        }
        return $this->fetch('form');
    }
    /**
     * 启动图
     *
     * @return void
     * @author 617 <email：723875993@qq.com>
     */
    public function indexad()
    {
        if ($this->request->isAjax()) {
            $map    = $data = [];
            $map[] = ['type', 'eq', 1];
            $data   = $this->params;
            $page   = isset($data['page']) ? $data['page'] : 1;
            $limit  = isset($data['limit']) ? $data['limit'] : 15;

            $data = $this->AdModel->getList($map, $page, $limit);
            
            $data['code'] = 0;
            $data['msg'] = '';
            $data['data'] = $data['list'];
            return json($data);
        }
        $this->assign('type', 1);
        return $this->fetch();
    }
    /**
     * 首页广告
     *
     * @return void
     * @author 617 <email：723875993@qq.com>
     */
    public function indexHomeAd()
    {
        if ($this->request->isAjax()) {
            $map    = $data = [];
            $map[] = ['type', 'eq', 2];
            $data   = $this->params;
            $page   = isset($data['page']) ? $data['page'] : 1;
            $limit  = isset($data['limit']) ? $data['limit'] : 15;

            $data = $this->AdModel->getList($map, $page, $limit);
            
            $data['code'] = 0;
            $data['msg'] = '';
            $data['data'] = $data['list'];
            return json($data);
        }
        $this->assign('type', 2);
        return $this->fetch();
    }

    /**
     * 播放页弹窗广告
     *
     * @return void
     * @author 617 <email：723875993@qq.com>
     */
    public function indexPlayerAd()
    {
        if ($this->request->isAjax()) {
            $map    = $data = [];
            $map[] = ['type', 'eq', 3];
            $data   = $this->params;
            $page   = isset($data['page']) ? $data['page'] : 1;
            $limit  = isset($data['limit']) ? $data['limit'] : 15;

            $data = $this->AdModel->getList($map, $page, $limit);
            
            $data['code'] = 0;
            $data['msg'] = '';
            $data['data'] = $data['list'];
            return json($data);
        }
        $this->assign('type', 3);
        return $this->fetch();
    }
    /**
     * 播放页底部广告
     *
     * @return void
     * @author 617 <email：723875993@qq.com>
     */
    public function publicAd()
    {
        if ($this->request->isAjax()) {
            $map    = $data = [];
            $map[] = ['type', 'eq', 4];
            $data   = $this->params;
            $page   = isset($data['page']) ? $data['page'] : 1;
            $limit  = isset($data['limit']) ? $data['limit'] : 15;

            $data = $this->AdModel->getList($map, $page, $limit);
            
            $data['code'] = 0;
            $data['msg'] = '';
            $data['data'] = $data['list'];
            return json($data);
        }
        $this->assign('type', 4);
        return $this->fetch();
    }

    /**
     * 视频播放前广告
     *
     * @return void
     * @author 617 <email：723875993@qq.com>
     */
    public function videoAd()
    {
        if ($this->request->isAjax()) {
            $map    = $data = [];
            $map[] = ['type', 'eq', 5];
            $data   = $this->params;
            $page   = isset($data['page']) ? $data['page'] : 1;
            $limit  = isset($data['limit']) ? $data['limit'] : 15;

            $data = $this->AdModel->getList($map, $page, $limit);
            
            $data['code'] = 0;
            $data['msg'] = '';
            $data['data'] = $data['list'];
            return json($data);
        }
        $this->assign('type', 5);
        return $this->fetch();
    }
    /**
     * 播放前激励广告
     *
     * @return void
     * @author 617 <email：723875993@qq.com>
     */
    public function playStartAd()
    {
        if ($this->request->isAjax()) {
            $map    = $data = [];
            $map[] = ['type', 'eq', 6];
            $data   = $this->params;
            $page   = isset($data['page']) ? $data['page'] : 1;
            $limit  = isset($data['limit']) ? $data['limit'] : 15;

            $data = $this->AdModel->getList($map, $page, $limit);
            
            $data['code'] = 0;
            $data['msg'] = '';
            $data['data'] = $data['list'];
            return json($data);
        }
        $this->assign('type', 6);
        return $this->fetch();
    }
    
    /**
     * 添加广告
     *
     * @return void
     * @author 617 <email：723875993@qq.com>
     */
    public function addad() 
    {
        $data = [];
        $data = $this->params;
        $id = isset($data['id']) && !empty($data['id']) ? $data['id'] : 0;
        $type = isset($data['type']) && !empty($data['type']) ? $data['type'] : 1;
        $action = isset($data['url']) && !empty($data['url']) ? $data['url'] : 'indexad';
        if ($this->request->isAjax()) {
            $_data = [
                'title' => $data['title'],
                'img' => $data['img'],
                'url' => $data['url'],
                'v_url' => $data['v_url'],
                'desc' => isset($data['desc']) ? $data['desc'] : '',
                'type' => $data['type'],
                'ad_type' => $data['ad_type'],
            ];
            if ($id > 0) {
                $this->AdModel->save($_data, ['id'=>$id]);
            }else{
                $this->AdModel->save($_data);
            }
            
            $this->success('保存成功');
        }
        if ($id > 0) {
            $__data = $this->AdModel->where('id', $id)->find()->toArray();
            $this->assign('formData', $__data);
        }
        $this->assign('type', $type);
        $this->assign('url', $action);
        return $this->fetch('addad');
    }
}