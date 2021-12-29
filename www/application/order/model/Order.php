<?php
namespace app\order\model;

use think\Model;
use app\goods\model\Goods as GoodsModel;
use app\user\model\User as UserModel;

class Order extends Model
{
    // 定义时间戳字段名
    protected $createTime = 'create_time';
    protected $updateTime = 'update_time';

    // 自动写入时间戳
    protected $autoWriteTimestamp = true;
    
    /**
     * 获取一条订单信息
     *
     * @param string $id
     * @param array $map
     * @return void
     * @author 617 <email：723875993@qq.com>
     */
    public function one($id = '', $map = []) {
        $result = $this->where($map)->get($id);
        if ($result) {
            $res = $result->append(['goods_info', 'user_info'])->toArray();
            return $res;
        }
        return [];
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
    public function getList ($map = [], $page = 0, $limit = 10, $order = "create_time desc", $field = '') {
        $obj = $this->where($map)->field($field)->orderRaw($order);
        $ret = [];
        $ret['code'] = 0;
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
        $ret['list'] = $obj->append(['goods_info', 'user_info'])->select();
        return $ret;
    }
    
    public function getGoodsInfoAttr($v, $d) {
        $GoodsModel = new GoodsModel();
        $info = $GoodsModel->where('id', $d['goods_id'])->find();
        return $info ? $info : [];
    }

    public function getUserInfoAttr($v, $d)
    {
        $UserModel = new UserModel();
        $info = $UserModel->field('username, nick, mobile')->where('id', $d['uid'])->find();
        return $info ? $info : [];
    }
}