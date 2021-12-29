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
 * 短信模型
 * @package plugins\sms\model
 */
class PluginsSms extends Model
{
    // 定义时间戳字段名
    protected $createTime = 'ctime';
    protected $updateTime = 'mtime';

    // 自动写入时间戳
    protected $autoWriteTimestamp = true;

    // 配置获取器
    public function getConfigAttr($value)
    {
        if (empty($value)) return [];
        return json_decode($value, 1);
    }

    public static function getConfig($gateway = '', $update = false)
    {
        $cache = cache('plugins_sms_config');
        if (!$cache || $update == true) {
            $cache = self::where('status', 1)->order('id asc')->column('gateway,config');
            foreach ($cache as $k => &$v) {
                $v = json_decode($v, 1);
            }
            cache('plugins_sms_config', $cache);
        }

        if ($gateway && isset($cache[$gateway])) {
            return $cache[$gateway];
        }

        return $cache;
    }

    public static function getDefault()
    {
        $row = self::where('default', 1)->where('status', 1)->find();
        if (!$row) {
            $row = self::where('status', 1)->order('id asc')->find();
        }
        return $row;
    }
}