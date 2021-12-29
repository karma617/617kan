<?php
namespace app\common\admin;

use app\system\admin\Admin;

class AdminCommon extends Admin{

    public function initialize() {
        parent::initialize();
        $this->params = input();
    }
    
}