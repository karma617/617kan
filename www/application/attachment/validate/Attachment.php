<?php
namespace app\attachment\validate;

use think\Validate;

/**
 * 报价验证器
 * @package app\user\validate
 */
class Attachment extends Validate
{
    //定义验证规则
    protected $rule = [
        'code|base64码'  => 'require',
        'type|图片类型'  => 'require',
    ];

    //定义验证提示
    protected $message = [
        'code.require'   => 'base64码不能为空',
        'type.require'   => '图片类型不能为空',
    ];

    //定义验证场景
    protected $scene = [
        'default' => [
            'code',
            'type',
        ],
    ];
}