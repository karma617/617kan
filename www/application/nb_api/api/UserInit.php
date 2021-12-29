<?php
namespace app\nb_api\api;
use app\nb_api\api\ApiInit;
use app\user\model\User;
/**
 * 用户基础控制器
 * @package app\common\controller
 */
class UserInit extends ApiInit
{
    public $user = '';
    public $check_login = true;
    protected function initialize() {
        parent::initialize();
        isset($this->params['uid']) && $this->params['uid'] = $this->_params['uid'] = str_coding($this->params['uid']);
        $this->UserModel = new User();
        $this->_check_login();
    }

    public function _check_login() {
        if (($this->user = $this->UserModel->isLogin($this->token)) === false && $this->check_login) {
            return $this->_error('请先登录', [], 1200);
        }
    }

    public function _success($msg = 'success', $data = [], $code=200 ,$header = [], $type = 'json') {
        isset($data['uid']) && $data['uid'] = str_coding($data['uid'], 'ENCODE');
        if (isset($data['list']) && !empty($data['list'])) {
            foreach ($data['list'] as $key => $value) {
                isset($data['list'][$key]['uid']) && $data['list'][$key]['uid'] = str_coding($value['uid'], 'ENCODE');
                continue;
            }
        }
        $this->_result($msg, $data, $code, $header, $type);
    }
}