<?php namespace App\Http\Controllers\Admin;
//require 'vendor/Carbon/Carbon.php';
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;



use App\Model\User; /* Model name*/
use App\Model\Userrefer; /* Model name*/
use Customhelpers;
use App\Model\SiteUser; /* Model name*/
use App\Model\TicketsReply; /* Model Name */
use App\Model\Ticket; /* Model Name */
use App\Model\TicketIssue;

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
//use Carbon\Carbon;



class UserTicketDetailsController extends BaseController {

  /**
    * Display a listing of the resource.
    *
    * @return Response
    */
    public function __construct() {
    	parent::__construct();
    }
    
    public function ticket_list(){
    	      if (Auth::id() == '') {
                  return redirect('/auth/login');
                }


      $allTicket_Ids = Ticket::select('*')->orderByRaw('id DESC')->get();

      foreach ($allTicket_Ids as $allTicket_Ids) {
        $newmsg[] = DB::table('ticket_replies')->where('tickets_id',$allTicket_Ids->id)->where('admin_unread',0)->count();
                }

                 

    	return view('admin.ticketdetails.ticket_list',compact('newmsg'));
    }

    public function closed_ticket_list(){
            if (Auth::id() == '') {
                  return redirect('/auth/login');
                }


      $allTicket_Ids = Ticket::select('*')->orderByRaw('id DESC')->get();

      foreach ($allTicket_Ids as $allTicket_Ids) {
        $newmsg[] = DB::table('ticket_replies')->where('tickets_id',$allTicket_Ids->id)->where('admin_unread',0)->count();
                }

                 

      return view('admin.ticketdetails.closed_ticket_list',compact('newmsg'));
    }

    public function ticket_issue_list(){

      if (Auth::id() == '') 
        {
          return redirect('/auth/login');
        }
        $module_head  = "Ticket Issue Management";
        $title        = "Ticket Issue Management";
        $ticket_issue = TicketIssue::where('status','0')->orderBy('id','desc')->get();

        /*echo "<pre>";
        print_r($ticket_issue->toArray());
        exit();*/
        return view('admin.ticketdetails.issue_list',compact('module_head','title','ticket_issue'));

    }

    public function ticket_issue_edit($id=''){
      $id = base64_decode($id);
      $issue_detail = TicketIssue::where('id',$id)->first();
      $module_head  = "Ticket Issue Management";
      $title        = "Ticket Issue Management";
      return view('admin.ticketdetails.issue_update',compact('module_head','title','issue_detail'));
    }

    public function postIssueupdate(){

          $data   = Request::all();
          $id = $data['issue_id'];
          $content = $data['issue_type'];
          TicketIssue::where('id',$id)->update(['issue_type' => $content]);
          Session::flash('success_message', 'Issue successfully updated.');
          Session::flash('alert-class', 'alert alert-success');
          return redirect('admin/ticket_issue_list');

    }

    public function postIssuedelete(){
      $data = Request::all();
      $id   = $data['issueid'];
      TicketIssue::where('id',$id)->update(['status' => '1']);
      Session::flash('success_message', 'Issue successfully deleted.');
      Session::flash('alert-class', 'alert alert-success');
      return 1;

    }

    public function getIssueadd(){
      $module_head  = "Add Issue";
      $title        = "Ticket Issue Management";
      return view('admin.ticketdetails.issue_add',compact('module_head','title'));
    }
    public function postIssueadd(){
      $data = Request::all();
      TicketIssue::create([
          'issue_type'  => $data['issue_type'],
          'status'      => 0
        ]);
            Session::flash('success_message', 'Issue successfully saved');
            Session::flash('alert-class', 'alert alert-success');
            return redirect('admin/ticket_issue_list');
    }

