<?php
// +----------------------------------------------------------------------
// | HisiPHP框架[基于ThinkPHP5开发]
// +----------------------------------------------------------------------
// | Copyright (c) 2016-2021 http://www.hisiphp.com
// +----------------------------------------------------------------------
// | HisiPHP提供个人非商业用途免费使用，商业需授权。
// +----------------------------------------------------------------------
// | Author: 橘子俊 <364666827@qq.com>，开发者QQ群：50304283
// +----------------------------------------------------------------------
namespace app\pay\admin;
use app\system\admin\Admin;
use app\pay\model\PayPayment as PaymentModel;
use app\pay\model\PayLog as LogModel;

class Logs extends Admin
{
    /**
     * 支付日志管理
     * @author 橘子俊 <364666827@qq.com>
     * @return mixed
     */
    public function index()
    {
        if ($this->request->isAjax()) {
            $map    = $data = [];
            $page   = $this->request->param('page/d', 1);
            $limit  = $this->request->param('limit/d', 15);
            $date   = $this->request->param('date');
            $method = $this->request->param('method');
            $uid    = $this->request->param('uid');
            $status = $this->request->param('status');
            $orderNo= $this->request->param('order_no');
            $tradeNo= $this->request->param('trade_no');
                
            if ($date) {
                $expl = explode(' - ', $date);
                $map['ctime'] = ['between', [strtotime($expl[0]), strtotime($expl[1].' 23:59:59')]];
            }

            if ($uid) {
                $map['uid'] = $uid;
            }

            if ($method) {
                $map['method'] = $method;
            }

            if (is_numeric($status)) {
                $map['status'] = $status;
            }

            if ($orderNo) {
                $map['order_no'] = $orderNo;
            }

            if ($tradeNo) {
                $map['trade_no'] = $tradeNo;
            }

            $data['data']   = LogModel::where($map)->order('id desc')->page($page)->limit($limit)->select();
            $data['count']  = LogModel::where($map)->count('id');
            $data['code']   = 0;

            return json($data);
        }

        $tabData = [
            'menu' => [
                ['title' => '在线支付', 'url' => 'pay/index/index'],
                ['title' => '支付日志', 'url' => 'pay/logs/index'],
            ],
            'current' => url(''),
        ];

        $this->assign('payment', PaymentModel::column('code,title,applies,config', 'code'));
        $this->assign('hisiTabType', 3);
        $this->assign('hisiTabData', $tabData);
        return $this->fetch();
    }
}