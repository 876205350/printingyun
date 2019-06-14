<?php
/**
 * Created by PhpStorm.
 * User: xx
 * Date: 2019-2-19
 * Time: 23:40
 */

namespace app\api\validate;

class ContentGet extends BaseValidate
{
    protected $rule = [
        'content' => 'require|isNotEmpty'
    ];
    protected $message = [
        'content' => '没有内容还想获取资料'
    ];
}