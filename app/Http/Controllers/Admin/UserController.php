<?php namespace App\Http\Controllers\Admin;

use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;


use App\Model\Module; /* Model name*/
use App\Model\Usersmodules; /* Model name*/
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

//require_once('vendor/Stripe/init.php');

use Stripe\Stripe;
use Stripe\Charge;
use Stripe\Customer;
use Stripe\Stripe_CardError;
use Stripe\Stripe_InvalidRequestError;
use App\Model\Previousemail; /* Model name*/


class UserController extends BaseController {

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

    	$module_head 		= "User List";
        $user_class 		= "active";
        $search_key 		= Request::query('search_key');
        $active 			= Request::query('active');
		$title				= "User Management";
		return view('admin.user.user_list',compact('user_class',
														'module_head','search_key','search_email','search_contact',
														'active','title'
													)
					);
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

		//add email to Previousemail table to check previous email can not be used

		$count=Previousemail::where('email',$data['email'])->count();

        if($count==0){

            Previousemail::create(['email'=>$data['email'],'created_at'=>$datetime]);

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

               /*********************Assign roles to subadmin*****************************/

               Usersmodules::create(['users_id'=>$lastInsertedId,'modules_id'=>3,'created_at'=>$datetime,'updated_at'=>$datetime]);
               Usersmodules::create(['users_id'=>$lastInsertedId,'modules_id'=>6,'created_at'=>$datetime,'updated_at'=>$datetime]);
               Usersmodules::create(['users_id'=>$lastInsertedId,'modules_id'=>9,'created_at'=>$datetime,'updated_at'=>$datetime]);
               Usersmodules::create(['users_id'=>$lastInsertedId,'modules_id'=>12,'created_at'=>$datetime,'updated_at'=>$datetime]);

               //add email to Previousemail table to check previous email can not be used

		        $count=Previousemail::where('email',$data['email'])->count();

		        if($count==0){

		            Previousemail::create(['email'=>$data['email'],'created_at'=>$datetime]);

		        }      

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
			
			$subject = "You have added as ".$usertype." in Checkout Saver";
			
			$message_body = "Admin has added you in Checkout Saver.Your password is: ".$user_pass.". <br/>Your login link is ".url('')."/admin";
			
			################# SEND MAIL TO SITE-USER -start ################################
			
			
			
			if(($_SERVER['REMOTE_ADDR'] != 'localhost') && ($_SERVER['REMOTE_ADDR'] != '192.168.1.115')) //check if code is ruuning in localhost
			{
				$mail = Mail::send(['html' => 'admin.emailtemplate.send_mail'], array('message_body' => $message_body, 'user_name'=>$user_name), function($message) use ($admin_users_email, $user_email,$user_name,$subject)
				{
					$message->from($admin_users_email,'Checkout Saver');
		
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
		// if(!empty($user_details['image'])){
		// 	@unlink('uploads/profile_image/'.$user_details['image']);
		// }
        $user_details->is_deleted=1; // soft delete admin user
        $user_details->save();
       
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
        $where = " `role`!= 1 AND ";

        //$role=Auth::user()->role;
        $userId = Auth::id();
        $userdetails=User::find($userId);
        $role=$userdetails->role;
        $data 			= Request::all();


        $columns = array( 
			// datatable column index  => database column name
			0 =>'id',
			1=>'#',
			2=>'firstname',
			3 =>'email',
			4 =>'contact',
			5=>'status'
		);

        if($role==2){

        	$where.=" `id`= '".$userId."' AND ";
        }

		 if($search_key != ''){
	            $where 			.= "(`firstname` LIKE '%".$search_key."%' OR CONCAT(firstname,' ',lastname) LIKE '%".$search_key."%' OR `lastname` LIKE '%".$search_key."%' OR `email` LIKE '%".$search_key."%' OR `contact` LIKE '%".$search_key."%') AND ";
	     }
        if($active != ''){
            $where 		   .= "`status`= '".$active."' AND ";
        }
        $where 		   .= 'is_deleted=0';
        $all_patients =  User::select(['id', 'firstname','lastname','contact', 'email', 'created_at', 'updated_at','status','role'])->whereRaw($where)->orderBy($columns[$data['order'][0]['column']],$data['order'][0]['dir']);

		return Datatables::of($all_patients)
		->addColumn('checkbox_td', function ($all_patients) {
                return '<input type="checkbox"  recordType="multipleRecord" multipleRecord="'.$all_patients->id.'" />';
            })
		 ->addColumn('action', function ($all_patients) {
                $str= '<a class="edit-icon" href="'.url().'/admin/user/edit/'.$all_patients->id.'"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a><a class="view-icon" href="'.url().'/admin/user/view/'.$all_patients->id.'"><i class="fa fa-eye" aria-hidden="true"></i></a><a class="delete-icon" href="javascript:void(0);" onclick="userJs.remove('.$all_patients->id.')"><i class="fa fa-trash-o" aria-hidden="true"></i></a>';

                
                	$str.='<a  href="'.url().'/admin/user/role/'.$all_patients->id.'" class="assignrole">Assign Role</a>';
              
                return $str;
                
            })
		 ->editColumn('name', function ($all_patients) {
		 		$fullname = $all_patients->firstname." ".$all_patients->lastname;
            	return $fullname;

            })
		 ->editColumn('uniqueid', function ($all_patients) {
                $uniqueid = $all_patients->id;
                return $uniqueid."A";

            })
		 ->editColumn('status', function ($all_patients) {
		 	if($all_patients->status == 1){
             	$status_html = '<select class="custom-select table-custom-select" name="status" id="user_active_'.$all_patients->id.'" onchange="userJs.changeStatus(this.value,'.$all_patients->id.')"><option value="1" selected>Active</option><option value="0">Inactive</option></select><br /><span class="alert-success" id="success_status_span_'.$all_patients->id.'" style="display:none;"></span>';
		 	}else{
		 		$status_html = '<select class="custom-select table-custom-select" name="status" id="user_active_'.$all_patients->id.'" onchange="userJs.changeStatus(this.value,'.$all_patients->id.')"><option value="1">Active</option><option value="0" selected>Inactive</option></select><span class="alert-success" id="success_status_span_'.$all_patients->id.'" style="display:none;"></span>';

		 	}
		 	return $status_html;
            })
		->make(true);
	}

	
	


	 function  getDeleteAll()
    {
        $post_id  = Request::all();
        $id = explode(",", $post_id['ids']);
        $SiteUser = SiteUser::whereIn('id',$id)->update(['is_deleted'=>1]);//soft delete admin
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

    /**************************Sub admin roll management start**********************************************/

    public function getRole($id){

    	$user_class 			= "active";
        $module_head 			= "Edit User Role";
		$user_id				= $id;
        $user_details 			= User::where('id', '=', $id)->first();


        $Usersmodules=Usersmodules::with('modules','user')->where('users_id',$user_id)->get();
        $userdetails=User::find($user_id);
        $userrole=$userdetails->role;

        $module=Module::all();
        //echo "<pre>";
        //print_r($Usersmodules);
        //exit();
        if(!$user_details){
        	Session::flash('failure_message', 'User not found.'); 
        	Session::flash('alert-class', 'alert alert-danger'); 
        	return redirect('admin/patient/list');
        	exit;
        }
        $title	= "Edit User Role Details";
		return view('admin.user.role',compact(
			'module_head',
			'user_id',
			'user_class',
			'user_details',
			'title',
			'Usersmodules',
			'module'
			));

    }

    /***********************Update roll **********************************/

    public function getEditRole($id){

    	/* call custom helper */
		$datetime= Customhelpers::Returndatetime();
    	$data=Request::all();
    	
    	//echo "<pre>";
    	//print_r($data);exit();

    	$userid=$data["hid_user_id"];
    	
    	Usersmodules::where('users_id',$userid)->delete();

    	if(!empty($data["module"])){

    		foreach ($data["module"] as $key => $value) {
    			
    			Usersmodules::create(['users_id'=>$userid,'modules_id'=>$value,'created_at'=>$datetime,'updated_at'=>$datetime]);
    		}
    	}
    	Session::flash('success', 'User role has been saved  successfully.'); 
    	Session::flash('alert-class', 'alert alert-success'); 
    	return redirect('admin/user/role/'.$id);
    }

    /**************************Sub admin roll management end**********************************************/
    
}
