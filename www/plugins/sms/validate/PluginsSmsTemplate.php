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
namespace plugins\sms\validate;

use think\Validate;

/**
 * 短信模板验证器
 * @package app\admin\validate
 */
class PluginsSmsTemplate extends Validate
{
    //定义验证规则
    protected $rule = [
        'title|模板标题' => 'require',
        'alias|模板别名' => 'require|unique:plugins_sms_template',
    ];
}
