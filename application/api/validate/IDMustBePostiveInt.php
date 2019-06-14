<?php
/**
 * Created by PhpStorm.
 * User: xx
 * Date: 2019-2-14
 * Time: 20:35
 */
namespace app\api\validate;


class IDMustBePostiveInt extends BaseValidate {
    protected $rule = [
       'id' => 'require|isPositiveInteger'
    ];
    protected $message = [
        'id' => 'id必须是正整数'
    ];
}