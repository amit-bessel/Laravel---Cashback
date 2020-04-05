<?php namespace App\Http\Controllers\Frontend; /* path of this controller*/
// Define Model
use App\Model\SiteUser;
use App\Model\Product; /* Model name*/
use App\Model\SiteUserBankAccount; /* Model name*/
use App\Model\AdminUserCard; /* Model name*/
use App\Model\WithdrawalHistory; /* Model name*/
use App\Model\Sitesetting; /* Model name*/

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

require_once('vendor/Stripe/init.php');

use Stripe\Stripe;
use Stripe\Charge;
use Stripe\Customer;
use Stripe\Stripe_CardError;
use Stripe\Stripe_InvalidRequestError;


class BankAccountController extends BaseController {

    public function __construct() 
    {
        parent::__construct();
	       
        $obj = new helpers();
        view()->share('obj',$obj);
    }
   /**
    * Display a listing of the resource.
    *
    * @return Response
    */

   public function getIndex(){

      // Check for authentication
      $this->authenticateUser();

      $user_bank_data = array();
      $sql_user_bank = SiteUserBankAccount::where('site_user_id',Session::get('user_id'));
       if($sql_user_bank->count()!=0){

          $user_bank_data = $sql_user_bank->first()->toArray();

       }

       /*echo "<pre>";
       print_r($user_bank_data);
       exit;*/
      $title = 'Bank Account Details';
      return view('frontend.bank_account.bank_account',compact('title', 'user_bank_data'));
   }

   public function postBankDetails(){

    // Check for authentication
    $user_details = $this->authenticateUser();
    /*echo "<pre>";
    print_r($user_details);
    exit;*/
    $data = Request::all();
    /*echo "<pre>";
    print_r($data);
    exit;*/
    $count = SiteUserBankAccount::where('site_user_id',Session::get('user_id'))->count();
    if($count==0){

      if($data['account_type']==1){

        $user = SiteUserBankAccount::create([
                    'site_user_id'        => Session::get('user_id'),
                    'account_type'        => $data['account_type'],
                    'secret_key'          => $data['secret_key'],
                    'publish_key'         => $data['publish_key'],
                    'created_at'          => date('Y-m-d H:i:s')
        ]);
        $insertedId = $user->id;
      }
      else if($data['account_type']==2){
          $user = SiteUserBankAccount::create([
                    'site_user_id'       => Session::get('user_id'),
                    'account_type'       => $data['account_type'],
                    'paypal_email'       => $data['paypal_email'],
                    'created_at'         => date('Y-m-d H:i:s')
          ]);
      }
      else if($data['account_type']==3){
          
          Stripe::setApiKey($this->fetchStripSecretAPI());
          /* Creating customer managed account */
          $create_account=\Stripe\Account::create(array(
                 "managed" => true,
                 "country" => $data['country'],
                 "email" => $user_details->email
               ));
          $strip_account_id = $create_account->id;
          /*-------------------------------------*/

          $error = $this->createAndUpdateStripeAccount($strip_account_id,$data,'ADD');
          /*echo $file_obj->id;
          exit;*/
          if($error==0){
            $dob  = date('Y-m-d',strtotime($data['dob']));


            $user = SiteUserBankAccount::create([
                        'site_user_id'                => Session::get('user_id'),
                        'account_type'                => $data['account_type'],
                        'strip_account_id'            => $strip_account_id,
                        'stripe_address'              => $data['autocomplete'],
                        'stripe_city'                 => $data['locality'],
                        'stripe_state'                => $data['administrative_area_level_1'],
                        'stripe_country'              => $data['country'],
                        'stripe_postal_code'          => $data['postal_code'],
                        'stripe_business_name'        => $data['business_name'],
                        'stripe_business_tax_id'      => $data['business_tax_id'],
                        'stripe_dob'                  => $dob,
                        'stripe_first_name'           => $data['first_name'],
                        'stripe_last_name'            => $data['last_name'],
                        'stripe_personal_id_number'   => $data['personal_id_number'],
                        'stripe_ssn_last_4'           => $data['ssn_last_4'],
                        'stripe_account_holder_name'  => $data['account_holder_name'],
                        'stripe_account_holder_type'  => $data['account_holder_type'],
                        'stripe_routing_number'       => $data['routing_number'],
                        'stripe_account_number'       => $data['account_number'],
                        'created_at'                  => date('Y-m-d H:i:s')
              ]);
          }
          else{
            Session::flash('failure_message', $error);
            return redirect('user/my-account-details');
          }
          

      }

    }
    else{

          if($data['account_type']==1){
            SiteUserBankAccount::where('site_user_id','=',Session::get('user_id'))
                ->update(['account_type'=>$data['account_type'],'secret_key'=>$data['secret_key'],'publish_key'=>$data['publish_key'],'updated_at'=>date('Y-m-d H:i:s')]);
          }
          else if($data['account_type']==2){
            SiteUserBankAccount::where('site_user_id','=',Session::get('user_id'))
                ->update(['account_type'=>$data['account_type'],'paypal_email'=>$data['paypal_email'],'updated_at'=>date('Y-m-d H:i:s')]);
          }
          else if($data['account_type']==3){

            Stripe::setApiKey($this->fetchStripSecretAPI());

            $site_user_details = SiteUserBankAccount::where('site_user_id',Session::get('user_id'))->first();
            $strip_account_id = $site_user_details->strip_account_id;

            $error = $this->createAndUpdateStripeAccount($strip_account_id,$data,'EDIT');
            if($error==0){

              $dob  = date('Y-m-d',strtotime($data['dob']));

              SiteUserBankAccount::where('site_user_id','=',Session::get('user_id'))
                  ->update([
                      'account_type'                => $data['account_type'],
                      //'strip_account_id'            => $data['strip_account_id'],
                      'stripe_address'              => $data['autocomplete'],
                      'stripe_city'                 => $data['locality'],
                      'stripe_state'                => $data['administrative_area_level_1'],
                      'stripe_country'              => $data['country'],
                      'stripe_postal_code'          => $data['postal_code'],
                      'stripe_business_name'        => $data['business_name'],
                      'stripe_business_tax_id'      => $data['business_tax_id'],
                      'stripe_dob'                  => $dob,
                      //'stripe_first_name'           => $data['first_name'],
                      //'stripe_last_name'            => $data['last_name'],
                      'stripe_personal_id_number'   => $data['personal_id_number'],
                      //'stripe_ssn_last_4'           => $data['ssn_last_4'],
                      'stripe_account_holder_name'  => $data['account_holder_name'],
                      'stripe_account_holder_type'  => $data['account_holder_type'],
                      'stripe_routing_number'       => $data['routing_number'],
                      'stripe_account_number'       => $data['account_number'],
                      'updated_at'                  => date('Y-m-d H:i:s')
                  ]);
            }
            else{
              Session::flash('failure_message', $error);
              return redirect('user/my-account-details');
            }
            
          }
    }

    Session::flash('success_message', 'You have successfully update your bank account.');
    return redirect('user/my-account-details');

   }


