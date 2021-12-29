<?php
namespace app\codekey\validate;

use think\Validate;
/**
 * 会员分组验证器
 * @package app\user\validate
 */
class Codekey extends Validate
{
    //定义验证规则
    protected $rule = [
        'code|兑换码'  => 'require',
    ];

    //定义验证提示
    protected $message = [
        'code.require' => '兑换码不能为空',
    ];
}
