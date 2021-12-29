<?php
// +----------------------------------------------------------------------
// | HisiPHP框架[基于ThinkPHP5开发]
// +----------------------------------------------------------------------
// | Copyright (c) 2016-2021 http://www.hisiphp.com
// +----------------------------------------------------------------------
// | HisiPHP承诺基础框架永久免费开源，您可用于学习和商用，但必须保留软件版权信息。
// +----------------------------------------------------------------------
// | Author: 橘子俊 <364666827@qq.com>，开发者QQ群：50304283
// +----------------------------------------------------------------------
namespace app\user\model;

use think\Model;
use app\user\validate\User as UserValidate;
use app\user\model\UserToken;
use app\user\model\UserCertification;
use app\user\model\UserGroup;
/**
 * 会员模型
 * @package app\user\model
 */
class User extends Model
{
    // 定义时间戳字段名
    protected $createTime = 'ctime';
    protected $updateTime = 'mtime';

    // 自动写入时间戳
    protected $autoWriteTimestamp = true;

    protected $pk = 'id';
    
    public $code = 0;

    public function initialize() {
        parent::initialize();
        $this->UserValidate = new UserValidate();
        $this->UserTokenModel = new UserToken();
        $this->UserCertificationModel = new UserCertification();
        $this->UserGroup = new UserGroup();
    }

    public static function init()
    {
        self::event('after_delete', function ($user) {
            runhook('user_delete', $user);
        });
        self::event('after_update', function ($user) {
            // runhook('user_update', $user);
        });
    }

    // 过滤昵称里面的表情符号
    public function setNickAttr($value)
    {
        // if (preg_match('/[\x{10000}-\x{10FFFF}]/u', $value)) {
        //     return 'nick'.random(15, 0);
        // }
        preg_replace("/\\\u[ed][0-9a-f]{3}\\\u[ed][0-9a-f]{3}/","{emoji}",$value);
        return $value;
    }

    public function setUserNameAttr($value)
    {
        preg_replace("/\\\u[ed][0-9a-f]{3}\\\u[ed][0-9a-f]{3}/","{emoji}",$value);
        return $value;
    }

    // 密码加密
    public function setPasswordAttr($value, $data)
    {
        if (strlen($value) != 32) {
            $value = md5($value);
        }
        return md5($value.$data['salt']);
    }

    // 最后登陆ip
    public function setLastLoginIpAttr()
    {
        return get_client_ip();
    }

    // 最后登陆ip
    public function setLastLoginTimeAttr()
    {
        return request()->time();
    }

    // 有效期
    public function setExpireTimeAttr($value)
    {
        if ($value != 0) {
            return strtotime($value);
        }
        return 0;
    }

    // 有效期
    public function getExpireTimeAttr($value)
    {
        if ($value != 0) {
            return date('Y-m-d H:i:s', $value);
        }
        return 0;
    }

    public function getMobileAttr($value)
    {
        if (!$value) return '';
        return $value;
    }

    public function getNickAttr($value)
    {
        preg_replace("/\\\u[ed][0-9a-f]{3}\\\u[ed][0-9a-f]{3}/","{emoji}",$value);
        return $value;
    }

    public function getUserNameAttr($value)
    {
        preg_replace("/\\\u[ed][0-9a-f]{3}\\\u[ed][0-9a-f]{3}/","{emoji}",$value);
        return $value;
    }

    public function hasGroup()
    {
        return $this->hasOne('app\user\model\UserGroup', 'id', 'group_id');
    }

    public function hasCertification()
    {
        return $this->hasOne('app\user\model\UserCertification', 'uid', 'id');
    }
    