    public function reply_ticket(Request $request, $ticket_id){

      if (Auth::id() == '') {
                  return redirect('/auth/login');
                }

    	$ticket_id = base64_decode($ticket_id);
    	 
  
    	$ticketdetail = DB::table('tickets as tck')
                          ->join('ticket_issues as ti', 'tck.issuetype_id', '=', 'ti.id')
                          ->join('siteusers as su', 'tck.siteusers_id', '=', 'su.id')
                          ->join('ticket_emotional_state as tes', 'tck.emotionstate_id', '=', 'tes.id')
                          ->where('tck.id', '=', $ticket_id)
                          ->orderByRaw('tck.id DESC')
                          ->select('tck.*','ti.issue_type as issues','su.firstname as fname', 'su.lastname as lname','tes.emotional_state as emotionstate')
                          ->first();

      $admin_reply_msg = DB::table('ticket_replies')
	         			    ->where('tickets_id', '=', $ticket_id)
	         			    ->where('reply_by_admin', '=', Auth::id())
	         			    ->select('msg')
	         			    ->get(); 

     $msgupdate        = DB::table('ticket_replies')
                         ->where('tickets_id', $ticket_id)
                         ->update(['admin_unread' => 1]); 




      if (!empty($ticketdetail)) {
                     $user_reply_msg = DB::table('ticket_replies')
                    ->where('tickets_id', '=', $ticket_id)
                    ->where('reply_by_user', '=', $ticketdetail->siteusers_id)
                    ->select('msg')
                    ->get(); 
                    }              
	    

	    $replydetails = TicketsReply::with('replybyuser','replytoadmin','replybyadmin','replytouser')->where('tickets_id',$ticket_id)->get();     			    
	     
    return view('admin.ticketdetails.reply_ticket', compact('ticketdetail','replydetails','ticket_id'));
    }
    
