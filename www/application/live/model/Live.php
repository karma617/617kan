<?php
namespace app\live\model;

use think\Model;

class Live extends Model
{
    // 定义时间戳字段名
    protected $createTime = 'create_time';
    protected $updateTime = 'update_time';

    // 自动写入时间戳
    protected $autoWriteTimestamp = true;

    /**
     * 列表
     * @param array $map
     * @param int $page
     * @param int $limit
     * @param string $order
     * @param bool|true $field
     * @return mixed
     */
    public function getList ($map = [], $page = 0, $limit = 10, $order = "sort asc", $field = '') {
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
        $ret['list'] = $obj->append(['url_list'])->select()->toArray();
        return $ret;
    }

    public function getUrlListAttr($v, $d)
    {
        $res = [];
        $info = explode("\n", $d['url']);
        foreach ($info as $key => $value) {
            $str = explode('$', trim($value));
            $res[$key]['name'] = isset($str[0]) ? $str[0] : '';
            $res[$key]['url'] = isset($str[1]) ? $str[1] : '';
        }
        return $res;
    }
}