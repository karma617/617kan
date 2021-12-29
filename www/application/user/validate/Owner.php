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
namespace app\user\validate;

use think\Validate;

/**
 * 会员分组验证器
 * @package app\user\validate
 */
class Owner extends Validate
{
    //定义验证规则
    protected $rule = [
        'order_id|运单id' => 'require',
        'page|当前页' => 'require',
        'limit|条数' => 'require',
    ];

    //定义验证提示
    protected $message = [
        'order_id.require' => '运单id不能为空',
        'page.require' => '当前页不能为空',
        'limit.require' => '条数不能为空',
    ];

    //定义验证场景
    protected $scene = [
        'alldone' => [
            'order_id'
        ],
        // 后台创建会员
        'lists' => [
            'page',
            'limit',
        ],
    ];
}
