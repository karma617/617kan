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
namespace plugins\sms\model;

use think\Model;

/**
 * 短信模板模型
 * @package plugins\sms\model
 */
class PluginsSmsTemplate extends Model
{
    // 自动写入时间戳
    protected $autoWriteTimestamp = false;

    public function hasIndexs()
    {
    	return $this->hasMany('\plugins\sms\model\PluginsSmsTemplateIndex', 'alias', 'alias');
    }
}