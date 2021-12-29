<?php

namespace plugins\sms\driver\cl253;
use plugins\sms\driver\Factory;

class cl253 extends Factory {
    //参数的配置 请登录zz.253.com 获取以下API信息
    const API_SEND_URL='http://smssh1.253.com/msg/send/json'; //创蓝发送短信接口URL
    const API_VARIABLE_URL ='http://smssh1.253.com/msg/variable/json';//创蓝变量短信接口URL
    const API_BALANCE_QUERY_URL='http://smssh1.253.com/msg/balance/json';//创蓝短信余额查询接口URL
    
    /**
     * 单发短信
     * @param int $mobile 手机号码
     * @param array $templateData 模板参数
     * @param string $content 短信内容
     * @return bool
     */
    public function sendSms($mobile, $templateData = [], $content = '',$nationCode = 86){
        $msg = str_replace(array_keys($templateData),$templateData,$this->templateExample).'【'.$this->gatewayConfig['sign_name'].'】';
        //创蓝接口参数
        $postArr = array (
            'account'  => $this->gatewayConfig['api_account'],
            'password' => $this->gatewayConfig['api_password'],
            'msg'      => urlencode($msg),
            'phone'    => $mobile,
            'report'   => 'true'
        );
        if($this->gatewayConfig['api_debug'] == 1){
            return $this->error = ['code'=>0,'msg'=>'调试模式','params'=>$templateData,'msg'=>$msg];
        }
        $res = json_decode($this->curlPost(self::API_SEND_URL,$postArr),true);
        $res['params'] = $templateData;
        $res['msg'] = $msg;

        return $this->error = $res;
    }
    public function massSms($param){}
        
    /**
     * 通过CURL发送HTTP请求
     * @param string $url  //请求URL
     * @param array $postFields //请求参数 
     * @return mixed
     *  
     */
    private function curlPost($url,$postFields){
        $postFields = json_encode($postFields);   
        $ch = curl_init ();
        curl_setopt( $ch, CURLOPT_URL, $url ); 
        curl_setopt( $ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json; charset=utf-8'   //json版本需要填写  Content-Type: application/json;
            )
        );
        curl_setopt( $ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4); 
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt( $ch, CURLOPT_POST, 1 );
        curl_setopt( $ch, CURLOPT_POSTFIELDS, $postFields);
        curl_setopt( $ch, CURLOPT_TIMEOUT,60); 
        curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, 0);
        $ret = curl_exec ( $ch );
        if (false == $ret) {
            $result = curl_error(  $ch);
        } else {
            $rsp = curl_getinfo( $ch, CURLINFO_HTTP_CODE);
            if (200 != $rsp) {
                $result = "请求状态 ". $rsp . " " . curl_error($ch);
            } else {
                $result = $ret;
            }
        }
        curl_close ( $ch );
        return $result;
    }
}