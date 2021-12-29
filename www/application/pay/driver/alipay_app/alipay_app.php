<?php
namespace app\pay\driver\alipay_app;
use app\pay\driver\PayAbstract;
use app\pay\model\PayLog as PayLogModel;
use app\common\util\Http;

class alipay_app extends PayAbstract
{
    public function __construct($config = [])
    {
        if (!empty($config)) $this->config = $config;
        $this->config['gateway_url'] = 'https://openapi.alipay.com/gateway.do?charset=utf-8';
        $this->config['format'] = 'JSON';
        $this->config['charset'] = 'utf-8';
        $this->config['sign_type'] = 'RSA2';
        $this->config['version'] = '1.0';
        $this->config['gateway_method'] = 'POST';
    }

    /**
     * 支付请求提交
     * @return bool|string
     */
    public function _submit($param)
    {
        if (!$this->setGlobalParams($param)) {
            return false;
        }

        if (!isset($param['subject']) || empty($param['subject'])) {
            $this->error = 'subject参数不允许为空！';
            return false;
        }

        $data['param'] = [];
        // 业务请求参数
        $data['param']['out_trade_no']      = $param['order_no'];
        // $data['param']['product_code']      = 'FACE_TO_FACE_PAYMENT';
        $data['param']['product_code']      = 'QUICK_MSECURITY_PAY';
        $data['param']['total_amount']      = sprintf('%.2f', $param['money']);
        $data['param']['subject']           = $param['subject'];  
        $data['param']['body']              = isset($param['body']) ? $param['body'] : '';
        // 公共请求参数
        $data['param'] = self::publicParam($data['param']);
        $data['param']['sign'] = self::sign($data['param']);
        $result['json_data'] = http_build_query($data['param']);
        return $result;
    }

    /**
     * 同步通知
     * @return bool|string
     */
    public function _sync($param = [], $async = false)
    {
        $params = $async === true ? $_POST : $_GET;

        if (!self::signVerify($params)) {
            $this->error = '回调验证失败！';
            return false;
        }

        $row = PayLogModel::where('order_no', $param['out_trade_no'])
                            ->where('method', input('param.method'))
                            ->find();
        if (!$row) {
            $this->error = '数据不存在！';
            return false;
        }

        if ($row['status'] === 2) {
            $this->error = '已支付成功！';
            return $param['out_trade_no'];
        }

        $sqlmap = [];
        $sqlmap['trade_no'] = $param['trade_no'];
        $sqlmap['return'] = json_encode($param, 1);
        $sqlmap['status'] = 2;

        if (!PayLogModel::where('id', $row['id'])->update($sqlmap)) {
            $this->error = '支付处理失败！';
            return false;
        }

        return $param['out_trade_no'];
    }

    /**
     * 异步通知
     * @return bool|string
     */
    public function _async($param = [])
    {
        $result = self::_sync($param, true);
        if ($result === false) {
            echo 'fail';
            exit;
        }
        return $result;
    }

    /**
     * 发起退款
     * @return bool|array
     */
    public function _refundSubmit($param = [])
    {
        $payLog = $param['pay_log'];
        unset($param['pay_log']);
        if (!$this->setGlobalParams($param)) {
            return false;
        }
        
        $data['param'] = [];
        // 业务请求参数
        $data['param']['out_trade_no']      = $param['order_no'];
        $data['param']['trade_no']          = $param['trade_no'];
        $data['param']['refund_amount']     = sprintf('%.2f', $param['money']);
        $data['param']['refund_reason']     = isset($param['remark']) ? $param['remark'] : '';  
        $data['param']['out_request_no']    = $param['refund_no'];

        // 公共请求参数
        $data['param'] = self::publicParam($data['param'], 'alipay.trade.refund');

        // 删除同步和异步通知地址
        unset($data['param']['return_url'], $data['param']['notify_url']);

        $data['param']['sign'] = self::sign($data['param']);

        // 发送请求并返回结果
        $result = Http::post($this->config['gateway_url'], $data['param']);
        $result = json_decode($result, 1);
        if (!isset($result['alipay_trade_refund_response'])) {
            $this->error = '支付宝请求异常';
            return false;
        }

        $response = $result['alipay_trade_refund_response'];

        if ($response['code'] != 10000) {
            $this->error = $response['sub_msg'].'('.$response['sub_code'].')';
            return false;
        }
        
        $backData = [];
        $backData['request'] = json_encode($data['param'], 1);
        $backData['return'] = json_encode($response, 1);
        $backData['trade_no'] = $response['trade_no'];

        return ['result' => $backData];
    }

