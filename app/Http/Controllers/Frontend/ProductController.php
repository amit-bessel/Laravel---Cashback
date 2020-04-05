<?php namespace App\Http\Controllers\Frontend; /* path of this controller*/
// Define Model
use App\Model\Sitesetting;
use App\Model\Adverts; /* Model name*/
use App\Model\Brand; /* Model name*/
use App\Model\Category;
use App\Model\Product;
use App\Model\Wishlist;
use App\Model\BrandCategory; /* Model name*/
use App\Model\Vendor; /* Model name*/

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
use Redirect;
use Lang;
use App;
use Config;


class ProductController extends BaseController {
    public function __construct() 
    {
        parent::__construct();

    }
   /**
    * Display a listing of the resource.
    *
    * @return Response
    */
	public function viewProducts($id,$name=false){

        $search_keyword = Request::input('search_keyword');
        $category_id = base64_decode($id);
        $sessionvalue = Session::get('menwomen');
        $total_no_of_products = Product::where('category_id', '=', $category_id)->where('status',1)->count();

        $category_details = Category::where('id' , '=', $category_id)->first();
        if(count($category_details)==0){
            /*In any case category id not found, then redirect to home page.*/
            return redirect('/');
        }
        else{
            $category_details = $category_details->toArray();
        }

        $parent_category_id = $this->getMainCategoryId($category_id);

        $categories_levels = Category::with(['children'=>function($q) use ($sessionvalue){
                                                $q->whereIn('gender_cat',array($sessionvalue,3));
                                                $q->where('status',1);
                                }])->where('parent_id' , '=', $parent_category_id)->where('status',1)->whereIn('gender_cat',array($sessionvalue,3))->orderBy('name', 'asc')->get()->toArray();

        /**************  Making category array for secting submenues ***********/
        $category_id_array = array();
        foreach($categories_levels as $categories){
            $sub_category_id_array = array();
            $sub_category_id_array[] = $categories['id'];
            if(count($categories['children'])>0){
                foreach($categories['children'] as $sub_category_info){
                    $sub_category_id_array[] = $sub_category_info['id'];
                }
                
                $category_id_array[$categories['id']] = $sub_category_id_array;
                
            }
        }
        /*---------------------------------------------------------------------*/
       /* echo "<pre>";
        print_r($category_id_array);
        exit;*/
        $sub_category_details = array();
        if($category_details['parent_id'] != 0){

            $main_category_details = Category::with('parent')->where('id' , '=', $category_details['parent_id'])->where('status',1)->whereIn('gender_cat',array($sessionvalue,3))->orderBy('name', 'asc')->first();
            if(!empty($main_category_details))
                $main_category_details=$main_category_details->toArray();

            /*----  Fetching main category id(has parent_id=0)(Bhaskar) -------*/
            $category_id_with_layer = $this->getMainCategoryId($category_id);
            /*----------------------------------------------------------------*/

            /*$sub_category_details = Category::with('parent')->where('parent_id' , '=' , $category_id_with_layer)->where('status',1)->whereIn('gender_cat',array($sessionvalue,3))->orderBy('name', 'asc')->get()->toArray();*/
        }
        else{
            $main_category_details = Category::with('parent')->where('id' , '=', $category_details['id'])->where('status',1)->whereIn('gender_cat',array($sessionvalue,3))->orderBy('name', 'asc')->first();
            if(!empty($main_category_details))
                $main_category_details=$main_category_details->toArray();
            /*$sub_category_details = Category::with('parent')->where('parent_id' , '=' , $category_details['id'])->where('status',1)->whereIn('gender_cat',array($sessionvalue,3))->orderBy('name', 'asc')->get()->toArray();*/
        }

        /////################# Category all layer found ###############///
        $secnd_category = array();
        /*if(!empty($sub_category_details)){
            foreach ($sub_category_details as $key => $parrent_id) {

                $sub_category_details[$key]['layer_one'] = Category::where('parent_id' , '=' , $parrent_id['id'])->where('status',1)->whereIn('gender_cat',array($sessionvalue,3))->orderBy('name', 'asc')->get()->toArray();

                foreach ($sub_category_details[$key]['layer_one'] as $newkey => $newvalue) {

                    $sub_category_details[$key]['layer_one'][$newkey]['layer_two'] = Category::where('parent_id' , '=' , $newvalue['id'])->where('status',1)->whereIn('gender_cat',array($sessionvalue,3))->orderBy('name', 'asc')->get()->toArray();
                }
            }
        }   */   
        /*echo "<pre>";
        print_r($main_category_details);
        echo "<pre>";
        print_r($sub_category_details);
        exit;*/
        /////################# Category all layer found ###############///

        //$brand_id = Product::where('category_id', '=', $category_id)->where('status',1)->groupBy('brand_id')->get()->toArray();

        $brand_id = BrandCategory::where('category_id',$category_id)->get();
        if(count($brand_id)>0){
            $brand_id = $brand_id->toArray();
        }
        
        $brand_name = array();
        foreach($brand_id as $val){
         
            $brand_info = Brand::where('id','=',$val['brand_id'])->where('status',1)->first();
            if(count($brand_info)>0){
                $brand_name[] = $brand_info->toArray();
            }
        }
        asort($brand_name);
        $title = ucwords($category_details['name']);
        //print_r($categories_levels);exit;
        return view('frontend.home.product_listing',compact('category_details', 'total_no_of_products', 'main_category_details', 'category_id', 'brand_name', 'title', 'sub_category_details','search_keyword','name','category_details','categories_levels','category_id_array'));
    }

