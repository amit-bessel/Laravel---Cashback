<?php namespace App\Http\Controllers\Frontend; /* path of this controller*/
// Define Model
use App\Model\Brand; /* Model name*/
use App\Model\Product;
use App\Model\BrandCategory;

use App\Http\Requests;
use App\Http\Controllers\Controller;    
use Illuminate\Support\Facades\Request;
use Mail;
use Input; /* For input */
use Validator;
use Session;

use Intervention\Image\Facades\Image; // Use this if you want facade style code

use Illuminate\Pagination\Paginator;
use App\Model\UtilityCuretedFeature;
use DB;
use Hash;
use Auth;
use Cookie;
use Cart;
use Redirect;
use Config;
//use Socialize;
use App\Model\Address; 

class DesignerController extends BaseController {
    public function __construct() 
    {
        parent::__construct();

    }

    public function designerList(){


      $start='a';
      $end='z'; 
      $pageindex=array();
      for($i=$start;$i<$end;$i++){
         
         $inv=DB::table('brands')->whereRaw(" brand_name like '".$i."%'")
                     ->orderBy('brand_name', 'ASC')->get();
             $pageindex[$i]=$inv;
      }
      $inv=DB::table('brands')->whereRaw(" brand_name like 'z%'")
                     ->orderBy('brand_name', 'ASC')->get();
             $pageindex['z']=$inv;

      //echo "<pre>"; print_r($pageindex); exit;
	  $title = 'Designer';

      return view('frontend.home.all-designers',compact('pageindex', 'title'));
    }

    public function designerBrandList($id){

      $id = base64_decode($id);

      //$brand_prod_query = Product::where('brand_id', '=', $id)->where('status', '=', 1);

     // $total_no_of_products = $brand_prod_query->count();

      $brand_details = Brand::where('id' , '=', $id)->first()->toArray();
      $sessionvalue = Session::get('menwomen');
      $title = $brand_details['brand_name'];
	    $sub_cat = $main_cat = array();

		// get category and subcategory from brand_cat table
		
		  $main_cat = BrandCategory::whereHas('get_brand_category', function($q) use ($sessionvalue){
                                            $q->where('parent_id',0);
                                            $q->where('id','!=',1);
								                            $q->where('status','=',1);
                                            $q->whereIn('gender_cat',array($sessionvalue,3));
                                        })->with(['get_brand_category' => function($q) use ($sessionvalue){
                                            $q->select('id','name'); 
                                            $q->where('parent_id',0);
                                            $q->where('id','!=',1);
                                            $q->whereIn('gender_cat',array($sessionvalue,3));
														                $q->where('status','=',1);
                                        }])
													->where('brand_id',$id)->get()->toArray();

		  $sub_cat = BrandCategory::whereHas('get_brand_category', function($q) use ($sessionvalue){
                                           $q->where('parent_id','!=',0);
                                           $q->where('id','!=',1);
      				                             $q->where('status','=',1);
                                           $q->whereIn('gender_cat',array($sessionvalue,3));
                                        })->with(['get_brand_category' => function($q) use ($sessionvalue){
                                            $q->select('id','name','parent_id'); 
                                           $q->where('parent_id','!=',0);
                                           $q->where('id','!=',1);
                                           $q->whereIn('gender_cat',array($sessionvalue,3));
													                 $q->where('status','=',1);
                                        }])
													->where('brand_id',$id)->get()->toArray();
    /*echo "<pre>";
    print_r($main_cat);
    echo "<hr>";
    echo "<pre>";
    print_r($sub_cat);
    exit;*/

     $main_cat_arr = $sub_cat_arr = array();
     if(!empty($main_cat)){
        foreach ($main_cat as $key => $value) {
          # code...
          $main_cat_arr[] = array('category_id'=>$value['category_id'],'name'=>$value['get_brand_category']['name']);
        }
      
     }

     if(!empty($sub_cat)){
        foreach ($sub_cat as $key => $value) {
          # code...
          $sub_cat_arr[] = array('category_id'=>$value['category_id'],'parent_id'=>$value['get_brand_category']['parent_id'],'name'=>$value['get_brand_category']['name']);
        }      
     }

     $all_cat_arr = $cat_view_arr = array();
     if(!empty($main_cat_arr)){

        foreach ($main_cat_arr as $key => $value) {
          $flag = 0;

          if(!empty($sub_cat_arr)){
            $k = 0;
            foreach ($sub_cat_arr as $key => $sub_value) {
             
              if($sub_value['parent_id']==$value['category_id']){
                 $cat_view_arr[$value['category_id']][] = array('main_cat'=>$value['name'],'subcategory'=>array('id'=>$sub_value['category_id'],'name'=>$sub_value['name']));
                 $flag=1;
                 $all_cat_arr[] =  $sub_value['category_id'];
                 $all_cat_arr[] = $value['category_id'];
                 $k++;
              }
             
            }
          }
        }
     }


    $all_cat_arr = array_unique($all_cat_arr);

    $individual_array = array();
    // Create a new sub-array
    if(!empty($main_cat_arr)){
        foreach ($main_cat_arr as $key => $value) {
          if(!in_array($value['category_id'], $all_cat_arr)){
             
              $individual_array[] = array('category_id'=>$value['category_id'],'name'=>$value['name']);

            }
          }
    }
    // Create a new sub-array
    if(!empty($sub_cat_arr)){
        foreach ($sub_cat_arr as $key => $value) {
          
          if(!in_array($value['category_id'], $all_cat_arr)){

              $individual_array[] = array('category_id'=>$value['category_id'],'name'=>$value['name']);

            }
          }
    }



    /*echo "<pre>"; print_r($individual_array);
    echo "<pre>"; print_r($cat_view_arr);
    echo "<pre>"; print_r($main_cat_arr);
    echo "<pre>"; print_r($sub_cat_arr);
    exit;*/
	   $total_no_of_products = Product::with(['product_category'=>function($q){
                                                $q->select(['id','name','parent_id','slug']);
                                      }])->whereHas('product_category',function($q) use ($sessionvalue){
                                                      $q->whereIn('gender_cat',array($sessionvalue,3));
                                      })->where('brand_id', '=', $brand_details['id'])->where('status',1);

      return view('frontend.home.category-product-listing',compact('brand_details', 'total_no_of_products', 'id', 'title', 'cat_view_arr', 'individual_array'));
      
    }