    /**
     * 注册
     * @param array $data 参数，可传参数account,username,password,email,mobile,nick,avatar
     * @param bool $login 注册成功后自动登录
     * @author 橘子俊 <364666827@qq.com>
     * @return stirng|array
     */
    public function register($data = [], $login = true)
    {
        $map                = [];
        $map['email']       = '';
        $map['mobile']      = '';
        $map['username']    = '';
        $map['nick']        = isset($data['nick']) ? $data['nick'] : '';
        $map['avatar']      = isset($data['avatar']) ? $data['avatar'] : '';
        $map['group_id']    = 0;
        $map['salt']        = random(16, 0);
        if (isset($data['password'])) {
            $map['password'] = $data['password'];
        }
    
        if (isset($data['account'])) {
            if (is_email($data['account'])) {// 邮箱
                $map['email']       = $data['account'];
            } elseif (is_mobile($data['account'])) {// 手机号
                $map['mobile']      = $data['account'];
            } elseif (is_username($data['account'])) {// 用户名
                $map['username']    = $data['account'];
            } else {
                $this->error = '手机号不正确';
                return false;
            }
        }

        if (isset($data['email']) && is_email($data['email'])) {
            $map['email'] = $data['email'];
        }

        if (isset($data['mobile']) && is_mobile($data['mobile'])) {
            $map['mobile'] = $data['mobile'];
        }

        if (isset($data['username']) && is_username($data['username'])) {
            $map['username'] = $data['username'];
        }
        
        if (isset($data['pid']) && !empty($data['pid'])) {
            $map['pid'] = str_coding($data['pid']);
        }
        
        if (isset($data['drice_id']) && !empty($data['drice_id'])) {
            $map['drice_id'] = md5($data['drice_id']);
        }

        $group = model('user/UserGroup')->where('id', $data['group_id'])->find();
        if ($group) {
            $map['group_id']    = $group['id'];
            $map['expire_time'] = $group['expire'] > 0 ? strtotime('+ '.$group['expire'].' days') : 0;
        }

        if($this->UserValidate->scene('register')->check($map) !== true) {
            $this->error = $this->UserValidate->getError();
            return false;
        }
        runhook('user_before_register', $map);
        $res = $this->isUpdate(false)->save($map);
        if (!$res) {
            return false;
        }

        $map['id'] = $this->id;
        unset($map['password']);
        runhook('user_after_register', $map);
        if ($login) {
            return $this->autoLogin($map);
        }

        return true;
    }

    /**
     * 授权登录注册，只为了提供授权登录时绑定user_id
     * @param string $data 传入数据
     * @author 橘子俊 <364666827@qq.com>
     * @return stirng|array
     */
    public function oauthRegister($data = [])
    {
        $map                    = [];
        $map['nick']            = isset($data['nick']) ? $data['nick'] : '';
        $map['email']           = '';
        $map['mobile']          = isset($data['mobile']) ? $data['mobile'] : '';
        $map['username']        = isset($data['username']) ? $data['username'] : '';
        $map['avatar']          = isset($data['avatar']) ? $data['avatar'] : '';
        $map['last_login_ip']   = get_client_ip();
        $map['last_login_time'] = time();
        $map['salt']            = random(16, 0);

        $group = model('user/UserGroup')->where('default',1)->find();
        if ($group) {
            $map['group_id']    = $group['id'];
            $map['expire_time'] = $group['expire'] > 0 ? strtotime('+ '.$group['expire'].' days') : 0;
        }

        if (isset($data['email']) && is_email($data['email'])) {
            $map['email'] = $data['email'];
        }

        if (isset($data['mobile']) && is_mobile($data['mobile'])) {
            $map['mobile'] = $data['mobile'];
        }

        if (isset($data['username']) && is_username($data['username'])) {
            $map['username'] = $data['username'];
        }

        if (isset($data['password'])) {
            $map['password'] = $data['password'];
        }

        if (isset($data['where'])) {
            $map = array_merge($map, $data['where']);
        }

        $res = $this->isUpdate(false)->save($map);
        if (!$res) {
            $this->error = $this->getError() ? : '授权登录失败！';
            return false;
        }

        $map['id'] = $this->id;
        runhook('user_after_register', $map);

        return $this->autoLogin($map);
    }

