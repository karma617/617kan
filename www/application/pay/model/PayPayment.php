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
namespace app\pay\model;

use think\Model;

class PayPayment extends Model
{
    // 定义时间戳字段名
    protected $createTime = 'ctime';
    protected $updateTime = 'mtime';

    // 自动写入时间戳
    protected $autoWriteTimestamp = true;

    /**
     * 获取所有支付插件
     * @param bool $update 是否更新缓存
     * @param array $data 入库数据
     * @author 橘子俊 <364666827@qq.com>
     * @return array
     */
    public static function lists($code = '', $update = true, $status = 1)
    {
        $result = cache('pay_payment');
        if (!$result || $update == true) {
            $map = [];
            if (is_numeric($status)) {
                $map[] = ['status', '=', $status];
            }
            $result = self::where($map)->order('sort asc')->column('code,title,applies,config', 'code');
            foreach ($result as $k => &$v) {
                if ($v['config']) {
                    $v['config'] = json_decode($v['config'], 1);
                }
            }
            cache('pay_payment', $result);
        }

        if ($code && isset($result[$code])) {
            return $result[$code];
        } else if ($code) {
            return false;
        }

        return $result;
    }
}