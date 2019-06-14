<?php
/**
 * Created by PhpStorm.
 * User: xx
 * Date: 2019-2-16
 * Time: 21:53
 */
namespace app\api\model;

class BookData extends BaseModel {
    protected $hidden = ['id','delete_time','update_time'];
    public function getUrlAttr($value){
        return $this->prefixContentUrl($value);
    }
}