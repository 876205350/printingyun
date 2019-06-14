<?php
/**
 * Created by PhpStorm.
 * User: xx
 * Date: 2019-3-12
 * Time: 14:24
 */

namespace app\api\validate;


class OrderStatus extends BaseValidate
{
    protected $rule = [
        'id' => 'isPositiveInteger',
        'num' => 'isPositiveInteger'
    ];

    protected $message = [
        'id' => 'id必须是正整数',
        'num' => 'num必须是正整数'
    ];
}