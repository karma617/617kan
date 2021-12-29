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

namespace plugins\sms\driver;
use plugins\sms\model\PluginsSms as SmsModel;
use plugins\sms\model\PluginsSmsTemplateIndex as IndexModel;
use plugins\sms\model\PluginsSmsTemplate as TemplateModel;
use hisi\Http;

abstract class Factory
{

    /**
     * 错误信息
     * @var string
     */
    public $error = '系统未知错误！';

    /**
     * 网关编码
     * @var string
     */
    public $gatewayCode = '';

    /**
     * 网关配置
     * @var array
     */
    public $gatewayConfig = [];

    /**
     * 网关模板CODE
     * @var string
     */
    public $templateCode = '';

    /**
     * 构造方法，根据网关编码获取配置信息
     * @param string $alias 模板别名
     * @param string $gateway 网关编码
     * @author 橘子俊 <364666827@qq.com>
     */
    public function __construct($alias = '', $gateway = '')
    {
        $this->gatewayCode = $gateway;
        $this->gatewayConfig = SmsModel::getConfig($gateway);
        if (isset($this->gatewayConfig['bind_template'])) {
            $this->templateCode = IndexModel::where('gateway', $gateway)
                                    ->where('alias', $alias)
                                    ->value('gateway_template');
            $this->templateExample = TemplateModel::where('alias', $alias)
                                    ->value('example');
        }
    }

    /**
     * 用CURL模拟POST提交数据
     * @param string $url 提交的网址
     * @param array  $data 所要提交的数据
     * @param string $proxy 代理设置
     * @param integer $expire 所用的时间限制
     * @author 橘子俊 <364666827@qq.com>
     * @return string
     */
    final public function postRequest($url, $params = '', $header = [], $timeout = 30, $options = [])
    {
        return Http::post($url, $params, $header, $timeout, $options);
    }

    /**
     * 用CURL模拟GET提交数据
     * @param string $url 提交的网址
     * @param array  $data 所要提交的数据
     * @param string $proxy 代理设置
     * @param integer $expire 所用的时间限制
     * @author 橘子俊 <364666827@qq.com>
     * @return string
     */
    final public function getRequest($url, $params = '', $header = [], $timeout = 30, $options = [])
    {
        return Http::get($url, $params, $header, $timeout, $options);
    }

    /**
     * 获取错误信息
     * @author 橘子俊 <364666827@qq.com>
     * @return string
     */
    final public function getError()
    {
        if (!empty($this->instance->error)) {
            return $this->instance->error;
        }
        return $this->error;
    }

    /**
     * 获取驱动实例
     * @param string $gateway 网关编码
     * @param string $alias 模板别名
     * @author 橘子俊 <364666827@qq.com>
     * @return mixed 返回实例对象
     */
    public static function instance($alias = '', $gateway = '') {
        if (!$gateway) {
            $default = SmsModel::getDefault();
            if (!$default) {
                return false;
            }
            $gateway = $default['gateway'];
        }

        $class = "\\plugins\\sms\\driver\\{$gateway}\\{$gateway}";
        if (class_exists($class)) {
            return new $class($alias, $gateway);
        } else {
            return false;
        }
    }

    /**
     * 抽象方法，在driver中实现
     * 单发短信
     * @param int $mobile 手机号码
     * @param array $templateData 模板参数
     * @param string $content 短信内容
     * @param int $nationCode 国际区号
     * @author 橘子俊 <364666827@qq.com>
     * @return bool
     */
    abstract public function sendSms($mobile, $templateData = [], $content = '', $nationCode = 86);

    /**
     * 抽象方法，在driver中实现
     * @author 橘子俊 <364666827@qq.com>
     * 群发短信
     */
    abstract public function massSms($param);
}