<?php
/**
 * Created by PhpStorm.
 * User: xx
 * Date: 2019-2-20
 * Time: 18:23
 */

namespace app\api\controller\v1;


use app\api\controller\BaseController;
use app\api\model\User as UserModel;
use app\api\model\UserAddress;
use app\api\validate\AddressNew;
use app\api\service\Token as TokenService;
use app\lib\exception\SuccessMessage;
use app\lib\exception\UserException;

class Address extends BaseController
{
    protected $beforeActionList = [
        'checkPrimaryScope' => ['only' =>'createOrUpdateAddress,getUserAddress']
    ];


    //权限控制
//    protected $beforeActionList = [
//        'first' => ['only' =>'second']
//    ];
//
//    protected function first(){
//        echo 'first';
//    }
//    //API接口
//    public function second(){
//        echo 'second';
//    }
    public function getUserAddress(){
        $uid = TokenService::getCurrentUid();
        $userAddress = UserAddress::where('user_id',$uid)->find();
        if(!$userAddress){
            throw new UserException([
               'msg' => '用户地址不存在',
               'errorCode' => 6001
            ]);
        }
        return $userAddress;
    }
    public function createOrUpdateAddress(){
        $validate = new AddressNew();
        $validate->goCheck();
        //根据Token获取用户Uid
        //根据uid查用户，判断是否存在，不存在抛出异常
        //获取用户从客户端传过来的的地址信息
        //根据用户地址信息是否存在判断是添加还是更新地址
        $uid = TokenService::getCurrentUid();
        $user = UserModel::get($uid);
        if(!$user){
            throw new UserException();
        }
        $dataArray = $validate->getDataByRule(input('post.'));
        $userAddress = $user->address;
        if(!$userAddress){
            $user->address()->save($dataArray);
        }else{
            $user->address->save($dataArray);
        }
//        return $user;
        return json(new SuccessMessage(),201);
    }
}