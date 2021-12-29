<?php
namespace think;
header('Content-Type:text/html;charset=utf-8');
if(version_compare(PHP_VERSION,'5.6.0','<'))  die('PHP版本过低，最少需要PHP5.6，请升级PHP版本！');
define('APP_PATH', __DIR__ . '/application/');
define('APPS_PATH', __DIR__ . '/../application/');
define('THINKS_PATH', __DIR__ . '/../thinkphp/');
require __DIR__ . '/../thinkphp/base.php';
if(!is_file('./../install.lock')) {
	define('INSTALL_ENTRANCE', true);
    Container::get('app')->bind('install')->run()->send();
} else {
    Container::get('app')->run()->send();
}