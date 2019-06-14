<?php
/**
 * Created by PhpStorm.
 * User: xx
 * Date: 2019-2-17
 * Time: 21:22
 */

namespace app\api\controller\v1;

use app\api\controller\BaseController;
use app\api\model\Product as ProductModel;
use app\api\model\Category as CategoryModel;
use app\api\validate\IDMustBePostiveInt;
use app\lib\exception\CategoryException;
use think\Config;
use think\Controller;
use think\Db;
use think\Response;

class Category extends Controller
{
    public function getAllCategories(){
        $categories = CategoryModel::all();
        if($categories->isEmpty()){
            throw new CategoryException();
        }
        return $categories;
    }
    public function category(){
        $result = BaseController::checkSession();
        if($result){
//            $data = CategoryModel::all();
            $list = CategoryModel::getCategory();
//            $this->assign('data', $data);
            $this->assign('list', $list);
//            var_dump($list);
//            var_dump($data);
            return Response::create($this->fetch('category/category'));
        }else{
            $this->redirect( Config::get('_url_').'404.html');
        }
    }
    public function deleteCategory($id){
        (new IDMustBePostiveInt())->goCheck();
        $result = BaseController::checkSession();
        if($result){
            Db::startTrans();
            try{
                CategoryModel::where('id',$id)->delete();
                ProductModel::where('category_id',$id)->delete();
                // 提交事务
                Db::commit();
                return true;
            } catch (\Exception $e) {
                // 回滚事务
                Db::rollback();
            }
        }else{
            $this->redirect( Config::get('_url_').'404.html');
        }
    }
    public function insertCategory($name){
        $result = BaseController::checkSession();
        if($result){
             $data = CategoryModel::insertCategory($name);
             if($data){
                 return true;
             }else{
                 return false;
             }
        }
    }
}