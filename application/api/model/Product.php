<?php
/**
 * Created by PhpStorm.
 * User: xx
 * Date: 2019-2-17
 * Time: 21:39
 */

namespace app\api\model;

use think\Db;

class Product extends BaseModel
{
    protected $hidden = ['delete_time','update_time'];
    protected $autoWriteTimestamp = true;
    public static function getProductByCategoryID($categoryID) {
        $products = self::where('category_id','=',$categoryID)->select();
        return $products;
    }
    public static function getProductID($id){
        $productOne = self::where('id','=',$id)->find();
        return $productOne;
    }
    public static function getContents($content){
        $result = self::where('name','like',"%".$content."%")
            ->select();
        return $result;
    }
    public static function saveFile($id,$name,$price,$double,$url){
        $data = [
            'name' => $name,
            'price' => $price,
            'doubleprice' => $double,
            'category_id' => $id,
            'book_url' => $url,
            'create_time' => time()
        ];
        $result = Db::table('product')->insert($data);
        return $result;
    }

}