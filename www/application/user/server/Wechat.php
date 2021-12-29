<?php
namespace app\user\server;
use app\common\server\Service;
use app\user\model\User;

class Wechat extends Service{
    public $appid = '';
    public $secret = '';

    public function initialize() {
        parent::initialize();
        $this->appid = config('wechat_mp')['appid'];
        $this->secret = config('wechat_mp')['secret'];
        $this->UserModel = new User();
    }

    public function mplogin($data)
    {
        $appid = $this->appid;
        $secret = $this->secret;
        $js_code = $data['code'];
        $url = "https://api.weixin.qq.com/sns/jscode2session?appid={$appid}&secret={$secret}&js_code={$js_code}&grant_type=authorization_code";
        $res = wx_send($url);
        $res = json_decode($res, true);
        if (isset($res['session_key']) && !empty($res['session_key'])) {
            $info = $this->decodeUserInfo($appid, $res['session_key'], $data);
            if ($info) {
                // 判断是否已绑定帐号
                $map = [];
                $map['where'] = ['wxmp_openid' => $info['openId']];
                $user_model_res = $this->UserModel->autoLogin($map, true);
                $info['has_account'] = $user_model_res ? 1 : 0;
                $result = array_merge($info, empty($user_model_res) ? [] : $user_model_res);
                return $result;
            }
            return false;
        }
        $this->error = $res['errmsg'];
        return false;
    }
    /**
     * 绑定手机号（未加短信验证）
     *
     * @param [type] $data
     * @return void
     * @author 617 <email：723875993@qq.com>
     */
    public function bindMobile($data = [])
    {
        // 如果该手机号注册过，直接绑定
        if ($user_id = $this->UserModel->where('mobile', $data['mobile'])->value('id')) {
            $updateData = [
                'id' => $user_id,
                'wxmp_openid' => $data['openId'],
                'nick' => $data['nickName'],
                'mobile' => $data['mobile'],
                'username' => $data['nickName'],
                'avatar' => $data['avatarUrl']
            ];
            if ($this->UserModel->isUpdate(true)->save($updateData)) {
                $map = [];
                $map['id'] = $user_id;
                $user_model_res = $this->UserModel->autoLogin($map);
                if (false !== $user_model_res) {
                    $result = array_merge($data, empty($user_model_res) ? [] : $user_model_res);
                    return $result;
                }
                $this->error = '帐号绑定失败。erro: 4001';
                return false;
            }
            $this->error = '帐号绑定失败。erro: 4002';
            return false;
        }
        $addData = [
            'nick' => $data['nickName'],
            'mobile' => $data['mobile'],
            'username' => $data['nickName'],
            'avatar' => $data['avatarUrl'],
            'password' => '123456',
            'where' => ['wxmp_openid' => $data['openId']]
        ];
        $result = $this->UserModel->oauthRegister($addData);
        if (false !== $result) {
            return $result;
        }
        $this->error = '帐号绑定失败。erro: 4003';
        return false;
    }

    public function wechatMobile()
    {
        $data = input();
        $appid = $this->appid;
        $secret = $this->secret;
        $js_code = $data['code'];
        $url = "https://api.weixin.qq.com/sns/jscode2session?appid={$appid}&secret={$secret}&js_code={$js_code}&grant_type=authorization_code";
        $res = wx_send($url);
        $res = json_decode($res, true);
        if (isset($res['session_key']) && !empty($res['session_key'])) {
            $info = $this->decodeUserInfo($appid, $res['session_key'], $data);
            if (!empty($info)) {
                // 判断是否已有该手机号，有绑定 无注册
                $user_info = $this->UserModel->where( ['mobile' => $info['purePhoneNumber']] )->find();
                if (!empty($user_info)) {
                    if (empty($user_info['wxmp_openid'])) {
                        $this->UserModel->where( ['mobile' => $info['purePhoneNumber']] )->update(['wxmp_openid' => $data['openId']]);
                    }
                    if (empty($user_info['avatar'])) {
                        $this->UserModel->where( ['mobile' => $info['purePhoneNumber']] )->update(['avatar' => $data['avatarUrl']]);
                    }
                    if (empty($user_info['username'])) {
                        $this->UserModel->where( ['mobile' => $info['purePhoneNumber']] )->update(['username' => $data['nickName']]);
                    }
                    $user_info['openId'] = $data['openId'];
                    return $user_info;
                }
                if (empty($user_info)) {
                    $addData = [
                        'password'   => md5('123456'),
                        'mobile' => $info['purePhoneNumber'],
                        'wxmp_openid' => $data['openId'],
                        'username' => $data['nickName'],
                        'avatar' => $data['avatarUrl']
                    ];
                    $this->UserModel->save($addData);
                    unset($addData['password']);
                    $addData['openId'] = $data['openId'];
                    return $addData;
                }
            }
            return false;
        }
        $this->error = $res['errmsg'];
        return false;
    }

    /**
     * 解密用户信息
     *
     * @param [type] $data
     * @return void
     * @author 617 <email：723875993@qq.com>
     */
    public function decodeUserInfo($appid, $sessionKey, $data)
    {
        $errCode = $this->decryptData($appid, $sessionKey, urldecode($data['encryptedData']), urldecode($data['iv']), $decode);
        if ($errCode === 0) return json_decode($decode, true);
        $this->error = $errCode;
        return [];
    }
    public function decryptData($appid, $sessionKey, $encryptedData, $iv, &$data)
    {
        if (strlen($sessionKey) != 24) {
            return -41001;
        }
        $aesKey = base64_decode($sessionKey);
        if (strlen($iv) != 24) {
            return -41002;
        }
        $aesIV = base64_decode($iv);
        $aesCipher = base64_decode($encryptedData);
        $result = openssl_decrypt($aesCipher, "AES-128-CBC", $aesKey, 1, $aesIV);
        $dataObj = json_decode($result);
        if ($dataObj  == NULL) {
            return -41003;
        }
        if ($dataObj->watermark->appid != $appid) {
            return -41003;
        }
        $data = $result;
        return 0;
    }
}