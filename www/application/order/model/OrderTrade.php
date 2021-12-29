<?php
namespace app\order\model;

use think\Model;

class OrderTrade extends Model
{
    // 定义时间戳字段名
    protected $createTime = 'create_time';
    protected $updateTime = 'update_time';

    // 自动写入时间戳
    protected $autoWriteTimestamp = true;

    public function trade($data) {
        // 如果已有，全部改为取消
        if ($this->where('order_sn', $data['order_no'])->where('status', 1)->find()) {
            $this->where('order_sn', $data['order_no'])->where('status', 1)->setField('status', -1);
        }
        if ($this->isUpdate(false)->save($data)) {
            return $this->id;
        }
        return false;
    }
}