<?php

namespace app\index\api;
use app\common\controller\Common;

class Index extends Common{
	protected function initialize()
    {
        parent::initialize();
    }
    
    public function index()
    {
        echo '123';
    }
}