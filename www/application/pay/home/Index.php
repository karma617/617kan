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
use app\pay\model\PayLog as PayLogModel;
use app\pay\model\PayPayment as PaymentModel;

/**
 * 支付请求控制器
 * @package app\pay\home
 */
class Index extends Common
{
    protected function initialize()
    {
        parent::initialize();
        if (!defined('IS_SAFE_PAY')) {
            define('IS_SAFE_PAY', true);
        }
    }

    /**
     * 支付请求跳转
     * @author 橘子俊 <364666827@qq.com>
     */
    public function index()
    {
        $param = $this->request->param();
        if (isset($param['sign'])) {
            $sign = $param['sign'];
            unset($param['sign']);

            if (str_replace('.html', '', $sign) != md5(http_build_query($param).config('hs_auth.key'))) {
                return $this->error('签名验证失败！');
            }

        } else {
            return $this->error('sign签名参数为必传！');
        }

        $method = $param['method'];
        $goback = isset($param['goback']) ? urldecode($param['goback']) : '/';

        if (!$method) {
            $error = '请以GET或POST方式传递以下参数：';
            $error .= '<br><b style="color:#f00">*</b>method：支付方式CODE'; 
            $error .= '<br><b style="color:#f00">*</b>order_no：订单号(必须唯一)'; 
            $error .= '<br><b style="color:#f00">*</b>money：金额(元)'; 
            $error .= '<br><b style="color:#f00">*</b>uid：用户标记'; 
            $error .= '<br><b style="color:#f00">*</b>callback：内部业务处理接口[可传参，可在后台配置。格式：模块/控制器/方法]'; 
            $error .= '<br><b style="color:#f00">*</b>sign：签名[md5(http_build_query($_GET).config("hs_auth.key"))]';
            $error .= '<br>subject：商品的标题/交易标题[支付宝必填]';
            $error .= '<br>openid：用户标识[JSAPI，此参数必传]';
            $error .= '<br>bank：银行CODE[选填]';
            $error .= '<br>show_url：产品展示地址[选填]';
            $error .= '<br>body：交易的具体描述信息[微信支付必填]';

            return $this->error($error, url(), '', 150);
        }

        $factory = new Factory($method);
        $result = $factory->__call('_submit', $param);
        
        if ($result === false || $result == null) {
            return $this->error($factory->getError(), $goback);
        }

        // 缓存请求数据
        $map                = [];
        $map['order_no']    = $param['order_no'];
        $map['order_sn']    = $param['order_sn'];
        $map['type']        = 1;
        $map['method']      = $method;
        $map['money']       = $param['money'];
        $map['bank']        = isset($param['bank']) ? $param['bank'] : '';
        $map['request']     = isset($result['param']) ? json_encode($result['param'], 1) : '';

        $where    = [];
        $where[]  = ['order_no', '=', $param['order_no']];
        $where[]  = ['order_sn', '=', $param['order_sn']];
        $where[]  = ['method', '=', $method];
        $where[]  = ['type', '=', 1];

        $find   = PayLogModel::where($where)->find();
        if (!$find) {
            $map['uid'] =  $param['uid'];

            $res = PayLogModel::create($map);

            if (!$res) {
                return $this->error('支付请求写入失败！');
            }

        } else if ($find->status == 2) {

            return $this->error('订单已支付！', input('param.goback'));

        } else {

            $map['id'] = $find->id;

            $res = PayLogModel::update($map);

            if (!$res) {
                return $this->error('支付请求写入失败！');
            }

        }

        if (isset($result['template'])) {// 模板输出

            return $result['template'];

        } else if (isset($result['jump_url'])) {// 链接跳转

            echo "<script>location.href='{$result['jump_url']}';</script>";
            exit(0);

        } else if (isset($result['json_data'])) {// json数据

            return json($result);

        }

        $html = '<form id="payForm" name="payForm" action="'.$result['url'].'" method="'.$result['method'].'">';

        foreach ($result['param'] as $k => $v) {
            if (is_empty($v)) {
                continue;
            }
            $html .= "<input type='hidden' name='".$k."' value='".$v."'>";
        }

        $html .= '<input type="submit" value="正在跳转至第三方支付平台，如果未自动跳转请手动点此跳转......" style="border:none;background:#fff;outline:none;font-size:14px;display:none"></form>';

        $this->assign('html', $html);

        return $this->fetch('submit');
    }

