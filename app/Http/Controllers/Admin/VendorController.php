<?php namespace App\Http\Controllers\Admin;

use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Model\Category; /* Model name*/
use App\Model\SubCategory; /* Model name*/
use App\Model\Product; /* Model name*/
use App\Model\Brand; /* Model name*/
use App\Model\Vendor; /* Model name*/
use App\Helper\CropAvatar;

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


class VendorController extends BaseController {

   /**
    * Display a listing of the resource.
    *
    * @return Response
    */
     public function __construct() 
    {
        parent::__construct();

    }
    
	/********************************************************************************
	 *								GET PRODUCT LIST								*
	 *******************************************************************************/
	public function paginate($items,$perPage)
    {
        $pageStart = \Request::get('page', 1);
        // Start displaying items from this number;
        $offSet = ($pageStart * $perPage) - $perPage; 

        // Get only the items you need using array_slice
        $itemsForCurrentPage = array_slice($items, $offSet, $perPage, true);

        return new LengthAwarePaginator($itemsForCurrentPage, count($items), $perPage,Paginator::resolveCurrentPage(), array('path' => Paginator::resolveCurrentPath()));
    }

    function getList()
    {
		/*echo "Test";
		exit;*/
        $module_head 		= "Vendor List";
        $vendor_class 		= "active";
        $record_per_page    = 100;
        $page 				= Input::get('page', 1);
		$paginate 			= 100;
        $search_key 		= Request::query('search_key');
        $active 			= Request::query('active');
		$title				= "Vendor Management";
        $where 				= '';
		
		$sl_no				= 1;

		if(Request::input('page')!=''){
			$sl_no = ((Request::input('page')-1)*$record_per_page)+1;
		}

		if($search_key != '')
		{
            $where 	= "`advertiser-name` LIKE '%".$search_key."%'";
        }else{
        	$where 	.= '1';
        }
        
        $linkshare_vendors  = array();
        if($active == 'LS'){

        	//$linkshare_vendors  = Product::where('api','LS')->whereRaw($where)->orderBy('id','ASC')->groupby('advertiser-id')->select('api','percentage','ad-id','advertiser-id','advertiser-name')->get()->toArray();

        	$all_vendors_tbl  = Vendor::where('api','LS')->whereRaw($where)->orderBy('id','ASC')->select('id','api','percentage','advertiser-id','advertiser-name','vendor_url','status')->get()->toArray();

			$api_vendors = array_merge($linkshare_vendors,$all_vendors_tbl);

        }else if($active == 'CJ'){

        	//$linkshare_vendors  = Product::where('api','CJ')->whereRaw($where)->orderBy('id','ASC')->groupby('advertiser-id')->select('api','percentage','ad-id','advertiser-id','advertiser-name')->get()->toArray();

        	$all_vendors_tbl  = Vendor::where('api','CJ')->whereRaw($where)->orderBy('id','ASC')->select('id','api','percentage','advertiser-id','advertiser-name','vendor_url','status')->get()->toArray();

			$api_vendors = array_merge($linkshare_vendors,$all_vendors_tbl);

        }else{

        	/*$cj_vendors  = Product::where('api','CJ')->whereRaw($where)->orderBy('id','ASC')->groupby('advertiser-id')->select('api','percentage','ad-id','advertiser-id','advertiser-name');

			$linkshare_vendors  = Product::where('api','LS')->whereRaw($where)->orderBy('id','ASC')->groupby('advertiser-id')->select('api','percentage','ad-id','advertiser-id','advertiser-name')->union($cj_vendors)->get()->toArray();*/

			//$linkshare_vendors  = Product::where('api','CJ')->orwhere('api','LS')->whereRaw($where)->orderBy('id','ASC')->groupby('advertiser-id')->select('api','percentage','ad-id','advertiser-id','advertiser-name')->get()->toArray();

			$all_vendors_tbl  = Vendor::whereRaw($where)->orderBy('id','ASC')->select('id','api','percentage','advertiser-id','advertiser-name','vendor_url','status')->get()->toArray();

			$api_vendors = array_merge($linkshare_vendors,$all_vendors_tbl);
        }

        if($api_vendors){
        	$api_vendors = array_reverse($api_vendors);
        	$all_vendor_id = array();
        	foreach ($api_vendors as $key => $vendor_details) {

        		if(!in_array($vendor_details['advertiser-id'], $all_vendor_id)){

        			$all_api_vendors[] = $api_vendors[$key];
        			$all_vendor_id[] = $vendor_details['advertiser-id'];
        		}	
        	}
        }else{
        	$all_api_vendors = $api_vendors;
        }
		

		/*$offSet = ($page * $paginate) - $paginate;
		$itemsForCurrentPage = array_slice($linkshare_vendors, $offSet, $paginate, true);
		$all_vendors = new \Illuminate\Pagination\LengthAwarePaginator($itemsForCurrentPage, count($linkshare_vendors),Paginator::resolveCurrentPage(), array('path' => Paginator::resolveCurrentPath()));*/

		//$gdprecords2 = $linkshare_vendors;
		$all_vendors = self::paginate($all_api_vendors, $record_per_page);
		
		/*echo "<pre>";
        print_r($all_vendors);exit;*/
		
        return view('admin.vendor.vendor_list',compact(
														'all_vendors','product_class',
														'module_head','search_key',
														'active','title','sl_no'
													)
					);
    }


