<?php
namespace app\banner\model;

use think\Model;

class Ad extends Model
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
    public function getList ($map = [], $page = 0, $limit = 10, $order = "create_time desc", $field = true) {
        $obj = $this->where($map)->field($field)->orderRaw($order);
        $ret = [];
        $ret['count'] = (int)$obj->count();
        $ret['page'] = (int)$page;
        $ret['limit'] = (int)$limit;
        if($page) {
            $obj = $obj->page($page)->limit($limit);
        }
        $obj = $obj->select();
        if (!$obj) return [];
        $ret['list'] = $obj->toArray();
        return $ret;
    }
    /**
     * 随机取一条
     * type 3 弹窗广告
     * 4 常驻广告
     * @param array $map
     * @return void
     * @author 617 <email：723875993@qq.com>
     */
    public function getOne($type = 0, $min = 1)
    {
        $map = [];
        $map[] = ['status', 'eq', 1];
        $map[] = ['type', 'eq', $type];
        
        if ($min == 1) {
            $ret = $this->where($map)->orderRaw('rand()')->select();
        } else {
            $ret = $this->where($map)->orderRaw('rand()')->limit(0, $min)->select();
        }
        $ret = $ret->toArray();
        return !empty($ret) ? $ret[0] : [];
        // $count = $this->where($map)->count();
        // if ($count > $min && $min == 1) {
        //     // 随机取一条
        //     $sql = 'SELECT * FROM `'.config('database.prefix').'ad` 
        //         AS t1 JOIN (SELECT ROUND(RAND() * ((SELECT MAX(id) FROM `'.config('database.prefix').'ad`)-(SELECT MIN(id) FROM `'.config('database.prefix').'ad`))+(SELECT MIN(id) FROM `'.config('database.prefix').'ad`)) AS id) AS t2
        //         WHERE t1.id >= t2.id
        //         AND status = 1 AND type = '.$type.'
        //         ORDER BY t1.id LIMIT 1';
        //     $r = $this->query($sql);
        //     return $r ? $r[0] : [];
        // }else{
        //     if ($count == 0) {
        //         return [];
        //     }
        //     if ($min > 1) {
        //         // 随机取一条
        //         $sql = 'SELECT * FROM `'.config('database.prefix').'ad` 
        //             AS t1 JOIN (SELECT ROUND(RAND() * ((SELECT MAX(id) FROM `'.config('database.prefix').'ad`)-(SELECT MIN(id) FROM `'.config('database.prefix').'ad`))+(SELECT MIN(id) FROM `'.config('database.prefix').'ad`)) AS id) AS t2
        //             WHERE t1.id >= t2.id
        //             AND status = 1 AND type = '.$type.'
        //             ORDER BY t1.id LIMIT ' . $min;
        //         $r = $this->query($sql);
        //         return $r ? $r : [];
        //         // return $this->where($map)->limit($min)->select();
        //     }
        //     return $this->where($map)->find();
        // }
    }
}