    /**
     * 同步退款回调[无]
     * @return bool|string
     */
    public function _syncRefund($param = [])
    {
        return true;
    }

    /**
     * 异步退款回调[无]
     * @return bool|string
     */
    public function _asyncRefund($param = [])
    {
        return true;
    }

    /**
     * 支付请求公共参数
     * @return bool|string
     * 
     */
    private function publicParam($param, $method = 'alipay.trade.app.pay')
    {
        $data = [];
        $data['biz_content']       = json_encode($param, 1);
        $data['app_id']            = $this->config['app_id'];
        $data['method']            = $method;
        $data['format']            = $this->config['format'];
        $data['charset']           = $this->config['charset'];
        $data['sign_type']         = $this->config['sign_type'];
        $data['timestamp']         = date('Y-m-d H:i:s');
        $data['version']           = $this->config['version'];
        //$data['return_url']        = $this->globalParams['sync_url'];
        $data['notify_url']        = $this->globalParams['async_url'];
        return $data;
    }

    /**
     * 生成签名
     * @return bool|string
     */
    private function sign($param = []) {
        if (is_empty($this->config['merchant_private_key'])) {
            return false;
        }
        
        $data = self::signStr($param);
        $priKey=$this->config['merchant_private_key'];

        $res = "-----BEGIN RSA PRIVATE KEY-----\n" .
            wordwrap($priKey, 64, "\n", true) .
            "\n-----END RSA PRIVATE KEY-----";

        ($res) or die('您使用的私钥格式错误，请检查RSA私钥配置'); 

        if ("RSA2" == $this->config['sign_type']) {
            openssl_sign($data, $sign, $res, OPENSSL_ALGO_SHA256);
        } else {
            openssl_sign($data, $sign, $res);
        }
        
        $sign = base64_encode($sign);
        
        return $sign;
    }

    /**
     * 签名验证
     * @return bool|string
     */
    private function signVerify($param = []) {
        if (is_empty($this->config['alipay_public_key'])) {
            return false;
        }

        if (!isset($param['sign'])) {
            return false;
        }

        $sign = $param['sign'];
        unset($param['sign'], $param['sign_type']);

        $data = self::signStr($param);
        $pubKey = $this->config['alipay_public_key'];
        $res = "-----BEGIN PUBLIC KEY-----\n" .
            wordwrap($pubKey, 64, "\n", true) .
            "\n-----END PUBLIC KEY-----";

        ($res) or die('支付宝RSA公钥错误。请检查公钥文件格式是否正确');  

        //调用openssl内置方法验签，返回bool值
        if ("RSA2" == $this->config['sign_type']) {
            $result = (bool)openssl_verify($data, base64_decode($sign), $res, OPENSSL_ALGO_SHA256);
        } else {
            $result = (bool)openssl_verify($data, base64_decode($sign), $res);
        }
        return $result;
    }

    /**
     * 参数拼接成签名字符串
     * @return string
     */
    private function signStr($params = []) {
        ksort($params);

        $str = "";
        $i = 0;
        foreach ($params as $k => $v) {
            if (is_empty($v) === false && "@" != substr($v, 0, 1)) {
                if ($i == 0) {
                    $str .= "$k" . "=" . "$v";
                } else {
                    $str .= "&" . "$k" . "=" . "$v";
                }
                $i++;
            }
        }

        unset ($k, $v);
        return $str;
    }
}