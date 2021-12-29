<?php
namespace app\videos\model;

use think\Model;

class Videos extends Model
{
    // 设置当前模型对应的完整数据表名称
    protected $table = 'mac_vod';
    
    // 设置当前模型的数据库连接
    protected $connection = [
        // 数据库类型
        'type'        => 'mysql',
        // 服务器地址
        'hostname'    => '127.0.0.1',
        // 数据库名
        'database'    => 'mac_gekunyan_com',
        // 数据库用户名
        'username'    => 'mac_gekunyan_com',
        // 数据库密码
        'password'    => 'sE3c8yfiYs4AL3TG',
        // 数据库编码默认采用utf8
        'charset'     => 'utf8',
        // 数据库表前缀
        'prefix'      => 'mac_',
        // 数据库调试模式
        'debug'       => false,
    ];
    /**
     * 列表
     * @param array $map
     * @param int $page
     * @param int $limit
     * @param string $order
     * @param bool|true $field
     * @return mixed
     */
    public function getList ($map = [], $page = 1, $limit = 10, $order = "vod_year desc", $field = '') {
        if (config('commoncfg.show_xx') == 1) {
            $order = "vod_year asc";
        }
        $field = $field ? $field : 'vod_id,type_id,type_id_1,vod_name,vod_score,vod_status,vod_class,vod_letter,vod_pic,vod_actor,vod_director,vod_blurb,vod_remarks,vod_area,vod_lang,vod_year,vod_content,vod_play_from,vod_play_url';
        $obj = $this->where($map)->field($field)->orderRaw($order);
        $ret = [];
        $ret['count'] = (int)$obj->count();
        $ret['page'] = (int)$page;
        $ret['limit'] = (int)$limit;
        if($page) {
            $obj = $obj->page($page)->limit($limit);
        }
        if ($ret['count'] <= 0) {
            $ret['list'] = [];
            return $ret;
        }
        $ret['list'] = $obj->select()->toArray();
        return $ret;
    }

    public function getVodPicAttr($v)
    {
        return str_replace('mac://', 'http://', $v);
    }

    public function getVideoFieldGroup($field = '')
    {
        $list = $this->field($field)->group($field)->select();
        if (count($list) <= 0) return [];
        return $list->toArray();
    }

    public function getVideoDetail($vid = '')
    {
        $map = [];
        $map[] = is_numeric($vid) ? ['vod_id', 'eq', $vid] : ['vod_name', 'eq', $vid];
        $info = $this->cache('video_detail_' . $vid)->where($map)->find();
        if (!$info) {
            return false;
        }
        return $info->toArray();
    }

    public function searchKeywords($map = [])
    {
        $field = 'vod_id,vod_name,vod_status';
        $info = $this->field($field)->where($map)->limit(0,20)->order('vod_year desc, vod_hits_week desc')->select();
        if (count($info) <= 0) {
            return [];
        }
        return $info->toArray();
    }

}