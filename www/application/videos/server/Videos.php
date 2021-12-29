<?php
namespace app\videos\server;
use app\common\server\Service;
use app\videos\model\Videos as VideosModel;
use app\videos\model\VideoParse as VideoParseModel;
use app\user\model\User as UserModel;
use app\videos\model\VideosDanmu as VideosDanmuModel;
use app\videos\model\VideosType as VideosTypeModel;
use app\videos\validate\VideosDanmu as VideosDanmuValidate;
use app\user\model\UserLike as UserLikeModel;
use hisi\Http;
use think\Db;

class Videos extends Service
{

    public function initialize() 
    {
        parent::initialize();
        $this->VideosModel = new VideosModel();
        $this->VideoParseModel = new VideoParseModel();
        $this->UserModel = new UserModel();
        $this->VideosTypeModel = new VideosTypeModel();
        $this->VideosDanmuModel = new VideosDanmuModel();
        $this->VideosDanmuValidate = new VideosDanmuValidate();
        $this->UserLikeModel = new UserLikeModel();
    }
    /**
     * 用户添加喜欢视频
     *
     * @param [type] $data
     * @param [type] $user
     * @return void
     * @author 617 <email：723875993@qq.com>
     */
    public function addLike($data, $user)
    {
        if (!isset($user['id']) || !$user['id']) {
            $this->error = "请先登录";
            return false;
        }
        if ($this->UserLikeModel->where('uid', $user['id'])->find()) {
            $map = [];
            $map[] = ['uid', 'eq', $user['id']];
            $map[] = ['vid', 'like', "%{$data["id"]}%"];
            if (!$this->UserLikeModel->where($map)->find()) {
                $sql = "UPDATE hisi_user_like SET vid=concat(',{$data['id']}',vid) WHERE uid={$user['id']}";
                Db::execute($sql);
                $msg = "收藏成功";
            }else{
                $sql = "UPDATE hisi_user_like SET vid = replace(vid, ',{$data['id']}', '') WHERE uid={$user['id']}";
                Db::execute($sql);
                $msg = "取消收藏";
            }
        }else{
            $this->UserLikeModel->save(['uid'=>$user['id'],'vid'=>",{$data['id']}"]);
        }
        $this->error = $msg;
        return true;
    }

    /**
     * 获取视频列表
     *
     * @param [type] $data
     * @param [type] $user
     * @return void
     * @author 617 <email：723875993@qq.com>
     */
    public function getVideoLists($data, $user)
    {
        $page = $data['page'];
        $limit = $data['limit'];
        $map = [];
        $map[] = ['vod_status', 'eq', 1];
        $map[] = ['vod_lock', 'eq', 0];
        // 一级分类 电影电视剧动漫
        $map[] = ['type_id_1|type_id', 'eq', $data['type_id']];
        // 子分类id
        $area = str_replace('全部','', $data['area']);
        $area = str_replace('-1','', $area);
        if (!empty($area)) {
            $map[] = ['type_id', 'eq', $area];
        }
        // 年份
        $year = str_replace('全部', '', $data['year']);
        if (!empty($year)) {
            $map[] = ['vod_year', 'eq', $year];
        }
        return $this->VideosModel->getList($map, $page, $limit);
    }
    /**
     * 获取视频分类
     *
     * @param [type] $data
     * @param [type] $user
     * @return void
     * @author 617 <email：723875993@qq.com>
     */
    public function getVideoTypeLists($data, $user)
    {
        $list = $this->VideosTypeModel->listToTree('_c', ($user['group_id'] == 6));
        if (empty($list)) {
            $this->error = "获取失败";
            return false;
        }
        return $list;
    }

    public function getVideoArea($data, $user)
    {
        $list = $this->VideosModel->getVideoFieldGroup('vod_area');
        if (empty($list)) {
            $this->error = "获取失败";
            return false;
        }
        foreach ($list as $key => $value) {
            if (empty($value['vod_area'])) {
                unset($list[$key]);
            }
        }
        arrayMsort($list, 'vod_area');
        array_splice($list, 0, 3);
        array_unshift($list,['vod_area'=>'全部']);
        return array_values($list);
    }

