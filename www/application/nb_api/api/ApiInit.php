<?php
namespace app\nb_api\api;
use think\Controller;
use think\Response;
use think\exception\HttpResponseException;
use app\nb_api\model\NbApi as apiModel;
use think\Env;
/**
 * 框架公共控制器
 * @package app\common\controller
 */
class ApiInit extends Controller
{
    public $params;
    public $_params;
    public $token;
    protected function initialize() {
        //接收参数
        $this->params = $this->_params = input();
        //授权码
        $this->secret = request()->header('secret');
        // 签名
        $this->sign = request()->header('sign');
        //登录后的token
        $this->token = request()->header('token');
        //时间戳
        $this->timestamp = request()->header('timestamp');
        
        if(!isset($this->secret)) {
            $this->_error('缺少参数',[],401);
        }
        
        // if(!isset($this->sign)) {
        //     $this->_error('缺少参数',[],402);
        // }
        
        // if(!isset($this->timestamp) || abs(time()-$this->timestamp) > 30 ) {
        if(!isset($this->timestamp)) {
            $this->_error('缺少参数！', [], 416);
        }
        
        if(!apiModel::vaildSecret($this->secret)) {
            $this->_error('非法请求', [], 401);
        }
        // 检测授权
        // if (!$this->shouquan() || !$this->verifySign($this->sign, $this->params, $this->secret, $this->timestamp)) {
		if (!$this->verifySign($this->sign, $this->params, $this->secret, $this->timestamp)) {
            $this->_error('该接口未授权，请联系群管理！若你不是在互站购买的源码，请联系你的卖家处理！请勿擅自替换文件，否则将造成严重后果！！！', [], 910);
        }

    }
    
    public function shouquan(){
        $now_url = $_SERVER['HTTP_HOST'];
        $is_shouquan = cache($now_url);
        if ($is_shouquan == 'yes') {
            return true;
        }
        if(!$is_shouquan){
            if($json_get = file_get_contents('http://xxx/api.php?tpye=apicxsq&url='.urlencode($now_url))){
                $row_json = json_decode($json_get, true);
                if($row_json['code'] != 1){
                    return false;
                }else{
                    cache($now_url, 'yes', 259200);
                    return true;
                }
            }else{
                return false;
            }
        }
        return false;
    }

    public function _success($msg = 'success', $data = [],$code=200 ,$header = [], $type = 'json') {
        $this->_result($msg, $data, $code, $header, $type);
    }

    public function _error ($msg = 'error', $data = [],$code=200, $header = [], $type = 'json') {
        $this->_result($msg, $data, $code,  $header,  $type);
    }

    /**
     * 返回封装后的API数据到客户端
     * @access protected
     * @param  mixed     $data 要返回的数据
     * @param  integer   $code 返回的code
     * @param  mixed     $msg 提示信息
     * @param  string    $type 返回数据格式
     * @param  array     $header 发送的Header信息
     * @return void
     */
    public function _result($msg = '', $data= [], $code = 0, array $header = [], $type = 'json')
    {
        $result = [
            'code' => $code,
            'msg'  => $msg,
            'time' => time(),
            'data' => $data,
        ];
        $response_code = $code < 0 ? $code : 200;
        $response = Response::create($result, $type)->header($header)->code($response_code);
        throw new HttpResponseException($response);
    }

    /**
     * 生成签名（商城端签名）
     * @return string 本函数不覆盖sign成员变量，如要设置签名需要调用SetSign方法赋值
     */
    private function verifySign($sign, $values, $secret, $timestamp)
    {
        ksort($values);
        $string = toUrlParams($values);
        $string = 'key=' . $secret . '&' . $string . '&timestamp=' . (int)($timestamp * 1000) . $_SERVER['HTTP_HOST'];
        $string = md5($string);
        $string = substr($string, 5, 21);
        return $sign === strtoupper($string);
    }

}
