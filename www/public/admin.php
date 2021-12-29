<?php
namespace think;
header('Content-Type:text/html;charset=utf-8');
define('APP_PATH', __DIR__ . '/application/');
define('APPS_PATH', __DIR__ . '/../application/');
define('THINKS_PATH', __DIR__ . '/../thinkphp/');
define('ENTRANCE', 'admin');
require __DIR__ . '/../thinkphp/base.php';
if(!is_file('./../install.lock')) {
    header('location: /');
} else {
    Container::get('app')->run()->send();
}