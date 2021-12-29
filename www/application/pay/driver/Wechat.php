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

use app\pay\model\PayLog as PayLogModel;

class Wechat extends PayAbstract
{
    public $payUrl = 'https://api.mch.weixin.qq.com/pay/unifiedorder';
    public $refundUrl = 'https://api.mch.weixin.qq.com/secapi/pay/refund';

    public function __construct($config = [])
    {
        if (!empty($config)) $this->config = $config;
    }

    public function _submit($param)
    {

    }

    /**
     * 同步通知
     * @author 橘子俊 <364666827@qq.com>
     * @return bool|string
     */
    public function _sync($param = [])
    {
        $tmpData = $param;
        unset($tmpData['sign']);

        if (!isset($param['result_code']) || $param['result_code'] != 'SUCCESS') {
            return false;
        }

        $return = [];
        $sign = $this->wechatSign($tmpData, $this->config['key']);//本地签名
        if ($param['sign'] == $sign) {

            $row = PayLogModel::where('order_no', $param['out_trade_no'])
                                ->where('method', input('param.method'))
                                ->find();
            if ($row) {
                $return['return_code']  = 'SUCCESS';
                $return['return_msg']   = 'OK';

                if ($row['status'] == 2) {// 已支付
                    postXmlCurl(arrayToXml($return), $this->payUrl);
                    return $param['out_trade_no'];
                }

                // 更新LOG记录
                $sqlmap             = [];
                $sqlmap['trade_no'] = $param['transaction_id'];
                $sqlmap['return']   = json_encode($param, 1);
                $sqlmap['status']   = 2;

                if (PayLogModel::where('id', $row['id'])->update($sqlmap)) {
                    postXmlCurl(arrayToXml($return), $this->payUrl);
                    return $param['out_trade_no'];
                }

            }
        }

        $return['return_code'] = 'FAIL';
        $return['return_msg'] = '';
        postXmlCurl(arrayToXml($return), $this->payUrl);
        return false;
    }

    /**
     * 异步通知
     * @author 橘子俊 <364666827@qq.com>
     * @return bool|string
     */
    public function _async($param = [])
    {
        return self::_sync($param);
    }

    /**
     * 发起退款
     * @author 橘子俊 <364666827@qq.com>
     * @return bool|string
     */
    public function _refundSubmit($param = [])
    {

        $payLog = $param['pay_log'];
        unset($param['pay_log']);
        if (!$this->setGlobalParams($param)) {
            return false;
        }

        if (!$this->config['cert_pem'] || !$this->config['key_pem']) {
            $this->error = '证书文件缺失';
            return false;
        }

        $data['param']                      = [];
        $data['param']['appid']             = $this->config['appid'];
        $data['param']['mch_id']            = $this->config['mch_id'];
        $data['param']['nonce_str']         = random(10);
        $data['param']['transaction_id']    = $param['trade_no'];
        $data['param']['out_refund_no']     = $param['refund_no'];
        $data['param']['total_fee']         = (100*(float)$payLog['money']);// 订单总金额
        $data['param']['refund_fee']        = (100*(float)$param['money']);// 退款金额

        if (isset($param['refund_account'])) {
            $data['param']['refund_account'] = $param['refund_account'];   
        }

        if (isset($param['refund_desc'])) {
            // 退款原因
            $data['param']['refund_desc']   = $param['refund_desc'];
        }

        $data['param']['notify_url']        = $this->globalParams['async_url'];
        $data['param']['sign']              = $this->wechatSign($data['param'], $this->config['key']);

        $content = postXmlCurl(arrayToXml($data['param']), $this->refundUrl, true, $this->config['cert_pem'], $this->config['key_pem']);

        $result = xmlToArray($content);

        if (!isset($result['refund_id'])) {

            if ($result['err_code'] == 'NOTENOUGH' && 
                (!isset($param['refund_account']) || 
                    $param['refund_account'] != 'REFUND_SOURCE_RECHARGE_FUNDS')) {

                $param['refund_account'] = 'REFUND_SOURCE_RECHARGE_FUNDS';
                $param['pay_log'] = $payLog;

                return self::_refundSubmit($param);

            }

            if (isset($result['err_code_des'])  && $result['err_code_des']) {
                $this->error = $result['err_code_des'];
                return false;
            }

            $this->error = '请求失败';
            return false;
        }

        $backData               = [];
        $backData['request']    = json_encode($data['param'], 1);
        $backData['return']     = json_encode($result, 1);
        $backData['trade_no']   = $result['refund_id'];

        return ['result' => $backData];
    }

    /**
     * 同步退款[无]
     * @author 橘子俊 <364666827@qq.com>
     * @return bool|string
     */
    public function _syncRefund($param = [])
    {
        return true;
    }

    /**
     * 异步退款[无]
     * @author 橘子俊 <364666827@qq.com>
     * @return bool|string
     */
    public function _asyncRefund($param = [])
    {
        return true;
    }

    /**
     *  作用：格式化参数，签名过程需要使用
     */
    public function formatBizQueryParaMap($paraMap, $urlencode)
    {
        $buff = "";
        ksort($paraMap);
        foreach ($paraMap as $k => $v) {
            
            if($urlencode) {
               $v = urlencode($v);
            }

            $buff .= $k . "=" . $v . "&";

        }

        $reqPar;

        if (strlen($buff) > 0) {

            $reqPar = substr($buff, 0, strlen($buff)-1);

        }

        return $reqPar;
    }
    
    /**
     *  作用：生成签名
     */
    public function wechatSign($obj, $key)
    {
        foreach ($obj as $k => $v) {
            $param[$k] = $v;
        }
        //签名步骤一：按字典序排序参数
        ksort($param);
        $str = $this->formatBizQueryParaMap($param, false);
        //签名步骤二：在string后加入KEY
        $str = $str . '&key=' . $key;
        
        //签名步骤三：MD5加密
        $str = md5($str);
        //签名步骤四：所有字符转为大写
        $result = strtoupper($str);
        return $result;
    }
}
