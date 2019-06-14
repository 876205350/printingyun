<?php
/**
 * Created by PhpStorm.
 * User: xx
 * Date: 2019-3-17
 * Time: 17:08
 */
namespace  app\api\validate;
class AppTokenGet extends BaseValidate {
    protected $rule = [
        'ac' => 'require|isNotEmpty',
        'se' => 'require|isNotEmpty'
    ];
}