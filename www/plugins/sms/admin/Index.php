<?php
// +----------------------------------------------------------------------
// | HisiPHP框架[基于ThinkPHP5开发]
// +----------------------------------------------------------------------
// | Copyright (c) 2016-2018 http://www.hisiphp.com
// +----------------------------------------------------------------------
// | HisiPHP承诺基础框架永久免费开源，您可用于学习和商用，但必须保留软件版权信息。
// +----------------------------------------------------------------------
// | Author: 橘子俊 <364666827@qq.com>，开发者QQ群：50304283
// +----------------------------------------------------------------------

namespace plugins\sms\admin;

use app\system\admin\Admin;
use plugins\sms\model\PluginsSms as SmsModel;
use hisi\Dir;
use Env;

/**
 * 短信平台控制器
 * @package plugins\sms\admin
 */
class Index extends Admin
{
    protected function initialize()
    {
        parent::initialize();
        $this->driver_path = Env::get('root_path').'plugins/sms/driver/';
    }

    /**
     * 短信平台管理
     * @return mixed
     */
    public function index()
    {
        $rows = SmsModel::column('gateway,id,title,status,default', 'gateway');
        $dlist = Dir::getList($this->driver_path);
        $dataList = [];

        foreach ($dlist as $k => $v) {

            if (!is_file($this->driver_path.$v.'/config.xml')) {
                continue;
            }

            $xml    = file_get_contents($this->driver_path.$v.'/config.xml');
            
            $config             = xml2array($xml);
            $config['install']  = 0;
            $config['id']       = 0;
            $config['status']   = 0;

            if (array_key_exists($v, $rows)) {
                $config['status']   = $rows[$v]['status'];
                $config['install']  = 1;
                $config['id']       = $rows[$v]['id'];
                $config['default']  = $rows[$v]['default'];
            }

            $dataList[$k] = $config;
        }

        $this->assign('dataList', $dataList);
        return $this->fetch();
    }

    /**
     * 网关配置
     * @author 橘子俊 <364666827@qq.com>
     * @return mixed
     */
    public function config()
    {
        $gateway = input('param.gateway');
        if (empty($gateway)) {
            return $this->error('参数传递错误！');
        }

        $map            = [];
        $map['gateway'] = $gateway;

        $row = SmsModel::where($map)->find();
        $xml = file_get_contents($this->driver_path.$gateway.'/config.xml');
        $xmlData = xml2array($xml);

        if ($this->request->isPost()) {

            $config = $this->request->post();

            foreach ($xmlData['config'] as $k => $v) {

                if (isset($config[$k]) && isset($v['sensitive']) && strpos($config[$k], '*')) {
                    $config[$k] = $row['config'][$k];
                }

            }

            unset($config['gateway']);

            $sqlmap = [];
            $sqlmap['gateway']  = $xmlData['gateway'];
            $sqlmap['title']    = $xmlData['title'];
            $sqlmap['status']   = 1;
            $sqlmap['config']   = json_encode($config, 1);
            
            if ((int)$xmlData['bind_template'] == 1) {
                $sqlmap['bind_template'] = 1;
            }

            if ($row) {

                $sqlmap['id'] = $row['id'];

                if (!SmsModel::update($sqlmap)) {

                    return $this->error('配置失败！');

                }

            } else {

                if (!SmsModel::create($sqlmap)) {
                    return $this->error('配置失败！');
                }

            }

            // 更新缓存
            SmsModel::getConfig('', true);
            return $this->success('配置成功', plugins_url('sms/index/index'));
        }

        if ($row && $row['config']) {
            $config = $row['config'];

            foreach ($xmlData['config'] as $k => &$v) {

                if (isset($config[$k]) && isset($v['sensitive'])) {

                    $strlen = (int)strlen($config[$k])/2;
                    $v['value'] = hide_str($config[$k], (int)($strlen/2), (strlen($config[$k])/2));

                } else if (isset($config[$k])) {

                    $v['value'] = $config[$k];

                }

            }

        }

        $this->assign('dataInfo', $xmlData);
        return $this->fetch();
    }
    
    /**
     * 设置状态
     * @author 橘子俊 <364666827@qq.com>
     * @return mixed
     */
    public function status()
    {

        $id = get_num();
        $val = input('param.val/d', 0);

        if (!SmsModel::where('id', $id)->setField('status', $val)) {
            return $this->error('设置失败');
        }

        // 更新缓存
        SmsModel::getConfig('', true);

        return $this->success('设置成功');
    }

    /**
     * 设置默认
     * @author 橘子俊 <364666827@qq.com>
     * @return mixed
     */
    public function def()
    {
        $id = get_num();
        
        SmsModel::where('default', 1)->setField('default', 0);

        if (!SmsModel::where('id', $id)->setField('default', 1)) {
            return $this->error('设置失败');
        }

        // 更新缓存
        SmsModel::getConfig('', true);
        
        return $this->success('设置成功');
    }
}