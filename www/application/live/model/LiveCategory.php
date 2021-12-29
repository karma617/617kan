<?php

namespace app\live\model;

use think\Model;

class LiveCategory extends Model
{

    // 定义时间戳字段名
    protected $createTime = 'create_time';
    protected $updateTime = 'update_time';

    // 自动写入时间戳
    protected $autoWriteTimestamp = true;
    /**
     * 核心函数, 将列表数据转化树形结构
     * 使用前提必须是先有父后有子, 即儿子的id必须小于父亲id
     * 列表数据必须安装id从小到大排序
     * @param $lists 原始列表数据
     * @param string $childKey 字段名
     * @return array 返回树形数据
     */
    public function listToTree($childKey = '_c')
    {
        $lists = $this->field('id,pid,name,status,is_top')
            ->where('status', 1)
            ->orderField('is_top', [1], 'desc')
            ->select();
        if (!$lists) {
            return false;
        }
        if (count($lists) <= 0) {
            return [];
        }
        $lists = $lists->toArray();
        $map = [];
        $res = [];
        foreach ($lists as $id => &$item) {
            // 获取出每一条数据的父id
            $pid = &$item['pid'];
            // 将每一个item的引用保存到$map中
            $map[$item['id']] = &$item;
            // 如果在map中没有设置过他的pid, 说明是根节点, pid为0,
            if (!isset($map[$pid])) {
                // 将pid为0的item的引用保存到$res中
                $res[$id] = &$item;
            } else {
                // 如果在map中没有设置过他的pid, 则将该item加入到他父亲的叶子节点中
                $pItem = &$map[$pid];
                $pItem[$childKey][] = &$item;
            }
        }
        return array_values($res);
    }

    /**
     * 列表
     * @param array $map
     * @param int $page
     * @param int $limit
     * @param string $order
     * @param bool|true $field
     * @return mixed
     */
    public function getList ($map = [], $page = 0, $limit = 10, $order = "create_time asc", $field = '') {
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
}
