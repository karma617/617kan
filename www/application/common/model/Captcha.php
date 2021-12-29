<?php
namespace app\common\model;

use think\Model;
use think\facade\Session;
use think\captcha\Captcha as CaptchaVendor;

class Captcha extends Model
{
    protected $seKey;
    protected $expire;

    public function __construct($seKey = '', $expire = '') {
        $this->seKey = $seKey ? $seKey : 'ThinkPHP.CN';
        $this->expire = $expire ? $expire : 1800;
        $config = [
            'fontSize' => 30,
            'length' => 4,
            'codeSet' => '0123456789',
            'useCurve' => false,
            'useNoise' => true
        ];
        $this->CaptchaVendor = new CaptchaVendor($config);
    }

    /**
     * 验证码文件缓存
     *
     * @return void
     * @author 617 <email：723875993@qq.com>
     */
    public function caches($key = '') {
        $options = [
           'type'   => 'file', 
           'expire' => $this->expire,
           'path'   => ROOT_PATH . 'runtime/img_cache/', 
        ];
        cache($options);
        // 验证码不能为空
        $secode = Session::get($key, '');
        cache('img_code_' . $key, $secode['verify_code']);
        return true;
    }

    /**
     * 验证码验证
     *
     * @param Type $var
     * @return void
     * @author 617 <email：723875993@qq.com>
     */
    public function check($code, $id = '') {
        $key = $this->authcode($this->seKey) . $id;
        $cache_code = cache('img_code_' . $key);
        if ($this->authcode(strtoupper($code)) == $cache_code) {
            cache('img_code_' . $key, null);
            Session::delete($key, '');
            return true;
        }
        return false;
    }

    /* 加密验证码 */
    private function authcode($str)
    {
        $key = substr(md5($this->seKey), 5, 8);
        $str = substr(md5($str), 8, 10);
        return md5($key . $str);
    }

    /**
     * 验证验证码是否正确
     * @access public
     * @param string $code 用户验证码
     * @param string $id   验证码标识
     * @return bool 用户验证码是否正确
     */
    public function getCaptcha($code, $id = '')
    {
        $key = $this->authcode($this->seKey) . $id;
        // 验证码不能为空
        $secode = Session::get($key, '');
        if (empty($code) || empty($secode)) {
            return false;
        }
        // session 过期
        if (time() - $secode['verify_time'] > $this->expire) {
            Session::delete($key, '');
            return false;
        }
        return $secode['verify_code'];
    }

    public function getImgCode($id = '') {
        $key = $this->authcode($this->seKey) . $id;
        cache('img_code_' . $key, null);
        $img = $this->CaptchaVendor->entry($id);
        $this->caches($key);
        return $img;
    }

}
