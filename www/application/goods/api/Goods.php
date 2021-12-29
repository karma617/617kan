<?php
namespace app\goods\api;
use app\nb_api\api\UserInit;
use app\goods\server\Goods as GoodsServer;

class Goods extends UserInit
{
    public function initialize() 
    {
        parent::initialize();
        $this->GoodsServer = new GoodsServer();
    }

    public function getGoodsLists()
    {
        $data= $this->params;
        $result = $this->GoodsServer->getGoodsLists($data, $this->user);
        if (false === $result) {
            return $this->_error($this->GoodsServer->getError(),'',30000);
        }
        return $this->_success("获取成功", $result);
    }
}