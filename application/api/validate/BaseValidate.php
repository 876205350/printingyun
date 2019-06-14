<?php
/**
 * Created by PhpStorm.
 * User: xx
 * Date: 2019-2-14
 * Time: 21:02
 */
namespace app\api\validate;

use app\lib\exception\ParameterException;
use think\Request;
use think\Validate;

class BaseValidate extends Validate {
    public function goCheck(){
        //获取http的参数
        //对参数做检验
        $request = Request::instance();
        $params =  $request->param();

        $result =  $this ->batch()->check($params);
        if(!$result){
            $e = new ParameterException([
                'msg' => $this->error,
            ]);
//            $e -> msg = $this->error;
                throw $e;
//            $error = $this -> error;
//            throw new Exception($error);
        }else{
            return true;
        }
    }
    //$field 传过来验证的参数名字 $value 传过来参数值
    protected function isPositiveInteger($value,$rule='',$date='',$field=''){
        if (is_numeric($value) && is_int($value + 0) && ($value + 0) > 0){
            return true;
        }else{
            return false;
        }
    }
    protected function isNotEmpty($value,$rule='',$date='',$field=''){
        $result = trim($value);
        if(empty($result)){
            return false;
        }else{
            return true;
        }
    }
    //没有使用TP的正则验证，集中在一处方便以后修改
    //不推荐使用正则，因为复用性太差
    //手机号的验证规则 isMobile
    protected function isMobile($value)
    {
        $rule = '^1(3|4|5|7|8)[0-9]\d{8}$^';
        $result = preg_match($rule, $value);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }
    public function getDataByRule($arrays){
        if (array_key_exists('user_id', $arrays) | array_key_exists('uid', $arrays)) {
            // 不允许包含user_id或者uid，防止恶意覆盖user_id外键
            throw new ParameterException([
                'msg' => '参数中包含有非法的参数名user_id或者uid'
            ]);
        }
        $newArray = [];
        foreach ($this->rule as $key => $value) {
            $newArray[$key] = $arrays[$key];
        }
        return $newArray;
    }
}