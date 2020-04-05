<?php namespace App\Http\Controllers\Admin; /* path of this controller*/
use App\Model\Emailnotification; /* Model name*/
use App\Model\Emailnotification_Siteuser; /* Model name*/
use App\Model\Sitesetting;
use App\Model\SiteUser; /* Model name*/
use App\Model\SiteUserReferId; /* Model name*/
use App\Http\Requests;
use App\Http\Controllers\Controller;
//use Illuminate\Support\Facades\Request;
use Input; /* For input */
use Validator;
use Session;
use Imagine\Image\Box;
use Image\Image\ImageInterface;
use Illuminate\Pagination\Paginator;
use DB;
use Customhelpers;
use Illuminate\Http\Request;
use Hash;
use Yajra\Datatables\Datatables;
use Mail;
use Auth;
use App\Model\Invitefriend;/*Model Name*/
use App\Model\User;/*Model Name*/
use App\Model\Previousemail; /* Model name*/

use App\Model\Walletdetails;  /* Model name*/
use App\Model\Userrefer; /* Model name*/
class SiteUserController extends BaseController {

   /**
    * Display a listing of the resource.
    *
    * @return Response
    */
    public function __construct() 
    {
        parent::__construct();
        view()->share('faq_class','active');
    }
   public function index(Request $request)
   {



        $module_head        = "User List";
        $user_class         = "active";
        $search_key         = $request->query('search_key');
        $active             = $request->query('active');
        $searchtime             =  $request->query('searchtime');
        $title              = "Site User Management";

        //======total user wallet money======

        $walletsumar=SiteUser::selectRaw("sum(wallettotalamount) as sumwalletmoney")->get();
        $walletsum=$walletsumar[0]->sumwalletmoney;

        return view('admin.siteuser.index',compact('user_class',
                                                        'module_head','search_key','search_email','search_contact',
                                                        'active','title','searchtime','walletsum'
                                                    )
                    );

    }

    /********************************************************************************
     *                              GET USER LIST                                   *
     *******************************************************************************/
    function getList(Request $request)
    {




//===get post data 
       //  $data       = $request->all();

       //  $search_key         =  $request->query('search_key');
       //  $active             =  $request->query('active');
       //  $searchtime             =  $request->query('searchtime');



       //  $where = "";

        

       //   if($search_key != ''){
       //          $where          .= "(`firstname` LIKE '%".$search_key."%' OR CONCAT(firstname,' ',lastname) LIKE '%".$search_key."%' OR `lastname` LIKE '%".$search_key."%' OR `email` LIKE '%".$search_key."%' OR `phoneno` LIKE '%".$search_key."%') AND ";
       //   }
       //  if($active != ''){
       //      $where         .= "`status`= '".$active."' AND ";
       //  }
        
        
       //  if($searchtime=="daily"){

       //      $todaydate="Y-m-d";

       //      $where         .= "date(created_at)='".$todaydate."' AND ";
       //  }

       //  $where         .= 'is_deleted=0';


       //  //$all_patients =  SiteUser::select(['id', 'firstname','lastname','email','phoneno','status','profileimage'])->whereRaw($where)->orderBy('id', 'DESC');

    

       // //add order by 
       //  $all_patients = SiteUser::with(['userreferidrelation' => function ($query) {
       //  $query->where('superaffiliate_status', '=', '1');

       //  }])->whereRaw($where)->get();

       //  echo "<pre>";
       //  print_r($all_patients);
       //  exit();










       $module_head        = "User List";
        $user_class         = "active";
        $search_key         = $request->query('search_key');
        $active             = $request->query('active');
        $searchtime             =  $request->query('searchtime');
        $title              = "Site User Management";

        //======total user wallet money======

        $walletsumar=SiteUser::selectRaw("sum(wallettotalamount) as sumwalletmoney")->get();
        $walletsum=$walletsumar[0]->sumwalletmoney;

        return view('admin.siteuser.index',compact('user_class',
                                                        'module_head','search_key','search_email','search_contact',
                                                        'active','title','searchtime','walletsum'
                                                    )
                    );
    }
   

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $SiteUser = SiteUser::all();

        return view('admin.siteuser.create',array('title'=>'Site User Management','module_head'=>'Add Site User','SiteUser'=>$SiteUser));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $datetime= Customhelpers::Returndatetime();
       // $siteuser=Request::all();
        $title = $request->input('title');
        $firstname = $request->input('firstname');
        $lastname = $request->input('lastname');
        //$address = $request->input('address');
        //$address1 = $request->input('address1');
        //$country = $request->input('country');
        //$city = $request->input('city');
        //$state = $request->input('state');
        //$zipcode = $request->input('zipcode');
        $phoneno = $request->input('phoneno');
        //$mobileno = $request->input('mobileno');
        $email = $request->input('email');
        //$dob = $request->input('dob');

        //$randompassword=$this->randomPassword();

        $inputpassword=$request->input('password');

        $password  = Hash::make($inputpassword);

        //$updated_at=$datetime;
        $created_at=$datetime;
        

        $file = $request->file('profileimage');
        if(!empty($file)){
        $fileorgname=$file->getClientOriginalName();

        if($fileorgname!=''){

        $extension=strtolower($file->getClientOriginalExtension());
        
        $extensionar=array('jpg', 'jpeg', 'gif', 'png','bmp');


        if (in_array($extension, $extensionar))
          {
          //echo "Match found";exit();
                $destinationPath = public_path('backend/profileimage');
                $filename=rand().time().$file->getClientOriginalName();
                $file->move($destinationPath,$filename);
          }
        else
          {
         // echo "Match not found";
                $filename='';
          }


        }
        else{

             $filename='';
        }
    }
    else
    {
        $filename='';
    }

        //$Sitelastinsertid = SiteUser::create(['firstname'=>$firstname,'lastname' => $lastname, 'address' => $address,'created_at' => $created_at, 'address1' => $address1, 'country' => $country, 'city' => $city, 'state' => $state, 'zipcode' => $zipcode, 'phoneno' => $phoneno, 'mobileno' => $mobileno, 'email' => $email, 'dob' => $dob,'profileimage'=>$filename,'password'=>$password,'status'=>1])->id;


        $Sitelastinsertid = SiteUser::create(['firstname'=>$firstname,'lastname' => $lastname, 'created_at' => $created_at, 'phoneno' => $phoneno, 
         'email' => $email,'password'=>$password,'status'=>1,'updated_at'=>$created_at,'title'=>$title,'newsletterstatus'=>1])->id;

