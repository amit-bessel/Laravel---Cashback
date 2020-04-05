<?php namespace App\Http\Controllers\Admin;

use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Model\Country; /* Model name*/
//use App\Model\Restaurant; /* Model name*/
use App\Model\Category; /* Model name*/
use App\Model\SiteUser; /* Model name*/
use App\Model\Sitesetting; /* Model name*/
use App\Model\DataFeed;

use App\Book;
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
use Cookie;
use Yajra\Datatables\Datatables;

require_once('vendor/Stripe/init.php');

use Stripe\Stripe;
use Stripe\Charge;
use Stripe\Customer;
use Stripe\Stripe_CardError;
use Stripe\Stripe_InvalidRequestError;

class DataFeedController extends BaseController {

  /**
    * Display a listing of the resource.
    *
    * @return Response
    */
    public function __construct() {
    	parent::__construct();
    }
    
	/********************************************************************************
	 *								GET USER LIST									*
	 *******************************************************************************/
    function getList()
    {

    	$module_head 		= "DataFeed List";
        $datafeed_class 		= "active";
        $search_key 		= Request::query('search_key');
        $active 			= Request::query('active');
		$title				= "DataFeed Management";

		return view('admin.datafeed.list',compact('datafeed_class',
														'module_head','search_key','search_email','search_contact',
														'active','title'
													)
					);
    }

    function getAdd()
	{
		$user_class = "active";
	    $module_head = "Add DataFeed";
	    $title		= "DataFeed Management";
		return view('admin.datafeed.add',compact('module_head','user_class','title'));
	}

    function getEdit($id='')
	{
        $user_class 			= "active";
        $module_head 			= "Edit DataFeed Details";
		$user_id				= $id;
        $user_details 			= DataFeed::where('id', '=', $id)->first();
        if(!$user_details){
        	Session::flash('failure_message', 'DataFeed not found.'); 
        	Session::flash('alert-class', 'alert alert-danger'); 
        	return redirect('admin/datafeeds/list');
        	exit;
        }
        $title	= "Edit DataFeed Details";
		return view('admin.datafeed.edit',compact(
			'module_head',
			'user_id',
			'user_class',
			'user_details',
			'title'
			));
    }

  	function postAdd($id='')
	{

		$data = Request::all();

	    $datafeeds= DataFeed::create([
                 'name'    => trim($data['datafeed_name']),
                 'status'    => $data['status'],
                 'vendor_id'    => $data['vendor_id'],
                 'created_at'  => date('Y-m-d H:i:s'),
                 ]);

        $lastInsertedId = $datafeeds->id;
          
		Session::flash('success_message', 'DataFeed details has been Saved successfully.'); 
        Session::flash('alert-class', 'alert alert-success'); 
        return redirect('admin/datafeeds/list');
    }

	function postEdit($id='')
	{
		$data				= Request::all();
		$user_id			= $id;
		$user_details		= DataFeed::where('id', $user_id)->first();

		$update_user		= DataFeed::where('id', $user_id)
									->update([
										'name'          => trim($data['name']),
										'vendor_id'     => trim($data['vendor_id']),
										'status'    		=> $data['status']
									]);

		Session::flash('success_message', 'DataFeed details has been updated successfully.'); 
        Session::flash('alert-class', 'alert alert-success'); 
        return redirect('admin/datafeeds/list');
    }
	
	/********************************************************************************
	 *						CHECK USER EXISTS OR NOT								*
	 *******************************************************************************/
	function postCheck(){
		$data	= Request::all();
		
		$where_raw = "1=1";
		$where_raw .= " AND `email` = '".$data['email']."'";
		if($data['hid_user_id']!=""){
			$where_raw .= " AND `id`!= '".$data['hid_user_id']."'";
		}
		$user_details 	= DataFeed::whereRaw($where_raw)->first();
		if(count($user_details)>0){
			echo "false";
		}
		else{
			echo "true";
		}
		
	}
	
	/********************************************************************************
	 *								REMOVE USER										*
	 *******************************************************************************/
	function getRemove($id=""){

		$user_details = DataFeed::find($id);
        $user_details->delete();
       
    	Session::flash('success_message', 'DataFeed has been removed successfully.'); 
    	Session::flash('alert-class', 'alert alert-success'); 
    	return redirect('admin/datafeeds/list');
	}
	
	/********************************************************************************
	 *							CHANGE STATUS										*
	 *******************************************************************************/
	function getStatus(){
		
		$post_data  = Request::all();
        $status 	= $post_data['this_val'];
        $id 		= $post_data['this_id'];
        $site_user 	= DataFeed::find($id);
        $site_user->status = $status;
        $site_user->save();
        echo "1";
		exit();
	
	}

	function postAjaxDatafeedsList(){

		$search_key 		= Request::query('search_key');
        $active 			= Request::query('active');
        $where = "";
        
		 if($search_key != ''){
	            $where 			.= "(`name` LIKE '%".$search_key."%') AND ";
	     }
        if($active != ''){
            $where 		   .= "`status`= '".$active."' AND ";
        }

        $i = 0;
        $where 		   .= '1';
        $all_patients =  DataFeed::select(['id', 'name','status'])->whereRaw($where)->orderBy('id', 'DESC');

		return Datatables::of($all_patients)
		->addColumn('action', function ($all_patients) {
                return '<a href="'.url().'/admin/datafeeds/edit/'.$all_patients->id.'"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>&nbsp;<a href="javascript:void(0);" onclick="remove_datafeed('.$all_patients->id.')"><i class="fa fa-trash-o" aria-hidden="true"></i></a>';
            })
		->addColumn('slnbr', function ($all_patients) {
				global $i;
				$i++;
                return  $i/*'<input type="checkbox"  recordType="multipleRecord" multipleRecord="'.$all_patients->id.'" />'*/;
            })
		 ->editColumn('name', function ($all_patients) {
		 		$fullname = $all_patients->name." ".$all_patients->last_name;
            	return $fullname;

            })
		 ->editColumn('status', function ($all_patients) {

		 	if($all_patients->status == 1){
             	$status_html = '<select style="width:94%;" name="status" id="user_active_'.$all_patients->id.'" onchange="change_datafeed_status(this.value,'.$all_patients->id.')"><option value="1" selected>Active</option><option value="0">Inactive</option></select><br /><span class="alert-success" id="success_status_span_'.$all_patients->id.'" style="display:none;"></span>';
		 	}else{
		 		$status_html = '<select style="width:94%;" name="status" id="user_active_'.$all_patients->id.'" onchange="change_datafeed_status(this.value,'.$all_patients->id.')"><option value="1">Active</option><option value="0" selected>Inactive</option></select><span class="alert-success" id="success_status_span_'.$all_patients->id.'" style="display:none;"></span>';

		 	}
		 	return $status_html;
            })
		->make(true);
	}

    function getSearchUser(){

    	$term = $_GET['term'];
    	$order_details_arr = array();
        $order_arr = DataFeed::orWhere(function ($query) use($term) {
                				 $query->orWhere('name', 'like', $term . '%')
                      				   ->orWhere('email', 'like', $term . '%')
                      				   ->orWhere('contact', 'like', $term . '%');
                      			})->get();
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
    
}