    /**
     * 退款请求跳转
     * @author 橘子俊 <364666827@qq.com>
     */
    public function refund()
    {
        $param = $this->request->param();

        if (isset($param['sign'])) {

            $sign = $param['sign'];
            unset($param['sign']);

            if (str_replace('.html', '', $sign) != md5(http_build_query($param).config('hs_auth.key'))) {

                return $this->error('签名验证失败！');

            }

        } else {

            return $this->error('sign签名参数为必传！');

        }

        // 获取已支付记录
        $payLog = PayLogModel::where('order_no', $param['order_no'])
                            ->where('status', 2)
                            ->where('type', 1)
                            ->find();
        if (!$payLog) {

            return $this->error('支付记录不存在');

        }

        // 如果不传退款金额，默认为已支付金额（元）
        if (!isset($param['money']) || !$param['money']) {

            $param['money'] = $payLog['money'];

        }

        // 检查是否已退款
        $refundLog = PayLogModel::where('order_no', $param['order_no'])
                            ->where('status', 2)
                            ->where('type', 2)
                            ->find();
        if ($refundLog) {

            // 获取已退款总额
            $refundMoney = PayLogModel::where('order_no', $param['order_no'])
                            ->where('status', 2)
                            ->where('type', 2)
                            ->sum('money');

            $param['money'] = $payLog['money'] - $refundMoney;

            if ($param['money'] <= 0) {

                return $this->error('此订单已退款成功，请勿重复操作');

            }

        }

    
        $param['pay_log']   = $payLog;// 冗余
        $param['trade_no']  = $payLog['trade_no'];// 支付平台交易号
        $param['uid']       = $payLog['uid'];// 用户标识
        $param['refund_no'] = isset($param['refund_no']) ? $param['refund_no'] : random(20);

        $method = $payLog['method'];

        if (!$method) {

            $error = '请以GET或POST方式传递以下参数：';
            $error .= '<br><b style="color:#f00">*</b>order_no：要退款的订单号[必须唯一]';
            $error .= '<br><b style="color:#f00">*</b>callback：内部业务处理接口[可传参，可在后台配置。格式：模块/控制器/方法]'; 
            $error .= '<br><b style="color:#f00">*</b>sign：签名[md5(http_build_query($_GET).config("hs_auth.key"))]';
            $error .= '<br>money：退款金额(元)[不填默认为已支付金额]';
            $error .= '<br>refund_no：退款流水号[如需部分退款，则此参数必传]';
            $error .= '<br>show_url：产品展示地址';

            return $this->error($error, url(), '', 150);
        }

        // 生成退款请求日志
        $sqlmap             = [];
        $sqlmap['type']     = 2;
        $sqlmap['status']   = 1;
        $sqlmap['method']   = $method;
        $sqlmap['uid']      = $payLog['uid'];
        $sqlmap['order_no'] = $param['order_no'];
        $sqlmap['refund_no']= $param['refund_no'];
        $sqlmap['request']  = json_encode($param, 1);
        $sqlmap['money']    = $param['money'];

        $paylogModel = new PayLogModel;

        if (!$paylogModel->save($sqlmap)) {

            return $this->error('退款成功，数据写入失败');

        }

        $factory = new Factory($method);
        $goback = isset($param['goback']) ? urldecode($param['goback']) : '/';

        unset($param['goback']);

        $result = $factory->__call('_refundSubmit', $param);

        if ($result === false) {// 错误返回

            return $this->error($factory->getError(), $goback);

        } else if (isset($result['result'])) {

            // 将访问的request,return,trade_no存库
            $result['result']['status'] = 2;
            PayLogModel::where('id', $paylogModel->id)->update($result['result']);

            if (isset($param['callback'])) {

                $callback = $param['callback'];

            } else {

                $payment = PaymentModel::lists($method);
                $callback = $payment['config']['refund_callback'];

            }

            if ($callback) {

                $callback = explode('_', str_replace('/', '_', $callback));

                $module = $callback[0];
                $contr  = ucfirst($callback[1]);
                $action = $callback[2];

                // 返回格式：['status' => true, 'message' => '错误说明', 'url' => '退款成功后的跳转页面']
                $result = action($module.'/'.$contr.'/'.$action, ['orderNo' => $param['order_no'], 'param' => $result['result']], 'home');

                if ($result['status'] !== true) {
                    return $this->error($result['message']);
                }

            }
            
            return $this->success('退款成功', isset($result['url']) ? $result['url'] : $goback);
        }

        // 表单提交或跳转方式发起退款
    }
}
