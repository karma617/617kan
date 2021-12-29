<?php
// +----------------------------------------------------------------------
// | HisiPHP框架[基于ThinkPHP5开发]
// +----------------------------------------------------------------------
// | Copyright (c) 2016-2018 http://www.hisiphp.com
// +----------------------------------------------------------------------
// | HisiPHP承诺基础框架永久免费开源，您可用于学习和商用，但必须保留软件版权信息。
// +----------------------------------------------------------------------
// | Author: 橘子俊 <364666827@qq.com>，开发者QQ群：50304283
// +----------------------------------------------------------------------
namespace plugins\sms\driver\aliyun;
use plugins\sms\driver\Factory;

/**
 * 网关平台：阿里云通信
 * 访问地址：https://www.aliyun.com/product/sms
 */
class aliyun extends Factory {
    // API支持的RegionID
	const REGION_ID = 'cn-hangzhou';

    /// API的命名
	const DEFAULT_ACTION = 'SendSms';

    // API的版本
	const API_VERSION = '2017-05-25';

    // 短信API产品域名
	const API_HOST = 'dysmsapi.aliyuncs.com';

    // 返回格式
    const API_FORMAT = 'JSON';
    
    // 签名算法
    const SIGN_METHOD = 'HMAC-SHA1';
    
    // 签名版本号
    const SIGN_VERSION = '1.0';
    
    /**
     * 单发短信
     * @param int $mobile 手机号码
     * @param array $templateData 模板参数
     * @param string $content 短信内容
     * @param int $nationCode 国际区号
     * @author 橘子俊 <364666827@qq.com>
     * @return bool
     */
    public function sendSms($mobile, $templateData = [], $content = '', $nationCode = 86)
	{
	    $params = [];
	    // 必填: 短信接收号码
	    $params["PhoneNumbers"] = $mobile;
	    // 必填: 短信模板Code
	    $params["TemplateCode"] = $this->templateCode;
	    // 可选: 设置模板参数, 如模板中有变量需要替换则为必填项
	    $params['TemplateParam'] = $templateData;

	    if(!empty($params["TemplateParam"]) && is_array($params["TemplateParam"])) {
	        $params["TemplateParam"] = json_encode($params["TemplateParam"], JSON_UNESCAPED_UNICODE);
	    }

	    $params["SignName"] = $this->gatewayConfig['sign_name'];
	    $params['RegionId'] = self::REGION_ID;
	    $params['Action'] = self::DEFAULT_ACTION;
	    $params['Version'] = self::API_VERSION;

	    return $this->request($params);
	}

    /**
     * 群发短信
     * @param array $param 参数
     * @author 橘子俊 <364666827@qq.com>
     * @return bool
     */
    public function massSms($param)
    {
        $params = [];
        // 必填: 短信接收号码
        $params["PhoneNumbers"] = $mobile;
        // 必填: 短信模板Code
        $params["TemplateCode"] = $this->templateCode;
        // 可选: 设置模板参数, 如模板中有变量需要替换则为必填项
        $params['TemplateParam'] = $templateData;

        if(!empty($params["TemplateParam"]) && is_array($params["TemplateParam"])) {
            $params["TemplateParam"] = json_encode($params["TemplateParam"], JSON_UNESCAPED_UNICODE);
        }

        $params["SignName"] = $this->gatewayConfig['sign_name'];
        $params['RegionId'] = self::REGION_ID;
        $params['Action'] = self::DEFAULT_ACTION;
        $params['Version'] = self::API_VERSION;

        return $this->request($params);
    }

    /**
     * 生成签名并发起请求
     * @param $params array API具体参数
     * @param $protocol string 请求协议，可选https或http
     * @author 橘子俊 <364666827@qq.com>
     * @return bool 返回API接口调用结果
     */
    private function request($params, $protocol = 'http') {
    	$domain = self::API_HOST;
    	$params['SignatureMethod'] = self::SIGN_METHOD;
        $params['Format'] = self::API_FORMAT;
        $params['SignatureVersion'] = self::SIGN_VERSION;
    	$params['SignatureNonce'] = uniqid(mt_rand(0,0xffff), true);
    	$params['AccessKeyId'] = $this->gatewayConfig['app_id'];
    	$params['Timestamp'] = gmdate("Y-m-d\TH:i:s\Z");
        ksort($params);
        
        $queryString = '';
        foreach ($params as $key => $value) {
            $queryString .= '&' . $this->encode($key) . '=' . $this->encode($value);
        }

        $stringToSign = 'GET&%2F&' . $this->encode(substr($queryString, 1));

        $sign = base64_encode(hash_hmac('sha1', $stringToSign, $this->gatewayConfig['app_key'] . '&',true));

        $signature = $this->encode($sign);

        $url = $protocol."://{$domain}/?Signature={$signature}{$queryString}";
        try {
            $result = $this->getRequest($url);
            $result = json_decode($result, 1);
            if ($result['Code'] == 'OK') {
            	return true;
            }

            $this->error = '【' . $result['Code'] . '】'.$result['Message'];
            return false;
        } catch( \Exception $e) {

        	$this->error = '远程请求出现异常错误';
            return false;
        }
    }

    private function encode($str)
    {
        $res = urlencode($str);
        $res = preg_replace("/\+/", "%20", $res);
        $res = preg_replace("/\*/", "%2A", $res);
        $res = preg_replace("/%7E/", "~", $res);
        return $res;
    }
}