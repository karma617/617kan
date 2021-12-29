<?php
namespace app\attachment\api;

use app\nb_api\api\ApiInit;
use app\attachment\server\Attachment as AttachmentService;

class Attachment extends ApiInit{

    public function initialize() {
        $this->check_auth = false;
        parent::initialize();
        $this->AttachmentService = new AttachmentService();
    }
    /**
     * 上传图片
     *
     * @return void
     * @author 617 <email：723875993@qq.com>
     */
    public function uploadImg() {
        $data = $this->params;
        $result = $this->AttachmentService->uploadImg($data);
        if (false === $result) {
            return $this->_error($this->AttachmentService->getError());
        }
        return $this->_success("上传成功", $result);
    }
}