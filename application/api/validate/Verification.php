<?php
/**
 * Created by PhpStorm.
 * User: xx
 * Date: 2019-3-20
 * Time: 21:10
 */

namespace app\api\validate;


class Verification extends BaseValidate
{
    protected $rule = [
        'oldpassword' => 'require|isNotEmpty|min:6',
        'password' => 'require|isNotEmpty|min:6'
    ];
}