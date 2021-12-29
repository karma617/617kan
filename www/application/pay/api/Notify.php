<?php
namespace app\pay\api;
use app\common\controller\Common;
use Env;

class Notify extends Common
{
    protected function initialize()
    {
        parent::initialize();
    }

    public function index()
    {
        echo '支付成功';
    }

    /**
     * [示例]支付回调
     * @param int $orderNo 订单号
     * @param array $params 所有回传参数
     * @author 橘子俊 <364666827@qq.com>
     */
    public function payment($orderNo = '', $param = [])
    {
        if (!defined('IS_SAFE_PAY')) {
            return ['status' => false, 'message' => '非法请求'];
        }
        // 订单业务处理代码
        file_put_contents(Env::get('runtime_path').$orderNo.'_payment.txt', json_encode($param, 1));
        return ['status' => true, 'url' => '/pay/test'];
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
