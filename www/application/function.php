<?php
// +----------------------------------------------------------------------
// | HisiPHP框架[基于ThinkPHP5.1开发]
// +----------------------------------------------------------------------
// | Copyright (c) 2016-2021 http://www.hisiphp.com
// +----------------------------------------------------------------------
// | HisiPHP承诺基础框架永久免费开源，您可用于学习和商用，但必须保留软件版权信息。
// +----------------------------------------------------------------------
// | Author: 橘子俊 <364666827@qq.com>，开发者QQ群：50304283
// +----------------------------------------------------------------------

// 为方便系统升级，二次开发中用到的公共函数请写在此文件，禁止修改common.php文件
// ===== 系统升级时此文件永远不会被覆盖 =====

if (!function_exists('unique_order_number')) {
    /**
     * 生成订单号
     *
     * @param [type] $id 唯一因子
     * @return void
     * @Description
     * @author 617 <email：723875993@qq.com>
     */
    function unique_order_number($id = '') {
        $timestamp = time();
        $y = date('Ymd', $timestamp);
        $z = date('z', $timestamp);
        $key = str_pad($id, 6 , 'X', STR_PAD_LEFT);
        $num = substr_count($key,'X');
        $ramdom_str = random($num);
        $key = $ramdom_str.$id;
        return $y . $key . str_pad($z, 3, '0', STR_PAD_LEFT) . str_pad(random(6), 5, '0', STR_PAD_LEFT);
    }
}

if (!function_exists('base64_image_content')) {
    /**
     * 处理base64格式图片存致本地
     * 本地存放位置  /static/upload/日期/文件
     * @param $base64_image_content string base64图片
     * @param $file string 保存的路径
     * @return bool|string 成功返回图片web路劲
     */
    function base64_image_content($base64_image_content, $file, $is_temp = false, $check_size = 0){
        //匹配出图片的格式
        if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64_image_content, $result)){
            if ($is_temp) {
                $new_file = tempnam(sys_get_temp_dir(), 'k');
                if (file_put_contents($new_file, base64_decode(str_replace($result[1], '', $base64_image_content)))){
                    $img_content = @file_get_contents($new_file);
                    if ($check_size > 0 && strlen($img_content) > $check_size) {
                        return false;
                    }
                    return $new_file;
                }else{
                    return false;
                }
            }
            $type = $result[2];
            $new_file = ROOT_PATH.$file;
            if(!file_exists($new_file)){
                //检查是否有该文件夹，如果没有就创建，并给予最高权限
                mkdir($new_file, 0777,true);
            }
            list($msec, $sec) = explode(' ', microtime());
            $msectime =  (float)sprintf('%.0f', (floatval($msec) + floatval($sec)) * 1000);
            $now = $msectime.rand(10000,99999);
            $new_file = $new_file.$now.".{$type}";
            $file_dir = $file. $now .".{$type}";
            if (file_put_contents($new_file, base64_decode(str_replace($result[1], '', $base64_image_content)))){
                return $file_dir;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }
}
if (!function_exists('wx_send')) {
    /**
     * 微信请求
     *
     * @param [type] $url
     * @param string $params
     * @return void
     * @Description
     * @author 617 <email：723875993@qq.com>
     */
    function wx_send($url, $params = [], $method = 'GET', $header = array(), $multi = false){
        $opts = array(
            CURLOPT_TIMEOUT        => 30,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_HTTPHEADER     => $header
        );
        /* 根据请求类型设置特定参数 */
        switch(strtoupper($method)){
            case 'GET':
                $opts[CURLOPT_URL] = $url;
                if (!empty($params)) {
                    $opts[CURLOPT_URL] = $url . '?' . http_build_query($params);
                }
                break;
            case 'POST':
                //判断是否传输文件
                $params = $multi ? $params : http_build_query($params);
                $opts[CURLOPT_URL] = $url;
                $opts[CURLOPT_POST] = 1;
                $opts[CURLOPT_POSTFIELDS] = $params;
                break;
            default:
                throw new Exception('不支持的请求方式！');
        }
        /* 初始化并执行curl请求 */
        $ch = curl_init();
        curl_setopt_array($ch, $opts);
        $data  = curl_exec($ch);
        $error = curl_error($ch);
        if($error) throw new Exception('请求发生错误：' . $error);
        if ($data) {
            curl_close($ch);
            return $data;
        } else {
            $error = curl_errno($ch);
            curl_close($ch);
            return $error;
        }
    }
}