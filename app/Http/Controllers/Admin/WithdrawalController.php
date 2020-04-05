<?php namespace App\Http\Controllers\Admin;

use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Model\Category; /* Model name*/
use App\Model\SubCategory; /* Model name*/
use App\Model\Product; /* Model name*/
use App\Model\Brand; /* Model name*/
use App\Model\Vendor; /* Model name*/
use App\Model\OrderHistory; /* Model name*/
use App\Model\WithdrawalHistory; /* Model name*/
use App\Model\SiteUserBankAccount; /* Model name*/
use App\Model\Sitesetting; /* Model name*/

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


class WithdrawalController extends BaseController {

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

    function getAllWithdrawlist()
    {
        $module_head 		= "Withdrawal List";
        $withdrawl_class 		= "active";
        $record_per_page    = 100;
        $page 				= Input::get('page', 1);
		    $title				= "Withdrawal Management";
        $start_date 		= Request::query('start_date');
        $end_date 			= Request::query('end_date');

    		$sl_no				= 1;
    		//echo date('Y-m-d',strtotime($start_date));
    		if(Request::input('page')!=''){
    			$sl_no = ((Request::input('page')-1)*$record_per_page)+1;
    		}

    		if($start_date != '' && $end_date != '' )
    		{
                $where 	= "DATE(`withdrawal_history`.`created_at`) >= '".date('Y-m-d',strtotime($start_date))."' AND DATE(`withdrawal_history`.`created_at`) <= '".date('Y-m-d',strtotime($end_date))."'";
            }else if($start_date != '' && $end_date == '')
    		{
                $where 	= "DATE(`withdrawal_history`.`created_at`) >= '".date('Y-m-d',strtotime($start_date))."'";
            }else if($start_date == '' && $end_date != '')
    		{
                $where 	= "DATE(`withdrawal_history`.`created_at`) <= '".date('Y-m-d',strtotime($end_date))."'";
            }else{
            	$where 	= '1';
            }

        	$all_tranctions  = WithdrawalHistory::leftJoin('site_users', 'site_users.id', '=', 'withdrawal_history.user_id')
                                                ->select('withdrawal_history.*','site_users.name','site_users.last_name')
                                                ->where('payment_status','paid')->orWhere('payment_status','succeeded')->whereRaw($where)->orderBy('id','DESC')->get()->toArray();

    		$all_tranctions = self::paginate($all_tranctions, $record_per_page);
    		
    		/*echo "<pre>";
            print_r($all_tranctions);exit;*/
    		
            return view('admin.withdrawals.withdrawals_list',compact(
    														'all_tranctions','tranction_class',
    														'module_head','search_key','title','sl_no','start_date','end_date'
    													)
    					);
    }

    function getWithdrawsDetails($id="")
    {
        $module_head 		= "Withdrawal Details";
        $withdrawl_class 	= "active";
		$title				= "Withdrawal Management";

    	$tranctions_details  = WithdrawalHistory::leftJoin('site_users', 'site_users.id', '=', 'withdrawal_history.user_id')
                                ->select('withdrawal_history.*','site_users.name','site_users.last_name')
    						    ->where('withdrawal_history.id',base64_decode($id))->first()->toArray();
		
		/*echo "<pre>";
        print_r($tranctions_details);exit;*/
		
        return view('admin.withdrawals.view_withdrawals',compact(
														'tranctions_details','tranction_class',
														'module_head','title'
													)
					);
    }

    function getWithdrawlRequestList()
    {   
        $module_head        = "Withdrawal Request List";
        $withdraw2_class        = "active";
        $record_per_page    = 100;
        $page               = Input::get('page', 1);
        $title              = "Withdrawal Request";
        $start_date         = Request::query('start_date');
        $end_date           = Request::query('end_date');
        $search_by           = Request::query('search_by');

        $sl_no              = 1;
        //echo date('Y-m-d',strtotime($start_date));
        if(Request::input('page')!=''){
            $sl_no = ((Request::input('page')-1)*$record_per_page)+1;
        }

        if($start_date != '' && $end_date != '' )
        {
            $where  = "DATE(`withdrawal_history`.`created_at`) >= '".date('Y-m-d',strtotime($start_date))."' AND DATE(`withdrawal_history`.`created_at`) <= '".date('Y-m-d',strtotime($end_date))."'";
        }else if($start_date != '' && $end_date == '')
        {
            $where  = "DATE(`withdrawal_history`.`created_at`) >= '".date('Y-m-d',strtotime($start_date))."'";
        }else if($start_date == '' && $end_date != '')
        {
            $where  = "DATE(`withdrawal_history`.`created_at`) <= '".date('Y-m-d',strtotime($end_date))."'";
        }else{
            $where  = '1';
        }

        if($search_by!=''){
            $where1 = "`send_to` = ".$search_by;
        }else{
            $where1  = '1';
        }

        $all_tranctions  = WithdrawalHistory::leftJoin('site_users', 'site_users.id', '=', 'withdrawal_history.user_id')
                                            ->leftJoin('site_user_bank_accounts', 'site_user_bank_accounts.site_user_id', '=', 'withdrawal_history.user_id')
                                            ->select('site_user_bank_accounts.paypal_email','withdrawal_history.*','site_users.name','site_users.last_name')
                                            ->where('payment_status','pending')->whereRaw($where1)->whereRaw($where)->orderBy('id','DESC')->get()->toArray();

        $all_tranctions = self::paginate($all_tranctions, $record_per_page);
        
        /*echo "<pre>";
        print_r($all_tranctions);exit;*/
        
        return view('admin.withdrawals.request_list',compact(
                                                        'all_tranctions','tranction_class',
                                                        'module_head','search_by','title','sl_no','start_date','end_date'
                                                    )
                    );
    }

