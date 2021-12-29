<?php
namespace app\user\validate;

use think\Validate;

/**
 * 发布货源验证器
 * @package app\user\validate
 */
class UserCertification extends Validate
{
    //定义验证规则
    protected $rule = [
        'man_id|身份证号'  => 'require|checkManid:thinkphp',
        'car_id|车牌号'  =>   'require',
        'true_name|真实姓名'  => 'require|min:2',
        'sex|性别'  =>   'require',
        'car_date|驾驶证登记日期'  =>   'require',
        'car_exp_date|驾驶证有效期'  =>   'require',
        'car_number|车牌号'  =>   'require',
        'car_color|车辆颜色'  =>   'require',
        'car_number_type|车牌类型'  =>   'require',
        'car_type|车型'  =>   'require',
        'car_length|车长'  =>   'require',
        'car_weight|载重'  =>   'require',
        'car_frame_number|车架号'  =>   'require',
        'car_drever_number|发动机号'  =>   'require',
        'car_man_address|车主地址'  =>   'require',
        'car_man_address_info|详细地址'  =>   'require',
        'man_avatar|真实自拍头像'  =>   'require',
        'man_id_img|身份证正反照片'  =>   'require',
        'car_id_img|驾驶证正副照片'  =>   'require',
        'car_drever_img|行驶证正反照片'  =>   'require',
        'car_img|车辆照片'  =>   'require'
    ];

    //定义验证提示
    protected $message = [
        'man_id.require'   => '身份证号不能为空',
        'man_id.length'   => '身份证号长度应为18位',
        'car_id.require'   => '驾驶证号不能为空',
        'car_id.length'   => '驾驶证号长度应是7位',
        'true_name.require'   => '真实姓名不能为空',
        'true_name.min'   => '真实姓名最少为2位',
        'sex.require'   => '性别不能为空',
        'car_date.require'   => '驾驶证登记日期不能为空',
        'car_exp_date.require'   => '驾驶证有效期不能为空',
        'car_number.require'   => '车牌号不能为空',
        'car_color.require'   => '车辆颜色不能为空',
        'car_number_type.require'   => '车牌类型不能为空',
        'car_type.require'   => '车型不能为空',
        'car_length.require'   => '车长不能为空',
        'car_weight.require'   => '载重不能为空',
        'car_frame_number.require'   => '车架号不能为空',
        'car_drever_number.require'   => '发动机号不能为空',
        'car_man_address.require'   => '车主地址不能为空',
        'car_man_address_info.require'   => '详细地址不能为空',
        'man_avatar.require'   => '真实自拍头像不能为空',
        'man_id_img.require'   => '身份证正反照片不能为空',
        'car_id_img.require'   => '驾驶证正副照片不能为空',
        'car_drever_img.require'   => '行驶证正反照片不能为空',
        'car_img.require'   => '车辆照片不能为空'
    ];

    //定义验证场景
    protected $scene = [
        'owner' => [
            'man_id',
            'true_name',
            'sex',
            'man_avatar',
            'man_id_img'
        ],
        'drivers' => [
            'man_id',
            'car_id',
            'true_name',
            'sex',
            'car_date',
            'car_exp_date',
            'car_number',
            'car_color',
            'car_number_type',
            'car_type',
            'car_length',
            'car_weight',
            'car_frame_number',
            'car_drever_number',
            'car_man_address',
            'car_man_address_info',
            'man_avatar',
            'man_id_img',
            'car_id_img',
            'car_drever_img',
            'car_img'
        ]
    ];

    /**
     * 检查用户名
     * @author 橘子俊 <364666827@qq.com>
     * @return stirng|array
     */
    protected function checkManid($value, $rule, $data = []) {
        if (!isCreditNo($data['man_id'])) {
            return '身份证号不正确！';
        }
        return true;
    }

}
