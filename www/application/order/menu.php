<?php
return [
  [
    'title' => '订单管理',
    'icon' => 'aicon ai-shezhi',
    'module' => 'order',
    'url' => 'order',
    'param' => '',
    'target' => '_self',
    'debug' => 0,
    'system' => 0,
    'nav' => 1,
    'sort' => 120,
    'childs' => [
      [
        'title' => '订单管理',
        'icon' => 'aicon ai-caidan',
        'module' => 'order',
        'url' => 'order/index/index',
        'param' => '',
        'target' => '_self',
        'debug' => 0,
        'system' => 1,
        'nav' => 1,
        'sort' => 0,
        'childs' => [
          [
            'title' => '订单列表',
            'icon' => 'aicon ai-caidan',
            'module' => 'order',
            'url' => 'order/index/index',
            'param' => '',
            'target' => '_self',
            'debug' => 0,
            'system' => 0,
            'nav' => 1,
            'sort' => 0,
          ],
        ],
      ],
    ],
  ],
];
