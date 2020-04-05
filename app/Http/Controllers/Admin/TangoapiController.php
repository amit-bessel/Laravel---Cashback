<?php namespace App\Http\Controllers\Admin;

use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;



use App\Model\User; /* Model name*/

use Customhelpers;


use App\Book;

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

use RaasLib;

require_once('vendor/Stripe/init.php');


//require_once('tangoraas/test/catalogSDK.php');

use Stripe\Stripe;
use Stripe\Charge;
use Stripe\Customer;
use Stripe\Stripe_CardError;
use Stripe\Stripe_InvalidRequestError;

require_once('tangoraas/Tangoapi.php');

class TangoapiController extends BaseController {

  /**
    * Display a listing of the resource.
    *
    * @return Response
    */
    public function __construct() {
    	parent::__construct();
    }
    
	/********************************************************************************
	 *								GET CATALOG LIST									*
	 *******************************************************************************/
    function getCatalog()
    {
    	//$obj1=new Tangoapi();

    	//$result = $obj1->testme();

    	$catalog_details =getcatalog();
      
		$catalog_class 			= "active";
        $module_head 			= "View Catalog Details";
		

        $title	= "View Catalog Details";
		

		 //$catalog_details->setPath('catalog_details');
        return view('admin.tangoapi.catalog',compact('catalog_details'),array('title'=>$title,'module_head'=>$module_head ));


    }

    function getCreateorder(){

    	$order_details =getcreateorder();

    	echo "<pre>";
    	print_r($order_details);exit();


    }

    /********************************************************************************
	 *								 USER VIEW DETAILS									*
	 *******************************************************************************/
	
	function getView($id='')
	{



        $user_class 			= "active";
        $module_head 			= "View User Details";
		$user_id				= $id;
        $user_details 			= User::where('id', '=', $id)->first();
        if(!$user_details){
        	Session::flash('failure_message', 'User not found.'); 
        	Session::flash('alert-class', 'alert alert-danger'); 
        	return redirect('admin/patient/list');
        	exit;
        }
        $title	= "View User Details";
		return view('admin.user.view_user_details',compact(
			'module_head',
			'user_id',
			'user_class',
			'user_details',
			'title'
			));



    }

	/********************************************************************************
	 *									EDIT USER 									*
	 *******************************************************************************/

    function getEdit($id='')
	{
        $user_class 			= "active";
        $module_head 			= "Edit User Details";
		$user_id				= $id;
        $user_details 			= User::where('id', '=', $id)->first();
        if(!$user_details){
        	Session::flash('failure_message', 'User not found.'); 
        	Session::flash('alert-class', 'alert alert-danger'); 
        	return redirect('admin/patient/list');
        	exit;
        }
        $title	= "Edit User Details";
		return view('admin.user.edit_user_details',compact(
			'module_head',
			'user_id',
			'user_class',
			'user_details',
			'title'
			));
    }


    function postEdit($id='')
	{
		/* call custom helper */
		$datetime= Customhelpers::Returndatetime();
		$data				= Request::all();
		
		$user_id			= $id;
		$user_pass			= "";
		$user_details		= User::where('id', $user_id)->first();
		$user_pass			= $user_details->password;
		$update_user		= User::where('id', $user_id)
									->update([
										'firstname'          => trim($data['firstname']),
										'lastname'     => trim($data['lastname']),
									
										'title'    		=> $data['title'],
										'email'       	=> $data['email'],
										'contact'       => preg_replace('/[^0-9]+/', '',$data['contact']),
										'updated_at'=>$datetime,
									]);
		if($data['password']!=''){
			$user_pass				= $data['password'];
			$update_password		= User::where('id', $user_id)
										->update([
											'password'      => Hash::make($data['password'])
										]);
										
		}
		Session::flash('success_message', 'User details has been updated successfully.'); 
        Session::flash('alert-class', 'alert alert-success'); 
        return redirect('admin/user/list');
    }



	/********************************************************************************
	 *									ADD USER 									*
	 *******************************************************************************/

	function getAdd()
	{
		$user_class = "active";
	    $module_head = "Add User";
		return view('admin.user.add_user_details',compact('module_head','user_class'));
	}


