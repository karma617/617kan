<?php
// +----------------------------------------------------------------------
// | HisiPHP框架[基于ThinkPHP5开发]
// +----------------------------------------------------------------------
// | Copyright (c) 2016-2021 http://www.hisiphp.com
// +----------------------------------------------------------------------
// | HisiPHP承诺基础框架永久免费开源，您可用于学习和商用，但必须保留软件版权信息。
// +----------------------------------------------------------------------
// | Author: 橘子俊 <364666827@qq.com>，开发者QQ群：50304283
// +----------------------------------------------------------------------
namespace app\videos\validate;

use think\Validate;
use app\videos\model\Sensitive;
/**
 * 会员分组验证器
 * @package app\user\validate
 */
class VideosDanmu extends Validate
{
    //定义验证规则
    protected $rule = [
        'text|弹幕内容' => 'require|checkText:thinkphp',
        'color|弹幕颜色'  => 'require',
        'time|弹幕时间'  => 'require',
        'vid|视频id'  => 'require',
    ];

    //定义验证提示
    protected $message = [
        'text.require' => '弹幕内容不能为空',
        'color.unique' => '弹幕颜色不能为空',
        'time.require'    => '弹幕时间不能为空',
        'vid.require'    => '视频id不能为空',
    ];


    protected function checkText($value, $rule, $data) {
        $instance = Sensitive::getInstance();
        $file_path = ROOT_PATH."public/badwords/badwords.txt";
        $instance->addSensitiveWords($file_path);
        $badWorlds = $instance->searchWords($data['text']);
        if (count($badWorlds) > 0) {
            return '含有敏感词';
        }
        return true;
    }
}
