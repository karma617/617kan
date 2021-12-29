<?php
return [
  [
    'title' => '卡密管理',
    'icon' => 'aicon ai-shezhi',
    'module' => 'codekey',
    'url' => 'codekey',
    'param' => '',
    'target' => '_self',
    'debug' => 0,
    'system' => 0,
    'nav' => 1,
    'sort' => 100,
    'childs' => [
      [
        'title' => '卡密管理',
        'icon' => 'fa fa-credit-card',
        'module' => 'codekey',
        'url' => 'codekey/index/index',
        'param' => '',
        'target' => '_self',
        'debug' => 0,
        'system' => 1,
        'nav' => 1,
        'sort' => 0,
        'childs' => [
          [
            'title' => '卡密列表',
            'icon' => 'fa fa-credit-card',
            'module' => 'codekey',
            'url' => 'codekey/index/index',
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
