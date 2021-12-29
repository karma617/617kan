<?php
namespace app\pay\server;

use app\common\server\Service;
use app\pay\validate\Pay as PayValidate;
use app\order\model\Order as OrderModel;
use app\order\server\OrderTrade as OrderTradeService;
use hisi\Http;

class Pay extends Service{

    public function initialize() {
        parent::initialize();
        $this->PayValidate = new PayValidate();
        $this->OrderModel = new OrderModel();
        $this->OrderTradeService = new OrderTradeService();
    }
    /**
     * 去支付
     *
     * @param [type] $data
     * @param [type] $user
     * @return void
     * @author 617 <email：723875993@qq.com>
     */
    public function gopay($data, $user) {
        if($this->PayValidate->scene('gopay')->check($data) !== true) {
            $this->error = $this->PayValidate->getError();
            return false;
        }
        switch ($data['pay_code']) {
            case 'alipay_app':  // 支付宝app
                $url = $this->_build_pay_params($data, 'alipay_qr');
                if ($url) {
                    $result = Http::get($url);
                    $result = json_decode($result, true);
                    return $result;
                }
                $this->error = $this->getError();
                return false;
            case 'wechat_app':  // 微信app
                $url = $this->_build_pay_params($data, 'personal');
                if ($url) {
                    $result = Http::get($url);
                    $result = json_decode($result, true);
                    if (isset($result['json_data']['code']) && $result['json_data']['code'] == -1) {
                    	$this->error = $result['json_data']['msg'];
                		return false;
                    }
                    return $result;
                }
                $this->error = $this->getError();
                return false;
            
            default:
                $this->error = '未找到支付方式';
                return false;
        }
    }
    /**
     * 组合支付参数，生成支付链接
     *
     * @return void
     * @author 617 <email：723875993@qq.com>
     */
    public function _build_pay_params($data = [], $method = '') {
        $order_info = $this->OrderModel->one($data['order_id'], ['status'=>1]);
        if (!$order_info) {
            $this->error = '未找到相关订单信息';
            return false;
        }
        $trade_no = unique_order_number($order_info['id']);
        $param = [];
        $param['method']    = $method;                                  // 支付方式[wechat, alipay]
        $param['money']     = $order_info['money'];                 	// 支付金额
        $param['uid']       = $order_info['uid'];                       // 用户标识
        $param['trade_no']  = $trade_no;                                // 支付订单号
        $param['order_no']  = $trade_no;                                // 订单号
        $param['order_sn']  = $order_info['order_sn'];                  // 支付订单号
        $param['subject']   = $trade_no;                                       // 支付标题
        $param['body']      = $trade_no;                                       // 支付标题
        $param['goback']    = get_domain().'/pay/Notify';               // 同步回调后的跳转地址
        $param['openid']    = '';
        $param['type']  	= $data['pay_code'] == 'wechat_app' ? 1 : 2;
        $param['sign']      = md5(http_build_query($param).config('hs_auth.key'));
        if (false === $this->OrderTradeService->trade($param)) {
            $this->error = $this->OrderTradeService->getError();
            return false;
        }
        return get_domain().'/pay?'.http_build_query($param);
    }
}