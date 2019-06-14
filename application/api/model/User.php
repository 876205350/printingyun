<?php
/**
 * Created by PhpStorm.
 * User: xx
 * Date: 2019-2-18
 * Time: 16:04
 */

namespace app\api\model;


class User extends BaseModel
{
    //有外键的一方用belongto没有外键建立关系用hasone
    public function address(){
        return $this->hasOne('UserAddress','user_id','id');
    }
    public static function getByOpenID($openid){
        $user = self::where('openid','=',$openid)->find();
        return $user;
    }
}