   public function createAndUpdateStripeAccount($strip_account_id="",$data=[],$operationMode=""){
     /* echo $strip_account_id;
      echo "<hr>";
      echo $operationMode;
      echo "<hr>";
      echo "<pre>";
      print_r($data);*/

      //exit;

      $dob = explode('-',$data['dob']);
      $account = \Stripe\Account::retrieve($strip_account_id);
      //echo '<pre>';print_r($account);exit;
      try{
        $account->legal_entity->dob->day              = $dob[0];
        $account->legal_entity->dob->month            = $dob[1];
        $account->legal_entity->dob->year             = $dob[2];

        if($operationMode=='ADD'){
          $account->legal_entity->first_name            = $data['first_name'];
          $account->legal_entity->last_name             = $data['last_name'];
          $account->legal_entity->ssn_last_4            = $data['ssn_last_4'];
        }
        
        $account->legal_entity->type                  = 'individual';
        $account->legal_entity->address->line1        = $data['autocomplete'];
        $account->legal_entity->address->postal_code  = $data['postal_code'];
        $account->legal_entity->address->city         = $data['locality'];
        $account->legal_entity->address->state        = $data['administrative_area_level_1'];
        $account->tos_acceptance->date                = time();
        $account->tos_acceptance->ip                  = $_SERVER['REMOTE_ADDR'];
        $account->save();
        sleep(2);

        $user_identity_verify = \Stripe\Account::retrieve($strip_account_id);
        if(!empty($data['document']) && ($operationMode=='ADD'))
        {
          $fp  = fopen($_FILES['document']['tmp_name'], 'r');
          $file_obj = \Stripe\FileUpload::create(
            array(
              "purpose" => "identity_document",
              "file" => $fp
            ),
            array(
              "stripe_account" =>$strip_account_id
            )
          );

          /* personal_id_number = any number of 9 digits */
          $user_identity_verify->legal_entity->personal_id_number = $data['personal_id_number'];
          $user_identity_verify->legal_entity->verification->document = $file_obj->id;
        }

        $user_identity_verify->external_account = array(
          "object" => "bank_account",
          "country" => "US",
          "currency" => "usd",
          "account_holder_name" => $data['account_holder_name'],
          "account_holder_type" => $data['account_holder_type'],
          "routing_number" => $data['routing_number'],
          "account_number" => $data['account_number']
        );

        $save_data=$user_identity_verify->save();
        //$error = 0;
        return 0;
      }
      catch (\Stripe\Error\InvalidRequest $e) 
      {
        // Invalid parameters were supplied to Stripe's API
        $error = $e->getMessage();
        return $error;
      } 
      catch (\Stripe\Error\Authentication $e) 
      {
        $error = $e->getMessage();
        return $error; 
      } 
      catch (\Stripe\Error\ApiConnection $e) 
      {
        // Network communication with Stripe failed
        $error = $e->getMessage();
        return $error; 
      } 
      catch (\Stripe\Error\Base $e)
      {
        // Display a very generic error to the user, and maybe send yourself an email
        $error = $e->getMessage();
        return $error; 
      } 
      catch (\Stripe\Error\Api $e) 
      {
        // Stripe's servers are down!
        $error = $e->getMessage();
        return $error;
      }
      catch (\Stripe\Error\Card $e)
      {
        // Card Was declined
        $error = $e->getMessage();
        return $error; 
      } 
      catch (Exception $e) 
      {
        $error = $e->getMessage();
        return $error; 
      }
   }