    public function getAdminCardDetails($id=""){

      $module_head                  = "Card Details";
      $withdraw2_class              = "active";
      $title                        = "Withdrawal Management";

      $all_details  = WithdrawalHistory::leftJoin('site_users', 'site_users.id', '=', 'withdrawal_history.user_id')
                                            ->leftJoin('site_user_bank_accounts', 'site_user_bank_accounts.site_user_id', '=', 'withdrawal_history.user_id')
                                            ->select('site_user_bank_accounts.secret_key','site_user_bank_accounts.publish_key','withdrawal_history.*','site_users.name','site_users.last_name')
                                            ->where('payment_status','pending')->where('withdrawal_history.id',base64_decode($id))->first()->toArray();
      /*echo "<pre>";
        print_r($all_details);exit;*/
      return view('admin.withdrawals.card_details',compact(
                            'all_details',
                            'withdraw2_class',
                            'module_head',
                            'title'
                          )
          );
    }


    function postStripePay()
    {
        $data = Request::all();
        
        $account_details = SiteUserBankAccount::leftJoin('site_users', 'site_users.id', '=', 'site_user_bank_accounts.site_user_id')
                                                ->select('site_user_bank_accounts.*','site_users.name','site_users.last_name','site_users.email')
                                                ->where('site_user_id',$data['user_id'])->first()->toArray();
        $request_details = WithdrawalHistory::where('id',$data['request_id'])->first()->toArray();

        //echo "<pre>";print_r($account_details);exit;
        Stripe::setApiKey($account_details['secret_key']);
        
        try
        {
            $token = $data['stripe_token'];

            // Charge the admin's card:
            $charge = \Stripe\Charge::create(array(
              "amount" => $request_details['amount']*100,
              "currency" => "usd",
              "description" => "Pay cashback amount",
              "source" => $token,
            ));

          $data_to_be_saved['txn_id']           = $charge->id;
          $data_to_be_saved['currency']         = $charge->currency;
          $data_to_be_saved['transfer_id']      = $charge->balance_transaction;
          //$data_to_be_saved['payment_id']       = $charge->destination_payment;
          $data_to_be_saved['description']      = $charge->description;
          $data_to_be_saved['payment_status']   = $charge->status; // payment pass
          $data_to_be_saved['status']           = ($charge->status=='succeeded')?1:0; // payment pass
          $data_to_be_saved['created']          = $charge['created'];
          $data_to_be_saved['data']             = base64_encode(serialize($charge));
          $data_to_be_saved['created_at']       = date('Y-m-d H:i:s');

          WithdrawalHistory::where('id',$request_details['id'])->update($data_to_be_saved);

          /**************************SEND MAIL -start *****************************/
        
            $site = Sitesetting::where(['name' => 'email'])->first();
            $admin_users_email = $site->value;

            $user_name = $account_details['name'].' '.$account_details['last_name'];
            $user_email = $account_details['email'];
            
            $subject = "Amount release from admin";
            $message_body = "Your amount has been transfer in your stripe account.<br><br>";
            $message_body .= "Amount            :$".$request_details['amount']."<br>";
            $message_body .= "Tranction Id      :".$charge->id."<br>";
            $message_body .= "Payment Date      :".$data_to_be_saved['created_at']."<br>";
            
            $mail = Mail::send(['html' => 'admin.emailtemplate.send_mail'], array('message_body' => $message_body, 'user_name'=>$user_name), function($message) use ($admin_users_email, $user_email,$user_name,$subject)
            {
              $message->from($admin_users_email,'Front Mode');
        
              $message->to($user_email)->subject($subject);
            });

            $subjectA = "Amount tranfer to ".$user_name;
            $message_bodyA  = "User cashback amount has been transfer from your account.<br><br>";
            $message_bodyA .= "User Name         :".$user_name."<br>";
            $message_bodyA .= "Amount            :$".$request_details['amount']."<br>";
            $message_bodyA .= "Tranction Id      :".$charge->id."<br>";
            $message_bodyA .= "Payment Date      :".$data_to_be_saved['created_at']."<br>";
            
            
            $mail = Mail::send(['html' => 'admin.emailtemplate.send_mail'], array('message_body' => $message_bodyA, 'user_name'=>'Admin'), function($message) use ($admin_users_email,$subjectA)
            {
              $message->from($admin_users_email,'Front Mode');
        
              $message->to($admin_users_email)->subject($subjectA);
            });
        
        /**************************SEND MAIL -start *****************************/ 

          Session::flash('success_message', 'Amount $'.$request_details['amount'].' sucessfully transfer.'); 
          Session::flash('alert-class', 'alert alert-success');
          return response()->json(['status'=>1]); 
          //return redirect('admin/user/withdrawal');  
        }

        catch (\Stripe\Error\InvalidRequest $e) 
        {
        // Invalid parameters were supplied to Stripe's API
            $error = $e->getMessage();
            Session::flash('failure_message', $error); 
            Session::flash('alert-class', 'alert alert-danger'); 
            //return redirect('admin/user/withdrawls-requests');
            return response()->json(['status'=>0]); 
        } 
        catch (\Stripe\Error\Authentication $e) 
        {
            $error = $e->getMessage();
            Session::flash('failure_message', $error); 
            Session::flash('alert-class', 'alert alert-danger'); 
            //return redirect('admin/user/withdrawls-requests');
            return response()->json(['status'=>0]); 
        } 
        catch (\Stripe\Error\ApiConnection $e) 
        {
        // Network communication with Stripe failed
            $error = $e->getMessage();
            Session::flash('failure_message', $error); 
            Session::flash('alert-class', 'alert alert-danger'); 
            //return redirect('admin/user/withdrawls-requests');
            return response()->json(['status'=>0]); 
        } 
        catch (\Stripe\Error\Base $e)
        {
        // Display a very generic error to the user, and maybe send yourself an email
            $error = $e->getMessage();
            Session::flash('failure_message', $error); 
            Session::flash('alert-class', 'alert alert-danger'); 
            //return redirect('admin/user/withdrawls-requests');
            return response()->json(['status'=>0]); 
        } 
        catch (\Stripe\Error\Api $e) 
        {
        // Stripe's servers are down!
            $error = $e->getMessage();
            Session::flash('failure_message', $error); 
            Session::flash('alert-class', 'alert alert-danger'); 
            //return redirect('admin/user/withdrawls-requests');
            return response()->json(['status'=>0]); 
        }
        catch (\Stripe\Error\Card $e)
        {
        // Card Was declined
            $error = $e->getMessage();
            Session::flash('failure_message', $error); 
            Session::flash('alert-class', 'alert alert-danger'); 
            //return redirect('admin/user/withdrawls-requests');
            return response()->json(['status'=>0]); 
        } 
        catch (Exception $e) 
        {
            $error = $e->getMessage();
            Session::flash('failure_message', $error); 
            Session::flash('alert-class', 'alert alert-danger'); 
            //return redirect('admin/user/withdrawls-requests');
            return response()->json(['status'=>0]); 
        }
      }

