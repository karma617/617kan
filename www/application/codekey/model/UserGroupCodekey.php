<?php
namespace app\codekey\model;

use think\Model;

class UserGroupCodekey extends Model
{

    // 定义时间戳字段名
    protected $createTime = 'create_time';
    protected $updateTime = 'update_time';

    // 自动写入时间戳
    protected $autoWriteTimestamp = true;
    
    protected $pk = 'id';

    public function belongGroup()
    {
        return $this->belongsTo('app\user\model\UserGroup', 'group_id', 'id');
    }

    public function getList ($map = [], $page = 0, $limit = 10, $order = "status asc, id desc", $field = '') {
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
        $ret['list'] = $obj->append(['belong_group', 'userinfo'])->select()->toArray();
        return $ret;
    }

    public function getUserinfoAttr($v, $d)
    {
        if ($d['user_id'] > 0) {
            $user = (new \app\user\model\User)->where('id', $d['user_id'])->find();
            return $user ? $user : '';
        }
        return '';
    }
}