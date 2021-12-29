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
namespace app\pay\driver\personal;
use app\pay\driver\PayAbstract;
use app\pay\model\PayLog as PayLogModel;
use app\common\util\Http;

class personal extends PayAbstract
{
    public function __construct($config = [])
    {
        if (!empty($config)) $this->config = $config;
        $this->config['gateway_url']    = 'http://pay.qdapi.com/createOrder';
    }

    /**
     * 支付请求提交
     * @author 橘子俊 <364666827@qq.com>
     * @return bool|string
     */
    public function _submit($param)
    {
        if (!$this->setGlobalParams($param)) {
            return false;
        }

        if (!isset($param['type']) || empty($param['type'])) {
            $this->error = 'type参数不允许为空！';
            return false;
        }
        // 如果定义请求网关
        $gateway_url = (isset($param['gateway_url']) && !empty($param['gateway_url'])) ? $param['gateway_url'] : $this->config['gateway_url'];

        $data = [];
        // 业务请求参数
        $data['payId']      = $param['trade_no'];
        $data['type']       = $param['type'];
        $data['param']      = isset($param['param']) ? $param['param'] : '';
        $data['price']      = sprintf('%.2f', $param['money']);
        $data['isHtml']     = 0;
        $data['notifyUrl']  = $this->globalParams['async_url'];
        $data['returnUrl']  = $this->globalParams['sync_url'];

        $data['sign'] = md5($data['payId'].$data['param'].$data['type'].$data['price'].$this->config['vkey']);
        $paramsStr = $this->ToUrlParams($data);
        $result = $this->posts($gateway_url, $paramsStr);
        $result = json_decode($result, true);
        if ($result['code'] == -1) {
        	return ['json_data' => ['code' => -1, 'msg' => $result['msg']]];
        }
        $ret['json_data'] = $result['data'];
        return $ret;
    }

    /**
     * 同步通知
     * @author 橘子俊 <364666827@qq.com>
     * @return bool|string
     */
    public function _sync($param = [], $async = false)
    {
        $param = $_GET;

        if (!$this->signVerify($param)) {
            $this->error = '回调验证失败！';
            return false;
        }
        
        $row = PayLogModel::where('order_no', $param['payId'])
                            ->where('method', input('param.method'))
                            ->find();
        if (!$row) {
            $this->error = '数据不存在！';
            return false;
        }

        if ($row['status'] === 2) {
            $this->error = '已支付成功！';
            return $param['payId'];
        }

        $sqlmap = [];
        $sqlmap['trade_no'] = $param['trade_no'];
        $sqlmap['return'] = json_encode($param, 1);
        $sqlmap['status'] = 2;

        if (!PayLogModel::where('id', $row['id'])->update($sqlmap)) {
            $this->error = '支付处理失败！';
            return false;
        }

        return $param['payId'];
    }

    /**
     * 异步通知
     * @author 橘子俊 <364666827@qq.com>
     * @return bool|string
     */
    public function _async($param = [])
    {
        $result = $this->_sync($param, true);
        if ($result === false) {
            echo 'fail';
            exit;
        }
        return $result;
    }

    public function posts($url,$params){
        $ch = curl_init();
        $this_header = array("content-type: application/x-www-form-urlencoded;charset=UTF-8");
        curl_setopt($ch,CURLOPT_HTTPHEADER,$this_header);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
         
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);//如果不加验证,就设false,商户自行处理
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
         
        $output = curl_exec($ch);
        curl_close($ch);
        return  $output;
    }

    public function ToUrlParams($array = []) {
        $buff = "";
        foreach ($array as $k => $v) {
            if($v != "" && !is_array($v)){
                $buff .= $k . "=" . $v . "&";
            }
        }
        $buff = trim($buff, "&");
        return $buff;
    }

    public function signVerify($params)
    {
        $payId = $params['payId'];//商户订单号
        $param = $params['param'];//创建订单的时候传入的参数
        $type = $params['type'];//支付方式 ：微信支付为1 支付宝支付为2
        $price = $params['price'];//订单金额
        $reallyPrice = $params['reallyPrice'];//实际支付金额
        $sign = $params['sign'];//校验签名，计算方式 = md5(payId + param + type + price + reallyPrice + 通讯密钥)
        //开始校验签名
        $_sign =  md5($payId . $param . $type . $price . $reallyPrice . $this->config['vkey']);
        return $_sign == $sign;
    }
    /**
     * 发起退款[无]
     * @author 橘子俊 <364666827@qq.com>
     * @return bool|string
     */
    public function _refundSubmit($param = [])
    {
        return true;
    }

    /**
     * 同步退款回调[无]
     * @author 橘子俊 <364666827@qq.com>
     * @return bool|string
     */
    public function _syncRefund($param = [])
    {
        return true;
    }

    /**
     * 异步退款回调[无]
     * @author 橘子俊 <364666827@qq.com>
     * @return bool|string
     */
    public function _asyncRefund($param = [])
    {
        return true;
    }
}