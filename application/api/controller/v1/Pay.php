<?php
/**
 * Created by PhpStorm.
 * User: xx
 * Date: 2019-3-11
 * Time: 17:03
 */

namespace app\api\controller\v1;


use app\api\controller\BaseController;
use app\api\service\Pay as PayService;
use app\api\validate\IDMustBePostiveInt;

class pay extends BaseController
{
    protected $beforeActionList = [
        'checkExclusiveScope' => ['only' => 'getPreOrder']
    ];
    public function getPreOrder($id='')
    {
        (new IDMustBePostiveInt()) -> goCheck();
        $pay= new PayService($id);
        return $pay->pay();
    }
}