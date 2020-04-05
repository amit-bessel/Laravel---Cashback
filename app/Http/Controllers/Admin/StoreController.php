<?php namespace App\Http\Controllers\Admin;

use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Model\Category; /* Model name*/
use App\Model\SubCategory; /* Model name*/
use App\Model\Product; /* Model name*/
use App\Model\Brand; /* Model name*/
use App\Model\Vendor; /* Model name*/
use App\Model\Store; /* Model name*/
use App\Model\CategoryStore; /* Model name*/
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


class StoreController extends BaseController {

   /**
    * Display a listing of the resource.
    *
    * @return Response
    */
     public function __construct() 
    {
        parent::__construct();

    }
    

    function getList()
    {
		/*echo "Test";
		exit;*/
        $module_head 		= "Store List";
        $stores_class 		= "active";
        $record_per_page    = 100;
        $page 				= Input::get('page', 1);
		$paginate 			= 100;
        $search_key 		= Request::query('search_key');
        $active 			= Request::query('active');
		$title				= "Store Management";
        $where 				= '';
		
		$sl_no				= 1;

		$stores_arr = Store::paginate($record_per_page);
		/*if(count($stores_arr)>0){
			$stores_arr = $stores_arr->toArray();
		}*/
		
        return view('admin.store.store_list',compact(
														'stores_arr','stores_class',
														'module_head','search_key',
														'active','title','sl_no'
													)
					);
    }

    public function getVendorList($store_id=""){

    	$module_head 		= "Store List";
        $stores_class 		= "active";
        $page 				= Input::get('page', 1);
        $search_key 		= Request::query('search_key');
        $active 			= Request::query('active');
		$title				= "Store Management";
		$store_id 			= base64_decode($store_id);
    	return view('admin.store.map_vendor',compact(
														'stores_class',
														'module_head','search_key',
														'active','title','sl_no','store_id'
													));
    }

    function getSearchVendor(){

    	$term = $_GET['term'];
    	$order_details_arr = array();
        $order_arr = Vendor::where('name','LIKE',$term.'%')->get();
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

    function getVendors($store_id = "")
    {
    	//$category_id = Request::input('cat_id');
    	$store_arr = Store::where('id', $store_id)->get()->first();

        $module_head 		= "Map Vendor To ". $store_arr->name ." Store";
        $stores_class 		= "active";
        $record_per_page    = 100;
        $search_key 		= Request::query('search_key');
		$title				= "Map Vendor";
        $where 				= '';
		
		$sl_no				= 1;

		if(Request::input('page')!=''){
			$sl_no = (Request::input('page')*$record_per_page)+1;
		}

        if($search_key != '')
		{
            $where 			= "`advertiser-name` LIKE '%".addslashes($search_key)."%' AND ";
        }

		$where 		   .= '1=1';

        $vendors		= Vendor::whereRaw($where)->offset(Request::input('offset'))->orderBy('advertiser-name','ASC')->limit(100)->get();
        /*echo "<pre>";
        print_r($vendors);
        exit;*/
        if(count($vendors)>0){
        	$vendors = $vendors->toArray();
        }

        $no_of_vendors 	= Vendor::whereRaw($where)->count();
		
        return view('admin.store.vendors',compact(
														'vendors',
														'module_head','search_key','stores_class',
														'active','title','sl_no','no_of_vendors'
													)
					);
    }

    function getEdit($store_id=""){
    	/*echo $store_id;
    	exit;*/
    	$module_head 		= "Edit Store";
        $stores_class 		= "active";
        $page 				= Input::get('page', 1);
        $search_key 		= Request::query('search_key');
        $active 			= Request::query('active');
		$title				= "Store Management";
		$store_id 			= base64_decode($store_id);

		$store_info 		= Store::where('id',$store_id)->first();

		/*if(count($store_info)>0){
			$store_info = $store_info->toArray();
		}*/

    	return view('admin.store.edit_store_details',compact(
    													'store_info',
														'stores_class',
														'module_head','search_key',
														'active','title','sl_no','store_id'
													));

    }

    function postEdit(){
        
        $data = Request::all();
        //echo "<pre>";print_r($data);exit;
        $update_password  = Store::where('id', $data['hid_validate_res'])
                                        ->update([
                                            'name'      => $data['store_name']
                                        ]);

        Session::flash('success_message', 'Store details has been updated successfully.'); 
        Session::flash('alert-class', 'alert alert-success'); 
        return redirect('admin/stores/list');

    }

    ########################### Vendor Change ####################
    function postStoreVendorAdd(){

    	$data = Request::all();
    	//echo "<pre>";print_r($data);exit;
    	$store_id = Request::input('store_id');

    	$vendor_array = Request::input('check_product');
    	foreach($vendor_array as $vendor_id){
    		$already_exists = CategoryStore::where('category_id',$store_id)->where('vendor_id',$vendor_id)->get();
    		if(count($already_exists)==0){
    			$insert = CategoryStore::create([
    				'category_id' => $store_id,
    				'vendor_id'   => $vendor_id
    			]);
    		}
  
    	}

    	Session::flash('success_message', 'Vendor mapped successfully'); 
		Session::flash('alert-class', 'alert alert-success'); 
		return redirect('admin/stores/list');
    }
     ########################### Vendor Change ####################


    ########################### store visibility Change ####################
    function postChangeVisibility($id){ 
        $status   = Request::input('status');
        $store  = Store::find($id);
        $store->status = Request::input('status');
        // echo $status;
        $store->save();
        return response()->json(['status'=>1,'msg' => 'Success']);
    }

}
