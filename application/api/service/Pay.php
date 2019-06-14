<?php
/**
 * Created by PhpStorm.
 * User: xx
 * Date: 2019-3-11
 * Time: 17:12
 */

namespace app\api\service;

use app\api\model\Order as OrderModel;
use app\api\model\OrderProduct;
use app\lib\exception\OrderException;
use think\Exception;

class pay
{
    private $orderNo;
    private $orderID;
//    private $orderModel;

    function __construct($orderID)
    {
        if (!$orderID)
        {
            throw new Exception('订单号不允许为NULL');
        }
        $this->orderID = $orderID;
    }

    public function pay()
    {
        $this->checkOrderValid();
        $order = new Order();
        $status = $order->checkOrderStock($this->orderID);
        if (!$status['pass'])
        {
            return $status;
        }
        return true;

    }

    private function checkOrderValid()
    {
        $order = OrderModel::where('id', '=', $this->orderID)
            ->find();
        if (!$order)
        {
            throw new OrderException();
        }
//        $currentUid = Token::getCurrentUid();
        if(!Token::isValidOperate($order->user_id))
        {
            throw new TokenException(
                [
                    'msg' => '订单与用户不匹配',
                    'errorCode' => 10003
                ]);
        }
        if($order->status != 1){
            throw new OrderException([
                'msg' => '订单已支付过啦',
                'errorCode' => 80003,
                'code' => 400
            ]);
        }
        $this->orderNo = $order->order_no;
        return true;
    }
}