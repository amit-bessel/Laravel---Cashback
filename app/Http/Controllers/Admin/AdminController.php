<?php namespace App\Http\Controllers\Admin;

use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Model\Country; /* Model name*/
use App\Model\Category; /* Model name*/
use App\Model\SiteUser; /* Model name*/
use App\Model\Sitesetting; /* Model name*/
use App\Model\UserCardDetail; /* Model name*/
use App\Model\CustomerNote; /* Model name*/


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

require_once('vendor/Stripe/init.php');

use Stripe\Stripe;
use Stripe\Charge;
use Stripe\Customer;
use Stripe\Stripe_CardError;
use Stripe\Stripe_InvalidRequestError;



class AdminController extends BaseController {

  /**
    * Display a listing of the resource.
    *
    * @return Response
    */
    function getsubadminlist()
	{
		$this->subadminrestrict();
		$module_head 		= "User List";
        $sub_admin_class 	= "active";
        $record_per_page 	= 20;
        $title				= "User Management";
        $where 				= '';
		
		$users = User::where("is_deleted","=","0")->orderBy('id', 'DESC')->get();
		
		
		return view('admin.subadmin.admin_list',compact(
							'users','sub_admin_class',
							'module_head','title'
			)
		);
		
	}
	
	function getsubadminadd()
	{
		$this->subadminrestrict();
		$module_head 		= "Add User";
        $sub_admin_class 		= "active";
        $record_per_page 	= 20;
        $title				= "User Management";
        $where 				= '';
		
		return view('admin.subadmin.admin_add',compact(
							'users','sub_admin_class',
							'module_head','title'
			)
		);
		
	}
	function postsubadminadd()
	{
		$this->subadminrestrict();
		if(Request::isMethod('post'))
		{
			$data = Request::all();
			
			$subadmin = new User;
			
			$subadmin->name = $data['name'];
            $subadmin->email = $data['email'];
			$subadmin->video_link = $data['video_link'];
            $subadmin->password = Hash::make($data['password']);
			$subadmin->role = $data['user_type'];
			$subadmin->status = '1';
            $subadmin->save();
			
			###################################Sening mail starts##############################
			
			$user_name 	= $data['name'];
			$user_email = $data['email'];
			$user_pass  = $data['password'];
			if($data['user_type'] == 1){
				$usertype = "admin";
			}else{
				$usertype = "sub-admin";
			}
			
			$admin_users_email = "";
			
			$sitesettings = DB::table('sitesettings')->where('name','email')->first();
			if(!empty($sitesettings))
			{
				$admin_users_email = $sitesettings->value;
			}
			
			$subject = "You have added as ".$usertype." in Cleardoc";
			
			$message_body = "Admin has added you in Cleardoc.Your password is: ".$user_pass;
			
			################# SEND MAIL TO SITE-USER -start ################################
			
			
			
			if(($_SERVER['REMOTE_ADDR'] != 'localhost') && ($_SERVER['REMOTE_ADDR'] != '192.168.1.115')) //check if code is ruuning in localhost
			{
				$mail = Mail::send(['html' => 'admin.emailtemplate.send_mail'], array('message_body' => $message_body, 'user_name'=>$user_name), function($message) use ($admin_users_email, $user_email,$user_name,$subject)
				{
					$message->from($admin_users_email,'ClearDoc');
		
					$message->to($user_email)->subject($subject);
				});
			}
			################# SEND MAIL TO SITE-USER -end ################################
			
			Session::flash('success_message', 'Users has been added successfully.'); 
			Session::flash('alert-class', 'alert alert-success'); 
			return redirect('admin/sub-admin-list');
		}
	}
	function getCheck(){
		$data	= Request::all();
		
		$where_raw = "1=1";
		$where_raw .= " AND `email` = '".$data['email']."' AND `is_deleted` = '0'";
		if($data['hid_user_id']!=""){
			$where_raw .= " AND `id`!= '".$data['hid_user_id']."'";
		}
		$user_details 	= User::whereRaw($where_raw)->first();
		
		if(count($user_details)>0){
			echo 1;
		}
		else{
			echo 0;
		}
		exit;
	}
	function getsubadminedit($user_id)
	{
		$this->subadminrestrict();
		$module_head 		= "Edit User";
        $sub_admin_class 	= "active";
        $record_per_page 	= 20;
        $title				= "User Management";
        $where 				= '';
		
		$user_details = User::where("id","=",$user_id)->first();
		
		
		return view('admin.subadmin.admin_edit',compact(
							'user_details','sub_admin_class','user_id',
							'module_head','title','user_arr'
			)
		);
	}
	function postsubadminedit()
	{
		$this->subadminrestrict();
		if(Request::isMethod('post'))
		{
			$data = Request::all();
			
			$user = User::find($data['hid_user_id']);
			$user->email = $data['email'];
			$user->name = $data['name'];
			$user->video_link = $data['video_link'];
			$user->role = $data['user_type'];
			
			if($data['password']!="")
			{
				$user->password = Hash::make($data['password']);
				
				###################################Sening mail starts##############################
			
				$user_name 	= $data['name'];
				$user_email = $data['email'];
				$user_pass  = $data['password'];
				//$subadmin->video_link = $data['video_link'];
				
				
				$admin_users_email = "";
				
				$sitesettings = DB::table('sitesettings')->where('name','email')->first();
				if(!empty($sitesettings))
				{
					$admin_users_email = $sitesettings->value;
				}
				
				
				$subject = "You have added as sub-admin in Cleardoc";
				
				$message_body = "Admin has updated your password in Cleardoc.Your new password is: ".$user_pass;
				
				################# SEND MAIL TO SITE-USER -start ################################
				
				if(($_SERVER['REMOTE_ADDR'] != 'localhost') && ($_SERVER['REMOTE_ADDR'] != '127.0.0.1')) //check if code is ruuning in localhost
				{
					$mail = Mail::send(['html' => 'admin.emailtemplate.send_mail'], array('message_body' => $message_body, 'user_name'=>$user_name), function($message) use ($admin_users_email, $user_email,$user_name,$subject)
					{
						$message->from($admin_users_email,'ClearDoc');
			
						$message->to($user_email)->subject($subject);
					});
				}
				################# SEND MAIL TO SITE-USER -end ################################
				
			}
			
			$user->save();
			Session::flash('success_message', 'User has been updated successfully.'); 
			Session::flash('alert-class', 'alert alert-success'); 
			return redirect('admin/sub-admin-list');
		}
	}
	public function getadminlist()
	{
		$term = $_GET['term'];
		$user_arr = User::where("role","=","2")->where("is_deleted","=","0")->Where('name', 'like', '%' . $term . '%')->get();
		$sub_user_arr = array();
		if(!empty($user_arr))
		{
			$user_arr = $user_arr->toArray();
			$i = 0;
			foreach($user_arr as $user_list)
			{
				$sub_user_arr[$i]['key'] = $user_list['id'];
				$sub_user_arr[$i]['value'] = $user_list['name'];
				$i++;
			}
		}
		echo json_encode($sub_user_arr);
		die;
	}
	public function getadminstatus($admin_id,$status)
	{
		$user = User::find($admin_id);
		$user->status = $status;
		$user->save();
		Session::flash('success_message', 'User status has been changed successfully.'); 
		Session::flash('alert-class', 'alert alert-success'); 
		return redirect('admin/sub-admin-list');
	}
	function getsubadmindelete($subadmin_id)
	{
		$this->subadminrestrict();
		$site_user_arr = SiteUser::where("subadmin_id",$subadmin_id)->get();
		$message = "";
		
		if(count($site_user_arr)>0)
		{
			$i = 0;
			$message.="This Sub-admin are assigned to this client(s) - ";
			$site_user_arr = $site_user_arr->toArray();
			$site_user_count = count($site_user_arr)-1;
			foreach($site_user_arr as $site_user_list)
			{
				$message.= $site_user_list['name'];
				if($site_user_count != $i)
				{
					$message.=" , ";
				}
				$i++;
			}
			$message.= ". You can delete this Sub-admin after assigning related client(s) to other Sub-admin .";
			
			
			Session::flash('failure_message', $message); 
			Session::flash('alert-class', 'alert alert-success'); 
			return redirect('admin/sub-admin-list');
			$i++;
			
		}
		else
		{
			$user = User::find($subadmin_id);
			$user->is_deleted = "1";
			$user->save();
			
			Session::flash('success_message', 'User deleted successfully.'); 
			Session::flash('alert-class', 'alert alert-success'); 
			return redirect('admin/sub-admin-list');
		}
	}
	function getNoteList($user_id)
	{
		$this->subadminusercheck($user_id);
		$module_head 		= "Note Management";
        $note_class 		= "active";
        $record_per_page 	= 20;
        $title				= "Note Management";
        $where 				= '';
		
		$note_arr = CustomerNote::with('admin_name')->where("user_id","=",$user_id)->orderBy('id','DESC')->get();
		
		return view('admin.user.note_list',compact(
							'user_details','note_class','user_id',
							'module_head','title','note_arr'
			)
		);
	}
	function getAddNote($user_id)
	{
		$this->subadminusercheck($user_id);
		$module_head 		= "Note Management";
        $note_class 	= "active";
        $record_per_page 	= 20;
        $title				= "Note Management";
        $where 				= '';
		
		//$note_arr = CustomerNote::where("user_id","=",)->get();
		
		return view('admin.user.add_note',compact(
							'user_details','note_class','user_id',
							'module_head','title'
			)
		);
	}
	function postAddNote()
	{
		if(Request::isMethod('post'))
		{
			$data = Request::all();
			
			$note = new CustomerNote;
			$note->user_id = $data['user_id'];
            $note->note = $data['note'];
            $note->created_by = Auth::id();
			$note->added_date = date("Y-m-d");
            $note->save();
			
			Session::flash('success_message', 'Note added successfully.'); 
			Session::flash('alert-class', 'alert alert-success'); 
			return redirect('admin/users/view/'.$data['user_id']);
		}
	}
	function getEditNote($id)
	{

		$module_head 		= "Note Management";
        $note_class 	= "active";
        $record_per_page 	= 20;
        $title				= "Note Management";
        $where 				= '';
		
		$note_arr = CustomerNote::where("id","=",$id)->first();
		$user_id = $note_arr->user_id;
		$this->subadminusercheck($user_id);
		
		
		return view('admin.user.edit_note',compact(
							'user_details','note_class','user_id',
							'module_head','title','note_arr'
			)
		);
	}
	function postEditNote()
	{
		if(Request::isMethod('post'))
		{
			$data = Request::all();
			$id = $data['id'];
			
			CustomerNote::where('id', $id)
			->update([
				'note'      => $data['note'],
				'added_date' => date("Y-m-d")
			]);
			
			Session::flash('success_message', 'Note added successfully.'); 
			Session::flash('alert-class', 'alert alert-success'); 
			return redirect('admin/note-list/'.$data['user_id']);
		}
	}
}
