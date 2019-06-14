<?php
/**
 * Created by PhpStorm.
 * User: xx
 * Date: 2019-3-18
 * Time: 13:03
 */
namespace app\api\service;
use app\api\model\ThirdApp;
use think\Session;

class AppToken extends Token{
    public function get($ac, $se)
    {
        $app = ThirdApp::check($ac, $se);
        if(!$app)
        {
            return false;
        }
        else{
            $uid = $app->id;
            Session::set('uid',$uid);
            return true;
        }
    }

//    private function saveToCache($values){
//        $token = self::generateToken();
//        $expire_in = config('setting.token_expire_in');
//        $result = cache($token, json_encode($values), $expire_in);
//        if(!$result){
//                throw new TokenException([
//                    'msg' => '服务器缓存异常',
//                    'errorCode' => 10005
//                ]);
//        }
//        return $token;
//    }
}