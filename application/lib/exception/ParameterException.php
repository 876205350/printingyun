<?php
/**
 * Created by PhpStorm.
 * User: xx
 * Date: 2019-2-16
 * Time: 15:42
 */
namespace app\lib\exception;

class ParameterException extends BaseException {
    public $code = 400;
    public $msg = '参数错误';
    public $errorCode = 10000;
}