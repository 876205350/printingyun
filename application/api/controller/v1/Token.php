<?php
/**
 * Created by PhpStorm.
 * User: xx
 * Date: 2019-2-18
 * Time: 15:03
 */

namespace app\api\controller\v1;


use app\api\service\UserToken;
use app\api\service\Token as TokenServer;
use app\api\validate\TokenGet;
use app\lib\exception\ParameterException;

class Token
{
    public function getToken($code=''){
        (new TokenGet())->goCheck();
        $wx = new UserToken($code);
        $token = $wx->get();
        return [
            'token' => $token
        ];
    }
    public function verifyToken($token=''){
        if(!$token){
            throw new ParameterException([
               'token 不能为空'
            ]);
        }
        $valid = TokenServer::verifyToken($token);
        return [
          'isValid' => $valid
        ];
    }
}