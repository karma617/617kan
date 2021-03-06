<?php
namespace app\nb_api\model;

use think\Model;

class NbApi extends Model
{
    protected $auto = ['secret'];

    protected function setSecretAttr($value,$data)
    {
        return str_coding(substr(md5($data['name']),8,16),'ENCODE');
    }

    public static function vaildSecret ($secret) {
        $info = self::where(['secret' => $secret, 'status' => 1])->find();
        return $info;
    }

}