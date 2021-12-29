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
namespace plugins\sms;
use app\common\controller\Plugins;
use plugins\sms\driver\Factory;
defined('IN_SYSTEM') or die('Access Denied');
/**
 * 短信插件
 * @package plugins\sms
 */
class sms extends Plugins
{
    /**
     * @var array 插件钩子清单
     */
    public $hooks = [
        'send_sms' => '短信触发钩子',
    ];

    /**
     * 短信触发钩子
     * @param array $param 所有请求参数
     * @param int   $param['mobile'] 手机号码，必须
     * @param array $param['data'] 模板参数，可选
     * @param string $param['template'] 可选
     * @param string $param['content'] 如果template为空，此项为必填
     * @param string $param['gateway'] 网关平台代码，可选
     */
    public function sendSms($param)
    {
        plugins_run('sms/index/sendSms', $param, 'home');
    }

    /**
     * 安装前的业务处理，可在此方法实现，默认返回true
     * @return bool
     */
    public function install()
    {
        return true;
    }

    /**
     * 安装后的业务处理，可在此方法实现，默认返回true
     * @return bool
     */
    public function installAfter()
    {
        return true;
    }

    /**
     * 卸载前的业务处理，可在此方法实现，默认返回true
     * @return bool
     */
    public function uninstall()
    {
        return true;
    }

    /**
     * 卸载后的业务处理，可在此方法实现，默认返回true
     * @return bool
     */
    public function uninstallAfter()
    {
        return true;
    }

}