<?php namespace App\Http\Controllers\Admin;

use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;

use App\Model\Category; /* Model name*/
use App\Model\SubCategory; /* Model name*/
use App\Model\Product; /* Model name*/
use App\Model\Brand; /* Model name*/
use App\Model\BrandCategory; /* Model name*/
use App\Model\Vendor; /* Model name*/



use App\User;
use App\Http\Requests;
use App\Helper\helpers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Request;
use Input; /* For input */
use Validator;
use Session;
use DB;
use Mail;
use Hash;
use Auth;
use Cache;


class ProductController extends BaseController {

   /**
    * Display a listing of the resource.
    *
    * @return Response
    */
    public function __construct() {
        parent::__construct();
    }
    
	/********************************************************************************
	 *								GET PRODUCT LIST								*
	 *******************************************************************************/
    function getList()
    {
		/*echo "Test";
		exit;*/
        $module_head 		= "Product List";
        $product_class 		= "active";
        $record_per_page    = 100;
        $search_key 		= Request::query('search_key');
        $active 			= Request::query('active');
        $srch_category_id	= Request::query('srch_category_id');
        $srch_brand_id		= Request::query('srch_brand_id');
        $srch_vendor_id		= Request::query('srch_vendor_id');
        $srch_gender_id     = Request::query('srch_gender_id');
		$title				= "product Management";
        $where 				= '';
		
		$sl_no				= 1;

		if(Request::input('page')!=''){
			$sl_no = ((Request::input('page')-1)*$record_per_page)+1;
		}

        if($search_key != '')
		{
            $where 			= "`name` LIKE '%".addslashes($search_key)."%' AND ";
        }
        if($active != '')
		{
            $where 		   .= "`status`= '".$active."' AND ";
        }
        if($srch_category_id != '')
		{
            $where 		   .= "`category_id`= '".$srch_category_id."' AND ";
        }
        if($srch_brand_id != '')
		{
            $where 		   .= "`brand_id`= '".$srch_brand_id."' AND ";
        }
        if($srch_vendor_id != '')
		{
            $where 		   .= "(`ad-id`= '".$srch_vendor_id."' OR `advertiser-id`= '".$srch_vendor_id."' ) AND ";
        }
        if($srch_gender_id != '')
        {
            $where_category          = array($srch_gender_id);
        }
        else{
            $where_category          = array(1,2,3);
        }
       /* echo "<pre>";
        print_r($where_category);
        exit;*/
		$where 		   .= '1';
        $products		= Product::with('product_category')
                                ->whereHas('product_category',function($q) use ($where_category){
                                                $q->whereIn('gender_cat',$where_category);
                                        })
                                ->whereRaw($where)->orderBy('name','ASC')->paginate($record_per_page);
		//print_r($products);exit;
		//$category_array = Category::with('children')->where('parent_id',0)->orderBy('name','ASC')->get();
        $category_array = Category::with('children')->orderBy('name','ASC')->get();
		if(count($category_array)>0){
			$category_array	= $category_array->toArray();
		}

		$brand_array = Brand::orderBy('brand_name','ASC')->get();
		if(count($brand_array)>0){
			$brand_array	= $brand_array->toArray();
		}

		$cj_vendors  = Product::whereIN('api',array('LS','CJ'))->orderBy('id','ASC')->groupby('advertiser-id')->get(['advertiser-id','advertiser-name']);
		if(count($cj_vendors)>0){
			$cj_vendors	= $cj_vendors->toArray();
		}

		/*$linkshare_vendors  = Product::where('api','LS')->orderBy('id','ASC')->groupby('advertiser-id')->get(['advertiser-id','advertiser-name']);
		if(count($linkshare_vendors)>0){
			$linkshare_vendors	= $linkshare_vendors->toArray();
		}

		if(!empty($cj_vendors) && !empty($linkshare_vendors)){
			//$all_vendor = array_merge($cj_vendors,$linkshare_vendors);
		}else{
			$all_vendor = $linkshare_vendors;
		}*/
		
		$all_vendor = $cj_vendors;
		/*echo "<pre>";
        print_r($products);
        exit;*/
		
        return view('admin.products.product_list',compact(
														'products','product_class',
														'module_head','search_key','category_array','brand_array','all_vendor',
														'active','title','sl_no','srch_category_id','srch_brand_id','srch_vendor_id'
													)
					);
    }

	
	/********************************************************************************
	 *									VIEW PRODUCT 								*
	 *******************************************************************************/
    function getView($product_id='')
	{
        $product_class 			= "active";
        $module_head 			= "View Product Details";
		$product_id				= base64_decode($product_id);
        $product_details 		= Product::with('product_category')->where('id',$product_id)->first();
        if(count($product_details)>0){
        	$product_details = $product_details->toArray();
        }
        
        $title					= "View Product Details";
        $category_details = Category::find($product_details['category_id']);
        $brand_details = Brand::find($product_details['brand_id']);
		return view('admin.products.view_product',compact(
			'module_head',
			'product_id',
			'product_class',
			'product_details',
			'category_details',
			'brand_details',
			'title'));
    }
	
	
	/********************************************************************************
	 *							REMOVE PRODUCT										*
	 *******************************************************************************/
	function getRemove($id=""){
		
		$category_details   = Product::find($id);

        $brand_id           = $category_details->brand_id;
        $category_id        = $category_details->category_id;
        
        $category_details->delete();

        $this->checkProductExistWithOldBrandCategory($brand_id,$category_id);
        Session::flash('success_message', 'Product has been removed successfully.'); 
        Session::flash('alert-class', 'alert alert-success'); 
        return redirect('admin/products/list');
	
	}
	