    public function getVideoClass($data, $user)
    {
        $list = $this->VideosModel->getVideoFieldGroup('vod_class');
        if (empty($list)) {
            $this->error = "获取失败";
            return false;
        }
        foreach ($list as $key => $value) {
            if (empty($value['vod_class'])) {
                unset($list[$key]);
            }
            if (false !== stripos($value['vod_class'], '伦理')) {
                unset($list[$key]);
            }
            if (false !== stripos($value['vod_class'], '福利')) {
                unset($list[$key]);
            }
        }
        arrayMsort($list, 'vod_class');
        array_unshift($list,['vod_class'=>'全部']);
        return array_values($list);
    }

    public function getVideoYear($data, $user)
    {
        $list = $this->VideosModel->getVideoFieldGroup('vod_year');
        if (empty($list)) {
            $this->error = "获取失败";
            return false;
        }
        foreach ($list as $key => $value) {
            if (empty($value['vod_year']) || $value['vod_year'] > date("Y", time())) {
                unset($list[$key]);
            }
        }
        $vod_year = array_column($list,'vod_year');
        array_multisort($vod_year,SORT_DESC,$list);
        array_unshift($list,['vod_year'=>'全部']);
        return array_values($list);
    }
    
    public function getVideoDetail($data, $user)
    {
        $info = $this->VideosModel->getVideoDetail($data['vid']);
        if (empty($info)) {
            $this->error = "获取失败";
            return false;
        }
        
        if($info['vod_lock'] == 1){
        	$this->error = "因版权限制已下架";
            return false;
        }

        if (empty($info['vod_play_url'])) {
            $this->error = "未找到播放地址，请反馈给管理员";
            return false;
        }
        // 线路列表开始
        // 在这里增加新的线路名
        // 'tkm3u8' => '天空'  tkm3u8为播放器id，右边为播放器名字
        // e.g.: 换行后新增，每一行最后的逗号别忘了加
        /**
         * $_arr = [
         *   'tkm3u8' => '天空',
         *   'mahuazy' => '麻花',
         * ];
         */
        $_arr = [
            'tkm3u8' => '天空',
        ];
        // 线路列表结束
        $arr['vod_play_from'] = explode('$$$', $info['vod_play_from']);
        foreach ($arr['vod_play_from'] as $k => $v) {
            $arr['vod_play_from'][$k] = $_arr[$v];
        }
        $srcList = explode('$$$', $info['vod_play_url']);
        $tmp = [];
        // 播放地址
        foreach ($srcList as $k=>$v) {
            $vod_from = $arr['vod_play_from'][$k];
            // 是否多个
            if (strstr($v, '#')) {
                $juji = explode('#', $v);
                foreach ($juji as $ke => $val) {
                    $dizhi = explode('$', $val);
                    $arr['srcList'][$vod_from][$ke]['name'] = $dizhi[0];
                    $arr['srcList'][$vod_from][$ke]['url'] = $dizhi[1];
                }
            } else {
                $dizhi = explode('$', $v);
                $arr['srcList'][$vod_from][$k]['name'] = $dizhi[0];
                $arr['srcList'][$vod_from][$k]['url'] = $dizhi[1];
                $arr['srcList'][$vod_from] = array_values($arr['srcList'][$vod_from]);
            }
        }
        
        // $juji = strstr($info['vod_play_url'], '#') ? explode('#', $info['vod_play_url']) : '';
        // $dizhi = '';
        // $arr = [];
        // if ($juji) {
        //     foreach ($juji as $key => $value) {
        //         $dizhi = explode('$', $value);
        //         $arr['srcList'][$key]['name'] = $dizhi[0];
        //         $arr['srcList'][$key]['url'] = $dizhi[1];
        //     }
        // }else {
        //     $dizhi = explode('$', $info['vod_play_url'] ? $info['vod_play_url'] : $info['vod_down_url']);
        //     $arr['srcList'][0]['name'] = $dizhi[0];
        //     $arr['srcList'][0]['url'] = $dizhi[1];
        // }
        // var_dump($arr['srcList']);die;
        
        
        $arr['vid'] = $info['vod_id'];
        $arr['title'] = $info['vod_name'];
        $arr['vod_pic'] = $info['vod_pic'];
        $arr['remark'] = strip_tags($info['vod_content']);
        $arr['type'] = $info['type_id_1'];
        $arr['vod_score'] = $info['vod_score'];
        // $arr['count'] = count($arr['srcList']);
        $arr['recommend'] = $this->getRecommendVideo($info['type_id']);
        $arr['danmuList'] = $this->getDanmuList(['vid' => $info['vod_id'], 'index' => isset($data['index']) ? $data['index'] : 0], $user);
        
        $arr['downLoadList'] = [];
        if ($info['vod_down_url']){
        	$downLoadList = explode('#', $info['vod_down_url']);
	        foreach ($downLoadList as $key=>$value){
	        	$tmp = explode('$', $value);
	        	$arr['downLoadList'][$key]['lable'] = $tmp[0];
	        	$arr['downLoadList'][$key]['value'] = $tmp[1];
	        }
        }
        $isLike = false;
        if (isset($user['id']) && !empty($user['id'])) {
        	// 是否收藏
	        $map = [];
	        $map[] = ['uid', 'eq', $user['id']];
	        $map[] = ['vid', 'like', "%{$data["vid"]}%"];
	        $isLike = $this->UserLikeModel->where($map)->find();
        }
        $arr['isLike'] = $isLike ? true : false;
        return $arr;
    }
    // 推荐电影
    public function getRecommendVideo($type = 1)
    {
        $map = [];
        $map[] = ['vod_status', 'eq', 1];
        $map[] = ['type_id', 'eq', $type];
        $list = $this->VideosModel->getList($map, 1, 3, 'vod_score desc, rand()');
        return $list['list'];
    }

