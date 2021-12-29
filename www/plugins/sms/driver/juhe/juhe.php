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
namespace plugins\sms\driver\juhe;
use plugins\sms\driver\Factory;

/**
 * 网关平台：聚合数据 
 * 访问地址：https://www.juhe.cn/docs/api/id/54
 */
class juhe extends Factory {

    // 接口地址
    const API_URI = 'http://v.juhe.cn/sms/send';
    
    // 返回格式
    const API_FORMAT = 'json';

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
            'mobile' => $mobile,
            'tpl_id' => $this->templateCode,
            'key' => $this->gatewayConfig['app_key'],
            'dtype' => self::API_FORMAT,
            'tpl_value' => '',
        ];

        // 模板参数拼接
        if (!empty($templateData)) {
            $paramKeys = array_keys($templateData);
            $tplValue = '';
            foreach ($paramKeys as $k => $v) {
                if ($k > 0) {
                    $tplValue .= '&';
                }
                $tplValue .= "#{$v}#=".$templateData[$v];
            }
            $data['tpl_value'] = $tplValue;
        }

        try {
            $result = $this->getRequest(self::API_URI, $data);
            $result = json_decode($result, 1);
            if ($result['error_code'] == 0) {
                return true;
            }

            $this->error = '【' . $result['error_code'] . '】'.$result['reason'];
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