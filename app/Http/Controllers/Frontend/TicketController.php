<?php namespace App\Http\Controllers\Frontend; /* path of this controller*/


//require 'vendor/Carbon/Carbon.php';

// Define Model
use App\Model\User; /* Model name*/
use App\Model\Category;/*Model Name*/
use App\Model\SiteUser;/*Model Name*/
use App\Model\SiteUserReferId;/*Model Name*/
use App\Model\Invitefriend;/*Model Name*/
use App\Model\Userrefer;/*Model Name*/

use App\Model\Notification;
use App\Model\Sitesetting;
use App\Model\Country;
use App\Model\Contact;
use App\Model\Product; /* Model name*/
use App\Model\HomePageDetail; /* Model name*/
use App\Model\Vendor; /* Model name*/
use App\Model\Topbanner; /* Model name*/
use App\Model\Brand; /* Model name*/
use App\Model\SubscriptionEmail; /* Model name*/
use App\Model\Cmspage; /* Model name*/
use App\Model\SiteUserBankAccount;
use App\Model\TicketIssue; /* Model Name */
use App\Model\Ticket; /* Model Name */
use App\Model\TicketsReply; /* Model Name */
use App\Model\TicketEmotionalState; /* Model Name */

use App\Http\Requests;
use App\Http\Controllers\Controller;    
use Illuminate\Support\Facades\Request;
use Mail;
use Input; /* For input */
use Validator;
use Session;
use Imagine\Image\Box;
use Image\Image\ImageInterface;
use Illuminate\Pagination\Paginator;
use App\Model\UtilityCuretedFeature;
use DB;
use Hash;
use Auth;
use Cookie;
use App\Helper\helpers;
use Redirect;
use Customhelpers;
//use Carbon\Carbon;
use App\Model\Emailnotification; /* Model name*/
use App\Model\Emailnotification_Siteuser; /* Model name*/


//use Socialize;
use App\Model\Address; 

class TicketController extends BaseController {

    public function __construct() 
    {

        parent::__construct();
	     
        //$this->list_last_minute_deal = 10;

        $obj = new helpers();
        view()->share('obj',$obj);
        
        
    }
   /**
    * Display a listing of the resource.
    *
    * @return Response
    */


   public function ticket_emotional_state(Request $request){

      if (Session::has('user_id')){

        }

        else{

        return redirect('login');
        }

        $esval = Request::input('esVal');



        if ($esval != '') {
                Session::put('esid', $esval);
                echo 1;
        }else{
          $name           = "Checkout Saver";
          $ticketemotions = TicketEmotionalState::select('*')->get();
        return view('frontend.ticket.ticketemotionalstate',compact('name','ticketemotions'));
        }

        
   }

