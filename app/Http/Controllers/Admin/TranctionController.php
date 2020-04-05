<?php namespace App\Http\Controllers\Admin;

use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Model\Category; /* Model name*/
use App\Model\SubCategory; /* Model name*/
use App\Model\Product; /* Model name*/
use App\Model\Brand; /* Model name*/
use App\Model\Vendor; /* Model name*/
use App\Model\OrderHistory; /* Model name*/

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


class TranctionController extends BaseController {

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

    function getAllTranctionsList()
    {
        $module_head 		= "Transactions List";
        $tranction_class 		= "active";
        $record_per_page    = 100;
        $page 				= Input::get('page', 1);
		$title				= "Transactions Management";
        $start_date 		= Request::query('start_date');
        $end_date 			= Request::query('end_date');

		$sl_no				= 1;
		//echo date('Y-m-d',strtotime($start_date));
		if(Request::input('page')!=''){
			$sl_no = ((Request::input('page')-1)*$record_per_page)+1;
		}

		if($start_date != '' && $end_date != '' )
		{
            $where 	= "DATE(`transaction_date`) >= '".date('Y-m-d',strtotime($start_date))."' AND DATE(`transaction_date`) <= '".date('Y-m-d',strtotime($end_date))."'";
        }else if($start_date != '' && $end_date == '')
		{
            $where 	= "DATE(`transaction_date`) >= '".date('Y-m-d',strtotime($start_date))."'";
        }else if($start_date == '' && $end_date != '')
		{
            $where 	= "DATE(`transaction_date`) <= '".date('Y-m-d',strtotime($end_date))."'";
        }else{
        	$where 	= '1';
        }

    	$all_tranctions  = OrderHistory::whereRaw($where)->orderBy('id','DESC')->select('id','order_id','product_name','sku_number','cashback_amount','transaction_date','user_id','sale_amount')->get()->toArray();

		$all_tranctions = self::paginate($all_tranctions, $record_per_page);
		
		/*echo "<pre>";
        print_r($all_tranctions);exit;*/
		
        return view('admin.tranctions.tranctions_list',compact(
														'all_tranctions','tranction_class',
														'module_head','search_key','title','sl_no','start_date','end_date'
													)
					);
    }

    function getTranctionsDetails($id="")
    {
        $module_head 		= "Transactions Details";
        $tranction_class 	= "active";
		$title				= "Transactions Management";

    	$tranctions_details  = OrderHistory::leftJoin('site_users', 'site_users.id', '=', 'order_history.user_id')
    						   ->leftjoin('products', 'products.sku', '=', 'order_history.sku_number')
    						   ->leftjoin('all_vendors', 'all_vendors.advertiser-id', '=', 'order_history.advertiser_id')
    						   ->select('order_history.*','site_users.name','site_users.last_name','products.image_url','all_vendors.advertiser-name')
    						   ->where('order_history.id',base64_decode($id))->first()->toArray();
		
		/*echo "<pre>";
        print_r($tranctions_details);exit;*/
		
        return view('admin.tranctions.view_tranctions',compact(
														'tranctions_details','tranction_class',
														'module_head','title'
													)
					);
    }
}
