<?php
if (!function_exists('base64_image_content')) {
    /**
     * 处理base64格式图片存致本地
     * 本地存放位置  /static/upload/日期/文件
     * @param $base64_image_content string base64图片
     * @param $file string 保存的路径
     * @return bool|string 成功返回图片web路劲
     */
    function base64_image_content($base64_image_content,$file){
        //匹配出图片的格式
        if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64_image_content, $result)){
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