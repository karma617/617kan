<?php
namespace app\attachment\server;

use app\common\server\Service;
use app\attachment\model\Attachment as AttachmentModel;
use app\attachment\validate\Attachment as AttachmentValidate;
use Env;

class Attachment extends Service{

    public function initialize() {
        parent::initialize();
        $this->AttachmentModel = new AttachmentModel();
        $this->AttachmentValidate = new AttachmentValidate();
    }
    /**
     * 上传图片
     *返回图片id 图片保存路径
     * @param [type] $data
     * @return void
     * @author 617 <email：723875993@qq.com>
     */
    public function uploadImg($data) {
        if($this->AttachmentValidate->scene('default')->check($data) !== true) {
            $this->error = $this->AttachmentValidate->getError();
            return false;
        }
        $save_file = "/public/upload/{$data['type']}/";
        $file = base64_image_content($data['code'], $save_file);
        if (false === $file) {
            $this->error = '图片保存失败';
            return false;
        }
        $true_file = Env::get('root_path').$file;
        $md5 = md5_file(Env::get('root_path').$file);
        $files = str_replace('/public', '', $file);
        $result = $this->AttachmentModel->uploadImg($files, $md5, $true_file, $data['type']);
        if (false === $result) {
            $this->error = $this->AttachmentModel->getError();
            return false;
        }
        return $result;
    }

}