   public function postBalanceTransferVaiStripe(){

    $user_details = $this->authenticateUser();

    $AccountDetails = SiteUserBankAccount::where('site_user_id',Session::get('user_id'))->first()->toArray();
    
    if($AccountDetails['account_type'] == 3){
       Stripe::setApiKey($this->fetchStripSecretAPI());
       try
        {
          $transfer = \Stripe\Transfer::create(
            array(
              "amount" => $user_details['cashback']*100,
              "currency" => "usd",
              "destination" => $AccountDetails['strip_account_id'],
              "description" => $user_details['name']." ".$user_details['last_name']." Direct Transfer External Bank Account"
            ));

            $updateCasback =SiteUser::where('id',$user_details['id'])->update(array('cashback'=>0));
            
            $data_to_be_saved['user_id']          = $user_details['id'];
            $data_to_be_saved['amount']           = $user_details['cashback'];
            $data_to_be_saved['send_to']          = $AccountDetails['account_type'];
            $data_to_be_saved['strip_account_id'] = $AccountDetails['strip_account_id'];
            $data_to_be_saved['txn_id']           = $transfer->id;
            $data_to_be_saved['currency']         = $transfer->currency;
            $data_to_be_saved['transfer_id']      = $transfer->balance_transaction;
            $data_to_be_saved['payment_id']       = $transfer->destination_payment;
            $data_to_be_saved['description']      = $transfer->description;
            $data_to_be_saved['payment_status']   = $transfer->status; // payment pass
            $data_to_be_saved['status']           = ($transfer->status=='paid')?1:0; // payment pass
            $data_to_be_saved['created']          = $transfer['created'];
            $data_to_be_saved['data']             = base64_encode(serialize($transfer));
            $data_to_be_saved['created_at']       = date('Y-m-d H:i:s');

            WithdrawalHistory::create($data_to_be_saved);
            //WithdrawalHistory->save($data_to_be_saved);

            /**************************SEND MAIL -start *****************************/
          
            $site = Sitesetting::where(['name' => 'email'])->first();
            $admin_users_email = $site->value;

            $user_name = $user_details['name'].' '.$user_details['last_name'];
            $user_email = $user_details['email'];
            
            $subject = "Amount release from admin";
              $message_body = "Your amount has been transfer in your ".$_POST['business']." paypal account.<br><br>";
              $message_body .= "Amount            :$".$user_details['cashback']."<br>";
              $message_body .= "Tranction Id      :".$transfer->id."<br>";
              $message_body .= "Payment Date      :".$data_to_be_saved['created_at']."<br>";
              
              $mail = Mail::send(['html' => 'admin.emailtemplate.send_mail'], array('message_body' => $message_body, 'user_name'=>$user_name), function($message) use ($admin_users_email, $user_email,$user_name,$subject)
              {
                $message->from($admin_users_email,'Front Mode');
          
                $message->to($user_email)->subject($subject);
              });

              $subjectA = "Amount tranfer to ".$user_name;
              $message_bodyA  = "User cashback amount has been transfer from your ".$_POST['payer_email']." paypal account.<br><br>";
              $message_bodyA .= "User Name         :".$user_name."<br>";
              $message_bodyA .= "Amount            :$".$user_details['cashback']."<br>";
              $message_bodyA .= "Tranction Id      :".$transfer->id."<br>";
              $message_bodyA .= "Payment Date      :".$data_to_be_saved['created_at']."<br>";
              
              
              $mail = Mail::send(['html' => 'admin.emailtemplate.send_mail'], array('message_body' => $message_bodyA, 'user_name'=>'Admin'), function($message) use ($admin_users_email,$subjectA)
              {
                $message->from($admin_users_email,'Front Mode');
          
                $message->to($admin_users_email)->subject($subjectA);
              });
          
          /**************************SEND MAIL -start *****************************/

            Session::flash('success_message', 'Your cashback amount $'.$user_details['cashback'].' sucessfully transfer.'); 
            Session::flash('alert-class', 'alert alert-success'); 
            return redirect('user/cashback');
        }
        catch (\Stripe\Error\InvalidRequest $e) 
        {
          // Invalid parameters were supplied to Stripe's API
          $error = $e->getMessage();
          return $error;
        } 
        catch (\Stripe\Error\Authentication $e) 
        {
          $error = $e->getMessage();
          return $error; 
        } 
        catch (\Stripe\Error\ApiConnection $e) 
        {
          // Network communication with Stripe failed
          $error = $e->getMessage();
          return $error; 
        } 
        catch (\Stripe\Error\Base $e)
        {
          // Display a very generic error to the user, and maybe send yourself an email
          $error = $e->getMessage();
          return $error; 
        } 
        catch (\Stripe\Error\Api $e) 
        {
          // Stripe's servers are down!
          $error = $e->getMessage();
          return $error;
        }
        catch (\Stripe\Error\Card $e)
        {
          // Card Was declined
          $error = $e->getMessage();
          return $error; 
        } 
        catch (Exception $e) 
        {
          $error = $e->getMessage();
          return $error; 
        }
    }else{
      
        $updateCasback =SiteUser::where('id',$user_details['id'])->update(array('cashback'=>0));
        
        $data_to_be_saved['user_id']          = $user_details['id'];
        $data_to_be_saved['amount']           = $user_details['cashback'];
        $data_to_be_saved['send_to']          = $AccountDetails['account_type'];
        $data_to_be_saved['payment_status']   = 'pending'; // payment pass
        $data_to_be_saved['status']           = 0; // payment pass
        $data_to_be_saved['created_at']       = date('Y-m-d H:i:s');
        

        WithdrawalHistory::create($data_to_be_saved);

        /**************************SEND MAIL -start *****************************/
        
          $site = Sitesetting::where(['name' => 'email'])->first();
          $admin_users_email = $site->value;

          $user_name = $user_details['name'].' '.$user_details['last_name'];
          $user_email = $user_details['email'];
          
          $subject = "Withdrawal Request";
          $message_body = "Your withdrawal request sent to Front Mode admin.<br><br>";
          $message_body .= "Amount            :$".$user_details['cashback']."<br>";
          $message_body .= "Request Date      :".$data_to_be_saved['created_at']."<br>";
          $message_body .= "Request Status    :".$data_to_be_saved['payment_status']."<br>";
          
          
          $mail = Mail::send(['html' => 'admin.emailtemplate.send_mail'], array('message_body' => $message_body, 'user_name'=>$user_name), function($message) use ($admin_users_email, $user_email,$user_name,$subject)
          {
            $message->from($admin_users_email,'Front Mode');
      
            $message->to($user_email)->subject($subject);
          });

          $Withdrawal_Type = $data_to_be_saved['send_to']==1?'Stripe':$data_to_be_saved['send_to']==2?'Paypal':$data_to_be_saved['send_to']==3?'Bank':'Unkwon';

          $subjectA = "Amount Withdrawal Request From ".$user_name;
          $message_bodyA  = $user_name." Front Mode user want withdrawal in ".$Withdrawal_Type." account.<br><br>";
          $message_bodyA .= "User Name         :".$user_name."<br>";
          $message_bodyA .= "Amount            :$".$user_details['cashback']."<br>";
          $message_bodyA .= "Withdrawal To     :".$Withdrawal_Type." Account<br>";
          $message_bodyA .= "Request Date      :".$data_to_be_saved['created_at']."<br>";
          $message_bodyA .= "Request Status    :".$data_to_be_saved['payment_status']."<br>";
          
          
          $mail = Mail::send(['html' => 'admin.emailtemplate.send_mail'], array('message_body' => $message_bodyA, 'user_name'=>'Admin'), function($message) use ($admin_users_email,$subjectA)
          {
            $message->from($admin_users_email,'Front Mode');
      
            $message->to($admin_users_email)->subject($subjectA);
          });
        
        /**************************SEND MAIL -start *****************************/ 
        //echo "<pre>";print_r($user_details);exit;
        Session::flash('success_message', 'Your request cashback amount $'.$user_details['cashback'].' sucessfully sent to admin.'); 
        Session::flash('alert_class', 'alert alert-success'); 
        return redirect('user/cashback');
    }
   }




