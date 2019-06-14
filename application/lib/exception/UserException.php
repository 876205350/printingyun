<?php
/**
 * Created by PhpStorm.
 * User: xx
 * Date: 2019-2-20
 * Time: 19:05
 */

namespace app\lib\exception;


class UserException extends BaseException
{
    public $code = 404;
    public $msg = '用户不存在';
    public $errorCode = 60000;
}