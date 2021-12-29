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
namespace app\pay\home;
use app\common\controller\Common;
use app\pay\driver\Factory;
use app\pay\model\PayPayment;
use Env;

/**
 * 支付回调控制器
 * @package app\pay\home
 */
class Callback extends Common
{
    public $callback = '';// 内部回调地址
    public $method = '';// 支付平台

    protected function initialize()
    {
        parent::initialize();

        if (!defined('IS_SAFE_PAY')) {

            define('IS_SAFE_PAY', true);

        }

        $this->method = $this->request->param('method');

        if (empty($this->method)) {

            return self::_return('method参数不允许为空！');

        }

        $callback = $this->request->param('callback');
        if (!$callback) {// 如果不存在，直接调用后台配置

            $action = strtolower($this->request->action());
            $payment = PayPayment::lists($this->method);

            if (strpos($action, 'refund')) {

                $callback = $payment['config']['refund_callback'];

            } else {

                $callback = $payment['config']['payment_callback'];

            }

        }

        if (empty($callback)) {

            return self::_return('callback参数不允许为空！');

        }

        $this->callback = explode('_', str_replace('/', '_', $callback));

        if (count($this->callback) != 3) {

            return self::_return('callback参数格式错误[模块_控制器_方法]！');

        }

        $this->factory = new Factory($this->method);
    }

    /**
     * 同步回调
     * @author 橘子俊 <364666827@qq.com>
     */
    public function sync()
    {
        return self::payCallback();
    }

    /**
     * 异步回调
     * @author 橘子俊 <364666827@qq.com>
     */
    public function async()
    {

        return self::payCallback(true);
    }

    /**
     * 支付回调业务处理
     * @param $async bool 异步调用
     * @author 橘子俊 <364666827@qq.com>
     */
    private function payCallback($async = false)
    {
        $module = $this->callback[0];
        $contr  = ucfirst($this->callback[1]);
        $action = $this->callback[2];
        $params = $this->request->param();

        if (strpos($this->method, 'wechat') !== false) {

            if (isset($GLOBALS['HTTP_RAW_POST_DATA'])) {

                $params = xmlToArray($GLOBALS['HTTP_RAW_POST_DATA']);

            } else {

                $params = xmlToArray(file_get_contents('php://input'));

            }

        }

        // 支付回调处理，只实现简单的支付数据处理，非实际业务处理
        $orderNo = $this->factory->__call( $async ? '_async' : '_sync', $params);

        if ( $orderNo === false || $orderNo == null) {

            return self::_return($this->factory->getError());

        }

        if (!method_exists("app\\{$module}\\home\\{$contr}", $action)) {

            return self::_return('内部方法不存在，请仔细检查url参数！');

        }

        // 调用内部方法处理实际业务
        // 返回格式：['status' => true, 'message' => '错误说明', 'url' => '支付成功后的跳转页面']
        $result = action($module.'/'.$contr.'/'.$action, ['orderNo' => $orderNo, 'param' => $params], 'home');

        if ($result['status'] !== true) {

            return self::_return($result['message']);

        }

        // 异步返回
        if ($async === true) {

            return self::_return();

        }

        // 同步返回，优先跳转到指定的提示页面
        if (isset($result['url']) && !empty($result['url'])) {

            header('location: '.$result['url']);
            exit;

        } else {

            return $this->success('恭喜您！支付业务处理成功。', '/');
            
        }
    }

    /**
     * 同步退款
     * @author 橘子俊 <364666827@qq.com>
     */
    public function syncRefund()
    {
        // TODO
        // 返回格式：['status' => true, 'message' => '错误说明', 'url' => '支付成功后的跳转页面']
    }

    /**
     * 异步退款
     * @author 橘子俊 <364666827@qq.com>
     */
    public function asyncRefund()
    {
        // TODO
        // 返回格式：['status' => true, 'message' => '错误说明', 'url' => '支付成功后的跳转页面']
    }

    /**
     * 返回结果
     * @param  string $info 为空表示返回正确结果，其他值返回错误结果
     * @author 橘子俊 <364666827@qq.com>
     */
    private function _return($info = '')
    {
        if (strtolower($this->request->action()) == 'sync') {
            return $this->error($info);
        }
        if (empty($info)) {
            echo 'success';
            exit;
        } else {
            echo 'fail';
            exit;
        }
    }
}