  function postStripeToStripeTransfer($stripe_details)
  {
    $user_details = $this->authenticateUser();
    //print_r($user_details);exit;
    //$access_token = Request::header('Access-Token');
    Stripe::setApiKey($stripe_details['secret_key']);
    // Fetch Card Details
    $card_details = AdminUserCard::select('cust_id','card_name','card_id')->where('primary_card',1)->first()->toArray();

    $cust_id = $card_details['cust_id'];
    $creditcard_id = $card_details['card_id'];

    try
    {
      $charge_card = \Stripe\Charge::create(array(
        "amount" => 100, // Amount in cents
        "currency" => "usd",
        "customer" => $cust_id,
        "card"=>$creditcard_id)
      );    

      echo '<pre>';print_r($charge_card);exit('1');   
    }
    catch (\Stripe\Error\InvalidRequest $e) 
    {print_r($e);
    // Invalid parameters were supplied to Stripe's API
    $error = $e->getMessage();
    return response()->json(['status'=>0,'msg' => $error]); 
    } 
    catch (\Stripe\Error\Authentication $e) 
    {print_r($e);
    $error = $e->getMessage();
    return response()->json(['status'=>0,'msg' => $error]); 
    } 
    catch (\Stripe\Error\ApiConnection $e) 
    {print_r($e);
    // Network communication with Stripe failed
    $error = $e->getMessage();
    return response()->json(['status'=>0,'msg' => $error]); 
    } 
    catch (\Stripe\Error\Base $e)
    {print_r($e);
    // Display a very generic error to the user, and maybe send yourself an email
    $error = $e->getMessage();
    return response()->json(['status'=>0,'msg' => $error]); 
    } 
    catch (\Stripe\Error\Api $e) 
    {print_r($e);
    // Stripe's servers are down!
    $error = $e->getMessage();
    return response()->json(['status'=>0,'msg' => $error]); 
    }
    catch (\Stripe\Error\Card $e)
    {print_r($e);
    // Card Was declined
    $error = $e->getMessage();
    return response()->json(['status'=>0,'msg' => $error]); 
    } 
    catch (Exception $e) 
    {print_r($e);
    $error = $e->getMessage();
    return response()->json(['status'=>0,'msg' => $error]); 
    }


    /* ================== Add Payment details in DB ============================= */

    $payment_query = PaymentHistory::select('order_id')->orderBy('id','DESC')->limit(1);
    if($payment_query->count()==0)
      $order_id = 10000;
    else{
      $payment_details = $payment_query->first()->toArray();
      $order_id = $payment_details['order_id'];
      $order_id = $order_id + 1;
    }

    $offer_code = mt_rand(10000, 99999);

    $charge_card_details = array('last4'=>$charge_card->source['last4'],'exp_month'=>$charge_card->source['exp_month'],'exp_year'=>$charge_card->source['exp_year'],'customer'=>$charge_card->source['customer'],'name'=>$charge_card->source['name']);

    $user_data_by_id = $this->getUserDetailById($user_id);
    $user_data_details = array('name'=>$user_data_by_id['name'],'email'=>$user_data_by_id['email'],'contact'=>$user_data_by_id['contact']); 

    $db_array = array('site_user_id'=>$user_id,'offer_id'=>$offerID,'restaurant_id'=>$restaurant_id,'quantity'=>$quantity,'price'=>$price,'total_price'=>($quantity*$price),        'order_id'=>$order_id,'offer_code'=>$offer_code,'site_user_card_id'=>$card_auto_id,'txn_id'=>$charge_card->id,'card_details'=>serialize($charge_card_details),'user_details'=>serialize($user_data_details),'payment_date'=>date('Y-m-d'));

    PaymentHistory::create($db_array);
    
    /* ================== Decrease quantity of offer ============================= */
    
      $new_available_quantity = $available_quantity - $quantity;
      $offer_query = Offer::where("id","=",$offerID)->first();
      if($new_available_quantity>0)
        $offer_query->available_quantity = $new_available_quantity;
      else{
        $offer_query->available_quantity = 0;
        $offer_query->status = 0;
      }
      
      $offer_query->save();

    /* ================== Send Mail To Restaurant owner and Admin ============================= */

      $sitesettings = DB::table('sitesettings')->where('id',1)->first();
      if(!empty($sitesettings))
      {
        $admin_users_email = $sitesettings->value;
      }

      // Get restaurant info
      $restaurant_detail_arr = Restaurant::with('userDetails')->where('id',$restaurant_id)->first()->toArray();
      $customerEmail  = $user_data_by_id['email'];
      $customerName   = $user_data_by_id['name'];

      /*echo "=========================<pre>";
      print_r($restaurant_detail_arr);
      exit;*/
      /* ================== Send Mail To Customer ============================= */
        $sent = Mail::send('service.restaurant.send_invoice_customer', array('invoice_number'=>$order_id,'offer_code'=>$offer_code,'restaurant_detail_arr'=>$restaurant_detail_arr,'user_data_by_id'=>$user_data_by_id,'available_from_time'=>$offer_query->available_from_time,'available_upto_time'=>$offer_query->available_upto_time,'quantity'=>$quantity,'price'=>$price,'total_price'=>($quantity *$price)), 
              function($message) use ($admin_users_email, $customerEmail,$customerName)
              {
                  $message->from($admin_users_email);
                  $message->to($customerEmail, $customerName)->subject('DineBags::Invoice Generation!');
              });

        $length = strrpos(url(), "/");
        $perm_domain = substr(url(),0,$length);

        $user_message = "Thank you for your purchase. Please Pick up your order during the allotted time. It's a pleasure to serve you.";
        $sent = @Mail::send('service.restaurant.send_mail', array('admin_name'=>$customerName,'email'=>$customerEmail,'user_message'=>$user_message,'perm_domain'=>$perm_domain), 
        function($message) use ($admin_users_email, $customerEmail, $customerName)
        {
          $message->from($admin_users_email);
          $message->to($customerEmail, $customerName)->subject("DineQuest :: Thank you for your purchase");
        });


      /* ================== Send Mail To restaurant owner ============================= */
        $restaurantEmail  = $restaurant_detail_arr['user_details']['email'];
        $restaurantName   = $restaurant_detail_arr['user_details']['name'];
        $sent = Mail::send('service.restaurant.send_invoice_customer', array('invoice_number'=>$order_id,'offer_code'=>$offer_code,'restaurant_detail_arr'=>$restaurant_detail_arr,'user_data_by_id'=>$user_data_by_id,'available_from_time'=>$offer_query->available_from_time,'available_upto_time'=>$offer_query->available_upto_time,'quantity'=>$quantity,'price'=>$price,'total_price'=>($quantity *$price)), 
              function($message) use ($admin_users_email, $restaurantEmail,$restaurantName)
              {
                  $message->from($admin_users_email);
                  $message->to($restaurantEmail, $restaurantName)->subject('DineBags::An order has been placed by customer!');
              });

          /* ================== Send Mail To Admin ============================= */
        
        $sent = Mail::send('service.restaurant.send_invoice_customer', array('invoice_number'=>$order_id,'offer_code'=>$offer_code,'restaurant_detail_arr'=>$restaurant_detail_arr,'user_data_by_id'=>$user_data_by_id,'available_from_time'=>$offer_query->available_from_time,'available_upto_time'=>$offer_query->available_upto_time,'quantity'=>$quantity,'price'=>$price,'total_price'=>($quantity *$price)), 
              function($message) use ($admin_users_email)
              {
                  $message->from($admin_users_email);
                  $message->to($admin_users_email, 'Admin')->subject('DineBags::An order has been placed by customer!');
              });



    //print_r($data);exit;
    
    echo json_encode(array('status'=>1));
  }
  
}