    public function getDanmuList($data, $user)
    {
        $map = [];
        if ((int)$data['vid'] == 0) {
            $info = $this->VideosModel->getVideoDetail($data['vid']);
            if (empty($info)) {
                $this->error = "获取失败";
                return false;
            }
            $map[] = ['vid', 'eq', $info['vod_id']];
        } else {
            $map[] = ['vid', 'eq', $data['vid']];
        }
        $map[] = ['index', 'eq', $data['index']];
        $list = $this->VideosDanmuModel->getList($map, 0);
        $danmuList = [];
        foreach ($list['list'] as $key => $value) {
            $danmuList[$key]['text'] = $value['text'];
            $danmuList[$key]['color'] = $value['color'];
            $danmuList[$key]['time'] = $value['time'];
        }
        return $danmuList;
    }

    public function searchKeywords($data, $user)
    {
        $keyword = trim($data['keyword']);
        $map = [];
        $map[] = ['vod_name|vod_director|vod_tag|vod_sub', 'like', "%$keyword%"];
        $map[] = ['vod_status', 'eq', 1];
        // $map = "vod_name like '%$keyword%' or vod_director like '%$keyword%' and vod_status = 1";
        return $this->VideosModel->searchKeywords($map);
    }
    /**
     * 首页推荐电影和电视剧
     *
     * @param [type] $data
     * @param [type] $user
     * @return void
     * @author 617 <email：723875993@qq.com>
     */
    public function getHomeVideoLists($data, $user)
    {
        $order = 'vod_level desc, vod_year desc,vod_hits desc';
        $fields = 'vod_id, vod_name, vod_pic, vod_play_url, vod_remarks, vod_score';
        // 推荐电影 ，年份倒序，点击倒序
        $map = [];
        $map[] = ['vod_status', 'eq', 1];
        $map[] = ['vod_lock', 'eq', 0];
        $map[] = ['type_id_1|type_id', 'eq', 1];
        $list['movie'] = $this->VideosModel->getList($map, 1, 9, $order, $fields);
        // 推荐电视剧 ，年份倒序，点击倒序
        $map = [];
        $map[] = ['vod_status', 'eq', 1];
        $map[] = ['vod_lock', 'eq', 0];
        $map[] = ['type_id_1|type_id', 'eq', 2];
        $list['tv'] = $this->VideosModel->getList($map, 1, 9, $order, $fields);
        // 动漫
        $map = [];
        $map[] = ['vod_status', 'eq', 1];
        $map[] = ['vod_lock', 'eq', 0];
        $map[] = ['type_id_1|type_id', 'eq', 4];
        $list['acg'] = $this->VideosModel->getList($map, 1, 9, $order, $fields);
        // 推荐综艺
        $map = [];
        $map[] = ['vod_status', 'eq', 1];
        $map[] = ['vod_lock', 'eq', 0];
        $map[] = ['type_id_1|type_id', 'eq', 3];
        $list['variety'] = $this->VideosModel->getList($map, 1, 9, $order, $fields);
        return $list;
    }

