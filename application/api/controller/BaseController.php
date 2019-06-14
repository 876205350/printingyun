<?php
/**
 * Created by PhpStorm.
 * User: xx
 * Date: 2019-2-21
 * Time: 16:07
 */

namespace app\api\controller;


use app\api\service\Token as TokenService;
use think\Controller;
use think\Session;

class BaseController extends Controller
{
    protected function checkPrimaryScope(){
        TokenService::needPrimaryScope();
    }
    protected function checkExclusiveScope(){
        TokenService::needExclusiveScope();
    }
    public static function checkSession(){
        $Session_id = Session::get('uid');
        if(empty($Session_id)){
           return false;
        }else{
            return true;
        }
    }
}