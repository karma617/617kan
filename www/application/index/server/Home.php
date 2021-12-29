<?php
namespace app\index\server;

use app\common\server\Service;
use app\banner\model\Ad as AdModel;
use app\index\model\Msg as MsgModel;

class Home extends Service{

    public function initialize() {
        parent::initialize();
        $this->appid = config('wechat_mp')['appid'];
        $this->secret = config('wechat_mp')['secret'];
        $this->AdModel = new AdModel();
        $this->MsgModel = new MsgModel();
    }

    public function getGuideImg() {
        $map = [];
        $map[] = ['status', 'eq', 1];
        $map[] = ['type', 'eq', 1];
        $list = $this->AdModel->getList($map, 0);
        return $list;
    }
    /**
     * 随机取首页广告
     *
     * @return void
     * @author 617 <email：723875993@qq.com>
     */
    public function getHomeAdList() {
        $map = [];
        $map[] = ['status', 'eq', 1];
        $map[] = ['type', 'eq', 2];
        $list = $this->AdModel->getList($map, 0);
        return $list;
    }
    /**
     * 随机取播放页广告
     *
     * @return void
     * @author 617 <email：723875993@qq.com>
     */
    public function getPlayerAdList() {
        $data['alert_ad'] = $this->AdModel->getOne(3);
        $data['alert_public'] = $this->AdModel->getOne(4);
        $data['video_ad'] = $this->AdModel->getOne(5);
        $data['play_start_ad'] = $this->AdModel->getOne(6);
        return $data;
    }
    /**
     * 随机取一条常驻广告
     *
     * @return void
     * @author 617 <email：723875993@qq.com>
     */
    public function getPublicAdList() {
        return $this->AdModel->getOne(4);
    }

    public function about()
    {
        return config('ucenter.about_app');
    }

    public function punch()
    {
        return config('ucenter.punch');
    }

    public function getMsgLists()
    {
        $map = [];
        $map[] = ['status', 'eq', 1];
        $list = $this->MsgModel->getList($map, 0);
        return $list;
    }

    /**
     * 图片等级检测
     *
     * @return void
     * @author 617 <email：723875993@qq.com>
     */
    public function checkImg($data)
    {
        $accessToken = cache('app_access_token');
        if (!$accessToken) {
            $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid={$this->appid}&secret={$this->secret}";
            $result = wx_send($url);
            $result = json_decode($result, 1);
            if (isset($result['errcode'])) {
                $this->error = $result['errmsg'];
                return false;
            }
            $accessToken = $result['access_token'];
            cache('app_access_token', $accessToken, 7000);
        }
        if ($accessToken) {
            $url = "https://api.weixin.qq.com/wxa/img_sec_check?access_token=$accessToken";
            $img_data = base64_image_content($data['str'], '', true);
            if ($img_data) {
                $postData = [
                    'media' => curl_file_create($img_data)
                ];
                $result = wx_send($url, $postData, 'POST', [], 1);
                $result = json_decode($result, 1);
                if ($result['errcode'] == 0) {
                    return true;
                }
                $this->error = '图片含有违规信息';
                return false;
            }
            $this->error = '图片大小不能超过1M';
            return false;
        }
        $this->error = '调用接口失败';
        return false;
    }
}