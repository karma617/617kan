<?php
namespace app\order\validate;

use think\Validate;

/**
 * 会员分组验证器
 * @package app\user\validate
 */
class Order extends Validate
{
    //定义验证规则
    protected $rule = [
        'goods_id|商品id' => 'require',
    ];

    //定义验证提示
    protected $message = [
        'goods_id.require' => '商品id不能为空',
    ];

    //定义验证场景
    protected $scene = [
        'createOrder' => [
            'goods_id',
        ]
    ];
}
