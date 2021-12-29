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
namespace plugins\sms\driver\tencent;
use plugins\sms\driver\Factory;

/**
 * 网关平台：腾讯云短
 * 访问地址：https://cloud.tencent.com/product/sms
 */
class tencent extends Factory {

    // 单发接口
    const SIGNLE_URI = 'https://yun.tim.qq.com/v5/tlssmssvr/sendsms';

    // 群发接口
    const MASS_URI = 'https://yun.tim.qq.com/v5/tlssmssvr/sendmultisms2';

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
		$random = random();
		$ctime = time();
		$apiUrl = self::SIGNLE_URI . '?sdkappid=' . $this->gatewayConfig['app_id'] . '&random=' . $random;

		$data = [];

	    // 必填: 短信接收号码
		$data['tel'] = [
			'mobile' => $mobile,// 手机号
			'nationcode' => $nationCode,// 国家代码
		];

	    // 必填: 短信模板ID
		$data['tpl_id'] = $this->templateCode;
		
		$data['params'] = '';
		if (!empty($templateData)) {
			// 该死的设计，请注意参数顺序
			$data['params'] = explode(',', implode(',', $templateData));
		}

		// 必填：短信签名头
		$data['sign'] = $this->gatewayConfig['sign_name'];
		if (isset($params['sign_name'])) {
			$data['sign'] = $params['sign_name'];
		}

		// 请求签名串
		$data['sig'] = hash("sha256", "appkey=".$this->gatewayConfig['app_key']."&random=".$random
            ."&time=".$ctime."&mobile=".$mobile);
		$data['time'] = $ctime;
		$data['extend'] = '';
		$data['ext'] = '';

        try {
            $result = $this->postRequest($apiUrl, json_encode($data, 1));
            $result = json_decode($result, 1);
            if ($result['result'] == 0) {
            	return true;
            }

            $this->error = '【' . $result['result'] . '】'.$result['errmsg'];
            return false;
        } catch( \Exception $e) {
        	$this->error = '远程请求出现异常错误';
            return false;
        }
		return true;
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
}