    public function add_reply_ticket(Request $request){

        if (Auth::id() == '') {
                  return redirect('/auth/login');
                }

        $reply_val = Request::input('reply_val');  
        $user_id   = Request::input('user_id'); 
        $ticket_id = Request::input('ticket_id'); 
        $file      = Request::file('file');  
      
          if (!empty($file)) {
               $extension   = strtolower($file->getClientOriginalExtension());
               $extensionar = array('jpg', 'jpeg', 'gif', 'png','bmp', 'docs', 'ods', 'odt');

                if (in_array($extension, $extensionar))
                {
                  $fileName        = time().'_'.rand(0,9999).'_'.$file->getClientOriginalName(); 
                  $file->move(public_path('uploads/attachments'), $fileName);
                }
                else
                 {
                   $fileName='';
                 }
          }else{ $fileName='';}

        if ($reply_val != '') {
        	 $reply_ticket_info['tickets_id']     = $ticket_id;
        	 $reply_ticket_info['reply_by_admin'] = Auth::id();
        	 $reply_ticket_info['reply_to_user']  = $user_id;
        	 $reply_ticket_info['msg']            = $reply_val;
           $reply_ticket_info['attachment']     = $fileName;

        	 $ticket_model  = new TicketsReply();
	         $ticket_model->fill($reply_ticket_info);

           //$ticket_model->updated_at = \Carbon::now(Session::get('timezone'));
           //$ticket_model->created_at = \Carbon::now(Session::get('timezone'));

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


    /********************************************************************************
     *                          AJAX DATATABLE LISTING TICKETS                                *
     *******************************************************************************/

    function ajax_active_issue_list(Request $request){

        
        $all_patients = Ticket::with('username','issuestype','emotionalState')->where('status','=','0')->orderByRaw('id DESC')->get();
        
        return Datatables::of($all_patients)
        ->addColumn('checkbox_td', function ($all_patients) {
                return '<input type="checkbox"  recordType="multipleRecord" multipleRecord="'.$all_patients->id.'" />';
            })
          ->editColumn('subject', function ($all_patients) {

            $newmsg = DB::table('ticket_replies')->where('tickets_id',$all_patients->id)->where('admin_unread',0)->count();

                if(!empty($all_patients->subject)){
                  $subject = $all_patients->subject;
                return '<a href="'.route('ReplyTicket', base64_encode($all_patients->id)).'">'.$subject.'</a>'.' <span style="color:#000">('.$newmsg.')</span>';
                }
                else{
                  return '';
                }
                

            })
         ->editColumn('firstname', function ($all_patients) {
            if(!empty($all_patients->username->firstname)){
                $fullname = $all_patients->username->firstname." ".$all_patients->username->lastname;
                return $fullname;
            }
            else{
              $fullname="";
              return $fullname;
            }
                

            })

         ->editColumn('issue', function ($all_patients) {

                if(!empty($issue_type)){
                  $issue_type = $all_patients->issuestype->issue_type;
                  return $issue_type;
                }
                else{
                  return '';
                }
                

            })
         ->editColumn('emotional_state', function ($all_patients) {
              if(!empty($all_patients->emotionalState->emotional_state)){
                $emotional_state = $all_patients->emotionalState->emotional_state;
              }
              else{
                $emotional_state = "";
              }
                
                return $emotional_state;

            })
         ->editColumn('status', function ($all_patients) {
            if($all_patients->status == 0){
                $status_html = '<select class="custom-select table-custom-select" name="status" id="user_active_'.$all_patients->id.'" onchange="ChangeTicketStatus(this.value,'.$all_patients->id.')"><option value="0" selected>Open</option><option value="1">Closed</option></select><br /><span class="alert-success" id="success_status_span_'.$all_patients->id.'" style="display:none;"></span>';
            }else{
                $status_html = '<select class="custom-select table-custom-select" name="status" id="user_active_'.$all_patients->id.'" onchange="ChangeTicketStatus(this.value,'.$all_patients->id.')"><option value="0">Open</option><option value="1" selected>Closed</option></select><span class="alert-success" id="success_status_span_'.$all_patients->id.'" style="display:none;"></span>';

            }
            return $status_html;
            })
        ->make(true);
    }

    function ajax_closed_issue_list(Request $request){

        
        $all_patients = Ticket::with('username','issuestype','emotionalState')->where('status','=','1')->orderByRaw('id DESC')->get();
        
        return Datatables::of($all_patients)
        ->addColumn('checkbox_td', function ($all_patients) {
                return '<input type="checkbox"  recordType="multipleRecord" multipleRecord="'.$all_patients->id.'" />';
            })
          ->editColumn('subject', function ($all_patients) {

            $newmsg = DB::table('ticket_replies')->where('tickets_id',$all_patients->id)->where('admin_unread',0)->count();

                if(!empty($all_patients->subject)){
                  $subject = $all_patients->subject;
                return '<a href="'.route('ReplyTicket', base64_encode($all_patients->id)).'">'.$subject.'</a>'.' <span style="color:#000">('.$newmsg.')</span>';
                }
                else{
                  return '';
                }
                

            })
         ->editColumn('firstname', function ($all_patients) {
            if(!empty($all_patients->username->firstname)){
                $fullname = $all_patients->username->firstname." ".$all_patients->username->lastname;
                return $fullname;
            }
            else{
              $fullname="";
              return $fullname;
            }
                

            })

         ->editColumn('issue', function ($all_patients) {

                if(!empty($issue_type)){
                  $issue_type = $all_patients->issuestype->issue_type;
                  return $issue_type;
                }
                else{
                  return '';
                }
                

            })
         ->editColumn('emotional_state', function ($all_patients) {
              if(!empty($all_patients->emotionalState->emotional_state)){
                $emotional_state = $all_patients->emotionalState->emotional_state;
              }
              else{
                $emotional_state = "";
              }
                
                return $emotional_state;

            })
         ->editColumn('status', function ($all_patients) {
            if($all_patients->status == 0){
                $status_html = '<select class="custom-select table-custom-select" name="status" id="user_active_'.$all_patients->id.'" onchange="ChangeTicketStatus(this.value,'.$all_patients->id.')"><option value="0" selected>Open</option><option value="1">Closed</option></select><br /><span class="alert-success" id="success_status_span_'.$all_patients->id.'" style="display:none;"></span>';
            }else{
                $status_html = '<select class="custom-select table-custom-select" name="status" id="user_active_'.$all_patients->id.'" onchange="ChangeTicketStatus(this.value,'.$all_patients->id.')"><option value="0">Open</option><option value="1" selected>Closed</option></select><span class="alert-success" id="success_status_span_'.$all_patients->id.'" style="display:none;"></span>';

            }
            return $status_html;
            })
        ->make(true);
    }

    public function change_ticket_status(){
      $val = Request::input('val');
      $id  = Request::input('id');
           $changestatus = DB::table('tickets')
                             ->where('id', $id)
                             ->update(['status' => $val]);

    }

}
