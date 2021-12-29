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
namespace app\pay\driver;

abstract class PayAbstract
{
    protected $config = array();
    protected $globalParams = array();
    protected $gateway_type = 'redirect';

    /**
     * 设置支付全局参数
     * @param array $param 参数
     * @author 橘子俊 <364666827@qq.com>
     * @return array
     */
    public function setGlobalParams($param) {
        if (empty($param)) {
            $this->error = '请传入交易信息！';
            return false;
        }

        if (!isset($param['order_no']) || empty($param['order_no'])) {
            $this->error = 'order_no 为必传参数！';
            return false;
        }

        if (!isset($param['money']) || empty($param['money'])) {
            $this->error = 'money 为必传参数，单位元！';
            return false;
        }

        if (!isset($param['uid']) || empty($param['uid'])) {
            $this->error = 'uid 为必传参数！';
            return false;
        }

        $action = '';
        if (strtolower(request()->action()) == 'refund') {
            $action = 'Refund';
        }

        // 默认为空，自动调用支付模块后台配置
        $callback = '';
        if (isset($param['sync_url'])) {// sync_url 兼容旧版本
            $callback = str_replace('/', '_', $param['sync_url']);

            if (count(explode('_', $callback)) != 3) {
                $this->error = '内部回调地址(callback)为必传参数！[格式：模块_控制器_方法]';
                return false;
            }
        } else if (isset($param['callback'])) {// 参数优先
            $callback = str_replace('/', '_', $param['callback']);

            if (count(explode('_', $callback)) != 3) {
                $this->error = '内部回调地址(callback)为必传参数！[格式：模块_控制器_方法]';
                return false;
            }
        }

        // 拼装完整的同步回调地址
        $param['sync_url'] = get_domain().'/pay/callback/sync'.$action.'/method/'.PAY_CODE.'/callback/'.$callback;
        
        // 拼装完整的异步回调地址
        $param['async_url'] = get_domain().'/pay/callback/async'.$action.'/method/'.PAY_CODE.'/callback/'.$callback;
        $this->globalParams = $param;
        return true;
    }
    
    /* 支付提交接口 */
    abstract public function _submit($param);
    /* 同步通知接口 */
    abstract public function _sync($param);
    /* 异步通知接口 */
    abstract public function _async($param);
    /* 退款提交接口 */
    abstract public function _refundSubmit($param);
    /* 同步退款通知接口 */
    abstract public function _syncRefund($param);
    /* 异步退款通知接口 */
    abstract public function _asyncRefund($param);
}