    /********************************************************************************
     *                              COUNT CATEGORY LAYER                            *
     *******************************************************************************/

    public function getMainCategoryId($category_id=""){
        
        $categories     = Category::where('id',$category_id)->first();
        if($categories->parent_id==0)
            return $categories->id;
        else{
            //$count_layer++;
            return Self::getMainCategoryId($categories->parent_id);
        }

    }

    public function getProductList(){
        
        $search_keyword = Request::input('search_key');
        $cat_id = trim(Request::query('category_id'));
        $price_search = Request::input('order');
        $brand_id = Request::input('brand_id');
        $minprice = Request::input('minprice');
        $maxprice = ((Request::input('maxprice')!='')?Request::input('maxprice'):'9999999999');
        $sessionvalue = Session::get('menwomen');

        if(Request::input('limit')){
            $limit = Request::input('limit');
        }else{
            $limit = 90;
        }

        if($brand_id!=''){
            $where_arr = " brand_id IN (".trim($brand_id,',').")";
        }
        else
            $where_arr = " 1 = 1 ";
        
        if(!empty($cat_id)){
            if(!empty($price_search)){

                $products = Product::with(['product_category'=>function($q){
                                                $q->select(['id','gender_cat']);
                                }])->whereHas('product_category',function($q) use ($sessionvalue){
                                                $q->whereIn('gender_cat',array($sessionvalue,3));
                                })->whereRaw($where_arr)->where('category_id', '=', $cat_id)->where('price', '>=', $minprice)->where('price', '<=', $maxprice)->where('name', 'like', '%' .$search_keyword. '%')->where('status',1)->orderBy('price',$price_search)->groupBy('name')->offset(Request::input('offset'))->limit(Request::input('limit'))->get();
                // count no. of products
                $no_of_product = Product::with(['product_category'=>function($q){
                                                $q->select(['id','gender_cat']);
                                }])->whereHas('product_category',function($q) use ($sessionvalue){
                                                $q->whereIn('gender_cat',array($sessionvalue,3));
                                })->whereRaw($where_arr)->where('category_id', '=', $cat_id)->where('price', '>=', $minprice)->where('price', '<=', $maxprice)->where('name', 'like', '%' .$search_keyword. '%')->where('status',1)->orderBy('price',$price_search)->groupBy('name')->get();
                $no_of_product = count($no_of_product);
                $max_price = Product::with(['product_category'=>function($q){
                                                $q->select(['id','gender_cat']);
                                }])->whereHas('product_category',function($q) use ($sessionvalue){
                                                $q->whereIn('gender_cat',array($sessionvalue,3));
                                })->whereRaw($where_arr)->where('category_id', '=', $cat_id)->where('name', 'like', '%' .$search_keyword. '%')->where('status',1)->max('price');

            }else{
                
                $products = Product::with(['product_category'=>function($q){
                                                $q->select(['id','gender_cat']);
                                }])->whereHas('product_category',function($q) use ($sessionvalue){
                                                $q->whereIn('gender_cat',array($sessionvalue,3));
                                })->whereRaw($where_arr)->where('category_id', '=', $cat_id)->where('price', '>=', $minprice)->where('price', '<=', $maxprice)->where('name', 'like', '%' .$search_keyword. '%')->where('status',1)->orderBy('id', 'DESC')->groupBy('name')->offset(Request::input('offset'))->limit(Request::input('limit'))->get();

                // count no. of products
                 $no_of_product = Product::with(['product_category'=>function($q){
                                                $q->select(['id','gender_cat']);
                                }])->whereHas('product_category',function($q) use ($sessionvalue){
                                                $q->whereIn('gender_cat',array($sessionvalue,3));
                                })->whereRaw($where_arr)->where('category_id', '=', $cat_id)->where('price', '>=', $minprice)->where('price', '<=', $maxprice)->where('name', 'like', '%' .$search_keyword. '%')->where('status',1)->orderBy('id', 'DESC')->count(DB::raw('DISTINCT name'));
                //$no_of_product = count($no_of_product);
//echo "<pre>";print_r($products) ;exit;
                $max_price = Product::with(['product_category'=>function($q){
                                                $q->select(['id','gender_cat']);
                                }])->whereHas('product_category',function($q) use ($sessionvalue){
                                                $q->whereIn('gender_cat',array($sessionvalue,3));
                                })->whereRaw($where_arr)->where('category_id', '=', $cat_id)->where('name', 'like', '%' .$search_keyword. '%')->where('status',1)->max('price');
            }
        }
        //exit;
        $fix_max_price = ((Request::input('maxprice')=='')?$max_price:$maxprice);

        //echo "<pre>";print_r($products) ;exit;
        return view('frontend.home.product',compact('products','no_of_product','max_price','fix_max_price','minprice'));
    }




