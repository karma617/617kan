<?php
//多维数组排序
function arrayMsort(&$data,$field){
    $regions = array_column($data, $field);
    utf8_array_asort($regions);
    array_multisort($regions, SORT_ASC, $data);
}
function utf8_array_asort(&$array){
    if (!isset($array) || !is_array($array)) {
        return false;
    }
    foreach ($array as $k => $v) {
        $array[$k] = iconv('UTF-8', 'GBK//IGNORE', $v);
    }
    return true;
}
/* 取中间字符 */
function getSubstr($str, $leftStr, $rightStr){
    $left = strpos($str, $leftStr);
    $right = strpos($str, $rightStr,$left);
    if($left < 0 or $right < $left) return '';
    return substr($str, $left + strlen($leftStr), $right-$left-strlen($leftStr));
}
/* 取右边字符 */
function getRightStr($str, $rightStr){
    $right = strpos($str, $rightStr);
    if(!$right) return '';
    return substr($str, $right+strlen($rightStr));
}
/* 取左边字符 */
function getLeftStr($str, $leftStr){
    $left = strpos($str, $leftStr);
    if(!$left) return '';
    return substr($str, 0, $left);
}
/**
 * 检测敏感词
 *
 * @param string $checkStr
 * @param boolean $full 是否全词匹配，默认只要字符串包含敏感词就屏蔽
 * @return void
 * @author 617 <email：723875993@qq.com>
 */
function checkWords($checkStr,$full = false){
    $file_path = ROOT_PATH."public/badwords/badwords.txt";
    if(file_exists($file_path)){
        $str = file_get_contents($file_path);//将整个文件内容读入到一个字符串中
        $badWord = explode("\r\n", $str);
    }
    $badword1 = array_combine($badWord,array_fill(0,count($badWord),'*'));
    $str = strtr($checkStr, $badword1);
    if (strpos($str,'*') !== false) {
        if($full){
            if(strlen($str) == 1){
                return true;
            }
            return false;
        }
        return true;
    }else{
        return false;
    }
}