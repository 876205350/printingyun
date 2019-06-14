<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
use think\Route;

//Route::rule('路由表达式','路由地址','请求类型','路由参数(数组)','变量规则(数组)');
// GET, POST, DELETE, PUT, *

Route::get('api/:version/banner/:id','api/:version.Banner/getBanner');
//商品列表
Route::get('api/:version/category/all','api/:version.Category/getAllCategories');
//列表对应的物品
Route::get('api/:version/product/by_category','api/:version.Product/getAllInCategory');
Route::get('api/:version/product/all','api/:version.Product/getAllProducts');
//物品详情
Route::get('api/:version/product/:id','api/:version.Product/getOne',[],['id'=>'\d+']);
Route::post('api/:version/product/content','api/:version.Product/getSearchAll');
//用户
Route::post('api/:version/order','api/:version.Order/placeOrder');
Route::get('api/:version/order/:id','api/:version.Order/getDetail',[],['id'=>'\d+']);
Route::get('api/:version/order/updateStatus','api/:version.Order/updateOrderStatus');
Route::get('api/:version/order/by_user','api/:version.Order/getSummaryByUser');
Route::get('api/:version/order/by_pend','api/:version.Order/getPendByUser');
Route::get('api/:version/order/by_delivery','api/:version.Order/getDeliveryByUser');
Route::get('api/:version/order/by_received','api/:version.Order/getReceivedByUser');
Route::get('api/:version/order/by_refund','api/:version.Order/getRefundByUser');
Route::post('api/:version/token/user','api/:version.Token/getToken');
Route::post('api/:version/token/verify','api/:version.Token/verifyToken');
Route::post('api/:version/address','api/:version.Address/createOrUpdateAddress');
Route::get('api/:version/address','api/:version.Address/getUserAddress');
Route::post('api/:version/pay/pre_order', 'api/:version.Pay/getPreOrder');

//登录
Route::post('api/admin/checkLogin', 'api/v1.Login/getAppToken');
Route::get('api/admin/login', 'api/v1.Login/login');
//退出登录
Route::get('api/admin/logout', 'api/v1.Login/logout');
//订单
Route::get('api/admin/oldOrder', 'api/v1.Order/oldOrder');
Route::get('api/admin/order', 'api/v1.Order/order');
Route::get('api/admin/unpaid', 'api/v1.Order/unpaid');
Route::get('api/admin/paid', 'api/v1.Order/paid');
Route::get('api/admin/received', 'api/v1.Order/received');
Route::get('api/admin/refund', 'api/v1.Order/refund');
Route::get('api/admin/invoice', 'api/v1.Order/invoice');
Route::get('api/admin/profit', 'api/v1.Order/profit');
//用户
Route::get('api/admin/user', 'api/v1.User/user');
Route::post('api/admin/updatePassword', 'api/v1.User/updatePassword');
Route::get('api/admin/change', 'api/v1.User/change');
//关闭订单
Route::get('api/admin/closeOrder', 'api/v1.Order/updateOrderStatus');
//分类
Route::get('api/admin/category', 'api/v1.Category/category');
Route::post('api/admin/insertCategory', 'api/v1.Category/insertCategory');
//删除分类
Route::get('api/admin/deleteCategory', 'api/v1.Category/deleteCategory');
Route::get('api/admin/product', 'api/v1.Product/product');
//移动到回收站
Route::get('api/admin/oldProduct', 'api/v1.Product/oldProduct');
//删除文件
Route::get('api/admin/deleteProductOne', 'api/v1.Product/deleteProductOne');
Route::post('api/admin/updateProduct', 'api/v1.Product/updateProduct');
Route::get('api/admin/content', 'api/v1.Product/content');
Route::get('api/admin/increaseFile', 'api/v1.Product/increaseFile');
Route::get('api/admin/upload', 'api/v1.Product/upload');
Route::post('api/admin/uploadFile', 'api/v1.Product/uploadFile');
Route::get('api/admin/download', 'api/v1.Product/download');
//Route::get('api/:version/second','api/:version.Address/second');
//return [
//    '__pattern__' => [
//        'name' => '\w+',
//    ],
//    '[hello]'     => [
//        ':id'   => ['index/hello', ['method' => 'get'], ['id' => '\d+']],
//        ':name' => ['index/hello', ['method' => 'post']],
//    ],
//
//];
