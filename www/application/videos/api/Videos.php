<?php
namespace app\videos\api;
use app\nb_api\api\UserInit;
use app\videos\server\Videos as VideosServer;

class Videos extends UserInit
{
    public function initialize() 
    {
        $this->check_login = false;
        parent::initialize();
        $this->VideosServer = new VideosServer();
    }

    /**
     * 获取视频分类列表
     *
     * @return void
     * @author 617 <email：723875993@qq.com>
     */
    public function getVideoTypeLists()
    {
        $data= $this->params;
        $result = $this->VideosServer->getVideoTypeLists($data, $this->user);
        if (false === $result) {
            return $this->_error($this->VideosServer->getError(),'',20001);
        }
        return $this->_success("", $result);
    }
    /**
     * 获取视频列表
     *
     * @return void
     * @author 617 <email：723875993@qq.com>
     */
    public function getVideoLists()
    {
        $data= $this->params;
        $result = $this->VideosServer->getVideoLists($data, $this->user);
        if (false === $result) {
            return $this->_error($this->VideosServer->getError(),'',20002);
        }
        return $this->_success("", $result);
    }
    /**
     * 获取视频地区列表
     *
     * @return void
     * @author 617 <email：723875993@qq.com>
     */
    public function getVideoArea()
    {
        $data= $this->params;
        $result = $this->VideosServer->getVideoArea($data, $this->user);
        if (false === $result) {
            return $this->_error($this->VideosServer->getError(),'',20003);
        }
        return $this->_success("", $result);
    }
    /**
     * 获取视频类别列表
     *
     * @return void
     * @author 617 <email：723875993@qq.com>
     */
    public function getVideoClass()
    {
        $data= $this->params;
        $result = $this->VideosServer->getVideoClass($data, $this->user);
        if (false === $result) {
            return $this->_error($this->VideosServer->getError(),'',20004);
        }
        return $this->_success("", $result);
    }
    /**
     * 获取视频发型年份列表
     *
     * @return void
     * @author 617 <email：723875993@qq.com>
     */
    public function getVideoYear()
    {
        $data= $this->params;
        $result = $this->VideosServer->getVideoYear($data, $this->user);
        if (false === $result) {
            return $this->_error($this->VideosServer->getError(),'',20005);
        }
        return $this->_success("", $result);
    }
    /**
     * 获取视频详情
     *
     * @return void
     * @author 617 <email：723875993@qq.com>
     */
    public function getVideoDetail()
    {
        $data= $this->params;
        $result = $this->VideosServer->getVideoDetail($data, $this->user);
        if (false === $result) {
            return $this->_error($this->VideosServer->getError(),'',20006);
        }
        return $this->_success("", $result);
    }
    /**
     * 搜索关键词
     *
     * @return void
     * @author 617 <email：723875993@qq.com>
     */
    public function searchKeywords()
    {
        $data= $this->params;
        $result = $this->VideosServer->searchKeywords($data, $this->user);
        if (false === $result) {
            return $this->_error($this->VideosServer->getError(),'',20007);
        }
        return $this->_success("", $result);
    }
    /**
     * 获取首页列表
     *
     * @return void
     * @author 617 <email：723875993@qq.com>
     */
    public function getHomeVideoLists()
    {
        $data= $this->params;
        $result = $this->VideosServer->getHomeVideoLists($data, $this->user);
        if (false === $result) {
            return $this->_error($this->VideosServer->getError(),'',20008);
        }
        return $this->_success("", $result);
    }
    /**
     * 获取搜索页热门关键词
     *
     * @return void
     * @author 617 <email：723875993@qq.com>
     */
    public function hotKeyWords()
    {
        $data= $this->params;
        $result = $this->VideosServer->hotKeyWords($data, $this->user);
        if (false === $result) {
            return $this->_error($this->VideosServer->getError(),'',20009);
        }
        return $this->_success("", $result);
    }
    /**
     * 获取分类视频列表
     *
     * @return void
     * @author 617 <email：723875993@qq.com>
     */
    public function getTypeVideoLists()
    {
        $data= $this->params;
        $result = $this->VideosServer->getTypeVideoLists($data, $this->user);
        if (false === $result) {
            return $this->_error($this->VideosServer->getError(),'',20010);
        }
        return $this->_success("", $result);
    }
    /**
     * 处理视频地址（bilibili）
     *
     * @return void
     * @author 617 <email：723875993@qq.com>
     */
    public function formatUrl()
    {
        $data= $this->params;
        $result = $this->VideosServer->formatUrl($data, $this->user);
        if (false === $result) {
            return $this->_error($this->VideosServer->getError(),'',20011);
        }
        return $this->_success("", $result);
    }
    /**
     * 发送弹幕
     *
     * @return void
     * @author 617 <email：723875993@qq.com>
     */
    public function sendDanme()
    {
        $data= $this->params;
        $result = $this->VideosServer->sendDanme($data, $this->user);
        if (false === $result) {
            return $this->_error($this->VideosServer->getError(),'',20012);
        }
        return $this->_success("", $result);
    }
    /**
     * vip解析
     *
     * @return void
     * @author 617 <email：723875993@qq.com>
     */
    public function vip() {
        $data = $this->params;
        $result = $this->VideosServer->vip($data, $this->user);
        if (false === $result) {
            return $this->_error($this->VideosServer->getError(),'',20013);
        }
        return $this->_success('获取成功',$result);
    }
    /**
     * 下载视频
     *
     * @return void
     * @author 617 <email：723875993@qq.com>
     */
    public function downloadVideo()
    {
        $data = $this->params;
        $result = $this->VideosServer->downloadVideo($data, $this->user);
        if (false === $result) {
            return $this->_error($this->VideosServer->getError(),'',20014);
        }
        return $this->_success('获取成功',$result);
    }
    /**
     * 添加喜欢
     *
     * @return void
     * @author 617 <email：723875993@qq.com>
     */
    public function addLike()
    {
        $data = $this->params;
        $result = $this->VideosServer->addLike($data, $this->user);
        if (false === $result) {
            return $this->_error($this->VideosServer->getError(),'',20015);
        }
        return $this->_success($this->VideosServer->getError(),$result);
    }
    /**
     * 获取视频弹幕列表
     *
     * @return void
     * @author 617 <email：723875993@qq.com>
     */
    public function getDanmuList()
    {
        $data= $this->params;
        $result = $this->VideosServer->getDanmuList($data, $this->user);
        if (false === $result) {
            return $this->_error($this->VideosServer->getError(),'',20016);
        }
        return $this->_success("", $result);
    }
    /**
     * 搜索tab列表
     *
     * @return void
     * @author 617 <email：723875993@qq.com>
     */
    public function searchTabs()
    {
        $data= $this->params;
        $result = $this->VideosServer->searchTabs($data, $this->user);
        if (false === $result) {
            return $this->_error($this->VideosServer->getError(),'',20017);
        }
        return $this->_success("", $result);
    }
}