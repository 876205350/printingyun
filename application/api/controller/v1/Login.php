<?php
/**
 * Created by PhpStorm.
 * User: xx
 * Date: 2019-3-17
 * Time: 16:03
 */
namespace app\api\controller\v1;

use app\api\controller\BaseController;
use app\api\service\AppToken;
use app\api\validate\AppTokenGet;
use think\Response;
use think\Session;

class Login extends BaseController{
    public function login(){
        return Response::create($this->fetch('login/login'));
    }
    public function getAppToken($ac='',$se='')
    {
//        header('Access-Control-Allow-Origin: *');
//        header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
//        header('Access-Control-Allow-Methods: GET');
        (new AppTokenGet())->goCheck();
        $app = new AppToken();
        $result = $app->get($ac,$se);
        return $result;
    }
    public function logout(){
        //销毁session
        session(null);
        //跳转页面
        $this->redirect('api/admin/login');
    }
}