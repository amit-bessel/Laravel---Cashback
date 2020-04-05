<?php namespace App\Http\Controllers\Admin;

use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;


use App\Model\Module; /* Model name*/
use App\Model\Usersmodules; /* Model name*/
use App\Model\User; /* Model name*/
use App\Model\Withdrawldetails; /* Model name*/
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

require_once('vendor/Stripe/init.php');

use Stripe\Stripe;
use Stripe\Charge;
use Stripe\Customer;
use Stripe\Stripe_CardError;
use Stripe\Stripe_InvalidRequestError;



class UserwithdrawlController extends BaseController {
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
     *                              GET USER WITHDRAWL REQUEST                                   *
     *******************************************************************************/
    function getWithdrawlRequest()
    {
    


        $module_head        = "User Withdrawl Request";
        $user_class         = "active";
        $search_key         = Request::query('search_key');
        $active             = Request::query('active');
        $startdate             = Request::query('startdate');
        $enddate             = Request::query('enddate');
        $title              = "User Withdrawl Request";
        $countwithdrawlrequest=Withdrawldetails::where('status',0)->count();

        return view('admin.siteuser.withdrawlrequest',compact('user_class',
                                                        'module_head','search_key','search_email','search_contact',
                                                        'active','title','startdate','enddate','countwithdrawlrequest'
                                                    )
                    );
    }



    /********************************************************************************
     *                          AJAX DATATABLE LISTING WITHDRAWL REQUEST                                *
     *******************************************************************************/

    function ajaxWithdrawlRequest(){

        //===get post data 
        $data       =Request::all();

        $search_key         =  trim(Request::query('search_key'));
        $active             =  Request::query('active');


        $startdate             =  Request::query('startdate');
        $enddate             =  Request::query('enddate');


        $where = "";

        

         if($search_key != ''){
                $whereRaw          = "(`firstname` LIKE '%".$search_key."%' OR CONCAT(firstname,' ',lastname) LIKE '%".$search_key."%' OR `lastname` LIKE '%".$search_key."%' OR `email` LIKE '%".$search_key."%' OR `phoneno` LIKE '%".$search_key."%')";
         }
      
        

        //$all_patients =  SiteUser::select(['id', 'firstname','lastname','email','phoneno','status','profileimage'])->whereRaw($where)->orderBy('id', 'DESC');

        //add columns
       $columns = array(

                0=>'id' ,
                1 =>'fullname',
                2=>'email',
                3=>'phoneno',
                4=>'Created_at',
                5=>'Amount'
                
               
            );

       //add order by 
       
       // search with date 
       
       //===only manual payment will show===

       if($startdate!='' && $enddate!='' && $search_key!=''){
        $all_patients = Withdrawldetails::with(['siteuser'=>function($query) use ($whereRaw) { $query->whereRaw($whereRaw);}])->whereHas('siteuser',function ($query) use ($whereRaw) { $query->whereRaw($whereRaw);})->whereDate('created_at','>=',$startdate)->whereDate('created_at','<=',$enddate)->where('status','0')->where('withdrawoption','0')->orderBy($columns[$data['order'][0]['column']],$data['order'][0]['dir']);  
       }

       else if($startdate!='' && $enddate!=''){

        $all_patients = Withdrawldetails::with('siteuser')->where('status','0')->where('withdrawoption','0')->whereDate('created_at','>=',$startdate)->whereDate('created_at','<=',$enddate)->orderBy($columns[$data['order'][0]['column']],$data['order'][0]['dir']);
        }
       else  if($search_key!=''){
          $all_patients = Withdrawldetails::with(['siteuser'=>function($query) use ($whereRaw) { $query->whereRaw($whereRaw);}])->whereHas('siteuser',function ($query) use ($whereRaw) { $query->whereRaw($whereRaw);})->where('status','0')->where('withdrawoption','0')->orderBy($columns[$data['order'][0]['column']],$data['order'][0]['dir']);  
        }
        else{
             $all_patients = Withdrawldetails::with('siteuser')->where('status','0')->where('withdrawoption','0')->orderBy($columns[$data['order'][0]['column']],$data['order'][0]['dir']);
        }


        return Datatables::of($all_patients)
        ->addColumn('checkbox_td', function ($all_patients) {
                return '<input type="checkbox"  recordType="multipleRecord" multipleRecord="'.$all_patients->siteusers_id.'"  value="'.$all_patients->siteusers_id."-".$all_patients->amount."-".$all_patients->id.'" class="payallchk" />';
            })
         
        
         ->editColumn('firstname', function ($all_patients) {
                $fullname = $all_patients->siteuser->firstname." ".$all_patients->siteuser->lastname;
                return $fullname;

            })
         ->editColumn('email', function ($all_patients) {
                $fullname = $all_patients->siteuser->email;
                return $fullname;

            })
         ->editColumn('phoneno', function ($all_patients) {
                $phoneno = $all_patients->siteuser->phoneno;
                return $phoneno;

            })
        ->editColumn('amount', function ($all_patients) {
                $amount = number_format($all_patients->amount,2);
                return "$".$amount;

            })
         ->editColumn('status', function ($all_patients) {
            if($all_patients->status == 0){
                $status_html = '<a onclick=paysingle("'.$all_patients->siteusers_id."-".$all_patients->amount."-".$all_patients->id.'") class="btn btn-blue" href="javascript:void(0)">Pay</a>';
            }
            return $status_html;
            })
        ->make(true);
    }