	/********************************************************************************
	 *							CHANGE STATUS										*
	 *******************************************************************************/
	function getStatus(){
		
		$post_data  = Request::all();
        $status 	= $post_data['this_val'];
        $id 		= $post_data['this_id'];
        $product 	= Product::find($id);
        $product->status = $status;
        $product->save();
        echo "1";
		exit();
	
	}

	/********************************************************************************
	 *							CHANGE PRODUCT CATEGORY								*
	 *******************************************************************************/
	function getChangeCategory(){
		
		$post_data  = Request::all();
        $product 	= Product::find($post_data['product_id']);

        $new_category_id      = $post_data['category_id'];
        $old_category_id      = $product->category_id;
        /********************* Product New Brnad And Category *********************/
        if($product->brand_id!=0)
            $this->productNewBrandCategory($product->brand_id,$new_category_id);

        $old_product_category = $product->category_id;
        $product->category_id = $post_data['category_id'];
        $product->save();

        /*********** Checking Product Exist With Old Brand And Category ************/
        if($product->brand_id!=0)
            $this->checkProductExistWithOldBrandCategory($product->brand_id,$old_category_id);

        $category 	= Category::find($post_data['category_id']);
        echo "1"."@@".$category->name;
		exit();
	
	}

	function getSearchProduct(){

    	$term = $_GET['term'];
    	$order_details_arr = array();
        $order_arr = Product::where('name','LIKE',$term.'%')->get();
        if(!empty($order_arr))
		{
			$order_arr = $order_arr->toArray();
			$i = 0;
			foreach($order_arr as $order_list)
			{
				$order_details_arr[$i]['key'] = $order_list['id'];
				$order_details_arr[$i]['value'] = $order_list['name'];
				$i++;
			}
		}
		echo json_encode($order_details_arr);
		exit;

    }