    public function viewsearchProducts(){

        $search_keyword = Request::input('header_serach_by');
        $brand_id = Request::input('search_by');
        $sessionvalue = Session::get('menwomen');

        $total_no_of_products = Product::where('name', 'like', '%' .$search_keyword. '%')->where('status',1)->count();

        /*$related_cats = Product::with(['product_category'=>function($q){
                                                $q->select(['id','name','parent_id','slug']);
                                }])->whereHas('product_category',function($q) use ($sessionvalue){
                                                $q->whereIn('gender_cat',array($sessionvalue,3));
                                })->with(['product_brand'=>function($q){
                                                $q->select(['id','brand_name']);
                                }])->where('category_id', '!=', 1)->where('name', 'like', '%' .$search_keyword. '%')->where('status',1)->select('id','category_id','brand_id')->groupBy('category_id')->get()->toArray();*/

        /* total array found */
        $related_cats = Product::with(['product_category'=>function($q){
                                                $q->select(['id','name','parent_id','slug']);
                                }])->whereHas('product_category',function($q) use ($sessionvalue){
                                                $q->whereIn('gender_cat',array($sessionvalue,3));
                                })->with(['product_brand'=>function($q){
                                                $q->select(['id','brand_name']);
                                }])->where('category_id', '!=', 1)->where('status',1);

        if(!empty($brand_id)){
			$related_cats = $related_cats->where('brand_id',$brand_id)->select('id','category_id','brand_id','advertiser-id','advertiser-name')->groupBy('category_id')->groupBy('advertiser-id')->get()->toArray();

			$brand_details = Brand::where('id',$brand_id)->first()->toArray();
        }else{
			$related_cats = $related_cats->where('name', 'like', '%' .$search_keyword. '%')->select('id','category_id','brand_id','advertiser-id','advertiser-name')->groupBy('category_id')->groupBy('advertiser-id')->get()->toArray();

			$brand_details = array();      	
        }
        //echo "<pre>";print_r($related_cats);exit;
        $total_found_cat = array();
        foreach($related_cats as $key=>$all_category){
            
            $total_found_cat[$key]['id'] = $all_category['category_id'];
            $total_found_cat[$key]['name'] = $all_category['product_category']['name'];
            $total_found_cat[$key]['parent_id'] = $all_category['product_category']['parent_id'];
        }
         /* total array found */

        //############### Main Parent Check #######################//                        
        $main_parren_category_arr = $first_child_category_arr = $secnd_child_category_arr = $brand_arr = $brand_array_ids = $vendor_arr = $vendor_array_ids = $indiviual_cat_array = $cat_id_1 = $cat_id_2 = array();
        foreach($related_cats as $key=>$parent_category){

            if($parent_category['product_category']['parent_id'] == 0){
            	if(!in_array($parent_category['category_id'], $cat_id_1)){
	                $main_parren_category_arr[] = $parent_category;
	                $cat_id_1[] = $parent_category['category_id'];
            	}
            }

            //################ Brand Array generate ######################//
            if($parent_category['brand_id'] != 0){
                if(!in_array($parent_category['product_brand']['id'], $brand_array_ids)){

                    $brand_arr[$key]['id'] = $parent_category['product_brand']['id'];
                    $brand_arr[$key]['brand_name'] = $parent_category['product_brand']['brand_name'];
                    $brand_array_ids[] = $parent_category['product_brand']['id'];

                }
            }
            //################ Brand Array generate ######################//

            //################ Brand Array generate ######################//
            if($parent_category['advertiser-id'] != 0){
                if(!in_array($parent_category['advertiser-id'], $vendor_array_ids)){

                    $vendor_arr[$key]['id'] = $parent_category['advertiser-id'];
                    $vendor_arr[$key]['vendor_name'] = $parent_category['advertiser-name'];
                    $vendor_array_ids[] = $parent_category['advertiser-id'];

                }
            }
            //################ Brand Array generate ######################//
        }
        if($brand_arr){
            //$brand_arr =array_unique($brand_arr);
        }
        //############### Main Parent Check #######################//

        //############### Main Child Check #######################//
        foreach($main_parren_category_arr as $key=>$parent_category){

            $parrent_cat_id = $parent_category['category_id'];
            foreach ($related_cats as $key1 => $child_category) {

               if($child_category['product_category']['parent_id'] == $parrent_cat_id){
            		if(!in_array($child_category['category_id'], $cat_id_2)){
	                    $first_child_category_arr[] = $child_category;
	                    $main_parren_category_arr[$key]['child_cat'][] = $child_category;
	                    $cat_id_2[] = $child_category['category_id'];
                	}
               }
            }
        }
        //############### Main Child Check #######################//

        //############### 2nd Layer Child Check #######################//
        /*foreach($main_parren_category_arr as $key=>$first_child_category){

            $first_child_arr = $first_child_category['child_cat'];
            foreach ($first_child_arr as $key1 => $se_child_category) {

               foreach ($total_found_cat as $key1 => $single_cat_id) { 

                   if($single_cat_id['parent_id'] == $se_child_category['category_id']){

                        $secnd_child_category_arr[] = $se_child_category;
                        $aaaaa[][$key]['child_cat'][] = $se_child_category;
                        $cat_id_3[] = $se_child_category['category_id'];
                   }
               }
            }
        }
        echo '<pre>';print_r($aaaaa);exit;*/
        //############### 2nd Layer Child Check #######################//

        //############### Main category remain cate ######################//

        /* Array merge for all used id */
        if(!empty($cat_id_1) && !empty($cat_id_2) && !empty($cat_id_3)){
            $used_cat_id = array_merge($cat_id_1,$cat_id_2,$cat_id_3);
        }elseif(empty($cat_id_3) && !empty($cat_id_2)){
            $used_cat_id = array_merge($cat_id_1,$cat_id_2);
        }elseif(!empty($cat_id_1)){
            $used_cat_id = $cat_id_1;
        }else{
            $used_cat_id = array();
        }

        $used_cat_id = array_unique($used_cat_id);
        $indiviual_cat_id_array = array();
        foreach($total_found_cat as $key=>$all_category){
            
            if(!in_array($all_category['id'], $used_cat_id)){
            	if(!in_array($all_category['id'], $indiviual_cat_id_array)){
                $indiviual_cat_array[] = $all_category;
                $indiviual_cat_id_array[] = $all_category['id'];
            	}
            }
        }

        /*foreach($indiviual_cat_array as $key=>$indivisual_category){
            
            if(!in_array($indivisual_category['id'], $indiviual_cat_id_array)){
                $indiviual_cat_array[] = $all_category;
            }
        }*/
        //############### Main category remain cate ######################//
        //echo '<pre>';print_r($indiviual_cat_id_array);exit;
        return view('frontend.home.search_product_listing',compact('main_parren_category_arr','indiviual_cat_array','brand_arr','vendor_arr','brand_details','total_no_of_products','search_keyword','brand_id'));
    }

