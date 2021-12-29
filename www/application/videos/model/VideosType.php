<?php
namespace app\videos\model;

use think\Model;

class VideosType extends Model
{
	// 设置当前模型对应的完整数据表名称
    protected $table = 'mac_type';
    
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
     * 核心函数, 将列表数据转化树形结构
     * 使用前提必须是先有父后有子, 即儿子的id必须小于父亲id
     * 列表数据必须安装id从小到大排序
     * @param $lists 原始列表数据
     * @param string $childKey 字段名
     * @return array 返回树形数据
     */
    public function listToTree($childKey = '_c', $is_super = false){
    	$map = [];
    	$map[] = ['type_mid', 'eq', 1];
    	if (!$is_super) {
    		$map[] = ['type_status', 'eq', 1];
    	}
        $lists = $this->field('type_id,type_mid,type_status,type_pid,type_name')->where($map)->select();
        if (!$lists) {
            return false;
        }
        if (count($lists) <= 0) {
            return [];
        }
        $lists = $lists->toArray();
        $map = [];
        $res = [];
        foreach($lists as $id => &$item){
            // 获取出每一条数据的父id
            $pid = &$item['type_pid'];
            // 将每一个item的引用保存到$map中
            $map[$item['type_id']] = &$item;
            // 如果在map中没有设置过他的pid, 说明是根节点, pid为0,
            if(!isset($map[$pid])){
                // 将pid为0的item的引用保存到$res中
                $res[$id] = &$item;
            }else{
                // 如果在map中没有设置过他的pid, 则将该item加入到他父亲的叶子节点中
                $pItem = &$map[$pid];
                $pItem[$childKey][] = &$item;
            }
        }
        
        $res = array_values($res);
        foreach ($res as $k => $v){
        	if(isset($v['_c']) && !empty($v['_c'])){
        		array_unshift($res[$k]['_c'], [
        			'type_id' => -1,
        			'type_mid' => -1,
        			'type_status' => -1,
        			'type_name' => '全部',
        		]);
        	}
        }
        return $res;
    }
}