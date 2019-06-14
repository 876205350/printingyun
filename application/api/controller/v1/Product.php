<?php
/**
 * Created by PhpStorm.
 * User: xx
 * Date: 2019-2-17
 * Time: 23:31
 */

namespace app\api\controller\v1;


use app\api\controller\BaseController;
use app\api\model\Category as CategoryModel;
use app\api\validate\ContentGet;
use app\api\validate\IDMustBePostiveInt;
use app\api\model\Product as ProductModel;
use app\lib\exception\ParameterException;
use app\lib\exception\ProductException;
use think\Config;
use think\Controller;
use think\Request;
use think\Response;

//use think\Request;
class Product extends Controller
{
    public function getAllInCategory($id){
        (new IDMustBePostiveInt())->goCheck();
        $products = ProductModel::getProductByCategoryID($id);
        if($products->isEmpty()){
            throw new ProductException();
        }
        $products = $products->hidden(['summary'])->toArray();
        return $products;
    }
    public function getOne($id){
        (new IDMustBePostiveInt())->goCheck();
        $result = ProductModel::getProductID($id);
        if (!$result){
            throw new ProductException();
        }
        return $result;
    }
    public function getAllProducts(){
        $products = ProductModel::where('status',1)->select();
        if($products->isEmpty()){
            throw new ProductException();
        }
        return $products;
    }
    public function getSearchAll(){
        $content = file_get_contents('php://input');

//        (new ContentGet())->goCheck();
        $getContent = ProductModel::getContents($content);
        if(!$getContent){
            throw new ParameterException();
        }
        return $getContent;
    }
    public function product(){
        $result = BaseController::checkSession();
        if($result){
            $list = ProductModel::where('status',1)->select();
            $this->assign('list', $list);
            return Response::create($this->fetch('product/product'));
        }else{
            $this->redirect( Config::get('_url_').'404.html');
        }
    }
    public function oldProduct(){
        $result = BaseController::checkSession();
        if($result){
            $list = ProductModel::where('status',2)->select();
            $this->assign('list', $list);
            return Response::create($this->fetch('product/oldProduct'));
        }else{
            $this->redirect( Config::get('_url_').'404.html');
        }
    }
    public function deleteProductOne($id,$num)
    {
        $result = BaseController::checkSession();
        if ($result) {
            $list = ProductModel::where('id', $id)
                ->update(['status' => $num]);
            if($list){
                return $num;
            }
        }
    }
    public function updateProduct($id,$price,$doubleprice){
        $result = BaseController::checkSession();
        if($result){
            $list = ProductModel::where('id',$id)
                ->update(['price'=>$price,'doubleprice'=>$doubleprice]);
            if($list){
                return true;
            }else{
                return false;
            }
        }
    }
    public function content($id){
        (new IDMustBePostiveInt())->goCheck();
        $result = BaseController::checkSession();
        if ($result) {
            $list = ProductModel::where('category_id', $id)->where('status',1)->select();
            $data = CategoryModel::where('id',$id)->find();
            $this->assign('list', $list);
            $this->assign('data', $data);
            return Response::create($this->fetch('product/content'));
        }
    }
    public function increaseFile($id){
        $result = BaseController::checkSession();
        if($result){
            $data = CategoryModel::where('id',$id)->find();
            $this->assign('data', $data);
            return Response::create($this->fetch('product/increaseFile'));
        }else{
            $this->redirect( Config::get('_url_').'404.html');
        }
    }
    public function upload(){
        $data = CategoryModel::all();
        $this->assign('data', $data);
        return Response::create($this->fetch('product/upload'));
    }
    public function uploadFile(){
       $file =  request()->file('file');
       $result = Request::instance()->post();
       $config = array('size'=>1024*1024*10,'ext'=>array('pdf'));
       if($file){
           $info = $file->validate($config)->move(ROOT_PATH.'public'.DS.'data');
           if($info){
               $fileUrl = $info->getSaveName();
               $result = ProductModel::saveFile($result['id'],$result['name'],$result['price'],$result['doubleprice'],$fileUrl);
               if ($result){
                   return true;
               }else{
                   return false;
               }
           }else{
               return false;
           }
       }

    }
    public function download($id)
    {
        $file_n = ProductModel::where("id", $id)->find();
        if (!$file_n) {
            return "暂无下载入口";
        }
        //        print_r($file_n["book_url"]);
        $file = $file_n["book_url"];
        //str_replace为了严谨点嘛，不要也可以
        $file_lj = str_replace("\\", "/", ROOT_PATH . 'public/data/');
        $files = $file_lj . $file;
        print_r($files);
        if (!file_exists($files)) {
            return "文件不存在";
        } else {
            //打开文件
            $file1 = fopen($files, "r");
            //输入文件标签
            Header("Content-type: application/octet-stream");
            Header("Accept-Ranges: bytes");
            Header("Accept-Length: " . filesize($files));
            Header("Content-Disposition: attachment; filename=" . $file_n["book_url"]);
            echo fread($file1, filesize($files));
            fclose($file1);
        }
    }
}