<?php
/**
 * Created by PhpStorm.
 * User: xx
 * Date: 2019-3-20
 * Time: 10:30
 */
namespace app\api\validate;

class AdminPage extends BaseValidate{
    protected $rule = [
        'num' => 'isPositiveInteger',
        'size' => 'isPositiveInteger'
    ];

    protected $message = [
        'num' => '分页参数必须是正整数',
        'size' => '分页参数必须是正整数'
    ];
}