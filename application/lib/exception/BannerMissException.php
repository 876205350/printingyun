<?php
/**
 * Created by PhpStorm.
 * User: xx
 * Date: 2019-2-14
 * Time: 22:24
 */
namespace app\lib\exception;

class BannerMissException extends BaseException {
    public $code = 404;
    public  $msg = '请求Banner不存在';
    public  $errorCode = 40000;
}