    /**
     * 登录
     * @param string $account 账号
     * @param string $password 密码
     * @param bool $remember 记住登录 TODO
     * @param string $field 登陆之后缓存的字段
     * @author 橘子俊 <364666827@qq.com>
     * @return stirng|array
     */
    public function login($account = '', $password = '', $code = '', $remember = false, $field = 'nick,username,mobile,email,avatar', $token = false)
    {
        $where = $rule = [];
        $account    = trim($account);
        $password   = trim($password);
        $field      = trim($field, ',');
        if (empty($code)) {
            $rule['password'] = $password;
        }

        // 匹配登录方式
        if (is_email($account)) {
            // 邮箱登录
            $where['email']       = $rule['email']    = $account;
        } elseif (is_mobile($account)) {
            // 手机号登录
            $where['mobile']      = $rule['mobile']   = $account;
        } elseif (is_username($account)) {
            // 用户名登录
            $where['username']    = $rule['username'] = $account;
        } else {
            $this->error = '登陆账号格式不正确！';
            return false;
        }

        if ($token !== false) {
            $rule['__token__'] = input('param.__token__') ? : $token;
            $scene = 'loginToken';
        } else {
            $scene = 'login';
        }

        $validate = new \app\user\validate\User;
        if($validate->scene($scene)->check($rule) !== true && empty($code)) {
            $this->error = $validate->getError();
            return false;
        }

        $where['status'] = 1;
        $member = $this->where($where)->field('id,'.$field.',group_id,password,salt,expire_time')->find();
        if (!$member) {
            $this->error = '用户不存在或被禁用！';
            return false;
        }
        
        $member = $member->toArray();

        if (strlen($password) != 32) {
            $password = md5($password);
        }

        if (empty($code)) {
            // 密码校验
            if (md5($password.$member['salt']) != $member['password'] ) {
                $this->error = '登陆密码错误！';
                return false;
            }
        }

        // 检查有效期
        if ($member['expire_time'] !== 0 && strtotime($member['expire_time']) < time()) {
            $this->error = '账号已过期！';
            return false;
        }

        $login              = [];
        $login['id']        = $member['id'];
        $login['group_id']  = $member['group_id'];
        $fields = explode(',', $field);

        foreach ($fields as $v) {
            if ($v == 'password' || $v == 'salt') {
                continue;
            }
            $login[$v] = $member[$v];
        }

        return $this->autoLogin($login);
    }

    /**
     * 判断是否登录
     * @author 橘子俊 <364666827@qq.com>
     * @return bool|array
     */
    public function isLogin($token = '', $encode = false) 
    {
        if (is_numeric($token)) {
            $uid = $token;
        }else{
            $uid = $this->UserTokenModel->where('token', $token)->value('uid');
        }
        if ($uid) {
            $result = $this->field('id,nick,username,expire_time,mobile,email,number,avatar,status,group_id')->where('id', $uid)->find();
            if (!empty($result)) {
                $auth = $this->UserCertificationModel->checkAuth($token, true);
                if (!empty($auth)) {
                    $auth['uid'] = $encode ? str_coding($auth['uid'], 'ENCODE') : $result['id'];
                }
                // 检查有效期
                if ($result['expire_time'] !== 0 && strtotime($result['expire_time']) < time()) {
                    // 会员到期，改为默认等级
                    $default_group_id = model('user/UserGroup')->where('default', 1)->value('id');
                    $this->where('id', $uid)->update([
                        'expire_time' => 0,
                        'group_id' => $default_group_id
                    ]);
                    $result['expire_time'] = 0;
                    $result['group_id'] = $default_group_id;
                }
                $userinfo = [
                    'id' => $encode ? str_coding($result['id'], 'ENCODE') : $result['id'],
                    'nick' => $result['nick'],
                    'username' => $result['username'],
                    'mobile' => $result['mobile'],
                    'email' => $result['email'],
                    'number' => $result['number'],
                    'avatar' => $result['avatar'],
                    'islock' => $result['status'] ? 0 : 1,
                    'group_id' => $result['group_id'],
                    'group_name' => $this->UserGroup->where('id', $result['group_id'])->value('name'),
                    'expire_time' => $result['expire_time'] > 0 ? $result['expire_time'] : '无限期',
                    'auth' => $auth ? 1 : 0,
                    'auth_info' => $auth ? $auth : []
                ];
                return $userinfo;
            }
            return false;
        }
        return false;
    }

    /**
     * 自动登陆
     * @author 橘子俊 <364666827@qq.com>
     * @param bool $oauth 第三方授权登录
     * @return bool|array
     */
    public function autoLogin($data = [], $oauth = false)
    {
        $map = [];
        $map['status']  = 1;
        if ($oauth) {
            $map = array_merge($map, $data['where']);
        }else{
            $map['id'] = $data['id'];
        }
        $data = $this->where($map)->field('id,group_id,nick,username,mobile,email,expire_time,avatar')->find();
        if (!$data) {
            $this->error = "未找到帐号信息";
            return false;
        }
        $data = $data->toArray();
        $map                    = [];
        // 检查有效期
        if ($data['expire_time'] !== 0 && strtotime($data['expire_time']) < time()) {
            // 会员到期，改为默认等级
            $map['group_id'] = model('user/UserGroup')->where('default', 1)->value('id');
            $map['expire_time'] = 0;
        }
        // $isLogin = model('user/UserToken')->where('uid', $data['id'])->find();
        // if (count($isLogin) > 0) {
        //     $this->error = '您的帐号已经登录';
        //     return false;
        // }
        $map['last_login_ip']   = get_client_ip();
        $map['last_login_time'] = request()->time();
        $this->where('id', $data['id'])->update($map);
        $token = $this->dataSign($data);
        // session('login_user', $data);
        // session('login_user_sign', $token);
        model('user/UserToken')->create([
            'uid' => $data['id'],
            'token' => $token,
            'expire_time' => time() + 86400
        ]);
        $data['token'] = $token;
        // $data['id'] = str_coding($data['id'],'ENCODE');
        runhook('user_after_login', $data);
        unset($data['id']);
        $result = $this->isLogin($token, true);
        $result['token'] = $token;
        cache('userinfos', $result);
        return $result;
    }

