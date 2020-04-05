<?php namespace App\Http\Controllers\Admin;

use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;



use App\Model\User; /* Model name*/
use App\Model\Userrefer; /* Model name*/
use Customhelpers;
use App\Model\SiteUser; /* Model name*/

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

require_once('vendor/Stripe/init.php');

use Stripe\Stripe;
use Stripe\Charge;
use Stripe\Customer;
use Stripe\Stripe_CardError;
use Stripe\Stripe_InvalidRequestError;



class UserReferDetailsController extends BaseController {

  /**
    * Display a listing of the resource.
    *
    * @return Response
    */
    public function __construct() {
    	parent::__construct();
    }
    
     public function getIndex(){

   }


	/********************************************************************************
	 *								GET USER REFER BY LIST									*
	 *******************************************************************************/
    function getList()
    {

    	$module_head 		= "User Refer Details";
        $user_class 		= "active";
        $search_key 		= Request::query('search_key');
        $active 			= Request::query('active');
        $is_superaffiliate 	= Request::query('is_superaffiliate');
		$title				= "User Refer Details";
		return view('admin.userreferdetails.userrefer_list',compact('user_class',
														'module_head','search_key','search_email','search_contact',
														'active','title','is_superaffiliate'
													)
					);
    }

    /********************************************************************************
	 *								 USER VIEW REFER DETAILS									*
	 *******************************************************************************/
	
	function getView($id='')
	{



        $user_class 			= "active";
        $module_head 			= "View User Refer Details";
		$user_id				= $id;
        $user_details 			= SiteUser::with('userrefertolink')->where('id', '=', $id)->where('status','1')->where('is_deleted','0')->get();
        $refer=array();
        if(!empty($user_details)){
	        foreach ($user_details[0]->userrefertolink as $key => $value) {
	        	# code...

	            $referto=$value->referto;
	        	$userrefer=Userrefer::with('userreferlink1')->where('referto', '=', $referto)->where('status','1')->get();
	        	
    	


	        	$count=$userrefer->count();
	        	if($count>0){

	        		 $refer_user_id = $userrefer[0]->userreferlink1->id;

	        		$refer_user_details = SiteUser::with('userreferidrelation','walletcashback')->where('id',$refer_user_id)->first();
	        	/*	echo "<pre>";
    				print_r($refer_user_details->toArray());
    				exit();*/
	        		
	        		$amt = 0;
	        		if(count($refer_user_details['walletcashback'])>0){
	        			foreach ($refer_user_details['walletcashback'] as $key => $value) {
	        				$amt = $amt + $value['amount'];
	        			}
	        		}
	        	$refer['cashback_amount'][$key] = '$'.$amt;
	        	// echo $refer_user_details['userreferidrelation'][0]['referid'];
	        	if(count($refer_user_details['userreferidrelation'])>0)
	        		$refer['refercode'][$key] = $refer_user_details['userreferidrelation'][0]['referid'];
	        	else
	        		$refer['refercode'][$key] = 'NA';
	        	$refercode=$value->refercode;
	        	$created_at=$value->created_at;
	        	$updated_at=$value->updated_at;
	        	$refer['affiliated'][$key]= $userrefer[0]->userreferlink1->superaffiliateuser;

	        	$refer['userreferid'][$key]=$userrefer[0]->userreferlink1->id;

	        	$refer['firstname'][$key]=$userrefer[0]->userreferlink1->firstname;
	        	$refer['lastname'][$key]=$userrefer[0]->userreferlink1->lastname;
	        	$refer['email'][$key]=$userrefer[0]->userreferlink1->email;
	        	$refer['phoneno'][$key]=$userrefer[0]->userreferlink1->phoneno;

	        	// $refer['refercode'][$key]=$refercode;
	        	$refer['created_at'][$key]=$created_at;
	        	$refer['updated_at'][$key]=$updated_at;

	           }
	        }
    	}
    	/*echo '<pre>';
    	print_r($refer);
exit();*/

        

        if(!$user_details){
        	Session::flash('failure_message', 'User not found.'); 
        	Session::flash('alert-class', 'alert alert-danger'); 
        	return redirect('admin/patient/list');
        	exit;
        }
        $title	= "View User Refer Details";
		return view('admin.userreferdetails.view_userrefer_details',compact(
			'module_head',
			'user_id',
			'user_class',
			'refer',
			'title'
			));
    }

	

	