  function postAdd($id='')
	{
		/* call custom helper */
		$datetime= Customhelpers::Returndatetime();
		
		$data = Request::all();
		//echo '<pre>';print_r($data);exit;
	    $user= User::create([
                 'firstname'    => $data['name'],
                 'role'    => $data['usertype'],
                 'title'    => $data['title'],
                 'lastname'    => $data['last_name'],
                 'email'  => $data['email'],
                 'contact'  => preg_replace('/[^0-9]+/', '',$data['contact']),
                 'password'  => Hash::make($data['password']),
                 'status'=>1,
                 'created_at'=>$datetime,

                 ]);
               $lastInsertedId = $user->id;

              

            ###################################Sening mail starts##############################
			
			$user_name 	= $data['name'];
			$user_email = $data['email'];
			$user_pass  = $data['password'];
			if($data['usertype'] == 1){
				$usertype = "admin";
			}else if($data['usertype'] == 2){
				$usertype = "sub-admin";
			}
			
			$admin_users_email = "";
			
			$sitesettings = DB::table('sitesettings')->where('name','email')->first();
			if(!empty($sitesettings))
			{
				$admin_users_email = $sitesettings->value;
			}
			
			$subject = "You have added as ".$usertype." in Cashback justin";
			
			$message_body = "Admin has added you in Cashback justin.Your password is: ".$user_pass;
			
			################# SEND MAIL TO SITE-USER -start ################################
			
			
			
			if(($_SERVER['REMOTE_ADDR'] != 'localhost') && ($_SERVER['REMOTE_ADDR'] != '192.168.1.115')) //check if code is ruuning in localhost
			{
				$mail = Mail::send(['html' => 'admin.emailtemplate.send_mail'], array('message_body' => $message_body, 'user_name'=>$user_name), function($message) use ($admin_users_email, $user_email,$user_name,$subject)
				{
					$message->from($admin_users_email,'Cashback justin');
		
					$message->to($user_email)->subject($subject);
				});
			}
			################# SEND MAIL TO SITE-USER -end ################################


		


          
		Session::flash('success_message', 'User details has been Saved successfully.'); 
        Session::flash('alert-class', 'alert alert-success'); 
        return redirect('admin/user/list');
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
		$user_details 	= User::whereRaw($where_raw)->first();
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
		$user_details = User::find($id);
		if(!empty($user_details['image'])){
			@unlink('uploads/profile_image/'.$user_details['image']);
		}
        $user_details->delete();
       
    	Session::flash('success_message', 'User has been removed successfully.'); 
    	Session::flash('alert-class', 'alert alert-success'); 
    	return redirect('admin/user/list');
        
	
	}
	
	/********************************************************************************
	 *							CHANGE STATUS										*
	 *******************************************************************************/
	function getStatus(){
		
		$post_data  = Request::all();
        $status 	= $post_data['this_val'];
        $id 		= $post_data['this_id'];
        $user 	= User::find($id);
        $user->status = $status;
        $user->save();
        echo "1";
		exit();
	
	}

	/********************************************************************************
	 *							AJAX DATATABLE LISTING USERS								*
	 *******************************************************************************/

	function ajaxPatientsList(){
		$search_key 		= Request::query('search_key');
        $active 			= Request::query('active');
        $where = "";

        $role=Auth::user()->role;
        $userId = Auth::id();

        if($role==2){

        	$where.=" `id`= '".$userId."' AND ";
        }

		 if($search_key != ''){
	            $where 			.= "(`firstname` LIKE '%".$search_key."%' OR CONCAT(firstname,' ',lastname) LIKE '%".$search_key."%' OR `lastname` LIKE '%".$search_key."%' OR `email` LIKE '%".$search_key."%' OR `contact` LIKE '%".$search_key."%') AND ";
	     }
        if($active != ''){
            $where 		   .= "`status`= '".$active."' AND ";
        }
        $where 		   .= '1';
        $all_patients =  User::select(['id', 'firstname','lastname','contact', 'email', 'created_at', 'updated_at','status','role'])->whereRaw($where)->orderBy('id', 'DESC');

		return Datatables::of($all_patients)
		->addColumn('checkbox_td', function ($all_patients) {
                return '<input type="checkbox"  recordType="multipleRecord" multipleRecord="'.$all_patients->id.'" />';
            })
		 ->addColumn('action', function ($all_patients) {
                return '<a href="'.url().'/admin/user/edit/'.$all_patients->id.'"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>&nbsp;<a href="'.url().'/admin/user/view/'.$all_patients->id.'"><i class="fa fa-eye" aria-hidden="true"></i></a>&nbsp;<a href="javascript:void(0);" onclick="userJs.remove('.$all_patients->id.')"><i class="fa fa-trash-o" aria-hidden="true"></i></a>';
            })
		 ->editColumn('name', function ($all_patients) {
		 		$fullname = $all_patients->firstname." ".$all_patients->lastname;
            	return $fullname;

            })
		 ->editColumn('status', function ($all_patients) {
		 	if($all_patients->status == 1){
             	$status_html = '<select style="width:94%;" name="status" id="user_active_'.$all_patients->id.'" onchange="userJs.changeStatus(this.value,'.$all_patients->id.')"><option value="1" selected>Active</option><option value="0">Inactive</option></select><br /><span class="alert-success" id="success_status_span_'.$all_patients->id.'" style="display:none;"></span>';
		 	}else{
		 		$status_html = '<select style="width:94%;" name="status" id="user_active_'.$all_patients->id.'" onchange="userJs.changeStatus(this.value,'.$all_patients->id.')"><option value="1">Active</option><option value="0" selected>Inactive</option></select><span class="alert-success" id="success_status_span_'.$all_patients->id.'" style="display:none;"></span>';

		 	}
		 	return $status_html;
            })
		->make(true);
	}

	
	


	 function  getDeleteAll()
    {
        $post_id  = Request::all();
        $id = explode(",", $post_id['ids']);
        $city = SiteUser::whereIn('id',$id)->delete();
        echo "1";
        exit;
    }

    function getSearchUser(){

    	$term = $_GET['term'];
    	$order_details_arr = array();
        $order_arr = SiteUser::orWhere(function ($query) use($term) {
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
