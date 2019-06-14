<?php
/**
 * Created by PhpStorm.
 * User: xx
 * Date: 2019-2-17
 * Time: 21:24
 */

namespace app\api\model;


use think\Db;

class Category extends BaseModel
{
    protected $autoWriteTimestamp = true;
    public function product(){
        return $this->hasMany('Product','category_id','id');
    }
    public static function getCategory(){
        $result= Db::table('category')
            ->alias('a')
            ->join('product','a.id=product.category_id','LEFT')
            ->field('a.id,a.name,count(a.id) as num')
            ->group('a.id')
            ->select();
        return $result;
    }
    public static function insertCategory($name){
        $data = ['name' => $name];
        $result = Db::table('category')->insert($data);
        return $result;
    }
}