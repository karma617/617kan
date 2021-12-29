<?php

namespace app\news\api;
use app\nb_api\api\ApiInit;
use app\news\server\News as NewsService;

class Index extends ApiInit{
	protected function initialize()
    {
        parent::initialize();
        $this->NewsService = new NewsService();
    }
    
    public function lists()
    {
        $data = $this->params;
        $result = $this->NewsService->getNewsLists($data);
        if (false === $result) {
            return $this->_error($this->BannerService->getError(),'',8000);
        }
        return $this->_success('获取成功',$result);
    }

    public function detail()
    {
        $data = $this->params;
        $result = $this->NewsService->getNewsDetail($data);
        if (false === $result) {
            return $this->_error($this->BannerService->getError(),'',8001);
        }
        return $this->_success('获取成功',$result);
    }
}