    public function hotKeyWords($data, $user)
    {
        $map = [];
        $map[] = ['vod_status', 'eq', 1];
        $fields = 'vod_id, vod_name';
        return $this->VideosModel->getList($map, 1, 10, 'vod_year desc, vod_hits desc', $fields);
    }

    public function getTypeVideoLists($data, $user)
    {
        $type = isset($data['type']) ? $data['type'] : '';
        $page = isset($data['page']) ? $data['page'] : 1;
        $limit = isset($data['limit']) ? $data['limit'] : 21;
        $map = [];
        $map[] = ['vod_status', 'eq', 1];
        $map[] = ['vod_lock', 'eq', 0];
        switch ($type) {
            case '1':
                $map[] = ['type_id_1|type_id', 'eq', 1];
                break;
            case '2':
                $map[] = ['type_id_1|type_id', 'eq', 2];
                break;
            case '3':
                $map[] = ['type_id_1|type_id', 'eq', 4];
                break;
            case '4':
                $map[] = ['type_id_1|type_id', 'eq', 3];
                break;
            default:
                $map[] = ['type_id_1|type_id', 'eq', 1];
                break;
        }
        return $this->VideosModel->getList($map, $page, $limit, 'vod_level desc, vod_time_add desc, vod_hits desc');
    }
	/**
     * 处理视频地址
     *
     * @param [type] $data
     * @param [type] $url
     * @return void
     * @author 617 <email：723875993@qq.com>
     */
    public function formatUrl($data, $url)
    {
        if (!isset($data['url']) || empty($data['url'])) {
            $this->error = "参数错误";
            return false;
        }
        // 哔哩哔哩的
        if (strpos($data['url'], config('videos.bilibili_host'))) {
            $id = getRightStr($data['url'], config('videos.bilibili_host'));
            if (strpos($data['url'], '/ep')) {
                return 'https://www.bilibili.com/bangumi/play/' . $id;
            }
            if (strpos($data['url'], '/av')) {
                return 'https://www.bilibili.com/video/' . $id;
            }
        }
        return $data['url'];
    }

    public function sendDanme($data, $user)
    {
        if($this->VideosDanmuValidate->check($data) !== true) {
            $this->error = $this->VideosDanmuValidate->getError();
            return false;
        }
        if (!isset($user['id']) || !$user['id']) {
            $this->error = "请先登录";
            return false;
        }
        $data['uid'] = $user['id'];
        if ($this->VideosDanmuModel->isUpdate(false)->save($data)) {
            return true;
        }
        $this->error = "发送失败";
        return false;
    }
    
    public function vip($data, $user)
    {
        if (empty($data['url'])) {
            $this->error = "url地址不能为空";
            return false;
        }
        // $cookies = ROOT_PATH.'vipcookie/cookies.sqlite';
        $cookies = ROOT_PATH.'vipcookie/iqiyi.txt';
        // var_dump("you-get --json -c {$cookies} {$data['url']}");die;
        $json_info = exec("you-get --json -c {$cookies} {$data['url']} ");
        return json_decode($json_info, 1);
    }

