<?php
return [
    'user_after_register'     => [
        'app\\user\\hook\\User',
    ],
    'user_delete'     => [
        'app\\user\\hook\\User',
    ],
    'user_update'     => [
        'app\\user\\hook\\User',
    ],
    'user_after_login'     => [
        'app\\user\\hook\\User',
    ],
    'after_driver_quote'     => [
        'app\\messages\\hook\\Notify',
    ],
    'after_choose_quote'     => [
        'app\\messages\\hook\\Notify',
    ],
    'expired_pull_time'     => [
        'app\\messages\\hook\\Notify',
    ],
    'out_confirm_time'     => [
        'app\\messages\\hook\\Notify',
    ],
];