    ######################### vendor percentage chnage #############################
    public function postChnagePercentage(){

		$post_data  = Request::all();

		$explode_data = explode('=', $post_data['pk']);

		$product_table_vendor = Product::where('advertiser-id', '=', $explode_data[0])->count();
		
		if($product_table_vendor){

	        if($explode_data[1] == 'LS'){
	        	Product::where('advertiser-id', '=', $explode_data[0])->update(['percentage' => $post_data['value']]);
	        }else{
	        	Product::where('advertiser-id', '=', $explode_data[0])->update(['percentage' => $post_data['value']]);
	        }
	    }

	    Vendor::where('advertiser-id', '=', $explode_data[0])->update(['percentage' => $post_data['value']]);

		echo "1";
		exit();
	}
    ######################### PRODUCT percentage chnage #############################

     ######################### vendor url chnage #############################
    public function postChnageVendorUrl(){

		$post_data  = Request::all();

		$explode_data = explode('=', $post_data['pk']);

	    Vendor::where('advertiser-id', '=', $explode_data[0])->update(['vendor_url' => $post_data['value']]);

		echo "1";
		exit();
	}
    ######################### PRODUCT url chnage #############################

    ################################### SEARCH VENDOR BY NAME ########################
    function getSearchVendor(){

    	$term = $_GET['term'];
    	$order_details_arr = array();

        $vendor_arr = Product::where('advertiser-name','LIKE','%'.$term.'%')->select('api','advertiser-id','ad-id','advertiser-name')->groupby('advertiser-name')->get();

        /*echo "<pre>";
        print_r($vendor_arr);exit;*/
        if(!empty($vendor_arr))
		{
			$vendor_arr = $vendor_arr->toArray();
			$i = 0;
			foreach($vendor_arr as $vendor_list)
			{
				if($vendor_list['api'] == 'CJ'){
					$order_details_arr[$i]['key'] = $vendor_list['advertiser-id'];
				}else{
					$order_details_arr[$i]['key'] = $vendor_list['advertiser-id'];
				}
				
				$order_details_arr[$i]['value'] = $vendor_list['advertiser-name'];
				$i++;
			}
		}
		echo json_encode($order_details_arr);
		exit;
    }
    ################################### SEARCH VENDOR BY NAME ########################
	
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
	 *							REMOVE VENDOR										*
	 *******************************************************************************/
	function getRemoveVendor($id=""){
		
		$post_data  = Request::all();
        $status 	= $post_data['this_val'];
        $id 		= $post_data['this_id'];
        $product 	= Vendor::find($id);
        $product->status = $status;
        $product->save();
        echo "1";
		exit();
	
	}
	
	/********************************************************************************
	 *							REMOVE CATEGORY										*
	 *******************************************************************************/
	function getRemove($id=""){
		
		$category_details = Product::find($id);
        $category_details->delete();
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
        $product->category_id = $post_data['category_id'];
        $product->save();
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

    function getEdit($vendor_id=''){

    	$vendor_class 			= "active";
        $module_head 			= "Edit Vendor Details";
		$vendor_id				= base64_decode($vendor_id);
        $vendor_details 		= Vendor::where('id',$vendor_id)->first();
        if(count($vendor_details)>0){
        	$vendor_details = $vendor_details->toArray();
        }
        /*echo "<pre>";
        print_r($vendor_details);
        exit;*/
        $title					= "Edit Vendor Details";
        $category = Category::all();
        $brand = Brand::all();
		return view('admin.vendor.edit_vendor_details',compact(
			'module_head',
			'vendor_id',
			'category_id',
			'product_class',
			'vendor_details',
			'category',
			'brand',
			'title'));
    	
    	
    }
    function postEdit(){

    	$data = Request::all();
    	/*echo "<pre>";
    	print_r($data);exit;*/
    	$vendor_details = Vendor::find($data['hid_vendor_id']);
    	$vendor_details['vendor_url'] = $data['vendor_url'];
    	$vendor_details['percentage'] = $data['percentage'];
    	$vendor_details['status'] = $data['status'];
    	$vendor_details['advertiser-name'] = $data['vendor_name'];
    	$vendor_details['description'] = htmlentities($data['description']);
    	$vendor_details['short_description'] = htmlentities($data['short_description']);

        if(!empty($data['upload_banner_image'])){
            $vendor_details['image'] = $data['upload_banner_image'];
            if($data['old_brand_image']){
                @unlink(public_path('uploads/vendor_image/' . $data['old_brand_image']));
            }
        }
		$vendor_details->save();

		Product::where('advertiser-id', '=', $data['hid_vendor_id'])->update(['advertiser-name' => $data['vendor_name']]);

		Session::flash('success_message', 'Vendor updated successfully'); 
		Session::flash('alert-class', 'alert alert-success'); 
		return redirect('admin/vendors/list');

    }

    public function postUploadImage()
    {
        $data = Request::all();
        /*print_r($data);
        exit;*/
        $crop_data_arr = array();
        $crop_data_arr['x'] = $data['dataX'];
        $crop_data_arr['y'] = $data['dataY'];
        $crop_data_arr['height'] = $data['dataHeight'];
        $crop_data_arr['width'] = $data['dataWidth'];
        $crop_data_arr['rotate'] = $data['dataRotate'];

        $json_cropped_data = json_encode($crop_data_arr);
        
        if($data['dataHeight']>0 && $data['dataWidth']>0){

            $path = 'uploads/vendor_image/';
            $crop = new CropAvatar(null, $json_cropped_data,$_FILES['file'],$path);
            $uploaded_img_name = $crop -> getResult();
        }
        else{
            $image = Input::file('file');
            $filename  = time() . '.' . $image->getClientOriginalExtension();

            $path = 'uploads/vendor_image/' . $filename;

            Image::make($image->getRealPath())->save($path);
            $uploaded_img_name = $filename;

        }

        echo $uploaded_img_name;
        exit();

    }

}
