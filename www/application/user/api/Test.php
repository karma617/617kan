<?php
namespace app\user\api;
use app\nb_api\api\UserInit;
use app\user\server\Commission;

class Test extends UserInit
{
    public function initialize() {
        parent::initialize();
    }
    /**
     * 测试佣金计算
     *
     */
    public function test() {
        $data = $this->params;
        $Commission = new Commission;
        $Commission->commission($data['id'], $data['money']);
    }
}