        //add the email into previous email table

        $count=Previousemail::where('email',$email)->count();

        if($count==0){

            Previousemail::create(['email'=>$email,'created_at'=>$created_at]);

        }
        

        /*
        DB::table('faqs')->insert(
            ['faqcategory_id'=>3,'question' => $question, 'answer' => $answer,'updated_at' => $updated_at,'created_at' => $created_at]
        );
        */

        $referid=$Sitelastinsertid.$this->userreferid();

        SiteUserReferId::create(['siteuser_id'=>$Sitelastinsertid,'referid'=>$referid,'created_at' => $created_at]);


         

            ###################################Sening Registration mail starts##############################
            
            $user_name  = $firstname." ".$lastname;
            $user_email = $email;
            
            
            $admin_users_email = "";
            
            $sitesettings = DB::table('sitesettings')->where('name','email')->first();
            if(!empty($sitesettings))
            {
                $admin_users_email = $sitesettings->value;
            }
            
            $subject = "You have added as a User";
            
            $message_body = "Admin has added you as a user  in Checkout Saver.Your password is: ".$inputpassword." . <br/>Your login link is ".url('');
            
            ################# SEND MAIL TO SITE-USER -start ################################
            
            
            
            if(($_SERVER['REMOTE_ADDR'] != 'localhost') && ($_SERVER['REMOTE_ADDR'] != '192.168.1.115')) //check if code is ruuning in localhost
            {
                $mail = Mail::send(['html' => 'admin.emailtemplate.send_mail'], array('message_body' => $message_body, 'user_name'=>$user_name), function($message) use ($admin_users_email, $user_email,$user_name,$subject)
                {
                    $message->from($admin_users_email,'Checkout Saver');
        
                    $message->to($user_email)->subject($subject);
                });
            }
            ################# SEND Registration  MAIL TO SITE-USER -end ################################


            