    /**
     * 管理后台重置密码
     * @author 橘子俊 <364666827@qq.com>
     * @return bool
     */
    public function adminResetPassword($id = [], $pwd = '123456')
    {
        $data = [];
        foreach ($id as $v) {
            $data[] = [
                'id' => $v,
                'salt' => random(16, 0),
                'password' => $pwd,
            ];
        }
        return $this->saveAll($data);
    }

    /**
     * 退出登陆
     * @author 橘子俊 <364666827@qq.com>
     * @return bool
     */
    public function logout($token = '') 
    {
        return $this->UserTokenModel->where('token', $token)->delete();
    }

    /**
     * 数据签名认证
     * @param array $data 被认证的数据
     * @author 橘子俊 <364666827@qq.com>
     * @return string 签名
     */
    public function dataSign($data = [])
    {
        $data['timestemp'] = time();
        if (!is_array($data)) {
            $data = (array) $data;
        }

        ksort($data);
        $code = http_build_query($data);
        $sign = sha1($code);

        return $sign;
    }
    /**
     * 手机号是否注册
     *
     * @param Type $var
     * @return void
     * @author 617 <email：723875993@qq.com>
     */
    public function checkMobile($account = "")
    {
        $where = [];
        // 匹配登录方式
        if (is_email($account)) {
            // 邮箱登录
            $where['email']       = $account;
        } elseif (is_mobile($account)) {
            // 手机号登录
            $where['mobile']      =  $account;
        } elseif (is_username($account)) {
            // 用户名登录
            $where['username']    = $account;
        } else {
            $this->error = '登陆账号异常！';
            return false;
        }
        if ($this->where($where)->find()) {
            return true;
        }
        return false;
    }
    /**
     * 找回密码
     *
     * @param [type] $data
     * @return void
     * @author 617 <email：723875993@qq.com>
     */
    public function forget($data) {
        $account = $data['account'];
        $password = $data['password'];
        $repassword = $data['repassword'];
        if ($password != $repassword) {
            $this->error = '两次输入的密码不一致';
            return false;
        }
        $where = [];
        // 匹配登录方式
        if (is_email($account)) {
            // 邮箱登录
            $where['email']       = $account;
        } elseif (is_mobile($account)) {
            // 手机号登录
            $where['mobile']      =  $account;
        } elseif (is_username($account)) {
            // 用户名登录
            $where['username']    = $account;
        } else {
            $this->error = '登陆账号异常！';
            return false;
        }
        $res = $this->where($where)->find();
        if (!$res) {
            $this->error = '未找到该帐号';
            return false;
        }
        $res = $this->where($where)->update(['password'=>md5(md5($password).$res['salt'])]);
        if (!$res) {
            return false;
        }
        return true;
    }

    public function one($id = '') {
        $info = $this->get($id);
        if (empty($info)) {
            return [];
        }
        $auth_info = $this->UserCertificationModel->where('uid', $id)->find();
        if (empty($auth_info)) {
            return [];
        }
        $ret = [
            'group_id' => $info['group_id'],
            'mobile' => $info['mobile'],
            'integral' => $info['integral'],
            'car_number' => $auth_info['car_number'],
            'car_number_type' => $auth_info['car_number_type'],
            'car_type' => $auth_info['car_type'],
            'car_length' => $auth_info['car_length'],
            'car_weight' => $auth_info['car_weight'],
            'man_avatar' => $auth_info['man_avatar'],
            'car_img' => $auth_info['car_img'],
            'true_name' => $auth_info['true_name'],
        ];
        return $ret;
    }
}