   public function addticket(Request $request){

        if (Session::has('user_id')){

        }

        else{

        return redirect('login');
        }

      if(Request::input('submit') != ""){


        $ticket_info      = Request::input('Ticket');
        $ticketemotion      = Request::input('emotionalstate');
        if($ticketemotion==""){
          $ticketemotion=0;
        }
        $validator        = Validator::make($ticket_info, [
            'subject'     => 'required|max:50',
            'description' => 'required|max:500'
         ]);

        if ($validator->fails()) {
            return redirect()->route('AddTicket')->withErrors($validator)->withInput();
        }
 
          // $file             = Request::file('attachment');  
          // if (!empty($file)) {
          //      $extension   = strtolower($file->getClientOriginalExtension());
          //      $extensionar = array('jpg', 'jpeg', 'gif', 'png','bmp', 'docs', 'odt', 'ods');

          //       if (in_array($extension, $extensionar))
          //       {
          //         $fileName        = time().'_'.rand(0,9999).'_'.$file->getClientOriginalName(); 
          //         $file->move(public_path('uploads/attachments'), $fileName);
          //       }
          //       else
          //        {
          //          $fileName='';
          //        }
          // }else{ $fileName='';}
          //$ticket_info['attachment']   = $fileName;


          $ticket_info['siteusers_id'] = Session::get('user_id');
          $ticket_model                = new Ticket();
          $ticket_model->emotionstate_id=$ticketemotion;
          $ticket_model->description   = nl2br($ticket_info['description']);
          $ticket_model->fill($ticket_info);
          
         $ticket_model->created_at = date('Y-m-d H:i:s');  
         $ticket_model->updated_at = date('Y-m-d H:i:s');

          //$ticket_model->created_at    = \Carbon::now(Session::get('timezone'));
          //$ticket_model->updated_at    = \Carbon::now(Session::get('timezone'));

          $ticket_insert               = $ticket_model->save(); 
          /**************************SEND MAIL -start *****************************/
          if ($ticket_insert) {
            $site              = Sitesetting::where(['name' => 'email'])->first();
            $admin_users_email = $site->value;
            
            $users             = SiteUser::where("id","=",Session::get('user_id'))->first();
            $user_name         = $users->firstname.' '.$users->lastname;
            $user_email        = $users->email;
            $encodedemail=base64_encode($user_email);
            $subject           = "Your Ticket has been created";
            $message_body      = "Your ticket successfully send to the Admin .<br/><a href='".url()."/user/unsubscribe?useremail=".$encodedemail."&type=supportticket'>Click on this link to Unsubscribe</a>";
            

            //Check ticket notification status in profile is on or off

            $siteuserid=Session::get('user_id');

            $emailnotification=Emailnotification::where('slug','supportticket')->where('status',1)->get();

            $emailnotification_count=Emailnotification::where('slug','supportticket')->where('status',1)->count();
            if($emailnotification_count>0){
            $emailnotification_id=$emailnotification[0]->id;


            $emailnotification_Siteuser=Emailnotification_Siteuser::where('siteusers_id',$siteuserid)->where("emailnotifications_id",$emailnotification_id)->get();


            $emailnotification_Siteuser_count=Emailnotification_Siteuser::where('siteusers_id',$siteuserid)->where("emailnotifications_id",$emailnotification_id)->count();

            if($emailnotification_Siteuser_count>0){

            $mailstatus=$emailnotification_Siteuser[0]->status;
              
              if($mailstatus==1){

                $mail = Mail::send(['html' => 'frontend.emailtemplate.send_mail'], array('message_body' => $message_body, 'user_name'=>$user_name), function($message) use ($admin_users_email, $user_email,$user_name,$subject)
                {
                  $message->from($admin_users_email,'Cashback justin');
            
                  $message->to($user_email)->subject($subject);
                });
              }

                  

            }

            }


            
          }
          
        /**************************SEND MAIL -end *****************************/ 
          Session::flash('success', 'Ticket successfully save.');
          return redirect()->route('ViewTicket');
      }

      $esval          = Session::get('esid'); 
      $name           = "cashback justin";
      $ticketissues   = TicketIssue::select('*')->where('status','0')->get();
      $ticketemotions = TicketEmotionalState::select('*')->get();

      $userprofileheadinfo=Customhelpers::getUserDetails();

      return view('frontend.ticket.createticket',compact('name','ticketissues','esval','ticketemotions','userprofileheadinfo'));

   }



