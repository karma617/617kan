<?php
namespace app\user\model;

use think\Model;

/**
 * 会员分组模型
 * @package app\user\model
 */
class UserToken extends Model
{
    // 定义时间戳字段名
    protected $createTime = 'create_time';
    protected $updateTime = 'update_time';

    // 自动写入时间戳
    protected $autoWriteTimestamp = true;
}