<?php
// +----------------------------------------------------------------------
// | HisiPHP框架[基于ThinkPHP5开发]
// +----------------------------------------------------------------------
// | Copyright (c) 2016-2018 http://www.hisiphp.com
// +----------------------------------------------------------------------
// | HisiPHP承诺基础框架永久免费开源，您可用于学习和商用，但必须保留软件版权信息。
// +----------------------------------------------------------------------
// | Author: 橘子俊 <364666827@qq.com>，开发者QQ群：50304283
// +----------------------------------------------------------------------

namespace plugins\sms\admin;
use app\system\admin\Admin;
use plugins\sms\model\PluginsSmsLog as LogModel;
use plugins\sms\model\PluginsSms as SmsModel;

/**
 * 短信日志控制器
 * @package plugins\sms\admin
 */
class Logs extends Admin
{
    /**
     * 短信日志管理
     * @return mixed
     */
    public function index()
    {
        if ($this->request->isAjax()) {

            $map = $data = [];
            $page       = $this->request->param('page/d', 1);
            $limit      = $this->request->param('limit/d', 15);
            $date       = $this->request->param('date');
            $gateway    = $this->request->param('gateway');
            $template   = $this->request->param('template');
            
            if ($date) {
                $expl = explode(' - ', $date);
                $map['ctime'] = ['between', [strtotime($expl[0]), strtotime($expl[1].' 23:59:59')]];
            }

            if ($template) {
                $map['template'] = $template;
            }

            if ($gateway) {
                $map['gateway'] = $gateway;
            }

            $data['data']   = LogModel::where($map)->order('id desc')->page($page)->limit($limit)->select();
            $data['count']  = LogModel::where($map)->count('id');
            $data['code']   = 0;

            return json($data);
        }

        $gateways = SmsModel::column('gateway,title');

        $this->assign('gateways', $gateways);
        
        return $this->fetch();
    }

    /**
     * 删除短信日志
     * @return mixed
     */
    public function del()
    {
        $id = get_num();
        if (!LogModel::where('id', $id)->delete()) {
            return $this->error('删除失败');
        }
        return $this->success('删除成功');
    }
}