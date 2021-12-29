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

namespace app\pay\home;
use app\common\controller\Common;
use Env;
use app\order\model\Order as OrderModel;
use app\order\model\OrderTrade as OrderTradeModel;
use app\user\server\Commission as CommissionService;

/**
 * 支付测试
 * 为了安全起见，测试完请删除或者禁用此文件
 * @package app\pay\home
 */
class Notify extends Common
{
    protected function initialize()
    {
        parent::initialize();
        $this->OrderModel = new OrderModel();
        $this->OrderTradeModel = new OrderTradeModel();
        $this->Commission = new CommissionService();
    }

    public function index()
    {
        echo '支付成功';
    }

    /**
     * [示例]发起支付
     * @author 橘子俊 <364666827@qq.com>
     */
    public function paySubmit()
    {
        $orderNo = input('get.order_no', order_number());
        $method = input('get.method', 'wechat_js');
        $param = [];
        $param['method'] = $method;// 支付方式[wechat, alipay]
        $param['money'] = 0.01;// 支付金额
        $param['uid'] = 1000001;// 用户标识
        $param['order_no'] = $orderNo;// 订单号
        $param['body'] = '支付测试';// 支付标题
        $param['goback'] = get_domain().'/pay/test';// 同步回调后的跳转地址
        $param['openid'] = '';
        $param['sign'] = md5(http_build_query($param).config('hs_auth.key'));
        
        $this->redirect('/pay?'.http_build_query($param));
    }

    /**
     * [示例]发起退款
     * @author 橘子俊 <364666827@qq.com>
     */
    public function refundSubmit()
    {
        $orderNo = input('get.order_no');

        $param = [];
        $param['order_no'] = $orderNo;// 已支付的订单号
        $param['money'] = 0.01;// 支付金额
        $param['goback'] = get_domain().'/pay/test';// 同步回调后的跳转地址
        $param['sign'] = md5(http_build_query($param).config('hs_auth.key'));
        $this->redirect('/pay/index/refund?'.http_build_query($param));
    }

    /**
     * [示例]支付回调
     * @param int $orderNo 订单号
     * @param array $params 所有回传参数
     * @author 橘子俊 <364666827@qq.com>
     */
    public function payment($orderNo, $param = [])
    {
        $message = '未找到相关运单：'.$orderNo;
        if (!defined('IS_SAFE_PAY')) {
            return ['status' => false, 'message' => '非法请求'];
        }
        // 订单业务处理代码
        file_put_contents(Env::get('runtime_path').'/pay_notify/'.$orderNo.'_payment.txt', json_encode($param, 1));
        
        // 支付时间
        $pay_time = 0;
        $order_trade_map[] = ['trade_no' , 'eq' , $orderNo];
        $order_trade_map[] = ['status' , 'eq' , 1];
        // 查询是否有此订单
        $order_infos = $this->OrderTradeModel->where($order_trade_map)->find();
        if ($order_infos) {
            switch ($order_infos['method']) {
                case 'alipay_qr':
                    $pay_time = strtotime($param['notify_time']);
                    $money = $param['buyer_pay_amount'];    //买家付款的金额
                    $pay_sn = $param['trade_no'];           //支付宝交易号
                    break;
                case 'alipay_app':
                    $pay_time = strtotime($param['notify_time']);
                    $money = $param['buyer_pay_amount'];    //买家付款的金额
                    $pay_sn = $param['trade_no'];           //支付宝交易号
                    break;
                case 'wechat_app':
                    $pay_time = strtotime($param['notify_time']);
                    $money = $param['buyer_pay_amount'];
                    break;
                case 'personal':
                    $pay_time = strtotime($param['notify_time']);
                    $money = $param['reallyPrice'];
                    $pay_sn = $param['trade_no'];
                    break;
                
                default:
                    return ['status' => false, 'message' => '未知支付方式'];
            }
            // 核对支付金额
            $order_trade_map[] = ['money' , 'eq' , $money];
            $order_info = $this->OrderTradeModel->where($order_trade_map)->find();
            if ($order_info) {
                // 修改订单状态
                if ($this->OrderModel->save(['status'=>2,'pay_status'=>2], ['order_sn' => $order_info['order_sn'], 'status' => 1])) {
                    if($this->OrderTradeModel->save(['status'=>2, 'pay_sn' => $pay_sn], ['trade_no'=> $orderNo, 'status' => 1])){
                        runhook('after_pay_notify', $param);
                        return ['status' => true, 'message' => '支付成功'];
                    }else{
                        $message = '订单状态修改失败！ERROR：-2';
                    }
                }else{
                    $message = '订单状态修改失败！ERROR：-1';
                }
            }else{
                $message = '支付金额与订单金额不符！订单金额：' . $order_infos['money'].'，实际支付：' . $money;
            }
        }
        return ['status' => false, 'message' => $message];
    }

    /**
     * [示例]退款回调
     * @param int $orderNo 订单号
     * @param array $params 所有回传参数
     * @author 橘子俊 <364666827@qq.com>
     */
    public function refund($orderNo, $param = [])
    {
        if (!defined('IS_SAFE_PAY')) {
            return ['status' => false, 'message' => '非法请求'];
        }
        // 订单业务处理代码
        file_put_contents(Env::get('runtime_path').$orderNo.'_refund.txt', json_encode($param, 1));
        return ['status' => true, 'url' => '/pay/test'];
    }
}
