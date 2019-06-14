<?php
/**
 * Created by PhpStorm.
 * User: xx
 * Date: 2019-2-20
 * Time: 18:26
 */

namespace app\api\validate;


class AddressNew extends BaseValidate
{
    protected $rule =[
        'name' => 'require|isNotEmpty',
        'mobile' => 'require',
        'detail' => 'require|isNotEmpty'
    ];
}