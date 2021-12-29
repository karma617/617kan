<?php
namespace app\order\validate;

use think\Validate;

/**
 * 会员分组验证器
 * @package app\user\validate
 */
class OrderTrade extends Validate
{
    //定义验证规则
    protected $rule = [
        'order_no|订单号'  => 'require',
        'trade_no|支付单号'  =>   'require',
        'money|支付金额'  =>   'require',
        'method|支付方式'  =>   'require|chackMoney:thinkphp',
    ];

    //定义验证提示
    protected $message = [
        'order_no.require'   => '订单号不能为空',
        'trade_no.require'   => '支付单号不能为空',
        'money.require'   => '支付金额不能为空',
        'method.require'   => '支付方式不能为空',
    ];

    //定义验证场景
    protected $scene = [
        'gopay' => [
            'order_no',
            'trade_no',
            'money',
            'method',
        ],
    ];

    protected function chackMoney($value, $rule, $data = []) {
        if ((float)$data['money'] <= 0) {
            return '支付金额最少为0.01元';
        }
        return true;
    }
}