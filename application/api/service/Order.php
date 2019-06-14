<?php
/**
 * Created by PhpStorm.
 * User: xx
 * Date: 2019-2-21
 * Time: 21:22
 */

namespace app\api\service;


use app\api\model\OrderProduct;
use app\api\model\Product;
use app\api\model\Order as OrderModel;
use app\api\model\UserAddress;
use app\lib\exception\OrderException;
use app\lib\exception\UserException;
use think\Db;
use think\Exception;

class Order
{
    //客户端传过来的Products参数
    protected $oProducts;
    //数据库查询出来的真实参数
    protected $products;
    protected $uid;
    public function place($uid,$oProducts){
        $this->oProducts = $oProducts;
        $this->products = $this->getProductsByOrder($oProducts);
        $this->uid = $uid;
        $status = $this->getOrderStatus();
        //开始创建订单
        $orderSnp = $this->snapOrder($status);
        $order = $this->createOrder($orderSnp);
        $order['pass'] = true;
        return $order;
    }

    /**
     * @param string $orderNo 订单号
     * @return array 订单商品状态
     * @throws Exception
     */
    public function checkOrderStock($orderID)
    {
        //        if (!$orderNo)
        //        {
        //            throw new Exception('没有找到订单号');
        //        }

        // 一定要从订单商品表中直接查询
        // 不能从商品表中查询订单商品
        // 这将导致被删除的商品无法查询出订单商品来
        $oProducts = OrderProduct::where('order_id', '=', $orderID)
            ->select();
        $this->products = $this->getProductsByOrder($oProducts);
        $this->oProducts = $oProducts;
        $status = $this->getOrderStatus();
        return $status;
    }
    private function createOrder($snap){
        Db::startTrans();
        try {
            $orderNo = $this->makeOrderNo();
            $order = new OrderModel();
            $order->user_id = $this->uid;
            $order->order_no = $orderNo;
            $order->total_price = $snap['orderPrice'];
            $order->total_count = $snap['totalCount'];
            $order->snap_name = $snap['snapName'];
            $order->snap_address = $snap['snapAddress'];
            $order->snap_items = json_encode($snap['pStatus']);
            $order->save();
            $orderID = $order->id;
            $create_time = $order->create_time;
            foreach ($this->oProducts as &$p) {
//                unset($p['price']);
                $p['order_id'] = $orderID;
            }
            $orderProduct = new OrderProduct();
            $orderProduct->saveAll($this->oProducts);
            Db::commit();
            return [
                'order_no' => $orderNo,
                'order_id' => $orderID,
                'create_time' => $create_time
            ];
        } catch (Exception $ex){
            Db::rollback();
            throw $ex;
        }
    }
    //生成订单编号
    public static function makeOrderNo()
    {
        $yCode = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J');
        $orderSn =
            $yCode[intval(date('Y')) - 2019] . strtoupper(dechex(date('m'))) . date(
                'd') . substr(time(), -5) . substr(microtime(), 2, 5) . sprintf(
                '%02d', rand(0, 99));
        return $orderSn;
    }
    private function snapOrder($status){
        $snap = [
            'orderPrice' => 0,
            'totalCount' => 0,
            'pStatus' =>[],
            'snapAddress' => null,
            'snapName'=>'',
        ];
        $snap['orderPrice'] = $status['orderPrice'];
        $snap['totalCount'] = $status['totalCount'];
        $snap['pStatus'] = $status['pStatusArray'];
        $snap['snapAddress'] = json_encode($this->getUserAddress());
        $snap['snapName'] = $this->products[0]['name'];
        if(count($this->products)>1){
            $snap['snapName'] .= '等';
        }
        return $snap;
    }
    private function getUserAddress(){
        $userAddress = UserAddress::where('user_id','=',$this->uid)->find();
        if(!$userAddress){
            throw new UserException([
               'msg'=>'用户收货地址不存在，下单失败',
                'errorCode'=>60001,
            ]);
        }
        return $userAddress->toArray();
    }
    private function getOrderStatus(){
        $status =[
            'pass' => true,
            'orderPrice' => 0,//价格
            'totalCount' => 0,
            'pStatusArray' =>[] //历史订单
        ];
        foreach ($this->oProducts as $oProduct){
            $pStatus = $this->getProductStatus(
                $oProduct['product_id'],$oProduct['count'],$oProduct['price'],$this->products
            );
            $status['totalCount'] += $pStatus['counts'];
            $status['orderPrice'] += $pStatus['totalPrice'];
            array_push($status['pStatusArray'],$pStatus);
        }
        return $status;
    }
    private function getProductStatus($oPID,$oCount,$price,$products){
        $judge = 0;
        $pIndex = 0;
        $pStatus = [
            'id' => null,
            'counts' => 0,
            'name' => '',
            'price' =>0,
            'judge' =>0,
            'totalPrice' => 0
        ];
        for($i=0; $i<count($products);$i++){
            if($oPID == $products[$i]['id']){
                if($price==$products[$i]['price']||$price==$products[$i]['doubleprice']){
                    if($price==$products[$i]['price']){
                        $judge = 1;
                    }else{
                        $judge = 2;
                    }
                    $pIndex = $i;
                }
            }
        }
        if($pIndex == -1){
            //客户端传递的product_id根本不存在
            throw new OrderException([
                'msg' => 'id为'.$oPID.'商品不存在，创建订单失败'
            ]);
        }else{
            $product = $products[$pIndex];
            $pStatus['id'] = $product['id'];
            $pStatus['name'] = $product['name'];
            $pStatus['counts'] = $oCount;
            $pStatus['judge'] = $judge;
            $pStatus['price'] = $price;
            $pStatus['totalPrice'] = $price * $oCount;
        }
        return $pStatus;
    }

    //禁止循环查询数据库
    private function getProductsByOrder($oProducts){
        $oPIDs = [];
        foreach ($oProducts as $item) {
            array_push($oPIDs, $item['product_id'],$item['price']);
        }
        // 为了避免循环查询数据库
        $products = Product::all($oPIDs)
            ->visible(['id', 'price','doubleprice', 'name'])
            ->toArray();
        return $products;
    }
}