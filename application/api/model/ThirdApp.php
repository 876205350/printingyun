<?php
/**
 * Created by PhpStorm.
 * User: xx
 * Date: 2019-3-17
 * Time: 17:05
 */
 namespace app\api\model;
class ThirdApp extends BaseModel {
    public static function check($ac, $se)
    {
        $app = self::where('app_id','=',$ac)
            ->where('app_secret', '=',$se)
            ->find();
        return $app;
    }
}