    public function getProductList(){

      $brand_id = Request::query('brand_id');
      $category_id = Request::query('category_id');
      $price_order = Request::input('order');
      $minprice = Request::input('minprice');
      $maxprice = Request::input('maxprice');
      $sessionvalue = Session::get('menwomen');
      // fetch product by brand_id
      $sql = Product::with(['product_category'=>function($q){
                                                $q->select(['id','name','parent_id','slug']);
                      }])->whereHas('product_category',function($q) use ($sessionvalue){
                                      $q->whereIn('gender_cat',array($sessionvalue,3));
                      })->where('brand_id', '=', $brand_id);

      // check if price sorting is working or not
      if($price_order!='')
        $sql = $sql->orderBy('price', $price_order);
      else
        $sql = $sql->orderBy('id', 'DESC');

      // Check catgory filter
      if($category_id!=''){
        $category_id = explode(',', $category_id);
        $sql = $sql->whereIn('category_id', $category_id);
      }else{
        $sql = $sql->where('category_id','!=', 1);
      }

      $max_price = $sql->max('price');

      if($maxprice){
        $sql = $sql->where('price', '>=', $minprice)->where('price', '<=', $maxprice);
      }
      $no_of_product = $sql->count();
      $products = $sql->offset(Request::input('offset'))->limit(Request::input('limit'))->get();

      $fix_max_price = (($maxprice=='')?$max_price:$maxprice);
        
      return view('frontend.designer_product.designer_product',compact('products','title','no_of_product','max_price','fix_max_price','minprice'));              
    }

}