	/********************************************************************************
	 *							AJAX DATATABLE LISTING REFER DETAILS								*
	 *******************************************************************************/

	
	public function ajaxPatientsList(){

		$data 		= Request::all();
		$columns = array(

                0=>'id' ,
                1 =>'firstname',
                2 =>'email', 
                3 =>'phoneno',
                4=>'superaffiliateuser'
                
               
            );

		$search_key 		= Request::query('search_key');
        $active 			= Request::query('active');
        $userid 			= Request::query('userid');
        $where = "";
        $where1 = "";
        $role=Auth::user()->role;
        $userId = Auth::id();

        

        $where.=" `id` !='' AND ";
        
        $where1.=" `id` !='' AND ";

		if($search_key != ''){
	            $where 			.= "(`firstname` LIKE '%".$search_key."%' OR CONCAT(firstname,' ',lastname) LIKE '%".$search_key."%' OR `lastname` LIKE '%".$search_key."%' OR `email` LIKE '%".$search_key."%' OR `phoneno` LIKE '%".$search_key."%') AND ";
	    }

	    if($userid!=''){
	    	$where 		   .= "`id`= '".$userid."' AND ";
	    }

        if($active != ''){
            $where1 		   .= "`status`= '".$active."' AND ";
        }

       
        	$value='1';
        
        $where 		   .= '1';
        $where1 	   .= '1';

     	/*$all_patients = SiteUser::with(['userreferidrelation' => function ($query) use ($value) {
        $query->where('superaffiliate_status', '=', $value);}])
        ->whereHas('userrefertolink', function ($query) {
		$query->where('status','1')->groupBy('referby');
		})->whereRaw($where)->orderBy($columns[$data['order'][0]['column']],$data['order'][0]['dir']);*/
		$all_patients = SiteUser::with(['userreferidrelation','userrefertolink','walletcashback'])
        ->whereHas('userrefertolink', function ($query) {
		$query->where('status','=',1)->groupBy('referby');
		})->whereRaw($where)->orderBy($columns[$data['order'][0]['column']],$data['order'][0]['dir']);

		return Datatables::of($all_patients)
		->addColumn('checkbox_td', function ($all_patients) {
                return '<input type="checkbox"  recordType="multipleRecord" multipleRecord="'.$all_patients->id.'" />';
            })
		 ->addColumn('action', function ($all_patients) {
                return '&nbsp;<a href="'.url().'/admin/userreferdetails/view/'.$all_patients->id.'"><i class="fa fa-eye" aria-hidden="true"></i></a>&nbsp;';
            })
		 ->editColumn('firstname', function ($all_patients) {

		 	$flag=0;
		 	if($all_patients->userreferidrelation->count()>0){
		 	foreach ($all_patients->userreferidrelation as $key => $value) {
		 		# code...
		 	
		 		$superaffiliate_status = $value->superaffiliate_status;
            	
            	if($superaffiliate_status==1){
            		$flag=1;
            	}

            	}
         	 }

		 		$fullname = $all_patients->firstname." ".$all_patients->lastname;
		 		if($flag==1){
         	 	$fullname='<a href="'.url().'/admin/userreferdetails/view/'.$all_patients->id.'"><span style="font-weight:bold;color:#31c3e4;">'.$fullname.'</span></a>';	
         	 	}
                else{
                    $fullname='<a href="'.url().'/admin/userreferdetails/view/'.$all_patients->id.'" style="color: black;">'.$fullname.'</a>';
                }

            	return $fullname;

            })
		 /*->editColumn('superaffiliateuser', function ($all_patients) {
		 	$flag=0;
		 	if($all_patients->userreferidrelation->count()>0){
		 	foreach ($all_patients->userreferidrelation as $key => $value) {
		 		# code...
		 		$superaffiliate_status = $value->superaffiliate_status;
		 		
		 		if($superaffiliate_status==1){
		 			$referid = $value->referid;
            		$flag=1;
            		return $referid;
            	}
            	
            }
          }
          
          if($flag==0){
          	return "None";
          }

            })*/
		 /*->editColumn('superaffiliateuser', function ($all_patients) {
		 	if($all_patients->userreferidrelation->count()==1){ if($all_patients->id){ return $msg='<a href="javascript:void(0);" onclick="userJs.generateSuperaffiliateCode('.$all_patients->id.')" class="btn btn-primary-sm" id="refer_buttion_'.$all_patients->id.'" >Generate Code</a><br /><div class="alert-success" id="success_referstatus_span_'.$all_patients->id.'" style="display:none; font-size:12px;"></div>'; } }
            else {
                return "<span  style='padding: 4px 2px; font-size:11px;'>Code Already Generated</span>";
            }
		 	return $status_html;
            })*/

            ->editColumn('reference', function ($all_patients){
            	return $all_patients->userrefertolink->count();

            })

            ->editColumn('cashback', function($all_patients){
            	$amt = 0;
            	if($all_patients->walletcashback->count()>0){
            		foreach ($all_patients->walletcashback as $key => $value) {
            			$amt = $amt + $value->amount;
            		}
            		return '$'.$amt;
            	}
            	else{
            		return '$0';
            	}
            })

		 ->editColumn('superaffiliateuser', function ($all_patients) {
		 	$flag=0;
		 	if($all_patients->userreferidrelation->count()>0){
		 	foreach ($all_patients->userreferidrelation as $key => $value) {
		 		# code...
		 		$superaffiliate_status = $value->superaffiliate_status;
		 		
		 		if($superaffiliate_status==1){
		 			$referid = $value->referid;
            		$flag=1;
            		return $referid;
            	}
            	
            }
          }
          
          if($flag==0){
          	return '<a href="javascript:void(0);" onclick="userJs.generateSuperaffiliateCode('.$all_patients->id.')" class="btn btn-primary-sm" id="refer_buttion_'.$all_patients->id.'" >Super Afiiliate</a><br /><div class="alert-success" id="success_referstatus_span_'.$all_patients->id.'" style="display:none; font-size:12px;"></div>';
          }

            })
		->make(true);
		



		/*$all_patients  = DB::table('userreferdetails')
           ->join('siteusers', 'userreferdetails.referby', '=', 'siteusers.id')
           ->select('siteusers.firstname','siteusers.lastname','siteusers.email','siteusers.phoneno','siteusers.status','userreferdetails.id')
           //->groupBy('userreferdetails.referby')
           ->get();*/

          
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