       Session::flash('success_message', 'Site User details has been Saved successfully.'); 
        Session::flash('alert-class', 'alert alert-success'); 
        return redirect('admin/siteuser');
    }


    /********************************************************************************
     *                          Generate Superaffiliate Code                                      *
     *******************************************************************************/
    function getGenerateSuperaffiliateCode(Request $request){
       
        $datetime= Customhelpers::Returndatetime();
        $post_data  = $request->all();
        $id         = $post_data['this_id'];
        $updated_at=$datetime;
        $created_at=$datetime;
        $referid=$id."S".$this->userreferid();

        $user_referid_count = SiteUserReferId::where('siteuser_id', '=', $id)->where('superaffiliate_status', '=', 1)->where('status', '=', 1)->get()->count();

        
        if($user_referid_count==0){
         
        /*SiteUserReferId::create(['siteuser_id'=>$id,'updated_at' => $updated_at,'created_at' => $created_at,'superaffiliate_status'=>1]);*/
        SiteUserReferId::where('siteuser_id','=',$id)->update(['updated_at' => $updated_at,'superaffiliate_status'=>1]);

        $siteuser=SiteUser::find($id);

        $siteuser->update(['superaffiliateuser'=>1]);









/**************************SEND MAIL -start Confirmation-as-superaffiliate notification mail*****************************/

              //Check Confirmation-as-superaffiliate status in profile is on or off

          $emailnotification=Emailnotification::where('slug','Confirmation-as-superaffiliate')->where('status',1)->get();
          
          $emailnotification_count=Emailnotification::where('slug','Confirmation-as-superaffiliate')->where('status',1)->count();
          if($emailnotification_count>0){
              $emailnotification_id=$emailnotification[0]->id;
            

            $emailnotification_Siteuser=Emailnotification_Siteuser::where('siteusers_id',$id)->where("emailnotifications_id",$emailnotification_id)->get();


            $emailnotification_Siteuser_count=Emailnotification_Siteuser::where('siteusers_id',$id)->where("emailnotifications_id",$emailnotification_id)->count();

            if($emailnotification_Siteuser_count>0){

              $mailstatus=$emailnotification_Siteuser[0]->status;
              if($mailstatus==1){

                //$status=0;

                      
            ###################################Sening Super Affiliate Code mail starts##############################
            
            $user_name  = $siteuser->firstname." ".$siteuser->lastname;
            $user_email = $siteuser->email;
            
            
            $admin_users_email = "";
            
            $sitesettings = DB::table('sitesettings')->where('name','email')->first();
            if(!empty($sitesettings))
            {
                $admin_users_email = $sitesettings->value;
            }
            
            $subject = "You are a super affiliate user in Checkout Saver";
            
            $message_body = "You are a super affiliate in Checkout Saver ";
            
            ################# SEND MAIL TO SITE-USER -start ################################
            
            
            
            if(($_SERVER['REMOTE_ADDR'] != 'localhost') && ($_SERVER['REMOTE_ADDR'] != '192.168.1.115')) //check if code is ruuning in localhost
            {
                $mail = Mail::send(['html' => 'admin.emailtemplate.send_mail'], array('message_body' => $message_body, 'user_name'=>$user_name), function($message) use ($admin_users_email, $user_email,$user_name,$subject)
                {
                    $message->from($admin_users_email,'Checkout Saver');
        
                    $message->to($user_email)->subject($subject);
                });
            }
            ################# SEND Super Affiliate Code MAIL TO SITE-USER -end ################################

                
              }
              
            }
          } 


           /**************************SEND MAIL -Confirmation-as-superaffiliate notification mail end*****************************/











        echo "1";

        }
        else
        {
            echo "0";
        }

        exit();
    
    }


    /* Generate random password */
    public function randomPassword() {
    $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
    $pass = array(); //remember to declare $pass as an array
    $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
    for ($i = 0; $i < 8; $i++) {
        $n = rand(0, $alphaLength);
        $pass[] = $alphabet[$n];
    }
    return implode($pass); //turn the array into a string
    }

    /* Generate user refer id */
    public function userreferid() {
    $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
    $pass = array(); //remember to declare $pass as an array
    $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
    for ($i = 0; $i < 4; $i++) {
        $n = rand(0, $alphaLength);
        $pass[] = $alphabet[$n];
    }
    return implode($pass); //turn the array into a string
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    /*
    public function show($id)
    {
       $faq=Faq::find($id);
       return view('admin.faq.show',compact('faq'));
    }*/

    /* Update the site user  */
    
    public function edit($id)
    {
        $siteuser=SiteUser::find($id);
        $user_id = $id;
        //$siteuser = SiteUser::all();
        return view('admin.siteuser.edit',compact('user_id'),array('title'=>'Edit Site User','module_head'=>'Edit Site User','siteuser'=>$siteuser));
    }

   
    public function update(Request $request, $id)
    {



        $datetime= Customhelpers::Returndatetime();
       // $siteuser=Request::all();
        $title=$request->input('title');
        $firstname = $request->input('firstname');
        $lastname = $request->input('lastname');
        //$address = $request->input('address');
        //$address1 = $request->input('address1');
        //$country = $request->input('country');
        //$city = $request->input('city');
        //$state = $request->input('state');
        //$zipcode = $request->input('zipcode');
        $phoneno = $request->input('phoneno');
       // $mobileno = $request->input('mobileno');
        $email = $request->input('email');
        //$dob = $request->input('dob');
        
       
        $updated_at=$datetime;
        $created_at=$datetime;
        
        $siteuser=SiteUser::find($id);

        $datetime= Customhelpers::Returndatetime();


        //$siteuser->update(['firstname'=>$firstname,'lastname' => $lastname, 'address' => $address,'updated_at' => $updated_at, 'address1' => $address1, 'country' => $country, 'city' => $city, 'state' => $state, 'zipcode' => $zipcode, 'phoneno' => $phoneno, 'mobileno' => $mobileno, 'email' => $email, 'dob' => $dob]);

        $siteuser->update(['title' => $title,'firstname'=>$firstname,'lastname' => $lastname, 'updated_at' => $updated_at, 'phoneno' => $phoneno,  
            'email' => $email]);

        $count=Previousemail::where('email',$email)->count();
        
        if($count==0){

            Previousemail::create(['email'=>$email,'created_at'=>$created_at]);

        }


        $file = $request->file('profileimage');
        if(!empty($file)){

        $fileorgname=$file->getClientOriginalName();

        if($fileorgname!=''){

        $extension=strtolower($file->getClientOriginalExtension());

        $extensionar=array('jpg', 'jpeg', 'gif', 'png','bmp');


        if (in_array($extension, $extensionar))
        {

        //echo "Match found";exit();
        $destinationPath = public_path('backend/profileimage');
        $filename=rand().time().$file->getClientOriginalName();
        $file->move($destinationPath,$filename);
        $siteuser->update(['profileimage'=>$filename]);
        }
        else
        {
        // echo "Match not found";
        $filename='';
        }


        }
        else{

        $filename='';
        }
    }

           Session::flash('success_message', 'Site User details has been updated successfully.'); 
        Session::flash('alert-class', 'alert alert-success'); 
           return redirect('admin/siteuser');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response	7278876384
     */
    /********************************************************************************
     *                              REMOVE USER                                     *
     *******************************************************************************/
    public function getRemove($id)
    {

        $user_details = SiteUser::find($id);
        $user_details->is_deleted=1; //1:deleted 0:exist
        $user_details->save();
        /*if(!empty($user_details['profileimage'])){
            @unlink('backend/profileimage/'.$user_details['profileimage']);
        }*/
       // $user_details->delete();
       
        Session::flash('success_message', 'Site User has been removed successfully.'); 
        Session::flash('alert-class', 'alert alert-success'); 
        return redirect('admin/siteuser');
        
    }
    
    /* FOR FRONT END  FAQ SHOWING */
	
	
    
    
    public function show($id='',Request $request)
    {


        
    }


    /********************************************************************************
     *                          AJAX DATATABLE LISTING USERS                                *
     *******************************************************************************/

    function ajaxPatientsList(Request $request){

        //===get post data 
        $data       = $request->all();

        $search_key         =  $request->query('search_key');
        $active             =  $request->query('active');
        $searchtime             =  $request->query('searchtime');



        $where = "";

        

         if($search_key != ''){
                $where          .= "(`firstname` LIKE '%".$search_key."%' OR CONCAT(firstname,' ',lastname) LIKE '%".$search_key."%' OR `lastname` LIKE '%".$search_key."%' OR `email` LIKE '%".$search_key."%' OR `phoneno` LIKE '%".$search_key."%') AND ";
         }
        if($active != ''){
            $where         .= "`status`= '".$active."' AND ";
        }
        
        if(!empty($searchtime)){

                $todaydate=date("Y-m-d");

                if($searchtime=="daily")
                {

                    

                    $where         .= "date(created_at)='".$todaydate."' AND ";
                }
                else if($searchtime=="weekly"){

                     $weekdate=date("Y-m-d", strtotime("-1 week"));
                     $where         .="date(created_at) >='".$weekdate."' AND ";
                }

                else if($searchtime=="monthly"){

                     $monthdate=date("Y-m-d", strtotime("-1 month"));
                     $where         .="date(created_at) >='".$monthdate."' AND ";
                }
                else if($searchtime=="yearly"){

                     $yeardate=date("Y-m-d", strtotime("-1 year"));
                     $where         .="date(created_at) >='".$yeardate."' AND ";
                }
        }   
        

        $where         .= 'is_deleted=0';

        //echo $where;exit();
        //$all_patients =  SiteUser::select(['id', 'firstname','lastname','email','phoneno','status','profileimage'])->whereRaw($where)->orderBy('id', 'DESC');

        //add columns
       $columns = array(

                0=>'id' ,
                1=>'#',
                2=>'loginip',
                3 =>'firstname',
                4 =>'email', 
                5=>'created_at',
                6 =>'wallettotalamount',
                7=>'spending',
                8=>'avgspending',
                9=>'walletcommissionsum',
                10=>'cashback',
                11=>'refer',
                12=>'status'
               
            );

       //add order by 
        $all_patients = SiteUser::with(['userreferidrelation' => function ($query) {
        $query->where('superaffiliate_status', '=', '1');

        }])->whereRaw($where)->orderBy($columns[$data['order'][0]['column']],$data['order'][0]['dir']);


        //print_r($all_patients);exit;
        return Datatables::of($all_patients)
        ->addColumn('checkbox_td', function ($all_patients) {
                return '<input type="checkbox"  recordType="multipleRecord" multipleRecord="'.$all_patients->id.'" />';
            })
         ->addColumn('action', function ($all_patients) {
                return '<a class="edit-icon" href="'.url().'/admin/siteuser/'.$all_patients->id.'/edit"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a> <a class="view-icon" href="'.url().'/admin/siteuser/view/'.$all_patients->id.'"><i class="fa fa-eye" aria-hidden="true"></i></a><a href="javascript:void(0);" class="delete-icon" onclick="userJs.siteuserremove('.$all_patients->id.')"><i class="fa fa-trash-o" aria-hidden="true"></i></a><a href="'.url().'/admin/userreferdetails/list?userid='.$all_patients->id.'" class="referral-link"> Referral</a>';
            })
         ->addColumn('refer', function ($all_patients) {
            // if($all_patients->userreferidrelation->count()==1){ if($all_patients->id){ return $msg="<span  style='padding: 4px 2px; font-size:11px;'>Code Already Generated</span>"; } }
            // else {
            //     return '<a href="javascript:void(0);" onclick="userJs.generateSuperaffiliateCode('.$all_patients->id.')" class="btn btn-primary-sm" id="refer_buttion_'.$all_patients->id.'" >Generate Code</a><br /><div class="alert-success" id="success_referstatus_span_'.$all_patients->id.'" style="display:none; font-size:12px;"></div>';
            // }
            if($all_patients->superaffiliateuser == 1){
                    return '<label class="switch"><input type= "checkbox"  name="superaffiliate[]" id="superaffiliate'.$all_patients->id.'" class="superaffiliate" value="'.$all_patients->id.'" onclick="userJs.changesuperaffiliatestatus('.$all_patients->id.')" checked="checked"/><span class="slider round"></span></label><div class="alert-success" id="superaffiliate'.$all_patients->id.'" style="display:none;"></div>';
                 }
                 else{

                    return '<label class="switch"><input type= "checkbox"  name="superaffiliate[]" id="superaffiliate'.$all_patients->id.'" class="superaffiliate" value="'.$all_patients->id.'" onclick="userJs.changesuperaffiliatestatus('.$all_patients->id.')"/><span class="slider round"></span></label><div class="alert-success" id="superaffiliate_status_span_'.$all_patients->id.'" style="display:none;"></div>';
                 }
            
            })
         ->editColumn('firstname', function ($all_patients) {
                $fullname = $all_patients->firstname." ".$all_patients->lastname;
                return $fullname;

            })
         ->editColumn('uniqueid', function ($all_patients) {
                $uniqueid = $all_patients->id;
                return $uniqueid."S";

            })
         ->editColumn('status', function ($all_patients) {
            if($all_patients->status == 1){
                $status_html = '<select class="custom-select table-custom-select" name="status" id="user_active_'.$all_patients->id.'" onchange="userJs.changesiteuserStatus(this.value,'.$all_patients->id.')"><option value="1" selected>Active</option><option value="0">Inactive</option></select><br /><div class="alert-success" id="success_status_span_'.$all_patients->id.'" style="display:none;"></div>';
            }else{
                $status_html = '<select class="custom-select table-custom-select" name="status" id="user_active_'.$all_patients->id.'" onchange="userJs.changesiteuserStatus(this.value,'.$all_patients->id.')"><option value="1">Active</option><option value="0" selected>Inactive</option></select><div class="alert-success" id="success_status_span_'.$all_patients->id.'" style="display:none;"></div>';

            }
            return $status_html;
            })

            ->editColumn('spending', function ($all_patients) {

               $userid=$all_patients->id;
               $count=Walletdetails::where('siteusers_id',$userid)->where('purpose_state','0')->where('type','0')->count();//for gift card debit
               if($count>0){
                 $walletsumar=Walletdetails::selectRaw("sum(total) as amount")->where('siteusers_id',$userid)->where('purpose_state','0')->where('type','0')->groupBy('siteusers_id')->get();
                 $walletsum=$walletsumar[0]->amount;
               }
               else{
                $walletsum=0;
               }

               $walletsum=number_format($walletsum,2);
           
            return "$".$walletsum;
            })

            ->editColumn('wallettotalamount', function ($all_patients) {

               $wallettotalamount=number_format($all_patients->wallettotalamount,2);
           
            return "$".$wallettotalamount;
            })

            ->editColumn('avgspending', function ($all_patients) {

               $wallettotalamount=number_format($all_patients->wallettotalamount,2);


               $userid=$all_patients->id;
               $count=Walletdetails::where('siteusers_id',$userid)->where('purpose_state','0')->where('type','0')->count();//for gift card debit
               if($count>0){
                 $walletsumar=Walletdetails::selectRaw("sum(total) as amount")->where('siteusers_id',$userid)->where('purpose_state','0')->where('type','0')->groupBy('siteusers_id')->get();
                 $walletsum=$walletsumar[0]->amount;
               }
               else{
                $walletsum=0;
               }

               $walletsum=number_format($walletsum,2);

               $saving=$wallettotalamount;
               $spending=$walletsum;
               if($spending!=0){
                $avgspending=$saving/$spending;
                 $avgspending=number_format($avgspending*100,2);
                 if($avgspending==0)
                    $avgspending="0%";
                 else
                    $avgspending=$avgspending."%";
               }
               else if($saving==0 && $spending==0){
                $avgspending="0%";
               }
               else{
                $avgspending="100%";
               }
               
                
            
             return "$".$avgspending;
            })

             ->editColumn('walletcommissionsum', function ($all_patients) {

                $userid=$all_patients->id;
                $raw="(purpose_state='2' or purpose_state='5')";

               $count=Walletdetails::where('siteusers_id',$userid)->whereRaw($raw)->where('type','1')->count();//commission credit

               if($count>0){

                $walletcommission=Walletdetails::selectRaw("sum(total) as amount")->where('siteusers_id',$userid)->whereRaw($raw)->where('type','1')->groupBy('siteusers_id')->get();

                $walletcommissionsum=$walletcommission[0]->amount;
               }
               else{
                $walletcommissionsum=0;
               }
               $walletcommissionsum=number_format($walletcommissionsum,2);
            return "$".$walletcommissionsum;
            })

            ->editColumn('cashback', function ($all_patients) {

                $userid=$all_patients->id;
                

               $count=Walletdetails::where('siteusers_id',$userid)->where('purpose_state','3')->where('type','1')->count();//cashback

               if($count>0){

                $cashback=Walletdetails::selectRaw("sum(total) as amount")->where('siteusers_id',$userid)->where('purpose_state','3')->where('type','1')->groupBy('siteusers_id')->get();

                $cashback=$cashback[0]->amount;
               }
               else{
                $cashback=0;
               }
               $cashback=number_format($cashback,2);
            return "$".$cashback;
            })
        ->make(true);
    }


    /********************************************************************************
     *                          CHANGE STATUS                                       *
     *******************************************************************************/
    function getStatus(Request $request){
       
        $post_data  = $request->all();
        $status     = $post_data['this_val'];
        $id         = $post_data['this_id'];
        $user   = SiteUser::find($id);
        $user->status = $status;
        $user->save();
        echo "1";
        exit();
    
    }

    public function getviewuser(Request $request,$id){
        
        $SiteUser   = SiteUser::find($id);
        return view('admin.siteuser.view_siteuser_details',array('title'=>'View Site User','module_head'=>'View Site User','user_details'=>$SiteUser));

    }

    /********************************************************************************
     *                      CHECK USER EXISTS OR NOT                                *
     *******************************************************************************/
    function postCheck(Request $request){
        $data   = $request->all();
        
        $where_raw = "1=1";
        $where_raw .= " AND `email` = '".$data['email']."'";
        if($data['hid_user_id']!=""){
            $where_raw .= " AND `id`!= '".$data['hid_user_id']."'";
        }
        $user_details   = SiteUser::whereRaw($where_raw)->first();
        if(count($user_details)>0){
            echo "false";
        }
        else{
            echo "true";
        }
        
    }

    /* Invite friends by admin */
    function getInvite(Request $request){
       // echo "hh";exit();

        $code="S".uniqid();
        return view('admin.siteuser.invite',array('title'=>'Invite User','module_head'=>'Invite User','code'=>$code));
    }

    public function postShareReferCodeInsert(Request $request){

        $data=$request->all();
        //echo "<pre>";
        //print_r($data);exit();
        $referbyname='Admin';
        
        $remembertoken=$data['remembertoken'];
        $fullname=$data["name"];
        $email=$data["email"];
        $refercode=$data["refercode"];
        //$link=$data["link"];
        $id = Auth::id();
        $encodeid=base64_encode($id);
        $usertype=User::find($id)->role;
        $datetime= Customhelpers::Returndatetime();
        Invitefriend::create(['remember_token'=>$remembertoken,'siteusers_id'=>$id,'name'=>$fullname,'email'=>$email,'created_at'=>$datetime,'refercode'=>$refercode,'usertype'=>$usertype]);



         ###################################Share Refer Code mail starts##############################
            
            $encodedrefercode=base64_encode($refercode);

            $user_name  = $fullname;
            $user_email = $email;
            
            
            $admin_users_email = "";
            
            $sitesettings = DB::table('sitesettings')->where('name','email')->first();
            if(!empty($sitesettings))
            {
                $admin_users_email = $sitesettings->value;
            }
            
            if(!empty($refercode)){
                $subject = "Use this refer code to register in Checkout Saver.";
                $link=url('')."/signup?shareutype=".$encodeid."&scd=".$encodedrefercode;
                $message_body = $referbyname. " has sent you a super affiliate refer code ".$refercode." . Use this refer code to register in Checkout Saver.  <a href='".$link."'>Click on this link to register</a>";
            }
            else if(!empty($link)){
                    $link=$link."&tk=".$remembertoken;
                    $subject = "Click on this link to register in Checkout Saver.";
            
                    $message_body = $referbyname. " has sent you a link ".$refercode." . Use this link to register in Checkout Saver. <a href='".$link."'>Click on this link to register</a>";

            }
            
            
            
            
            
            
            if(($_SERVER['REMOTE_ADDR'] != 'localhost') && ($_SERVER['REMOTE_ADDR'] != '192.168.1.115')) //check if code is ruuning in localhost
            {
                $mail = Mail::send(['html' => 'admin.emailtemplate.send_mail'], array('message_body' => $message_body, 'user_name'=>$user_name), function($message) use ($admin_users_email, $user_email,$user_name,$subject)
                {
                    $message->from($admin_users_email,'Checkout Saver');
        
                    $message->to($user_email)->subject($subject);
                });
            }
            ################# Share Refer Code -end ################################





        Session::flash('success_message', 'You have successfully invited user');
        return redirect('admin/siteuser/invite');
    }

    public function postChangeSuperaffiliatestatus(Request $request){


       
        $data=$request->all();

        $userid=$data['userid'];

        $siteuserdetails=SiteUser::find($userid);

        if($siteuserdetails->superaffiliateuser=='0'){

            $siteuserdetails->superaffiliateuser='1';
            $siteuserdetails->save();
            


            $flag="1";

           //echo "1";//super affiliated
            
        }
        else{
            $siteuserdetails->superaffiliateuser='0';
            $siteuserdetails->save();
            $flag="2";

            //echo "2";//not super affiliated
        }

        
        /**************************SEND MAIL -start Friend registration notification mail*****************************/

              //Check Confirmation-as-superaffiliate status in profile is on or off

          $emailnotification=Emailnotification::where('slug','Confirmation-as-superaffiliate')->where('status',1)->get();
          
          $emailnotification_count=Emailnotification::where('slug','Confirmation-as-superaffiliate')->where('status',1)->count();
          if($emailnotification_count>0){
              $emailnotification_id=$emailnotification[0]->id;
            

            $emailnotification_Siteuser=Emailnotification_Siteuser::where('siteusers_id',$userid)->where("emailnotifications_id",$emailnotification_id)->get();


            $emailnotification_Siteuser_count=Emailnotification_Siteuser::where('siteusers_id',$userid)->where("emailnotifications_id",$emailnotification_id)->count();

            if($emailnotification_Siteuser_count>0){

              $mailstatus=$emailnotification_Siteuser[0]->status;
              if($mailstatus==1){

                //$status=0;

                      $site = Sitesetting::where(['name' => 'email'])->first();
                      $admin_users_email = $site->value;

                      

                      $users = SiteUser::where("id","=",$userid)->first();
                      $user_name = $users->firstname.' '.$users->lastname;
                      $user_email = $users->email;
                     
                     if($flag==1){
                        $subject = "You Are Now A Super Affiliated User In Checkout Saver.";
                        $message_body = "You Are Now A Super Affiliated User In Checkout Saver.";
                     }
                     elseif($flag==2){
                        $subject = "You Are Now A Normal User In Checkout Saver.";
                        $message_body = "You Are Now A Normal User In Checkout Saver.";
                     }
                      
                      $mail = Mail::send(['html' => 'frontend.emailtemplate.send_mail'], array('message_body' => $message_body, 'user_name'=>$user_name), function($message) use ($admin_users_email, $user_email,$user_name,$subject)
                      {
                      $message->from($admin_users_email,'Checkout Saver');

                      $message->to($user_email)->subject($subject);
                      });


                
              }
              
            }
          } 

        echo $flag;
           /**************************SEND MAIL -start Friend registration notification mail end*****************************/
        
        exit();
    }


    /********************************All Site user transaction start******************/

    public function getAllSiteUserTransaction(Request $request){

      $module_head = 'All User Transaction History';
      $title = 'All User Transaction History';


      $user_class         = "active";
      $search_key         = $request->query('search_key');
      $active             = $request->query('active');
      $searchtime         =  $request->query('searchtime');


      return view('admin.siteuser.alltransaction_list',compact('module_head','title','user_class','search_key','active','searchtime'));
    }

    public function postAllSiteUserTransaction(Request $request){

        $data       = $request->all();

        $search_key         =  trim($request->query('search_key'));
        $active             =  $request->query('active');
        $searchtime         =  $request->query('searchtime');



        $where = "";

         $where1 = "";

         if($search_key != ''){
                $where1          .= "(`firstname` LIKE '%".$search_key."%' OR CONCAT(firstname,' ',lastname) LIKE '%".$search_key."%' OR `lastname` LIKE '%".$search_key."%' OR `email` LIKE '%".$search_key."%' OR `phoneno` LIKE '%".$search_key."%') AND ";
         }

        if($active != ''){
            $where         .= "`walletstatus`= '".$active."' AND ";
        }
        
        if(!empty($searchtime)){

                $todaydate=date("Y-m-d");

                if($searchtime=="daily")
                {

                    

                    $where         .= "date(created_at)='".$todaydate."' AND ";
                }
                else if($searchtime=="weekly"){

                     $weekdate=date("Y-m-d", strtotime("-1 week"));
                     $where         .="date(created_at) >='".$weekdate."' AND ";
                }

                else if($searchtime=="monthly"){

                     $monthdate=date("Y-m-d", strtotime("-1 month"));
                     $where         .="date(created_at) >='".$monthdate."' AND ";
                }
                else if($searchtime=="yearly"){

                     $yeardate=date("Y-m-d", strtotime("-1 year"));
                     $where         .="date(created_at) >='".$yeardate."' AND ";
                }
        }   
        

        $where         .= '1';
        $where1         .= '1';
        
        $columns = array(

            0 =>'id' ,
            1=>'id',
            2=>'name',
            3 =>'created_at',
            4=>'purpose_state',
            5 => 'amount',
            6=>'itemdetails',
            7=>'purpose',
            8 => 'type',
            9 => 'walletstatus'
                
            );

        if($search_key != ''){

           $alltransaction = Walletdetails::with(['siteusers' => function ($query) use ($where1) { $query->whereRaw($where1);}])->whereHas('siteusers', function ($query) use ($where1) { $query->whereRaw($where1);})->whereIn('purpose_state',['0','1','2','3','5'])->whereRaw($where)->orderBy($columns[$data['order'][0]['column']],$data['order'][0]['dir'])->get(); // except only withdraw money

        }
        else{
           $alltransaction = Walletdetails::with('siteusers')->whereIn('purpose_state',['0','1','2','3','5'])->whereRaw($where)->orderBy($columns[$data['order'][0]['column']],$data['order'][0]['dir'])->get(); // except only withdraw money
        }




       
        return Datatables::of($alltransaction)
            ->addColumn('checkbox_td', function ($alltransaction) {

              if(( $alltransaction->purpose_state=='2' || $alltransaction->purpose_state=='3' || $alltransaction->purpose_state=='5') &&   $alltransaction->walletstatus=='0')
              {
                return '<input type="checkbox"  recordType="multipleRecord" multipleRecord="'.$alltransaction->id.'" />';
              }
              else{
                return '';
              }

                


            })
            ->editColumn('purpose_state', function ($alltransaction) {
                if($alltransaction->purpose_state == '0')
                    $fullname = 'Gift Card';
                if($alltransaction->purpose_state == '1')
                    $fullname = 'Signup Balance';
                if($alltransaction->purpose_state == '2')
                    $fullname = 'Commission';
                if($alltransaction->purpose_state == '3')
                    $fullname = 'Cashback';
                if($alltransaction->purpose_state == '4')
                    $fullname = 'Withdrawl';
                if($alltransaction->purpose_state == '5')
                    $fullname = 'Super Affiliate Commission';
                return $fullname;

            })

            ->editColumn('type', function ($alltransaction) {
                if($alltransaction->type == 0)
                    $type = 'Debit';
                if($alltransaction->type == 1)
                    $type = 'Credit';
                return $type;
            })
            ->editColumn('amount', function ($alltransaction) {
                
                return '$'.$alltransaction->amount;
            })
            ->editColumn('name', function ($alltransaction) {
                
                return $alltransaction->siteusers->firstname." ".$alltransaction->siteusers->lastname;
            })
            ->editColumn('walletstatus', function ($alltransaction) {
                if($alltransaction->walletstatus == '0')
                    $walletstatus = '<span style="color:#007bff;"><b>Pending</b></span>';
                else if($alltransaction->walletstatus == '1')
                    $walletstatus = '<span style="color:green;"><b>Success</b></span>';
                else if($alltransaction->walletstatus == '2')
                    $walletstatus = 'Reject';
                
                return $walletstatus;

            })
            ->make(true);

    }

    /***************************All Site user transaction end**********************/


    public function getUsertransaction($id){
        $module_head = 'Transaction History';

        $id = base64_decode($id);
        $history = Walletdetails::where('siteusers_id',$id)->orderBy('created_at','desc')->get();
        if(count($history)> 0){
            $history = $history->toArray();
            }
        // echo "<pre>";
        $userDetails = SiteUser::find($id);
        $title = 'Transaction History';
        // print_r($userDetails->toArray());
        return view('admin.siteuser.transaction_list',compact('module_head','title','history','userDetails'));
        // exit();
    }

    public function PostUsertransaction(Request $request,$id){
        $data=$request->all();
        /*echo "<pre>";
        echo $id;
        print_r($data);
        exit();*/
        $columns = array(

                0 =>'id' ,
                1 =>'created_at',
                2 =>'purpose_state',
                3 => 'amount',
                5 => 'type',
                6 => 'walletstatus'
                
            );
        $alltransaction = Walletdetails::where('siteusers_id',$id)->orderBy($columns[$data['order'][0]['column']],$data['order'][0]['dir'])->get();
        return Datatables::of($alltransaction)
            ->editColumn('purpose_state', function ($alltransaction) {
                if($alltransaction->purpose_state == '0')
                    $fullname = 'Gift Card';
                if($alltransaction->purpose_state == '1')
                    $fullname = 'Signup Balance';
                if($alltransaction->purpose_state == '2')
                    $fullname = 'Commission';
                if($alltransaction->purpose_state == '3')
                    $fullname = 'Cashback';
                if($alltransaction->purpose_state == '4')
                    $fullname = 'Withdrawl';
                if($alltransaction->purpose_state == '5')
                    $fullname = 'Super Affiliate Commission';
                return $fullname;

            })
            ->editColumn('type', function ($alltransaction) {
                if($alltransaction->type == 0)
                    $type = 'Debit';
                if($alltransaction->type == 1)
                    $type = 'Credit';
                return $type;
            })
            ->editColumn('amount', function ($alltransaction) {
                
                return '$'.$alltransaction->amount;
            })
            ->editColumn('walletstatus', function ($alltransaction) {
                if($alltransaction->walletstatus == '0')
                    $fullname = 'Pending';
                if($alltransaction->walletstatus == '1')
                    $fullname = 'Success';
                if($alltransaction->walletstatus == '2')
                    $fullname = 'Reject';
                
                return $fullname;

            })
            ->make(true);
    }

    public function getSuperaffiliatepayout(){
        $module_head = 'Super Affiliate Payout';
        $title = 'Super Affiliate Payout';
         return view('admin.siteuser.super_affiliate_transaction_list',compact('module_head','title'));

    }

    public function postSuperaffiliatepayout(Request $request){
        $data=$request->all();
        $columns = array(

                0 =>'id' ,
                1 =>'created_at',
                2 =>'purpose_state',
                3 => 'amount',
                5 => 'type',
                6 => 'walletstatus'
                
            );
        $alltransaction = Walletdetails::where('purpose_state','5')->orderBy($columns[$data['order'][0]['column']],$data['order'][0]['dir'])->get();
        return Datatables::of($alltransaction)
            ->editColumn('purpose_state', function ($alltransaction) {
                if($alltransaction->purpose_state == '0')
                    $fullname = 'Gift Card';
                if($alltransaction->purpose_state == '1')
                    $fullname = 'Signup Balance';
                if($alltransaction->purpose_state == '2')
                    $fullname = 'Commission';
                if($alltransaction->purpose_state == '3')
                    $fullname = 'Cashback';
                if($alltransaction->purpose_state == '4')
                    $fullname = 'Withdrawl';
                if($alltransaction->purpose_state == '5')
                    $fullname = 'Super Affiliate Commission';
                return $fullname;

            })
            ->editColumn('type', function ($alltransaction) {
                if($alltransaction->type == 0)
                    $type = 'Debit';
                if($alltransaction->type == 1)
                    $type = 'Credit';
                return $type;
            })
            ->editColumn('walletstatus', function ($alltransaction) {
                if($alltransaction->walletstatus == '0')
                    $walletstatus = 'Pending';
                if($alltransaction->walletstatus == '1')
                    $walletstatus = 'Success';
                if($alltransaction->walletstatus == '2')
                    $walletstatus = 'Reject';
                return $walletstatus;
            })
            ->editColumn('amount', function ($alltransaction) {
                
                return '$'.$alltransaction->amount;
            })
            
            ->make(true);
    }

    /**************Invite friend Listing start**********************************************/

    //====Not registered friends======

    public function getInviteFriendListNotRegistered(Request $request)
   {



        $module_head        = "Invite Friend List Pending";
        $user_class         = "active";
        $search_key         = $request->query('search_key');
        $active             = $request->query('active');
        $searchtime             =  $request->query('searchtime');
        $title              = "Invite Friend List Pending";


        $pendinginvite = Invitefriend::with('siteuser')->where('status',0)->get();// only pending users

        $registerinvite = Userrefer::with("userreferlink1","userreferlink")->where('usertype',0)->get();
        if(count($pendinginvite)>0){
            $pendinginvite = $pendinginvite->toArray();
        }
        if(count($registerinvite)>0){
            $registerinvite = $registerinvite->toArray();
        }

        
      

       

        return view('admin.invite.index',compact('user_class',
                                                        'module_head','search_key','search_email','search_contact',
                                                        'active','title','searchtime'
                                                    )
                    );

    }

    public function postAjaxInviteFriendListNotRegistered(Request $request){
         //===get post data 
        $data       = $request->all();

        $search_key         =  $request->query('search_key');
        $active             =  $request->query('active');
        $searchtime             =  $request->query('searchtime');



        $where = "";

        

         if($search_key != ''){
                $where          .= "(`email` LIKE '%".$search_key."%') AND ";
         }
        if($active != ''){
            $where         .= "`status`= '".$active."' AND ";
        }
        
        if(!empty($searchtime)){

                $todaydate=date("Y-m-d");

                if($searchtime=="daily")
                {

                    

                    $where         .= "date(created_at)='".$todaydate."' AND ";
                }
                else if($searchtime=="weekly"){

                     $weekdate=date("Y-m-d", strtotime("-1 week"));
                     $where         .="date(created_at) >='".$weekdate."' AND ";
                }

                else if($searchtime=="monthly"){

                     $monthdate=date("Y-m-d", strtotime("-1 month"));
                     $where         .="date(created_at) >='".$monthdate."' AND ";
                }
                else if($searchtime=="yearly"){

                     $yeardate=date("Y-m-d", strtotime("-1 year"));
                     $where         .="date(created_at) >='".$yeardate."' AND ";
                }
        }   
        

        $where         .= '1';

        //echo $where;exit();
        //$all_patients =  SiteUser::select(['id', 'firstname','lastname','email','phoneno','status','profileimage'])->whereRaw($where)->orderBy('id', 'DESC');

        //add columns
       $columns = array(

                0=>'id' ,
                
                1=>'Referbyname',
                2 =>'Refertoemail',
                3 =>'status', 
                4=>'created_at',
                
               
            );

       //add order by



        
        $pendinginvite = Invitefriend::where('status',0)->whereRaw($where)->orderBy($columns[$data['order'][0]['column']],$data['order'][0]['dir']);  // only pending invite users




        //print_r($all_patients);exit;
        return Datatables::of($pendinginvite)
        ->addColumn('checkbox_td', function ($pendinginvite) {
                return '<input type="checkbox"  recordType="multipleRecord" multipleRecord="'.$pendinginvite->id.'"  />';
            })
         
         
         ->editColumn('siteuserfirstname', function ($pendinginvite) {



            //normal user
                if($pendinginvite->usertype==0){

                    $siteuserdetails=SiteUser::find($pendinginvite->siteusers_id);
                    if(!empty($siteuserdetails->firstname)){

                        $referbyname="<span class='rnm'>".$siteuserdetails->firstname." ".$siteuserdetails->lastname."</span>";
                    }
                    else{
                         $referbyname="";
                    }
                    

                }
                //admin user
                else if($pendinginvite->usertype==1){
                    $userdetails=User::find($pendinginvite->siteusers_id);
                    $referbyname="<span class='rnm' style='color:green'><b>".$userdetails->firstname." ".$userdetails->lastname."</b></span>";
                }

                $siteuserfirstname=$referbyname;
                
                return $siteuserfirstname;

            })

         ->editColumn('email', function ($pendinginvite) {
                $email = $pendinginvite->email;
                return $email;

            })



         ->editColumn('status', function ($pendinginvite) {
                $status = $pendinginvite->status;
                if($status==0){
                    $statustext="Not Registered";
                }
                return $statustext;

            })

         ->editColumn('created_at', function ($pendinginvite) {
                $created_at = $pendinginvite->created_at;
                return $created_at;

            })
         
           
        ->make(true);
    }

    //======registered invite friends==========

    public function getInviteFriendListRegistered(Request $request)
   {



        $module_head        = "Invite Friend List Registered";
        $user_class         = "active";
        $search_key         = $request->query('search_key');
        $active             = $request->query('active');
        $searchtime             =  $request->query('searchtime');
        $title              = "Invite Friend List";


        

        $registerinvite = Userrefer::with("userreferlink1")->get();
        
        // echo "<pre>";
        // print_r($registerinvite);
        // exit();
        

        return view('admin.invite.registeredfriends',compact('user_class',
                                                        'module_head','search_key','search_email','search_contact',
                                                        'active','title','searchtime'
                                                    )
                    );

    }


    public function postAjaxInviteFriendListRegistered(Request $request){
         //===get post data 
        $data       = $request->all();

        $search_key         =  $request->query('search_key');
        $active             =  $request->query('active');
        $searchtime             =  $request->query('searchtime');



        $where = "";

        

         if($search_key != ''){
                $where          .= "(`email` LIKE '%".$search_key."%') AND ";
         }
        if($active != ''){
             $where         .= "`status`= '".$active."' AND ";
        }
        
        if(!empty($searchtime)){

                $todaydate=date("Y-m-d");

                if($searchtime=="daily")
                {

                    

                    $where         .= "date(created_at)='".$todaydate."' AND ";
                }
                else if($searchtime=="weekly"){

                     $weekdate=date("Y-m-d", strtotime("-1 week"));
                     $where         .="date(created_at) >='".$weekdate."' AND ";
                }

                else if($searchtime=="monthly"){

                     $monthdate=date("Y-m-d", strtotime("-1 month"));
                     $where         .="date(created_at) >='".$monthdate."' AND ";
                }
                else if($searchtime=="yearly"){

                     $yeardate=date("Y-m-d", strtotime("-1 year"));
                     $where         .="date(created_at) >='".$yeardate."' AND ";
                }
        }   
        

        $where         .= '1';

        //echo $where;exit();
        //$all_patients =  SiteUser::select(['id', 'firstname','lastname','email','phoneno','status','profileimage'])->whereRaw($where)->orderBy('id', 'DESC');

        //add columns
       $columns = array(

                0=>'id' ,
                
                1=>'referbyname',
                2 =>'refertoname',
                3 =>'status', 
                4=>'created_at',
                
               
            );

       //add order by

        
        $registerinvite = Userrefer::with("userreferlink1")->where('status',1)->whereRaw($where)->orderBy($columns[$data['order'][0]['column']],$data['order'][0]['dir']);  // only registered users

        //echo $where;exit();


        //print_r($all_patients);exit;
        return Datatables::of($registerinvite)
        ->addColumn('checkbox_td', function ($registerinvite) {
                return '<input type="checkbox"  recordType="multipleRecord" multipleRecord="'.$registerinvite->id.'" />';
            })
         
         
         ->editColumn('refertoname', function ($registerinvite) {
            if(!empty($registerinvite->userreferlink1->firstname)){
                $refertoname = $registerinvite->userreferlink1->firstname. " ".$registerinvite->userreferlink1->lastname;
            }
            else{
                $refertoname="";
            }
                
                return $refertoname;

            })

         ->editColumn('referbyname', function ($registerinvite) {
                //normal user
                if($registerinvite->usertype==0){

                    $siteuserdetails=SiteUser::find($registerinvite->referby);
                    if(!empty($siteuserdetails->firstname)){

                        $referbyname="<span class='rnm'>".$siteuserdetails->firstname." ".$siteuserdetails->lastname."</span>";
                    }
                    else{
                         $referbyname="";
                    }
                    

                }
                //admin user
                else if($registerinvite->usertype==1){
                    $userdetails=User::find($registerinvite->referby);
                    $referbyname="<span class='rnm' style='color:green'><b>".$userdetails->firstname." ".$userdetails->lastname."</b></span>";
                }

                return $referbyname;

            })



         ->editColumn('status', function ($registerinvite) {
                
                    $status="Registered";
                
                return $status;

            })

         ->editColumn('created_at', function ($registerinvite) {
                $created_at = $registerinvite->created_at;
                return $created_at;

            })
         
           
        ->make(true);
    }
    /**************Invite friend Listing end**********************************************/
}
