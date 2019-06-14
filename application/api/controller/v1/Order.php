<?php
/**
 * Created by PhpStorm.
 * User: xx
 * Date: 2019-2-21
 * Time: 11:21
 */

namespace app\api\controller\v1;

use \app\api\model\Order as OrderModel;
use \app\api\service\Order as OrderService;
use app\api\controller\BaseController;
use app\api\validate\AdminPage;
use app\api\validate\IDMustBePostiveInt;
use app\api\validate\OrderPlace;
use \app\api\service\Token as TokenService;
use app\api\validate\OrderStatus;
use app\api\validate\PagingParameter;
use app\lib\exception\OrderException;
use think\Config;
use think\Response;

class Order extends BaseController
{
    //用户选择商品向API提交包括所选商品的信息
    //把订单数据存入数据库下单成功返回客户端信息，可以支付    //不用检测库存量

    //不需要再次进行库存检测    //调用支付接口，进行支付
    //服务器调用微信支付接口进行支付

    //微信返回支付结果
    //支付成功 还需要库存量检测
    //成功扣除库存量，失败返回失败的结果
    protected $beforeActionList = [
        'checkExclusiveScope' =>['only' => 'placeOrder'],
        'checkPrimaryScope' =>['only' => 'getDetail,getSummaryByUser,getPendByUser,getDeliveryByUser,getReceivedByUser'],
    ];
    //所有订单
    public function getSummaryByUser($page=1,$size=20){
        (new PagingParameter())->goCheck();
        $uid= TokenService::getCurrentUid();
        $pagingOrders = OrderModel::getSummaryByUser($uid,$page,$size);//是一个对象
        if($pagingOrders->isEmpty()){
            return [
              'data' => [],
                'current_page' => $pagingOrders->CurrentPage()
            ];
        }
        $data = $pagingOrders->toArray();
        return [
            'data' => $data,
            'current_page' => $pagingOrders->CurrentPage()
        ];
    }
    //待支付订单
    public function getPendByUser($page=1,$size=10){
        (new PagingParameter())->goCheck();
        $uid= TokenService::getCurrentUid();
        $pendOrders = OrderModel::getPendByUser($uid,$page,$size);//是一个对象
        if($pendOrders->isEmpty()){
            return [
                'data' => [],
                'current_page' => $pendOrders->CurrentPage()
            ];
        }
        $data = $pendOrders->toArray();
        return [
            'data' => $data,
            'current_page' => $pendOrders->CurrentPage()
        ];
    }
    //待发货
    public function getDeliveryByUser($page=1,$size=10){
        (new PagingParameter())->goCheck();
        $uid= TokenService::getCurrentUid();
        $deliveryOrders = OrderModel::getDeliveryByUser($uid,$page,$size);//是一个对象
        if($deliveryOrders->isEmpty()){
            return [
                'data' => [],
                'current_page' => $deliveryOrders->CurrentPage()
            ];
        }
        $data = $deliveryOrders->toArray();
        return [
            'data' => $data,
            'current_page' => $deliveryOrders->CurrentPage()
        ];
    }
    //待收货
    public function getReceivedByUser($page=1,$size=10){
        (new PagingParameter())->goCheck();
        $uid= TokenService::getCurrentUid();
        $ReceivedOrders = OrderModel::getReceivedByUser($uid,$page,$size);//是一个对象
        if($ReceivedOrders->isEmpty()){
            return [
                'data' => [],
                'current_page' => $ReceivedOrders->CurrentPage()
            ];
        }
        $data = $ReceivedOrders->toArray();
        return [
            'data' => $data,
            'current_page' => $ReceivedOrders->CurrentPage()
        ];
    }
    //退款列表
    public function getRefundByUser($page=1,$size=10){
        (new PagingParameter())->goCheck();
        $uid= TokenService::getCurrentUid();
        $RefundOrders = OrderModel::getRefundByUser($uid,$page,$size);//是一个对象
        if($RefundOrders->isEmpty()){
            return [
                'data' => [],
                'current_page' => $RefundOrders->CurrentPage()
            ];
        }
        $data = $RefundOrders->toArray();
        return [
            'data' => $data,
            'current_page' => $RefundOrders->CurrentPage()
        ];
    }
    public function placeOrder(){
        (new OrderPlace())->goCheck();
        $products = input('post.products/a');
        $uid = TokenService::getCurrentUid();
        $order = new OrderService();
        $status = $order->place($uid,$products);
        return $status;
    }
    public function getDetail($id){
        (new IDMustBePostiveInt())->goCheck();
        $orderDetail = OrderModel::get($id);
        if(!$orderDetail){
            throw new OrderException();
        }
        return $orderDetail->hidden(['prepay_id']);
    }
    public function updateOrderStatus($id,$num){
        (new OrderStatus())->goCheck();
        $result = OrderModel::where('id',$id)->find();
        if(!empty($result)){
            if($num == 7){
                OrderModel::where('id', $id)
                    ->update(['status' => $num,'delete_time'=>time()]);
                return $num;
            }else{
                OrderModel::where('id', $id)
                    ->update(['status' => $num,'update_time'=>time()]);
                return $num;
            }
        }else{
            throw new OrderException([
                'msg' => '订单信息不存在',
                'errorCode' => 6003
            ]);
        }
    }
    public function order(){
//     exit('ddddddddd');
//     return Response::create('<p>eeeeeeeee</p>');

        $result = BaseController::checkSession();
        if($result){
            $list = OrderModel::where('status',5)->whereOr('status',6)->select();
            $this->assign('list', $list);
            return Response::create($this->fetch('order/order'));
        }else{
//            var_dump(APP_PATH);
            $this->redirect( Config::get('_url_').'404.html');
        }
    }
    public function unpaid(){
        $result = BaseController::checkSession();
        if($result){
            $list = OrderModel::where('status',1)->select();
            $this->assign('list', $list);
            return Response::create($this->fetch('order/unpaid'));
        }else{
            $this->redirect( Config::get('_url_').'404.html');
        }
    }
    public function paid(){
        $result = BaseController::checkSession();
        if($result){
            $list = OrderModel::where('status',2)->select();
            $this->assign('list', $list);
            return Response::create($this->fetch('order/paid'));
        }else{
            $this->redirect( Config::get('_url_').'404.html');
        }
    }
    public function received(){
        $result = BaseController::checkSession();
        if($result){
            $list = OrderModel::where('status',3)->select();
            $this->assign('list', $list);
            return Response::create($this->fetch('order/received'));
        }else{
            $this->redirect( Config::get('_url_').'404.html');
        }
    }
    public function refund(){
        $result = BaseController::checkSession();
        if($result){
            $list = OrderModel::where('status',4)->select();
            $this->assign('list', $list);
            return Response::create($this->fetch('order/refund'));
        }else{
            $this->redirect( Config::get('_url_').'404.html');
        }
    }
    public function oldOrder(){
        $result = BaseController::checkSession();
        if($result){
            $list = OrderModel::where('status',7)->select();
//            var_dump($list);
            $this->assign('list', $list);
            return Response::create($this->fetch('order/oldOrder'));
        }else{
            $this->redirect( Config::get('_url_').'404.html');
        }
    }
    public function invoice($id){
        (new IDMustBePostiveInt())->goCheck();
        $result = BaseController::checkSession();
        if($result){
            $list = OrderModel::where('id',$id)->find();
//            var_dump($list['snap_items']);exit;
            $value = json_decode($list);
            $snap_items = $value->snap_items;
            $address = $value->snap_address;
//            var_dump($snap_items);
//            var_dump($address);
//            var_dump($list);
            $this ->assign('snap_items',$snap_items);
            $this ->assign('list',$list);
            $this ->assign('address',$address);
            return Response::create($this->fetch('order/invoice'));
        }else{
            $this->redirect( Config::get('_url_').'404.html');
        }
    }
    public function profit(){
        $result = BaseController::checkSession();
        if($result){
            $total = OrderModel::getTotal();
            $prediction = OrderModel::getPrediction();
            $todayTotal = OrderModel::getTodayTotal();
            $list = OrderModel::getTodayOrder();
            $this ->assign('total',$total);
            $this ->assign('prediction',$prediction);
            $this ->assign('todayTotal',$todayTotal);
            $this ->assign('list',$list);
            return Response::create($this->fetch('order/profit'));
        }else{
            $this->redirect( Config::get('_url_').'404.html');
        }
    }
}