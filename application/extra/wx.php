<?php
/**
 * Created by PhpStorm.
 * User: xx
 * Date: 2019-2-18
 * Time: 16:18
 */
return [
    'app_id' => 'wxa062d9ce0a4053ae',
    'app_secret' => 'c7a042ae258bdc1156f746e072fbcbc0',
    // 微信使用code换取用户openid及session_key的url地址
    'login_url' => "https://api.weixin.qq.com/sns/jscode2session?" .
        "appid=%s&secret=%s&js_code=%s&grant_type=authorization_code",
];