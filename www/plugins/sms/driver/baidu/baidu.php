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
namespace plugins\sms\driver\baidu;
use plugins\sms\driver\Factory;

/**
 * 百度云简单消息
 */
class baidu extends Factory {

    // 主机
	const API_HOST = 'sms.bj.baidubce.com';
    // 版本
    const AUTH_VERSION = 'bce-auth-v1';
    //签名有效期默认1800秒
    const DEFAULT_EXPIRATION = 1800;
    // 接口地址
    const API_URI = '/bce/v2/message';

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
        $data = [
            'invokeId' => $this->gatewayConfig['invoke_id'],
            'phoneNumber' => $mobile,
            'templateCode' => $this->templateCode,
            'contentVar' => '',
        ];
        
        if (isset($templateData)) {
            $data['contentVar'] = json_encode($templateData, 1);
        }

        $datetime = date('Y-m-d\TH:i:s\Z');
        $headers = [
            'host' => self::API_HOST,
            'content-type' => 'application/json',
            'x-bce-date' => $datetime,
            'x-bce-content-sha256' => hash('sha256', json_encode($data)),
        ];

        // 根据指定keys过滤要参与签名的header
        $signHeaders = array_intersect_key($headers, array_flip(['host', 'x-bce-content-sha256']));
        $headers['Authorization'] = $this->generateSign($signHeaders, $datetime);

        $result = $this->postRequest('http://'.self::API_HOST.self::API_URI, json_encode($data, 1), $headers);
		return $result;
	}

    /**
     * 群发短信
     * @param array $param 参数
     * @author 橘子俊 <364666827@qq.com>
     * @return bool
     */
    public function massSms($param)
    {
        // TODO
        return true;
    }

    /**
     * 包含AccessKey与请求签名
     * @param array $signHeaders 签名头
     * @param int $datetime 时间
     * @author 橘子俊 <364666827@qq.com>
     * @return string
     */
    private function generateSign($signHeaders, $datetime)
    {
        $authStr = self::AUTH_VERSION.'bce-auth-v1/'.$this->gatewayConfig['access_key'].'/'
            .$datetime.'/'.self::DEFAULT_EXPIRATION;
        $signKey = hash_hmac('sha256', $authStr, $this->gatewayConfig['secret_key']);
        $URI = str_replace('%2F', '/', rawurlencode(self::API_URI));
        $queryString = '';
        $headerStr = $this->requestHeader($signHeaders);
        $signedHeaders = empty($signHeaders) ? '' : strtolower(trim(implode(';', array_keys($signHeaders))));
        $requestStr = "POST\n{$URI}\n{$queryString}\n{$headerStr}";
        $signature = hash_hmac('sha256', $requestStr, $signKey);
        return "{$authStr}/{$signedHeaders}/{$signature}";
    }

    /**
     * 生成http请求头串
     * @param array $headers
     * @author 橘子俊 <364666827@qq.com>
     * @return string
     */
    private function requestHeader($headers)
    {
        $arr = [];
        foreach ($headers as $name => $value) {
            $arr[] = rawurlencode(strtolower(trim($name))).':'.rawurlencode(trim($value));
        }
        sort($arr);
        return implode("\n", $arr);
    }
}