    function getPaypalReturnData($id="")
    {
        $module_head        = "Withdrawal Details";
        $withdrawl_class    = "active";
        $title              = "Withdrawal Management";
        $id                 = base64_decode($id);
        $action             =$_GET['action'];

        $all_details  = WithdrawalHistory::leftJoin('site_users', 'site_users.id', '=', 'withdrawal_history.user_id')
                                ->select('withdrawal_history.*','site_users.name','site_users.last_name','site_users.email')
                                ->where('withdrawal_history.id',$id)->first()->toArray();

        if($action == 'sucess'){
            
            $data_to_be_saved['payment_status']     = $_POST['payment_status']=='Pending'?'paid':$_POST['payment_status'];
            $data_to_be_saved['amount']     = $_POST['payment_gross'];
            $data_to_be_saved['txn_id']             = $_POST['txn_id'];
            $data_to_be_saved['description']        = $_POST['item_name1'];
            //$data_to_be_saved['payment_currency']   = $_POST['mc_currency'];
            //$data_to_be_saved['txn_id']             = $_POST['txn_id'];
            $data_to_be_saved['currency']           = $_POST['mc_currency'];
            $data_to_be_saved['data']               = base64_encode(serialize($_POST));
            $data_to_be_saved['created']            = $_POST['payment_date'];
            $data_to_be_saved['transfer_id']        = $_POST['receiver_id'];
            $data_to_be_saved['status']             = 1;

            WithdrawalHistory::where('id',$id)->update($data_to_be_saved);

            /**************************SEND MAIL -start *****************************/
        
            $site = Sitesetting::where(['name' => 'email'])->first();
            $admin_users_email = $site->value;

            $user_name = $all_details['name'].' '.$all_details['last_name'];
            $user_email = $all_details['email'];
            
            $subject = "Amount release from admin";
            $message_body = "Your amount has been transfer in your ".$_POST['business']." paypal account.<br><br>";
            $message_body .= "Amount            :$".$_POST['payment_gross']."<br>";
            $message_body .= "Tranction Id      :".$_POST['txn_id']."<br>";
            $message_body .= "Payment Date      :".$_POST['payment_date']."<br>";
            $message_body .= "Tranction Fee     :$".$_POST['payment_fee']."<br>";
            
            
            $mail = Mail::send(['html' => 'admin.emailtemplate.send_mail'], array('message_body' => $message_body, 'user_name'=>$user_name), function($message) use ($admin_users_email, $user_email,$user_name,$subject)
            {
              $message->from($admin_users_email,'Front Mode');
        
              $message->to($user_email)->subject($subject);
            });

            $subjectA = "Amount tranfer to ".$user_name;
            $message_bodyA  = "User cashback amount has been transfer from your ".$_POST['payer_email']." paypal account.<br><br>";
            $message_bodyA .= "User Name         :".$user_name."<br>";
            $message_bodyA .= "Amount            :$".$_POST['payment_gross']."<br>";
            $message_bodyA .= "Tranction Id      :".$_POST['txn_id']."<br>";
            $message_bodyA .= "Payment Date      :".$_POST['payment_date']."<br>";
            $message_bodyA .= "Tranction Fee     :$".$_POST['payment_fee']."<br>";
            
            
            $mail = Mail::send(['html' => 'admin.emailtemplate.send_mail'], array('message_body' => $message_bodyA, 'user_name'=>'Admin'), function($message) use ($admin_users_email,$subjectA)
            {
              $message->from($admin_users_email,'Front Mode');
        
              $message->to($admin_users_email)->subject($subjectA);
            });
        
        /**************************SEND MAIL -start *****************************/ 
            
            Session::flash('success_message', 'Amount $'.$_POST['payment_gross'].' tranfer sucessfully to '.$user_name); 
            Session::flash('alert-class', 'alert alert-success'); 
            return redirect('admin/user/withdrawal');
        }else{
           Session::flash('failure_message', 'Your paypal payment failed'); 
           Session::flash('alert-class', 'alert alert-danger'); 
           return redirect('admin/user/withdrawls-requests'); 
        }

        
    }