  public function viewticket(Request $request, $ticket_id = null, $val = null){
      
      if (Session::has('user_id')){

        }

        else{

        return redirect('login');
        }

       $userprofileheadinfo=Customhelpers::getUserDetails(); 

      if(Request::input('submit') != ""){
        $reply      = array('reply'=>Request::input('reply'));

        $validator  = Validator::make($reply, [
            'reply'     => 'required',
            ]);

        if ($validator->fails()) {
            return redirect()->route('ViewTicket')->withErrors($validator)->withInput();
        }
  
           
      }

       $ticket_id = base64_decode($ticket_id);
       
      if ($ticket_id != '') {

          $getTicket   =  DB::table('tickets as tck')
                          ->join('ticket_issues as ti', 'tck.issuetype_id', '=', 'ti.id')
                          ->leftjoin('ticket_emotional_state as tes', 'tck.emotionstate_id', '=', 'tes.id')
                          ->where('tck.siteusers_id', '=', Session::get('user_id'))
                          ->where('tck.id', '=', $ticket_id)
                          ->orderByRaw('tck.id DESC')
                          ->select('tck.*','tck.id as tid','ti.issue_type as issues','tes.emotional_state as emotionstate')
                          ->first();

          $msgupdate = DB::table('ticket_replies')
                         ->where('tickets_id', $ticket_id)
                         ->update(['status' => 1]); 
      }else{
         $LastId = DB::table('tickets')->where('siteusers_id', '=',Session::get('user_id'))->orderByRaw('id DESC')->select('id')->first();
        
        if (!empty($LastId)) {
          $getTicket   =  DB::table('tickets as tck')
                          ->join('ticket_issues as ti', 'tck.issuetype_id', '=', 'ti.id')
                          ->leftjoin('ticket_emotional_state as tes', 'tck.emotionstate_id', '=', 'tes.id')
                          ->where('tck.siteusers_id', '=', Session::get('user_id'))
                          ->where('tck.id', '=', $LastId->id)
                          ->orderByRaw('tck.id DESC')
                          ->select('tck.*','ti.issue_type as issues','tes.emotional_state as emotionstate')
                          ->first(); 
          $ticket_id = $LastId->id;
        }else{
          //Session::flash('error', 'Please add ticket.');
          return redirect()->route('AddTicket');
        }
      }


      $name          = "cashback justin";
      $ticketlists   =  DB::table('tickets as tck')
                          ->join('ticket_issues as ti', 'tck.issuetype_id', '=', 'ti.id')
                          ->leftjoin('ticket_emotional_state as tes', 'tck.emotionstate_id', '=', 'tes.id')
                          ->where('tck.siteusers_id', '=', Session::get('user_id'))
                          ->orderByRaw('tck.id DESC')
                          ->select('tck.*','ti.issue_type as issues','tes.emotional_state as emotionstate')
                          ->get(); 

       $LastIds = DB::table('tickets')->where('siteusers_id', '=',Session::get('user_id'))->orderByRaw('id DESC')->select('id')->get(); 

       foreach ($LastIds as $tick_id) {
        $newmsg[] = DB::table('ticket_replies')->where('tickets_id',$tick_id->id)->where('status',0)->count();
                } 
         
      
     $replydetails = TicketsReply::with('replybyuser','replytoadmin','replybyadmin','replytouser')->where('tickets_id',$ticket_id)->get();
    
     return view('frontend.ticket.viewticket',compact('name','ticketlists','getTicket','ticket_id','replydetails','val','newmsg','userprofileheadinfo'));

   }

    public  function get_mime_type($file) {

        if (Session::has('user_id')){

        }

        else{

        return redirect('login');
        }

        $mtype = false;
        if (function_exists('finfo_open')) {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mtype = finfo_file($finfo, $file);
        finfo_close($finfo);
        } elseif (function_exists('mime_content_type')) {
        $mtype = mime_content_type($file);
        } 
        return $mtype;
    }

   public function user_add_reply_ticket(Request $request){

        if (Session::has('user_id')){

        }

        else{

        return redirect('login');
        }
        
        $reply_val = Request::input('reply_val'); 
        $user_id   = Request::input('user_id');
        $ticket_id = Request::input('ticket_id'); 
        $file      = Request::file('file');  

          if (!empty($file)) {

            
               $mimetype=$this->get_mime_type($file);
               $extension   = strtolower($file->getClientOriginalExtension());
               $extensionar = array('jpg', 'jpeg', 'gif', 'png','bmp', 'docs', 'ods','odt');

                if (in_array($extension, $extensionar))
                {
                  if($mimetype=="image/jpg" || $mimetype=="image/jpeg" || $mimetype=="image/gif" || $mimetype=="image/png"){

                    $fileName        = time().'_'.rand(0,9999).'_'.$file->getClientOriginalName(); 
                    $file->move(public_path('uploads/attachments'), $fileName);
                  }
                  
                }
                else
                 {
                   $fileName='';
                 }
          }else{ $fileName='';} 

        if ($reply_val != '' || !empty($file)) {
           $reply_ticket_info['tickets_id']     = $ticket_id;
           $reply_ticket_info['reply_by_user']  = $user_id;
           $reply_ticket_info['reply_to_admin'] = 1;
           $reply_ticket_info['msg']            = $reply_val;
           $reply_ticket_info['attachment']     = $fileName;


           $ticket_model  = new TicketsReply();
           $ticket_model->fill($reply_ticket_info);
           $ticket_model->status     = 2;

            //$ticket_model->created_at = \Carbon::now(Session::get('timezone'));  
            //$ticket_model->updated_at = \Carbon::now(Session::get('timezone'));

           $ticket_model->created_at = date('Y-m-d H:i:s');  
           $ticket_model->updated_at = date('Y-m-d H:i:s');

           $ticket_model->save(); 
           $reply_id  = $ticket_model->id;

           $reply_msg = DB::table('ticket_replies')
                        ->where('id', '=', $reply_id)
                        ->select('msg','attachment','created_at')
                        ->first();
        } 
  }


