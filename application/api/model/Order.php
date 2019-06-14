<?php
/**
 * Created by PhpStorm.
 * User: xx
 * Date: 2019-2-23
 * Time: 14:29
 */

namespace app\api\model;


use think\Db;

class Order extends BaseModel
{
    protected $hidden = ['user_id','delete_time','update_time'];
    protected $autoWriteTimestamp = true;

    public function getSnapItemsAttr($value){
        if(empty($value)){
            return null;
        }
        return json_decode($value);
    }
    public function getSnapAddressAttr($value){
        if(empty($value)){
            return null;
        }
        return json_decode($value);
    }
    public static function getSummaryByUser($uid,$page=1,$size=20){
        $pagingData = self::where('user_id','=',$uid)
            ->order('create_time desc')
            ->paginate($size,true,['page'=>$page]);
        return $pagingData;
    }
    public static function getPendByUser($uid,$page=1,$size=10){
        $pagingData = self::where('user_id','=',$uid)->where('status',1)
            ->order('create_time desc')
            ->paginate($size,true,['page'=>$page]);
        return $pagingData;
    }
    public static function getDeliveryByUser($uid,$page=1,$size=10){
        $pagingData = self::where('user_id','=',$uid)->where('status',2)
            ->order('create_time desc')
            ->paginate($size,true,['page'=>$page]);
        return $pagingData;
    }
    public static function getReceivedByUser($uid,$page=1,$size=10){
        $pagingData = self::where('user_id','=',$uid)->where('status',3)
            ->order('create_time desc')
            ->paginate($size,true,['page'=>$page]);
        return $pagingData;
    }
    public static function getRefundByUser($uid,$page=1,$size=10){
        $pagingData = self::where('user_id','=',$uid)->where('status',4)->whereOr('status',6)
            ->order('create_time desc')
            ->paginate($size,true,['page'=>$page]);
        return $pagingData;
    }
    public static function getTotal(){
        $result = Db::table('order')->where('status',5)->sum('total_price');
        return $result;
    }
    public static function getPrediction(){
        $result = Db::table('order')->where('status',3)->sum('total_price');
        return $result;
    }
    public static function getTodayTotal(){
        $result = Db::name('order')->whereTime('update_time', 'today')->where('status',5)->sum('total_price');
        return $result;
    }
    public static function getTodayOrder(){
        $result = Db::name('order')->whereTime('update_time', 'today')->where('status',2)->whereOr('status',4)->select();
        return $result;
    }

}