    function getSearchProductList(){

        $search_keyword = Request::input('search_key');
        $search_check = Request::input('search_check');
        $vendor_id = Request::input('vendor_id');
        $cat_id = trim(Request::query('category_id'));
        $price_search = Request::input('order');
        $brand_id = Request::input('brand_id');
        $minprice = Request::input('minprice');
        $maxprice = ((Request::input('maxprice')!='')?Request::input('maxprice'):'9999999999');
        $sessionvalue = Session::get('menwomen');
        $max_price = 0;
        $where_arr = "";

        if($search_check ==''){
            $where_arr .= " name LIKE '%".addslashes($search_keyword)."%'";
        }

        if(Request::input('limit')){
            $limit = Request::input('limit');
        }else{
            $limit = 90;
        }
        if($brand_id!=''){
            $where_arr .= ($search_check=='')?'AND ':'';
            $where_arr .= " brand_id IN (".trim($brand_id,',').")";
        }

        if($vendor_id){
        	$vendor_id_arr = explode(',', $vendor_id);
            $where_arr .= " AND `advertiser-id` IN (".$vendor_id.")";
        }

        if(!empty($price_search)){
            $order_by = "price ".$price_search;
        }else{
            $order_by = "id DESC";
        }
        
        if(!empty($cat_id)){
            $cat_id_arr = explode(',', $cat_id);
            $products = Product::with(['product_category'=>function($q){
	                                                $q->select(['id','gender_cat']);
	                                }])->whereHas('product_category',function($q) use ($sessionvalue){
	                                                $q->whereIn('gender_cat',array($sessionvalue,3));
	                                })->whereRaw($where_arr)->whereIn('category_id',$cat_id_arr)->where('price', '>=', $minprice)->where('price', '<=', $maxprice)->where('status',1)->groupBy('name')->orderByRaw($order_by)->offset(Request::input('offset'))->limit(Request::input('limit'))->get();

	                // count no. of products
	                $no_of_product = Product::with(['product_category'=>function($q){
	                                                $q->select(['id','gender_cat']);
	                                }])->whereHas('product_category',function($q) use ($sessionvalue){
	                                                $q->whereIn('gender_cat',array($sessionvalue,3));
	                                })->whereRaw($where_arr)->whereIn('category_id', $cat_id_arr)->where('price', '>=', $minprice)->where('price', '<=', $maxprice)->where('status',1)->count(DB::raw('DISTINCT name'));

	                $max_price = Product::with(['product_category'=>function($q){
	                                                $q->select(['id','gender_cat']);
	                                }])->whereHas('product_category',function($q) use ($sessionvalue){
	                                                $q->whereIn('gender_cat',array($sessionvalue,3));
	                                })->whereRaw($where_arr)->whereIn('category_id',$cat_id_arr)->where('status',1)->max('price');
        }else{
        		$products = Product::with(['product_category'=>function($q){
                                                    $q->select(['id','gender_cat']);
                                    }])->whereHas('product_category',function($q) use ($sessionvalue){
                                                    $q->whereIn('gender_cat',array($sessionvalue,3));
                                    })->whereRaw($where_arr)->where('category_id', '!=', 1)->where('price', '>=', $minprice)->where('price', '<=', $maxprice)->where('status',1)->groupBy('name')->orderByRaw($order_by)->offset(Request::input('offset'))->limit($limit)->get();

	            $no_of_product = Product::with(['product_category'=>function($q){
                                                    $q->select(['id','gender_cat']);
                                    }])->whereHas('product_category',function($q) use ($sessionvalue){
                                                    $q->whereIn('gender_cat',array($sessionvalue,3));
                                    })->whereRaw($where_arr)->where('category_id', '!=', 1)->where('price', '>=', $minprice)->where('price', '<=', $maxprice)->where('status',1)->count(DB::raw('DISTINCT name'));
                
	           $max_price = Product::with(['product_category'=>function($q){
                                                    $q->select(['id','gender_cat']);
                                    }])->whereHas('product_category',function($q) use ($sessionvalue){
                                                    $q->whereIn('gender_cat',array($sessionvalue,3));
                                    })->whereRaw($where_arr)->where('category_id', '!=', 1)->where('status',1)->max('price');
        }

        $fix_max_price = ((Request::input('maxprice')=='')?$max_price:$maxprice);
       //echo "<pre>";print_r($no_of_product) ;exit;
       return view('frontend.home.product',compact('products','no_of_product','max_price','fix_max_price','minprice'));
    }

