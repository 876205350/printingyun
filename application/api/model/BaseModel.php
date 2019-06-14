<?php
/**
 * Created by PhpStorm.
 * User: xx
 * Date: 2019-2-17
 * Time: 16:57
 */
namespace app\api\model;

use think\Model;

class BaseModel extends Model{
    protected function prefixContentUrl($value){
        return config('setting.content_prefix').$value;
    }
}