    function getEdit($product_id=''){

    	$product_class 			= "active";
        $module_head 			= "Edit Product Details";
		$product_id				= base64_decode($product_id);

        $search_key             = Request::query('search_key');
        $active                 = Request::query('active');
        $srch_category_id       = Request::query('srch_category_id');
        $srch_brand_id          = Request::query('srch_brand_id');
        $srch_vendor_id         = Request::query('srch_vendor_id');

        $product_details 		= Product::with('product_category')->where('id',$product_id)->first();
        if(count($product_details)>0){
        	$product_details = $product_details->toArray();
        }
        /*echo "<pre>";
        print_r($product_details);
        exit;*/
        $title					= "Edit Product Details";
        $category = Category::all();
        $brand = Brand::all();
		return view('admin.products.edit_product',compact(
			'module_head',
			'product_id',
			'product_class',
			'product_details',
			'category',
			'brand',
			'title',
            'search_key',
            'active',
            'srch_category_id',
            'srch_brand_id',
            'srch_vendor_id'));
    	
    	
    }
    function postEdit(){

    	$data = Request::all();
    	/*echo "<pre>";
    	print_r($data);exit;*/

        /* Serialize product description */
        $search_key             = $data['search_key'];
        $active                 = $data['active'];
        $srch_category_id       = $data['srch_category_id'];
        $srch_brand_id          = $data['srch_brand_id'];
        $srch_vendor_id         = $data['srch_vendor_id'];

        $pd_desc_data = array();
        $pd_desc_data['long']   = $data['description'];
        $pd_desc_data['short']  = $data['description'];
        $srze_pd_desc   = serialize($pd_desc_data);
        
        /* Serialize product description */

    	$product 	= Product::find($data['product_id']);
    	$old_product_category = $product->category_id;

    	$category_id = Category::find($data['category_id']);
    	$parent_id = $category_id->parent_id;
    	if($parent_id != 0){
    		$cat_id = Category::find($parent_id);
    		$add_category = $cat_id->name." > ".$category_id->name;
    	}
    	else{
    		$add_category = $category_id->name;
    	}

        $new_brand_id       = $data['brand'];
        $new_category_id    = $data['category_id'];

        $old_brand_id       = $product->brand_id;
        $old_category_id    = $product->category_id;

        /********************* Product New Brnad And Category *********************/
        $this->productNewBrandCategory($new_brand_id,$new_category_id);

        /***********************    Updating product details    *******************/
    	Product::where('id', '=', $data['product_id'])->update(['category_id' => $data['category_id'], 'name' => $data['name'], 
    		'advertiser-category' => $add_category, 'in-stock' => $data['stock'], 'price' => $data['price'], 'retail_price' => $data['retail_price'], 'brand_id' => $data['brand'], 'description' => $srze_pd_desc, 'status' => $data['status']]);
        /*-------------------------------------------------------------------------*/

        /*********** Checking Product Exist With Old Brand And Category ************/
        $this->checkProductExistWithOldBrandCategory($old_brand_id,$old_category_id);

		Session::flash('success_message', 'Product updated successfully'); 
		Session::flash('alert-class', 'alert alert-success'); 
		return redirect('/admin/products/list/?search_key='.$search_key.'&active='.$active.'&srch_category_id='.$srch_category_id.'&srch_brand_id='.$srch_brand_id.'&srch_vendor_id='.$srch_vendor_id);

    }

    /********************************************************************************
     *                          POPULAR PRODUCT                                     *
     *******************************************************************************/
    function getPopularProduct(){
        
        $post_data  = Request::all();
       /* echo "<pre>";
        print_r($post_data);
        exit;*/
        /*$commom_popular_product  = 0;
        $men_popular_product     = 0;
        $women_popular_product   = 0;

        $popular_products  = Product::with('product_category')->where('popular_product',1)->where('category_id',1)->get();
        if(count($popular_products)>0){
            $popular_products = $popular_products->toArray();
          
            foreach($popular_products as $product_category_details){
                $gender_cat_id = $product_category_details['product_category']['gender_cat'];
                if($gender_cat_id==3){
                    $commom_popular_prodct++;
                }
                if($gender_cat_id==2){
                    $women_popular_prodct++;
                }
                if($gender_cat_id==1){
                    $men_popular_prodct++;
                }
            }
        }
        exit;*/
        $count_popular_product = Product::with('product_category')->where('popular_product',1)->count();
        
        $product    = Product::find($post_data['product_id']);
        $product->popular_product = $post_data['popular_product'];
        $product->save();
        echo "1";
        /*if($post_data['popular_product']==0){
            $product->popular_product = $post_data['popular_product'];
            $product->save();
            echo "1";
        }
        else{
            if($count_popular_product>=10){

                echo "2";
            }
            else {
                $product->popular_product = $post_data['popular_product'];
                $product->save();
                echo "1";
            }
        }*/
        
        
        exit();
    
    }

}
