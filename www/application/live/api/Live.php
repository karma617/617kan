<?php
namespace app\live\api;
use app\nb_api\api\UserInit;
use app\live\server\Live as LiveServer;

class Live extends UserInit
{
    public function initialize() 
    {
        parent::initialize();
        $this->LiveServer = new LiveServer();
    }

    public function getLiveLists()
    {
        $data= $this->params;
        $result = $this->LiveServer->getLiveLists($data, $this->user);
        if (false === $result) {
            return $this->_error($this->LiveServer->getError(),'',30000);
        }
        return $this->_success("获取成功", $result);
    }
}