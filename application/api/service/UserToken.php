<?php
/**
 * Created by PhpStorm.
 * User: xx
 * Date: 2019-2-18
 * Time: 16:07
 */

namespace app\api\service;


use app\lib\enum\ScopeEnum;
use app\lib\exception\TokenException;
use app\lib\exception\WeChatException;
use think\Exception;
use app\api\model\User as UserModel;


class UserToken extends Token
{
    protected $code;
    protected $wxLoginUrl;
    protected $wxAppID;
    protected $wxAppSecret;

    function  __construct($code)
    {
        $this->code = $code;
        $this->wxAppID = config('wx.app_id');
        $this->wxAppSecret = config('wx.app_secret');
        $this->wxLoginUrl = sprintf(config('wx.login_url'),
             $this->wxAppID,$this->wxAppSecret,$this->code);
    }

    public function get(){
        $result = curl_get($this->wxLoginUrl);
        $wxResult = json_decode($result,true);
        if(empty($wxResult)){
            throw new Exception('获取session_key及openID时异常，微信内部错误');
        }else{
            $loginFail = array_key_exists('errcode',$wxResult);
            if($loginFail){
                $this->processLoginError($wxResult);
            }else{
                return $this->grantToken($wxResult);
            }
        }
    }

    private function grantToken($wxResult){
        //拿到openid
        //数据库看一下openid是否存在
        //不存在就新增一条user记录
        //生成令牌，准备写入缓存
        //令牌返回客户端
        //key: 令牌
        //value:wxResult uid scope
        $openid = $wxResult['openid'];
        $user = UserModel::getByOpenID($openid);
        if($user){
            $uid = $user->id;
        }else{
            //插入新用户
            $uid = $this->newUser($openid);
        }
        $cachedValue = $this->prepareCachedValue($wxResult,$uid);
        $token = $this->saveToCache($cachedValue);
        return $token;
    }
    private function saveToCache($cachedValue){
        $key =self::generateToken();
        $value = json_encode($cachedValue);
        $expire_in = config('setting.token_expire_in');
        $request = cache($key,$value,$expire_in);
        if (!$request){
            throw new TokenException([
               'msg' =>'服务器缓存异常',
               'errorCode'=> 10005
            ]);
        }
        return $key;
    }
    private function prepareCachedValue($wxResult,$uid){
        $cachedValue = $wxResult;
        $cachedValue['uid'] = $uid;
        //scop = 16 代表App用户权限数值
        $cachedValue['scope'] = ScopeEnum::User;
        //scop = 32  代表CMS（管理员）用户权限数值
//        $cachedValue['scope'] = 32;
        return $cachedValue;
    }
    private function newUser($openid){
        $user = UserModel::create([
            'openid'=>$openid
        ]);
        return $user->id;
    }
    private function processLoginError($wxResult)
    {
        throw new WeChatException(
            [
                'msg' => $wxResult['errmsg'],
                'errorCode' => $wxResult['errcode']
            ]);
    }
}