    /********************************************************************************
     *                              GET USER WITHDRAWL SUCCESS                                   *
     *******************************************************************************/
    function getWithdrawlSuccess()
    {
    


        $module_head        = "User Withdrawl History";
        $user_class         = "active";
        $search_key         = Request::query('search_key');
        $active             = Request::query('active');
        $startdate             = Request::query('startdate');
        $enddate             = Request::query('enddate');
        $title              = "User Withdrawl History";

        return view('admin.siteuser.withdrawlsuccess',compact('user_class',
                                                        'module_head','search_key','search_email','search_contact',
                                                        'active','title','startdate','enddate'
                                                    )
                    );
    }

    /********************************************************************************
     *                          AJAX DATATABLE LISTING WITHDRAWL SUCCESS                                *
     *******************************************************************************/

    function ajaxWithdrawlSuccess(){

        //===get post data 
        $data       =Request::all();

        $search_key         =  trim(Request::query('search_key'));
        $active             =  Request::query('active');


        $startdate             =  Request::query('startdate');
        $enddate             =  Request::query('enddate');


        $where = "";

        

         if($search_key != ''){
                $whereRaw          = "(`firstname` LIKE '%".$search_key."%' OR CONCAT(firstname,' ',lastname) LIKE '%".$search_key."%' OR `lastname` LIKE '%".$search_key."%' OR `email` LIKE '%".$search_key."%' OR `phoneno` LIKE '%".$search_key."%')";
         }
      
        

        //$all_patients =  SiteUser::select(['id', 'firstname','lastname','email','phoneno','status','profileimage'])->whereRaw($where)->orderBy('id', 'DESC');

        //add columns
       $columns = array(

                0=>'updated_at' ,
                1 =>'fullname',
                2=>'email',
                3=>'updated_at',
                4=>'Amount',
                5=>'senderbatchid'
                
               
            );

       //add order by 
       
       // search with date 

       if($startdate!='' && $enddate!='' && $search_key!=''){
        $all_patients = Withdrawldetails::with(['siteuser'=>function($query) use ($whereRaw) { $query->whereRaw($whereRaw);}])->whereHas('siteuser',function ($query) use ($whereRaw) { $query->whereRaw($whereRaw);})->whereDate('created_at','>=',$startdate)->whereDate('created_at','<=',$enddate)->where('status','1')->orderBy($columns[$data['order'][0]['column']],$data['order'][0]['dir']);  
       }

       else if($startdate!='' && $enddate!=''){

        $all_patients = Withdrawldetails::with('siteuser')->where('status','1')->whereDate('created_at','>=',$startdate)->whereDate('created_at','<=',$enddate)->orderBy($columns[$data['order'][0]['column']],$data['order'][0]['dir']);
        }
       else  if($search_key!=''){
          $all_patients = Withdrawldetails::with(['siteuser'=>function($query) use ($whereRaw) { $query->whereRaw($whereRaw);}])->whereHas('siteuser',function ($query) use ($whereRaw) { $query->whereRaw($whereRaw);})->where('status','1')->orderBy($columns[$data['order'][0]['column']],$data['order'][0]['dir']);  
        }
        else{
             $all_patients = Withdrawldetails::with('siteuser')->where('status','1')->orderBy($columns[$data['order'][0]['column']],$data['order'][0]['dir']);
        }


        return Datatables::of($all_patients)
        ->addColumn('checkbox_td', function ($all_patients) {
                return '<input type="checkbox"  recordType="multipleRecord" multipleRecord="'.$all_patients->siteusers_id.'"  value="'.$all_patients->siteusers_id."-".$all_patients->amount."-".$all_patients->id.'" class="payallchk" style="display:none" />';
            })
         
        
         ->editColumn('firstname', function ($all_patients) {
                $fullname = $all_patients->siteuser->firstname." ".$all_patients->siteuser->lastname;
                return $fullname;

            })
         ->editColumn('email', function ($all_patients) {
                $fullname = $all_patients->siteuser->email;
                return $fullname;

            })
         ->editColumn('phoneno', function ($all_patients) {
                $phoneno = $all_patients->siteuser->phoneno;
                return $phoneno;

            })
        ->editColumn('amount', function ($all_patients) {
                $amount = number_format($all_patients->amount,2);
                return "$".$amount;

            })
         ->editColumn('status', function ($all_patients) {
            if($all_patients->status == 1){
                $status_html = '<span>Paid</span>';
            }
            return $status_html;
            })
        ->make(true);
    }

}
?>