    public function viewProductDetails($id){

        $id = base64_decode($id);
        $sessionvalue = Session::get('menwomen');
        $product_details = Product::with('product_brand')
                                ->leftJoin('all_vendors', 'all_vendors.advertiser-id', '=', 'products.advertiser-id')
                                ->leftJoin('brands', 'brands.id', '=', 'products.brand_id')
                                ->select('products.*','products.description as pd_description','products.id as pd_id','all_vendors.*','brands.*')
                                ->where('products.id' , '=', $id)->first()->toArray();
        /*echo "<pre>";
        print_r($product_details);
        $var1 = unserialize($product_details['shiping_details']);  
        print_r($var1);
        exit;*/
        $related_products = Product::with(['product_category'=>function($q){
                                            $q->select(['id','gender_cat']);
                            },'product_vendor'=>function($qv){
                                            $qv->select(['advertiser-id']);
                            }])->whereHas('product_category',function($q) use ($sessionvalue){
                                            $q->whereIn('gender_cat',array($sessionvalue,3));
                            })
                            ->orderBy(DB::raw('RAND()'))->where('category_id' , '=', $product_details['category_id'])->where('id' , '!=', $product_details['pd_id'])->where('advertiser-id','=',$product_details['advertiser-id'])->limit(10)->get();
        
        $title = stripslashes($product_details['name']);
        
        if($product_details['api'] == 'LS'){
            $details =unserialize($product_details['pd_description']); 

            $dtl = '';
            if(!empty($details['long'])){
              //$dtl = implode(",",$details['long']);
                $dtl = $details['long'];
            }else{
                $dtl = $details['short'];
            }
        }else{
            $dtl = $product_details['pd_description'];
        }
        
        $og_description = $dtl;
        $og_url = url()."/product_details/".base64_encode($id);
        $og_image = $product_details['image_url'];

        $site_setting = Sitesetting::all();
        $site_settings = Array();
        foreach($site_setting as $site){
            $site_settings[$site['name']] = $site['value'];
        }
        
        //echo "<pre>";print_r($title); exit;
        return view('frontend.home.product-details',compact('product_details','related_products','title','og_description','og_url','og_image','site_settings'));
    }

    public function addToWishlist($id){

        $id = base64_decode($id);
        if(Session::has('user_id')){ 

            $user_id = Session::has('user_id');
            $wishlist = new Wishlist;

            $wishlist->user_id = $user_id;
            $wishlist->product_id = $id;
            $wishlist->save();
            
            redirect('');

        }else{

            return view('frontend.home.login',compact('id'));
        }
    }

    public function getProductCashback($id="")
    {
        if(is_numeric($id)){
            
            $product_detail =Product::where('id',$id)->first()->toArray();

            if(!empty($product_detail)){
               $product_details = $product_detail;
            }else{
                abort(404);
            }
        }else{
            abort(404);
        }
        
        //echo "<pre>";print_r($vendor_details);exit;
        $title = $product_details['advertiser-name'];
        
        return view('frontend.cashbackpage.product-percentage',compact(
                        'product_details','title'
                          )
                    );
    }

    function postSetSessionProductId()
    {
        $data = Request::all();
        Session::put('product_id', $data['product_id']);
        return 1;
    }

    function postForgotSessionProductId()
    {
        Session::forget('product_id');
        return 1;
    }

}
