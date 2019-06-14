<?php
/**
 * Created by PhpStorm.
 * User: xx
 * Date: 2019-3-20
 * Time: 17:08
 */

namespace app\api\controller\v1;


use app\api\controller\BaseController;
use app\api\model\ThirdApp;
use app\api\validate\Verification;
use think\Config;
use think\Response;
use think\Session;

class User extends BaseController
{
    public function user(){
        $result = BaseController::checkSession();
        if($result){
            return Response::create($this->fetch('user/user'));
        }else{
            $this->redirect( Config::get('_url_').'404.html');
        }
    }
    public function change(){
        $result = BaseController::checkSession();
        if($result){
            return Response::create($this->fetch('user/change'));
        }else{
            $this->redirect( Config::get('_url_').'404.html');
        }
    }
    public function updatePassword($oldpassword,$password){
        (new Verification())->goCheck();
        $id = Session::get('uid');
        $result = ThirdApp::where('id',$id)->find();
        if(!empty($result) && ($result->app_secret == $oldpassword)){
            ThirdApp::where('id', $id)
                ->update(['app_secret' => $password]);
        }else{
            throw new OrderException([
                'msg' => '订单信息不存在',
                'errorCode' => 6003
            ]);
        }
        return true;
    }
}