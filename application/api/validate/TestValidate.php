<?php
/**
 * Created by PhpStorm.
 * User: xx
 * Date: 2019-2-14
 * Time: 20:00
 */
namespace app\api\validate;

use think\Validate;

class TestValidate extends Validate {
    protected $rule = [
        'name' => 'require|max:10',
        'email' => 'email'
    ];
}