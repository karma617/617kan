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

namespace plugins\sms\home;
defined('IS_PLUGINS') or die('Access Denied');
use app\common\controller\Common;
use plugins\sms\driver\Factory;
use plugins\sms\model\PluginsSmsLog as LogModel;

/**
 * 短信触发控制器
 * @package plugins\sms\home
 */
class Index extends Common
{

    /**
     * 单发短信
     * @param array $param 所有请求参数
     * @param int   $param['mobile'] 手机号码，必须
     * @param array $param['data'] 模板参数，可选
     * @param string $param['template'] 可选
     * @param string $param['content'] 如果template为空，此项为必填
     * @param string $param['gateway'] 网关平台代码，可选
     * @return bool|string
     */
    public function sendSms($param)
    {
        defined('IS_RESULT') or define('IS_RESULT',true);
        if (!isset($param['mobile'])) {
    		return '[mobile]手机号必须';
    	}
        
        $mobile         = $param['mobile'];
        $template       = isset($param['template']) ? $param['template'] : '';
        $templateData   = isset($param['data']) ? $param['data'] : '';
        $gateway        = isset($param['gateway']) ? $param['gateway'] : '';
        $content        = isset($param['content']) ? $param['content'] : '';
        $smsGateway = Factory::instance($template, $gateway);
        if (!$smsGateway->sendSms($mobile, $templateData, $content)) {
           return $smsGateway->getError();
        }
        // 记录成功发送的日志
        $sqlmap             = [];
        $sqlmap['gateway']  = $smsGateway->gatewayCode;
        $sqlmap['template'] = $smsGateway->templateCode;
        $sqlmap['content']  = $content;
        $sqlmap['params']   = json_encode($param);
        $sqlmap['ctime']    = $_SERVER['REQUEST_TIME'];
        $sqlmap['status']   = 1;
        LogModel::create($sqlmap);
        return true;
    }
}