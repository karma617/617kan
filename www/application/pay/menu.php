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
/**
 * 模块菜单
 * 字段说明
 * url 【链接地址】格式：pay/控制器/方法，可填写完整外链[必须以http开头]
 * param 【扩展参数】格式：a=123&b=234555
 */
return [
    [
        'pid'   => 5,
        'title' => '在线支付',
        'icon' => 'fa fa-credit-card',
        'module' => 'pay',
        'url' => 'pay/index/index',
        'param' => '',
        'nav' => 1,
        'target' => '_self',
        'sort' => 100,
        'childs' => [
            [
                'title' => '支付平台安装',
                'icon' => '',
                'module' => 'pay',
                'url' => 'pay/index/install',
                'param' => '',
                'target' => '_self',
                'sort' => 0,
                'childs' => '',
            ], [
                'title' => '支付平台卸载',
                'icon' => '',
                'module' => 'pay',
                'url' => 'pay/index/uninstall',
                'param' => '',
                'target' => '_self',
                'sort' => 0,
                'childs' => '',
            ], [
                'title' => '支付平台状态设置',
                'icon' => '',
                'module' => 'pay',
                'url' => 'pay/index/status',
                'param' => '',
                'target' => '_self',
                'sort' => 0,
                'childs' => '',
            ], [
                'title' => '支付平台配置',
                'icon' => '',
                'module' => 'pay',
                'url' => 'pay/index/config',
                'param' => '',
                'target' => '_self',
                'sort' => 0,
                'childs' => '',
            ], [
                'title' => '支付平台排序',
                'icon' => '',
                'module' => 'pay',
                'url' => 'pay/index/sort',
                'param' => '',
                'target' => '_self',
                'sort' => 0,
                'childs' => '',
            ], [
                'title' => '支付日志',
                'icon' => '',
                'module' => 'pay',
                'url' => 'pay/logs/index',
                'param' => '',
                'target' => '_self',
                'debug' => 0,
                'system' => 0,
                'nav' => 0,
                'sort' => 0,
            ], [
                'title' => '删除支付日志',
                'icon' => '',
                'module' => 'pay',
                'url' => 'pay/index/logsDel',
                'param' => '',
                'target' => '_self',
                'debug' => 0,
                'system' => 0,
                'nav' => 0,
                'sort' => 0,
            ], [
                'title' => '上传文件',
                'icon' => '',
                'module' => 'pay',
                'url' => 'pay/index/upload',
                'param' => '',
                'target' => '_self',
                'debug' => 0,
                'system' => 0,
                'nav' => 0,
                'sort' => 0,
            ],
        ],
    ],
];