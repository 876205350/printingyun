<?php
/**
 * Created by PhpStorm.
 * User: xx
 * Date: 2019-2-18
 * Time: 15:05
 */

namespace app\api\validate;


class TokenGet extends BaseValidate
{
    protected $rule = [
      'code' => 'require|isNotEmpty'
    ];
    protected $message = [
        'code' => '没有code还想获取Token'
    ];
}