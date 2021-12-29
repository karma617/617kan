<?php
namespace think;
header('Content-Type:text/html;charset=utf-8');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods:GET,POST");
header('Access-Control-Allow-Credentials: true');
header("Access-Control-Allow-Headers: content-type,token,secret,timestamp,sign");
if(strtoupper($_SERVER['REQUEST_METHOD'])== 'OPTIONS') exit('Connected!');
if(version_compare(PHP_VERSION,'5.6.0','<'))  die('PHP版本过低，最少需要PHP5.6，请升级PHP版本！');
define('APP_PATH', __DIR__ . '/application/');
define('APPS_PATH', __DIR__ . '/../application/');
define('THINKS_PATH', __DIR__ . '/../thinkphp/');
define('API_ENTRANCE',true);
define('DOC_ROOT', __DIR__ );
require __DIR__ . '/../thinkphp/base.php';
Container::get('app')->run()->send();