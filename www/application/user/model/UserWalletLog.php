<?php
namespace app\user\model;
use app\user\model\User;
use think\Model;

/**
 * 会员分组模型
 * @package app\user\model
 */
class UserWalletLog extends Model
{
    // 定义时间戳字段名
    protected $createTime = 'create_time';
    protected $updateTime = 'update_time';

    // 自动写入时间戳
    protected $autoWriteTimestamp = true;

    //记录日志并增加余额
    public function log($uid, $money = 0, $from_id = 0, $type = 1,  $msg = '') {
        $arr = [];
        $arr['uid'] = $uid;
        $arr['from_id'] = $from_id;
        $arr['type'] = $type;
        $arr['value'] = $money;
        $arr['msg'] = $msg;
        $arr['start'] = User::where(['id' => $uid])->value('money');
        User::where(['id' => $uid])->setField('money', $money);
        $arr['end'] = User::where(['id' => $uid])->value('money');
        self::create($arr);
    }
}