    function getPaypalNotifyData($id="")
    {
        $module_head        = "Withdrawal Details";
        $withdrawl_class    = "active";
        $title              = "Withdrawal Management";
        $id                 = base64_decode($id);
        /*$tranctions_details  = WithdrawalHistory::leftJoin('site_users', 'site_users.id', '=', 'withdrawal_history.user_id')
                                ->select('withdrawal_history.*','site_users.name','site_users.last_name')
                                ->where('withdrawal_history.id',base64_decode($id))->first()->toArray();*/
        
        $mail = Mail::send(['html' => 'admin.emailtemplate.send_mail'], array('message_body' => 'message_body', 'user_name'=>'user_name'), function($message)
        {
          $message->from('partha90.paul@gmail.com','Cashback');
    
          $message->to('partha90.paul@gmail.com')->subject('getPaypalNotifyData');
        });

        $data_to_be_saved['payment_status']     = $_POST['payment_status']=='Pending'?'paid':$_POST['payment_status'];
        $data_to_be_saved['payment_amount']     = $_POST['payment_gross'];
        $data_to_be_saved['txn_id']             = $_POST['txn_id'];
        $data_to_be_saved['description']        = $_POST['item_name1'];
        $data_to_be_saved['payment_currency']   = $_POST['mc_currency'];
        $data_to_be_saved['txn_id']             = $_POST['txn_id'];
        $data_to_be_saved['currency']           = $_POST['mc_currency'];
        $data_to_be_saved['data']               = base64_encode(serialize($_POST));
        $data_to_be_saved['created']            = $_POST['payment_date'];
        $data_to_be_saved['transfer_id']        = $_POST['receiver_id'];
        $data_to_be_saved['status']             = 1;

        WithdrawalHistory::where('id',$id)->update($data_to_be_saved);
        /*echo "<pre>";
        print_r($_POST);exit;*/

        
        return view('admin.withdrawals.view_withdrawals',compact(
                                                        'tranctions_details','tranction_class',
                                                        'module_head','title'
                                                    )
                    );
    }
}
