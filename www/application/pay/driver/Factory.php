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
use app\pay\model\PayPayment;
class Factory
{
    public $error = '系统未知错误！';
    public $pay_code = '';
    public function __construct($code = '')
    {
        define('PAY_CODE', $code);
        $this->adapter($code);
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
     * 构造适配器
     * @param  $code 支付平台code
     * @param  $config 支付平台配置
     * @author 橘子俊 <364666827@qq.com>
     */
    public function adapter($code = '')
    {
        if (empty($code)) return false;
        
        $payment = PayPayment::lists($code);
        if (!$payment) {
            $this->error = '['.$code.']支付方式未安装或未开启！';
            return false;
        }

        $class = '\\app\\pay\\driver\\'.$code.'\\'.$code;
        if (!class_exists($class)) {
            $this->error = '缺少['.$code.']支付驱动！';
            return false;
        }

        $this->instance = new $class($payment['config']);
        return $this->instance;
    }
    
    public function __call($method_name, $method_args) {
        if (method_exists($this, $method_name)) {
            return call_user_func_array(array(& $this, $method_name), [$method_args]);
        } elseif (
            !empty($this->instance)
            && ($this->instance instanceof PayAbstract)
            && method_exists($this->instance, $method_name) ) {
            return call_user_func_array(array(& $this->instance, $method_name), [$method_args]);
        }
    }  
}