    public function downloadVideo($data, $user)
    {
        // $json = '{"video":{"urls":[{"cdn":"alimov2.a.yximgs.com","url":"http:\/\/alimov2.a.yximgs.com\/upic\/2019\/08\/31\/18\/BMjAxOTA4MzExODAxMjVfMTQ1NzgzMzU4Nl8xNzAwMzk2MTkxMF8xXzM=_b_B102303a6e5227c0169524bd82946acb4.mp4?tag=1-1568639138-sp-0-rbjvnecdam-519762799c9ab565&type=hot"},{"cdn":"jsmov2.a.yximgs.com","url":"http:\/\/jsmov2.a.yximgs.com\/upic\/2019\/08\/31\/18\/BMjAxOTA4MzExODAxMjVfMTQ1NzgzMzU4Nl8xNzAwMzk2MTkxMF8xXzM=_b_B102303a6e5227c0169524bd82946acb4.mp4?tag=1-1568639138-sp-1-imksdmdtty-2f4b1339c2cd1972&type=hot"}],"video_cover":[{"cdn":"ali2.a.yximgs.com","url":"http:\/\/ali2.a.yximgs.com\/upic\/2019\/08\/31\/18\/BMjAxOTA4MzExODAxMjVfMTQ1NzgzMzU4Nl8xNzAwMzk2MTkxMF8xXzM=_B5d6641e62d6e1b110cf023c0f1c2a391.jpg?tag=1-1568639138-sp-0-rg5loqnwat-0b3ebc8b3009b254&type=hot"},{"cdn":"js2.a.yximgs.com","url":"http:\/\/js2.a.yximgs.com\/upic\/2019\/08\/31\/18\/BMjAxOTA4MzExODAxMjVfMTQ1NzgzMzU4Nl8xNzAwMzk2MTkxMF8xXzM=_B5d6641e62d6e1b110cf023c0f1c2a391.jpg?tag=1-1568639138-sp-1-g9rsgiws2c-9349ae1e70a9285b&type=hot"}],"title":null,"stats":{"share_count":0,"like_count":83929,"unlike_count":0,"forward_count":0,"view_count":889042}},"user":{"name":"敏姐聊数码","description":"你以为华为只能拍月亮？不是滴，它拍星星也是超级棒的～#生活小妙招 #华为","avatar":"http:\/\/ali2.a.yximgs.com\/uhead\/AB\/2019\/08\/14\/17\/BMjAxOTA4MTQxNzA5MTlfMTQ1NzgzMzU4Nl8xX2hkMzgwXzgz_s.jpg","kwaiId":null,"profile_url":null}}';
        // $json = '{"user":{"name":"\u7384\u90fd","description":"\u5173\u6ce8\u54e6\uff0c\u6211\u6709\u70b9\u997f\u54e6(\u0e51][\u0e51\uff09","avatar":"https:\/\/p3-dy.byteimg.com\/img\/tos-cn-i-0000\/d9a9b7953f2b42e6a251058483dade56~200x200.jpeg","profile_url":null},"video":{"urls":[{"url":"http:\/\/v3-ppx.ixigua.com\/4fb064cfabe6b59cc8b1e61fdf2e8072\/5d806451\/video\/m\/2209ea6af369c044c54898b94eec58464cd116326e84000028adcf3fc81d\/?a=1319&br=617&cr=0&cs=0&dr=6&ds=2&er=&l=2019091711233901015309620424229&lr=&rc=anhoZXJrbm12bzMzZmYzM0ApaWQ1ODo3NDwzN2c4PDQ4OGdlY2xiXzIyLjZfLS1jMTBzczYyYWFjYjVgNGI1My9jYC06Yw%3D%3D","expires":604800},{"url":"http:\/\/v6-ppx.ixigua.com\/decd038110981cec0f8fbdd7cc785533\/5d806451\/video\/m\/2209ea6af369c044c54898b94eec58464cd116326e84000028adcf3fc81d\/","expires":604800}],"video_cover":[{"url":"https:\/\/p3-dy.byteimg.com\/img\/mosaic-legacy\/2cb3b0006b51db5b27c67~406x720_q80.jpeg"},{"url":"https:\/\/p3-ppx.bytecdn.cn\/img\/mosaic-legacy\/2cb3b0006b51db5b27c67~406x720_q80.jpeg"}],"title":"\u5355\u8eab\u72d7\u7684\u539a\u5ea6[\u6253\u8138]","stats":{"go_detail_count":717686,"impression_count":1566932,"comment_count":5709,"like_count":63283,"share_count":8170,"play_count":1308256,"dubbing_count":8,"bury_count":-1}},"share":{"share_url":"https:\/\/h5.pipix.com\/item\/6724930825407699204?app_id=1319&app=super&timestamp=1568690619&carrier_region=cn&region=cn&language=zh&utm_source=weixin","title":"\u5355\u8eab\u72d7\u7684\u539a\u5ea6[\u6253\u8138]","content":"[\u76ae\u76ae\u867e] \u641e\u7b11\u5a31\u4e50\u795e\u8bc4\u8bba\uff0c\u76ae\u8fd9\u4e00\u4e0b\u5f88\u5f00\u5fc3","image_url":"http:\/\/p3-dy.byteimg.com\/img\/mosaic-legacy\/2cb3b0006b51db5b27c67~c5_100x100_q60.jpeg","compound_page_url":"https:\/\/h5.pipix.com\/bds_web\/share\/backup\/?item_id=6724930825407699204&app_id=1319&carrier_region=cn&region=cn&language=zh","ios":{"channel":1,"weixin_strategy":4,"moments_strategy":4,"qq_strategy":4,"qzone_strategy":4},"android":{"channel":1,"weixin_strategy":4,"moments_strategy":2,"qq_strategy":4,"qzone_strategy":2},"link_text":"\u5355\u8eab\u72d7\u7684\u539a\u5ea6[\u6253\u8138]\nhttps:\/\/h5.pipix.com\/item\/6724930825407699204?app_id=1319&app=super&timestamp=1568690619&carrier_region=cn&region=cn&language=zh&utm_source=weixin","share_text":"[\u76ae\u76ae\u867e]\u5355\u8eab\u72d7\u7684\u539a\u5ea6[\u6253\u8138]\n","weixin_strategy":4,"moments_strategy":2,"qq_strategy":4,"qzone_strategy":2,"wechat_url":"https:\/\/h5.pipix.com\/item\/6724930825407699204?app_id=1319&app=super&timestamp=1568690619&carrier_region=cn&region=cn&language=zh&utm_source=weixin","moments_url":"https:\/\/h5.pipix.com\/item\/6724930825407699204?app_id=1319&app=super&timestamp=1568690619&carrier_region=cn&region=cn&language=zh&utm_source=weixin_moments","qq_url":"https:\/\/h5.pipix.com\/item\/6724930825407699204?app_id=1319&app=super&timestamp=1568690619&carrier_region=cn&region=cn&language=zh&utm_source=mobile_qq","qzone_url":"https:\/\/h5.pipix.com\/item\/6724930825407699204?app_id=1319&app=super&timestamp=1568690619&carrier_region=cn&region=cn&language=zh&utm_source=qzone","schema":"bds:\/\/cell_detail?item_id=6724930825407699204&type=2&item_cell_type=1","large_image_url":"http:\/\/p3-dy.byteimg.com\/img\/mosaic-legacy\/2cb3b0006b51db5b27c67~c5_500x500_q60.jpeg"}}';
    	// return json_decode($json, 1);
        if (!isset($data['url']) || empty($data['url'])) {
            $this->error = "参数错误";
            return false;
        }
        if (!isset($user['id']) || !$user['id']) {
            $this->error = "请先登录";
            return false;
        }
        // 查询是否有次数
        if ($user['number'] < 1) {
            $this->error = "次数不足，请充值";
            return false;
        }

        // 查询解析记录是否解析过
        if ($result = $this->VideoParseModel->where('video_url', $data['url'])->value('video_text')) {
            // 扣除次数
            $this->UserModel->where('id', $user['id'])->setDec('number');
            return unserialize($result);
        }

        $key = 'ad653b2557d9fecdfba156066c03f431';
        $url = 'http://api.lyfzn.top/shortVideoParse/?key='.$key.'&url='.urlencode($data['url']);
        $json_info = Http::get($url);
        $json_info = json_decode($json_info, 1);
        $json_info['h'] = $json_info['has_times'];
        if ($json_info['status_code'] != 0) {
            $this->error = $json_info['errorMes'];
            // $this->error = $json_info['errorMes'].'.[_c]='.$json_info['h'];
            return false;
        }
        if ($json_info['has_times'] <= 0) {
            $this->error = '非常规报错';
            return false;
        }
        $json = $json_info['result'];
        if (!$json['isVideo']) {
        	$this->error = '当前链接内未检测到视频，当前无法解析';
            return false;
        }
        // 扣除次数
        $this->UserModel->where('id', $user['id'])->setDec('number');
        $ret = [
            'title' => '',
            'author' => '',
            'url' => [],
            'msg' => ''
        ];
        // 是否抖音
        if (strpos($data['url'], 'douyin')) {
            $ret = [
                'title' => $json['aweme_detail']['info']['share_title'],
                'author' => $json['aweme_detail']['nickname'],
                'url' => $json['aweme_detail']['urls']
            ];
        }
        // 是否快手
        if (strpos($data['url'], 'gifshow')) {
            $ret = [
                'title' => $json['user']['description'],
                'author' => $json['user']['name'],
                'url' => [
                    $json['video']['urls'][0]['url'],
                    $json['video']['urls'][1]['url']
                ]
            ];
        }
        // 是否微博
        if (strpos($data['url'], 'weibo')) {
            $ret = [
                'title' => $json['video']['title'],
                'author' => $json['user']['name'],
                'url' => [
                    $json['video']['urls'][0]['stream_url'],
                    $json['video']['urls'][1]['stream_url_hd']
                ]
            ];
        }
        // 是否微视
        if (strpos($data['url'], 'weishi')) {
            $ret = [
                'title' => $json['share_info'][0]['title'],
                'author' => $json['user']['name'],
                'url' => [
                    $json['video']['urls'][0]['url']
                ]
            ];
        }
        // 是否皮皮搞笑
        if (strpos($data['url'], 'ippzone')) {
            $ret = [
                'title' => $json['video']['title'],
                'author' => $json['user']['name'],
                'url' => [
                    $json['video']['urls'][0]['url'],
                    $json['video']['urls'][1]['url'],
                    $json['video']['urls'][2]['url'],
                    $json['video']['urls'][3]['url'],
                    $json['video']['urls'][4]['url'],
                    $json['video']['urls'][5]['url']
                ]
            ];
        }
        // 是否美拍
        if (strpos($data['url'], 'meipai')) {
            $this->error = '接口调试中，暂停使用。';
            return false;
            $ret = [
                'title' => $json['video']['title'],
                'author' => $json['user']['name'],
                'url' => $json['video']['urls']
            ];
        }
        // 是否火山
        if (strpos($data['url'], 'huoshan')) {
            $ret = [
                'title' => $json['video']['title'],
                'author' => $json['user']['name'],
                'url' => $json['video']['urls']
            ];
        }
        // 是否最右
        if (strpos($data['url'], 'izuiyou')) {
            $ret = [
                'title' => $json['video']['title'],
                'author' => $json['user']['name'],
                'url' => [
                    $json['video']['urls'][0]['url'],
                    $json['video']['urls'][1]['url'],
                    $json['video']['urls'][2]['url'],
                    $json['video']['urls'][3]['url'],
                    $json['video']['urls'][4]['url']
                ]
            ];
        }
        // 是否皮皮虾
        if (strpos($data['url'], 'pipix')) {
            $ret = [
                'title' => $json['video']['title'],
                'author' => $json['user']['name'],
                'url' => [
                    $json['video']['urls'][0]['url'],
                    $json['video']['urls'][1]['url']
                ]
            ];
        }
        $_data = [
            'uid' => $user['id'],
            'video_url' => $data['url'],
            'video_text' => serialize($ret),
        ];
        $this->VideoParseModel->isUpdate(false)->save($_data);
        return $ret;
    }

    /**
     * 搜索tab推荐
     */
    public function searchTabs($data, $user)
    {
        $map = [];
        $map[] = ['vod_status', 'eq', 1];
        switch($data['type']){
            case 1:
                $order = 'vod_level desc'; // 根据 推荐值 倒序排序，需手动在cms后台设置视频推荐等级
                break;
            case 2:
                $map[] = ['type_id_1', 'eq', 1]; // 电影类目id
                $order = 'vod_level desc,vod_time_add desc,vod_year desc,vod_hits desc'; // 自动根据推荐值、入库时间、电影上映年份、点击量倒序排序
                break;
            case 3:
                $map[] = ['type_id_1', 'eq', 2]; // 电视剧类目id
                $order = 'vod_level desc,vod_time_add desc,vod_year desc,vod_hits desc'; // 自动根据推荐值、入库时间、电影上映年份、点击量倒序排序
                break;
            default:
                $order = 'vod_level desc'; // 根据 推荐值 倒序排序，需手动在cms后台设置视频推荐等级
                break;
        }
        return $this->VideosModel->searchTabs($map, $order);
    }
}