<?php
namespace app\attachment\model;

use think\Model;

class Attachment extends Model
{
    // 定义时间戳字段名
    protected $createTime = 'create_time';
    protected $updateTime = 'update_time';

    // 自动写入时间戳
    protected $autoWriteTimestamp = true;

    public function uploadImg($file, $md5, $true_file, $type) {
        // 如果已经上传过
        if (($info = $this->field('id,savepath')->where('md5', $md5)->find())) {
            @unlink($true_file);
            return ['id'=>(int)$info['id'], 'file' => $info['savepath']];
        }
        // 保存到数据库
        if ($this->save(['savepath' => $file, 'md5' => $md5, 'type' => $type])) {
            return ['id'=>(int)$this->id, 'file' => $file];
        }
        $this->error = '文件上传失败';
        return false;
    }
}