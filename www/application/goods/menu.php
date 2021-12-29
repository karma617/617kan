<?php
return [
  [
    'title' => '公告管理',
    'icon' => 'aicon ai-mokuaiguanli',
    'module' => 'goods',
    'url' => 'index',
    'param' => '',
    'target' => '_self',
    'debug' => 0,
    'system' => 0,
    'nav' => 1,
    'sort' => 100,
    'childs' => [
      [
        'title' => '公告管理',
        'icon' => '',
        'module' => 'goods',
        'url' => 'index/msg/index',
        'param' => '',
        'target' => '_self',
        'debug' => 0,
        'system' => 1,
        'nav' => 1,
        'sort' => 0,
        'childs' => [
          [
            'title' => '公告列表',
            'icon' => 'fa fa-volume-up',
            'module' => 'goods',
            'url' => 'index/msg/index',
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
