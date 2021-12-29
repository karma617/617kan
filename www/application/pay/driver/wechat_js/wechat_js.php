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
namespace app\pay\driver\wechat_js;

use app\pay\driver\Wechat;
use app\pay\model\PayLog as PayLogModel;
use think\view;

class wechat_js extends Wechat
{
    
    /**
     * 支付提交
     * @author 橘子俊 <364666827@qq.com>
     * @return bool|string
     */
    public function _submit($param = [])
    {
        if (!$this->setGlobalParams($param)) {
            return false;
        }

        if (!isset($param['body']) || empty($param['body'])) {
            $this->error = '缺少body参数（样例：腾讯充值中心-QQ会员充值）';
            return false;
        }

        if (!isset($param['openid']) || empty($param['openid'])) {
            $this->error = '缺少openid参数';
            return false;
        }

        $data['param']                      = [];
        $data['param']['appid']             = $this->config['appid'];
        $data['param']['mch_id']            = $this->config['mch_id'];
        $data['param']['nonce_str']         = random(16, 0);
        $data['param']['body']              = $param['body'];
        $data['param']['out_trade_no']      = $param['order_no'];
        $data['param']['total_fee']         = (100*(float)$param['money']);    // 单位：分
        $data['param']['spbill_create_ip']  = get_client_ip();
        $data['param']['notify_url']        = $this->globalParams['async_url'];
        $data['param']['trade_type']        = 'JSAPI';
        $data['param']['product_id']        = $param['order_no'];
        $data['param']['attach']            = isset($param['uid']) ? $param['uid'] : 0;
        $data['param']['openid']            = $param['openid'];
        $data['param']['sign']              = $this->wechatSign($data['param'], $this->config['key']);

        $prepare_xml = arrayToXml($data['param']);
        $result = xmlToArray(postXmlCurl($prepare_xml, $this->payUrl));

        if (!isset($result['return_code'])) {

            $this->error = '请求失败';
            return false;

        }

        if ($result['return_code'] == 'FAIL') {

            $this->error = '微信错误：'.$result['return_msg'];
            return false;

        } else if ($result['result_code'] == 'FAIL') {

            if ($result['err_code'] == 'ORDERPAID') {
                $this->error = '订单已支付，请联系管理员处理';
                return false;
            }

            $this->error = $result['err_code_des'];
            return false;
        } else if (!isset($result['prepay_id'])) {
            $this->error = '微信支付返回异常';
            return false;
        }

        // 缓存prepay_id
        $data['param']['prepay_id'] = $result['prepay_id'];

        $tplData               = [];
        $tplData['appId']      = $this->config['appid'];
        $tplData['timeStamp']  = $_SERVER['REQUEST_TIME'];
        $tplData['nonceStr']   = random(16, 0);
        $tplData['package']    = 'prepay_id='.$result['prepay_id'];
        $tplData['signType']   = 'MD5';
        $tplData['paySign']    = $this->wechatSign($tplData, $this->config['key']);
        $tplData['goback']     = $param['goback'];

        $data['template'] = view('wechat_js', ['data' => $tplData, 'param' => $param]);

        return $data;
    }
}
