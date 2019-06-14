<?php
/**
 * Created by PhpStorm.
 * User: xx
 * Date: 2019-2-17
 * Time: 22:55
 */

namespace app\lib\exception;

class CategoryException extends BaseException{
    public $code = 404;
    public $msg = '指定类目不存在，请检查参数ID';
    public $errorCode = 50000;
}