   public function close_ticket(Request $request){
       
      if (Session::has('user_id')){

        }

        else{

        return redirect('login');
        }
          
      $ticket_id = Request::input('tid'); 
      $val       = Request::input('val');

      //echo $val;exit;
      if ($val == 'Close') {
        $updateStatus = DB::table('tickets')
                        ->where('id', '=', $ticket_id)
                        ->update(['status'=>2]);
      }else{
        $updateStatus = DB::table('tickets')
                        ->where('id', '=', $ticket_id)
                        ->update(['status'=>0]);
      }
      
      echo "1";

   }

   public function serach_ticket(Request $request){
      $search_val    = Request::input('search_val');

      if($search_val != ''){
        $ticketlists  =  DB::table('tickets as tck')
                          ->join('ticket_issues as ti', 'tck.issuetype_id', '=', 'ti.id')
                          ->where('tck.siteusers_id', '=', Session::get('user_id'))
                          ->where('tck.subject', 'like', '%'.$search_val.'%')
                          ->orderByRaw('tck.id DESC')
                          ->select('tck.*','ti.issue_type as issues')
                          ->get(); 
      }else{
        $ticketlists  =  DB::table('tickets as tck')
                          ->join('ticket_issues as ti', 'tck.issuetype_id', '=', 'ti.id')
                          ->where('tck.siteusers_id', '=', Session::get('user_id'))
                          ->orderByRaw('tck.id DESC')
                          ->select('tck.*','ti.issue_type as issues')
                          ->get();
       }   

       foreach ($ticketlists as $tick_id) {
        $newmsg[]     = DB::table('ticket_replies')
                          ->where('tickets_id',$tick_id->id)
                          ->where('status',0)
                          ->count();
                }  

      return view('frontend.ticket.searchticket',compact('ticketlists','newmsg'));
   }

   public function filter_ticket(Request $request){

        if (Session::has('user_id')){

        }

        else{

        return redirect('login');
        }

        $filter_val    = Request::input('filter_val');

      if($filter_val != ''){
      

          if ($filter_val == 'Unread') {
                $ticketlists   =  DB::table('tickets as tck')
                                    ->join('ticket_issues as ti', 'tck.issuetype_id', '=', 'ti.id')
                                    ->join('ticket_replies as tr', 'tck.id', '=', 'tr.tickets_id')
                                    ->where('tck.siteusers_id', '=', Session::get('user_id'))
                                    ->where('tr.status', '=', 0)
                                    ->orderByRaw('tck.id DESC')
                                    ->select('tck.*','ti.issue_type as issues')
                                    ->groupBy('tr.tickets_id')
                                    ->get();
          }else{
                $ticketlists   =  DB::table('tickets as tck')
                                    ->join('ticket_issues as ti', 'tck.issuetype_id', '=', 'ti.id')
                                    ->where('tck.siteusers_id', '=', Session::get('user_id'))
                                    ->where('tck.status', '=', $filter_val)
                                    ->orderByRaw('tck.id DESC')
                                    ->select('tck.*','ti.issue_type as issues')
                                    ->get();
                }
      }else{
        
          $ticketlists  = DB::table('tickets as tck')
                            ->join('ticket_issues as ti', 'tck.issuetype_id', '=', 'ti.id')
                            ->where('tck.siteusers_id', '=', Session::get('user_id'))
                            ->orderByRaw('tck.id DESC')
                            ->select('tck.*','ti.issue_type as issues')
                            ->get();
         
      }  
      
      foreach ($ticketlists as $tick_id) {
          $newmsg[]     = DB::table('ticket_replies')
                            ->where('tickets_id',$tick_id->id)
                            ->where('status',0)
                            ->count();
                }
      return view('frontend.ticket.searchticket',compact('ticketlists','newmsg'));


   }

}