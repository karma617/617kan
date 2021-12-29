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
use plugins\sms\model\PluginsSmsTemplate as TemplateModel;
use plugins\sms\model\PluginsSmsTemplateIndex as IndexModel;
use think\Db;

/**
 * 短信模板控制器
 * @package plugins\sms\admin
 */
class Template extends Admin
{
    /**
     * 短信模板管理
     * @return mixed
     */
    public function index()
    {
        if ($this->request->isPost()) {
            $data = $this->request->post();

            $model = new TemplateModel();

            $tplData            = [];
            $tplData['title']   = $data['title'];
            $tplData['alias']   = $data['alias'];
            $tplData['example'] = $data['example'];
            $tplData['id']      = $data['id'];
            $tplData            = array_filter($tplData);

            $result = $this->validate($tplData, '\plugins\sms\validate\PluginsSmsTemplate');
            if ($result !== true) {
                return $this->error($result);
            }

            if (isset($tplData['id'])) {
                $result = $model->save($tplData, ['id' => $tplData['id']]);
            } else {
                $result = $model->save($tplData);
            }
            
            if ($result === false) {
                return $this->error($model->getError());
            }
        
            // 保存模板索引数据
            if (isset($data['gateways'])) {

                foreach ($data['gateways'] as $k => $v) {
                    
                    $sqlmap                     = [];
                    $sqlmap['id']               = $v['id'];
                    $sqlmap['gateway_template'] = $v['value'];
                    $sqlmap['alias']            = $data['alias'];
                    $sqlmap['gateway']          = $k;
                    $sqlmap                     = array_filter($sqlmap);

                    if (isset($sqlmap['id'])) {
                        IndexModel::update($sqlmap, ['id' => $sqlmap['id']]);
                    } else {
                        IndexModel::create($sqlmap);
                    }

                }

            }

            return $this->success('保存成功', plugins_url());
        }

        $gateways = SmsModel::where("status", 1)->where('bind_template', 1)->column('gateway,title');
        if (!$gateways) {
            return $this->error('没有可配置的数据');
        }

        $templates = TemplateModel::with('hasIndexs')->select();

        $this->assign('templates', $templates);
        $this->assign('gateways', $gateways);

        return $this->fetch();
    }

    /**
     * 删除短信模板
     * @return mixed
     */
    public function del()
    {
        $id = get_num();

        $template = TemplateModel::get($id);
        if (!$template) {
            return $this->error('记录不存在');
        }
        
        // 启动事务
        Db::startTrans();
        try{
            Db::name('plugins_sms_template')->delete(['id' => $id]);
            Db::name('plugins_sms_template_index')->delete(['alias' => $template['alias']]);
            // 提交事务
            Db::commit(); 
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
            return $this->error('删除失败');
        }

        return $this->success('删除成功');
    }
}