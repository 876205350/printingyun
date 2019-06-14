<?php
/**
 * Created by PhpStorm.
 * User: xx
 * Date: 2019-2-20
 * Time: 21:31
 */

namespace app\lib\exception;


class ForbiddenException extends BaseException
{
    public $code = 403;
    public $msg = '权限不够';
    public $errorCode = 10001;
}