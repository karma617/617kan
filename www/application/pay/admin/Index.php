<?php
// +----------------------------------------------------------------------
// | HisiPHP框架[基于ThinkPHP5开发]
// +----------------------------------------------------------------------
// | Copyright (c) 2016-2021 http://www.hisiphp.com
// +----------------------------------------------------------------------
// | HisiPHP提供个人非商业用途免费使用，商业需授权。
// +----------------------------------------------------------------------
// | Author: 橘子俊 <364666827@qq.com>，开发者QQ群：50304283
// +----------------------------------------------------------------------
namespace app\pay\admin;
use app\system\admin\Admin;
use app\pay\model\PayPayment as PaymentModel;
use app\common\model\SystemAnnex as AnnexModel;
use hisi\Dir;
use Env;

class Index extends Admin
{
    protected $hisiModel = 'PayPayment';

    protected function initialize()
    {
        parent::initialize();
        $this->driverPath = Env::get('app_path').'pay/driver/';
    }
    /**
     * 支付方式管理
     * @author 橘子俊 <364666827@qq.com>
     * @return mixed
     */
    public function index()
    {
        $rows = PaymentModel::order('sort asc')->column('code,id,title,applies,intro,status,sort', 'code');
        $dlist = Dir::getList($this->driverPath);
        $payment = [];
        foreach ($dlist as $k => $v) {
            if (!file_exists($this->driverPath.$v.'/config.xml') ||
                $v == 'Factory.php' || 
                $v == 'PayAbstract.php') {
                continue;
            }

            $xml = file_get_contents($this->driverPath.$v.'/config.xml');
            $config             = xml2array($xml);
            $config['install']  = 0;
            $config['id']       = 0;
            $config['sort']     = 100;

            if (array_key_exists($v, $rows)) {
                $config['status']   = $rows[$v]['status'];
                $config['install']  = 1;
                $config['id']       = $rows[$v]['id'];
                $config['sort']     = $rows[$v]['sort'];
            }

            if (isset($payment[$config['sort']])) {
                $payment[$k] = $config;
            } else {
                $payment[$k] = $config;
            }
        }
        
        ksort($payment);

        $tabData = [
            'menu' => [
                ['title' => '在线支付', 'url' => 'pay/index/index'],
                ['title' => '支付日志', 'url' => 'pay/logs/index'],
            ],
            'current' => url(''),
        ];

        $this->assign('hisiTabType', 3);
        $this->assign('hisiTabData', $tabData);
        $this->assign('data', $payment);
        return $this->fetch();
    }

    /**
     * 安装支付方式
     * @author 橘子俊 <364666827@qq.com>
     * @return mixed
     */
    public function install($code = '')
    {
        if (empty($code)) {
            return $this->error('参数传递错误！');
        }

        $map        = [];
        $map['code']= $code;
        $xml        = file_get_contents($this->driverPath.$code.'/config.xml');
        $xmlData    = xml2array($xml);

        if ($this->request->isPost()) {

            if (PaymentModel::where($map)->find()) {
                return $this->error('请勿重复安装');
            }

            $config = $this->request->post();
            unset($config['code']);
            $sqlmap             = [];
            $sqlmap['code']     = $xmlData['code'];
            $sqlmap['title']    = $xmlData['title'];
            $sqlmap['intro']    = $xmlData['intro'];
            $sqlmap['applies']  = $xmlData['applies'];
            $sqlmap['sort']     = 100;
            $sqlmap['status']   = 1;
            $sqlmap['config']   = json_encode($config, 1);

            if (!PaymentModel::create($sqlmap)) {
                return $this->error('安装失败');
            }

            return $this->success('安装成功', url('index'));
        }

        if (PaymentModel::where($map)->find()) {

            $this->redirect(url('config?code='.$code));

        }

        foreach ($xmlData['config'] as &$v) {
            if (isset($v['options'])) {
                $v['options'] = parse_attr($v['options']);
            }
        }
        
        $this->assign('data', $xmlData);
        return $this->fetch();
    }

    /**
     * 卸载支付方式
     * @author 橘子俊 <364666827@qq.com>
     * @return mixed
     */
    public function uninstall($code = '')
    {
        if (empty($code)) {
            return $this->error('参数传递错误');
        }

        $map = [];
        $map['code'] = $code;
        $title = PaymentModel::where($map)->value('title');

        if (!PaymentModel::where($map)->delete()) {
            return $this->error($title.' 卸载失败');
        }

        return $this->success($title.' 卸载成功');
    }

    /**
     * 支付方式配置
     * @author 橘子俊 <364666827@qq.com>
     * @return mixed
     */
    public function config($code = '')
    {
        if (empty($code)) {
            return $this->error('参数传递错误！');
        }
        $map = [];
        $map['code'] = $code;
        $row = PaymentModel::where($map)->find();

        if (!$row) {
            return $this->error($code . ' 支付方式不存在！');
        }

        if ($this->request->isPost()) {
            $config = $this->request->post();
            unset($config['code']);
            foreach ($config as $k => &$v) {

                if (!is_array($v) && strstr($v, '*')) {

                    $arr = json_decode($row['config'], 1);
                    $config[$k] = $arr[$k];

                }

            }
            $sqlmap = [];
            $sqlmap['id'] = $row['id'];
            $sqlmap['config'] = json_encode($config, 1);

            if (!PaymentModel::update($sqlmap)) {

                return $this->error('保存失败');

            }

            // 更新缓存数据
            PaymentModel::lists('', true);
            return $this->success('保存成功', url('index'));
        }

        $config     = json_decode($row['config'], 1);
        $xml        = file_get_contents($this->driverPath.$code.'/config.xml');
        $xmlData    = xml2array($xml);

        foreach ($xmlData['config'] as $k => &$v) {

            if (isset($config[$k]) && isset($v['sensitive'])) {

                $strlen = (int)strlen($config[$k])/2;
                $v['value'] = hide_str($config[$k], (int)($strlen/2), (strlen($config[$k])/2));

            } else if (isset($config[$k])) {

                $v['value'] = $config[$k];

            }

            if (isset($v['options'])) {
                $v['options'] = parse_attr($v['options']);
            }

        }

        $this->assign('data', $xmlData);
        return $this->fetch();
    }

    /**
     * 附件上传
     * @param string $from 来源
     * @param string $group 附件分组,默认sys[系统]，模块格式：m_模块名，插件：p_插件名
     * @param string $water 水印，参数为空默认调用系统配置，no直接关闭水印，image 图片水印，text文字水印
     * @param string $thumb 缩略图，参数为空默认调用系统配置，no直接关闭缩略图，如需生成 500x500 的缩略图，则 500x500多个规格请用";"隔开
     * @param string $thumb_type 缩略图方式
     * @param string $input 文件表单字段名
     * @author 橘子俊 <364666827@qq.com>
     * @return json
     */
    public function upload($from = 'input', $group = 'protect', $water = '', $thumb = '', $thumb_type = '', $input = 'file')
    {
        return json(AnnexModel::upload($from, $group, $water, $thumb, $thumb_type, $input));
    }
}