<?php
/**
 * Created by PhpStorm.
 * User: xx
 * Date: 2019-2-18
 * Time: 21:55
 */

namespace app\lib\exception;


class TokenException extends BaseException
{
    public $code = 401;
    public $msg = 'Token已过期或无效Token';
    public $errorCode = 10001;
}