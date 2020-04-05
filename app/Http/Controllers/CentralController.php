<?php
namespace App\Http\Controllers;
use Config;
use App\Url;
use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\ExecutePayment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\Transaction;
use App\Model\SiteUser; /* Model name*/
use PayPal\Api\Payout;
use PayPal\Api\PayoutSenderBatchHeader;
use App\Model\Withdrawldetails; /* Model name*/
use App\Model\Walletdetails;/*Model Name*/
use Session;
use Redirect;
use Customhelpers;
use App\Model\Sitesetting;
use App\Model\Emailnotification; /* Model name*/
use App\Model\Emailnotification_Siteuser; /* Model name*/
use Mail;
class CentralController extends Controller
{
    
    private $_api_context;

    public function __construct()
    {
        // setup PayPal api context comming from config folder paypal.php page
        $paypal_conf = Config::get('paypal');
        $this->_api_context = new ApiContext(new OAuthTokenCredential($paypal_conf['client_id'], $paypal_conf['secret']));
        $this->_api_context->setConfig($paypal_conf['settings']);
    }

	public function postPayment()
	{


	/*$payouts = new Payout();
	$senderBatchHeader = new PayoutSenderBatchHeader();
	$senderBatchHeader->setSenderBatchId(uniqid())
    ->setEmailSubject("You have a payment");
    $senderItem1 = new \PayPal\Api\PayoutItem();
	$senderItem1->setRecipientType('Email')
    ->setNote('Thanks you.')
    ->setReceiver('efundo@gmail.com')
    ->setSenderItemId("item_1" . uniqid())
    ->setAmount(new \PayPal\Api\Currency('{
                        "value":"0.99",
                        "currency":"USD"
                    }'));

    $senderItem2 = new \PayPal\Api\PayoutItem();
    $senderItem2->setRecipientType('Email')
    ->setNote('Thanks you.')
    ->setReceiver('tripoasia@gmail.com')
    ->setSenderItemId("item_1" . uniqid())
    ->setAmount(new \PayPal\Api\Currency('{
                        "value":"0.99",
                        "currency":"USD"
                    }'));



$payouts->setSenderBatchHeader($senderBatchHeader)
    ->addItem($senderItem1)->addItem($senderItem2);
$request = clone $payouts;




try {
    $output = $payouts->create(null, $this->_api_context);
} catch (Exception $ex) {
   
    ResultPrinter::printError("Created Batch Payout", "Payout", null, $request, $ex);
    exit(1);
}



return $output;*/

/* ********************************************Paypal batch code modified  start****************************************************/

	$payouts = new Payout();
	$senderBatchHeader = new PayoutSenderBatchHeader();
	$senderBatchHeader->setSenderBatchId(uniqid())
	->setEmailSubject("You have a payment");


	$a=$payouts->setSenderBatchHeader($senderBatchHeader);
	$data = json_decode(stripslashes($_POST['data']));
	$dataresult=$data;
	foreach($data as $d){

		$userar=explode("-", $d);
		$id=$userar[0];
		$amount=$userar[1];
		$withdrawlid=$userar[2];
		$userdata=SiteUser::find($id);
		$email=$userdata->email;	



		$senderitem = new  \PayPal\Api\PayoutItem();
					$senderitem->setRecipientType('Email')
					->setNote('Thanks you.')
					->setReceiver($email)
					->setSenderItemId("item_1" . uniqid())
					->setAmount(new \PayPal\Api\Currency('{
					"value":'.$amount.',
					"currency":"USD"
					}'));



		$a=$a->addItem($senderitem);


	}
	//print_r($a);exit();
	$request = clone $a;
	try {
	$output = $payouts->create(null, $this->_api_context);

	
	$output_array=$output->toArray();
	
	$batch_header=$output_array['batch_header'];

	//print_r($batch_header);

	$sender_batch_id=$batch_header['sender_batch_header']['sender_batch_id'];
	
	$status=1;
	} catch (Exception $ex) {
	echo "Error in payment";	
	//ResultPrinter::printError("Created Batch Payout", "Payout", null, $request, $ex);
	$status=0;
	exit(1);
	}

	if($status==1){

		foreach($dataresult as $d){

			$userar=explode("-", $d);
			$id=$userar[0];
			$amount=$userar[1];
			$withdrawlid=$userar[2];
			$userdata=SiteUser::find($id);
			$email=$userdata->email;

			$datetime= Customhelpers::Returndatetime();
			$withdrawldetails=Withdrawldetails::find($withdrawlid);
			$withdrawldetails->status=1; //paypal batch payment success
			$withdrawldetails->updated_at=$datetime;
			$withdrawldetails->senderbatchid=$sender_batch_id;
			// $withdrawldetails->payoutbatchid=$payoutbatchid;
			// $withdrawldetails->batchstatus=$batch_status;
			// $withdrawldetails->senderbatchid=$sender_batch_id;
			$withdrawldetails->save(); // store withdrawl paypal details

			$siteuserdata=SiteUser::find($id);
			$wallettotalamount=$siteuserdata->wallettotalamount;
			$remainingamount=$wallettotalamount-$amount;
			$siteuserdata->wallettotalamount=$remainingamount;// debit for withdrawl from user wallet
			$siteuserdata->save();
			$itemdetails='Withdrawl request<br/>Transaction Id : '.$sender_batch_id;
			
			Walletdetails::create(['siteusers_id'=>$id,'purpose'=>'Withdrawl request','amount'=>$amount,'total'=>$amount,'currencycode'=>'USD','created_at'=>$datetime,'status'=>1,'purpose_state'=>'4','walletstatus'=>'1','itemdetails'=>$itemdetails]); // store debit information 

			//check email notification in profile is on or off

			$emailnotification=Emailnotification::where('slug','withdrawlrequestsuccessful')->where('status',1)->get();

			$emailnotification_count=Emailnotification::where('slug','withdrawlrequestsuccessful')->where('status',1)->count();
			if($emailnotification_count>0){
			$emailnotification_id=$emailnotification[0]->id;


			$emailnotification_Siteuser=Emailnotification_Siteuser::where('siteusers_id',$id)->where("emailnotifications_id",$emailnotification_id)->get();


			$emailnotification_Siteuser_count=Emailnotification_Siteuser::where('siteusers_id',$id)->where("emailnotifications_id",$emailnotification_id)->count();

			if($emailnotification_Siteuser_count>0){

			$mailstatus=$emailnotification_Siteuser[0]->status;

					if($mailstatus==1)
					{

							  /**************************SEND MAIL -start *****************************/
							

							$site = Sitesetting::where(['name' => 'email'])->first();
							$admin_users_email = $site->value;

							
							$user_name = $siteuserdata->firstname.' '.$siteuserdata->lastname;
							$user_email = $siteuserdata->email;
							

							$subject = "Payment success for your withdrawl request in Checkout Saver";
							$message_body = "Admin has approved your withdrawl request in Checkout Saver.Your withdrawl amount is "." $".number_format($amount,2);


							$mail = Mail::send(['html' => 'frontend.emailtemplate.send_mail'], array('message_body' => $message_body, 'user_name'=>$user_name), function($message) use ($admin_users_email, $user_email,$user_name,$subject)
							{
							$message->from($admin_users_email,'Checkout Saver');

							$message->to($user_email)->subject($subject);
							});

							/**************************SEND MAIL -end *****************************/ 
					}
				}
			}


		}

	}
	else{


		foreach($dataresult as $d){

			$userar=explode("-", $d);
			$id=$userar[0];
			$amount=$userar[1];
			$withdrawlid=$userar[2];
			$userdata=SiteUser::find($id);
			$email=$userdata->email;
			$siteuserdata=SiteUser::find($id);

			//check email notification in profile is on or off

			$emailnotification=Emailnotification::where('slug','Cashback-withdrawal-unsuccessful')->where('status',1)->get();

			$emailnotification_count=Emailnotification::where('slug','Cashback-withdrawal-unsuccessful')->where('status',1)->count();
			if($emailnotification_count>0){
			$emailnotification_id=$emailnotification[0]->id;


			$emailnotification_Siteuser=Emailnotification_Siteuser::where('siteusers_id',$id)->where("emailnotifications_id",$emailnotification_id)->get();


			$emailnotification_Siteuser_count=Emailnotification_Siteuser::where('siteusers_id',$id)->where("emailnotifications_id",$emailnotification_id)->count();

			if($emailnotification_Siteuser_count>0){

			$mailstatus=$emailnotification_Siteuser[0]->status;

					if($mailstatus==1)
					{

							  /**************************SEND MAIL -start *****************************/
							

							$site = Sitesetting::where(['name' => 'email'])->first();
							$admin_users_email = $site->value;

							
							$user_name = $siteuserdata->firstname.' '.$siteuserdata->lastname;
							$user_email = $siteuserdata->email;
							

							$subject = "Payment failed for your withdrawl request in Checkout Saver";
							$message_body = " Your withdrawl request failed in Checkout Saver.Your withdrawl amount is "." $".number_format($amount,2);


							$mail = Mail::send(['html' => 'frontend.emailtemplate.send_mail'], array('message_body' => $message_body, 'user_name'=>$user_name), function($message) use ($admin_users_email, $user_email,$user_name,$subject)
							{
							$message->from($admin_users_email,'Checkout Saver');

							$message->to($user_email)->subject($subject);
							});

							/**************************SEND MAIL -end *****************************/ 
					}
				}
			}


		}



	}

	return $output;

	/* ********************************************Paypal batch code modified  end****************************************************/


	
 //    $senderItem1 = new \PayPal\Api\PayoutItem();
	// $senderItem1->setRecipientType('Email')
 //    ->setNote('Thanks you.')
 //    ->setReceiver('efundo@gmail.com')
 //    ->setSenderItemId("item_1" . uniqid())
 //    ->setAmount(new \PayPal\Api\Currency('{
 //                        "value":"0.99",
 //                        "currency":"USD"
 //                    }'));


	/* paypal batch payment code  starts*/
			/*
			$data = json_decode(stripslashes($_POST['data']));


			foreach($data as $d){

			$payouts = new Payout(); //create payout object in loop
			$senderBatchHeader = new PayoutSenderBatchHeader();
			$senderBatchHeader->setSenderBatchId(uniqid())
			->setEmailSubject("You have a payment");


				$userar=explode("-", $d);
				$id=$userar[0];
				$amount=$userar[1];
				$withdrawlid=$userar[2];
				$userdata=SiteUser::find($id);
			    $email=$userdata->email;
			    if (empty($senderItem)) $senderItem = '';
			    
				$s = new  \PayPal\Api\PayoutItem();
				$s->setRecipientType('Email')
				->setNote('Thanks you.')
				->setReceiver($email)
				->setSenderItemId("item_1" . uniqid())
				->setAmount(new \PayPal\Api\Currency('{
				"value":'.$amount.',
				"currency":"USD"
				}'));

				

				$payouts->setSenderBatchHeader($senderBatchHeader)
				->addItem($s);

				$request = clone $payouts;


				try {

				$output = $payouts->create(null, $this->_api_context);
				$outputjsondecode=json_decode($output);
				$payoutbatchid=$outputjsondecode->batch_header->payout_batch_id;
				$batch_status=$outputjsondecode->batch_header->batch_status;
				$sender_batch_id=$outputjsondecode->batch_header->sender_batch_header->sender_batch_id;

					if($payoutbatchid!='' && $batch_status!='' && $sender_batch_id!=''){

						$datetime= Customhelpers::Returndatetime();
						$withdrawldetails=Withdrawldetails::find($withdrawlid);
						$withdrawldetails->status=1; //paypal batch payment success
						$withdrawldetails->updated_at=$datetime;
						$withdrawldetails->payoutbatchid=$payoutbatchid;
						$withdrawldetails->batchstatus=$batch_status;
						$withdrawldetails->senderbatchid=$sender_batch_id;
						$withdrawldetails->save(); // store withdrawl paypal details

						$siteuserdata=SiteUser::find($id);
						$wallettotalamount=$siteuserdata->wallettotalamount;
						$remainingamount=$wallettotalamount-$amount;
						$siteuserdata->wallettotalamount=$remainingamount;// debit for withdrawl from user wallet
						$siteuserdata->save();

						
						Walletdetails::create(['siteusers_id'=>$id,'purpose'=>'Withdrawl request','amount'=>$amount,'total'=>$amount,'currencycode'=>'USD','created_at'=>$datetime,'status'=>1]); // store debit information 

					}
				

				} catch (Exception $ex) {
					echo "Error in payment";
				//ResultPrinter::printError("Created Batch Payout", "Payout", null, $request, $ex);
				exit(1);
				}
				echo "Payment Success";
				//print_r($outputjsondecode)  ;

			}
				*/
	/* paypal batch payment code  ends*/

//echo $s;exit();

    // $senderItem2 = new \PayPal\Api\PayoutItem();
    // $senderItem2->setRecipientType('Email')
    // ->setNote('Thanks you.')
    // ->setReceiver('tripoasia@gmail.com')
    // ->setSenderItemId("item_1" . uniqid())
    // ->setAmount(new \PayPal\Api\Currency('{
    //                     "value":"0.99",
    //                     "currency":"USD"
    //                 }'));















/*
	$payer = new Payer();
	$payer->setPaymentMethod('paypal');

	$item_1 = new Item();
	$item_1->setName('Item 1')
	->setCurrency('USD')
	->setQuantity(2)
	->setPrice('15');

	$item_2 = new Item();
	$item_2->setName('Item 2')
	->setCurrency('USD')
	->setQuantity(4)
	->setPrice('7');

	$item_3 = new Item();
	$item_3->setName('Item 3')
	->setCurrency('USD')
	->setQuantity(1)
	->setPrice('20');

	// add item to list
	$item_list = new ItemList();
	$item_list->setItems(array($item_1, $item_2, $item_3));

	$amount = new Amount();
	$amount->setCurrency('USD')
	->setTotal(78);

	$transaction = new Transaction();
	$transaction->setAmount($amount)
	->setItemList($item_list)
	->setDescription('Your transaction description');

	$redirect_urls = new RedirectUrls();
	$redirect_urls->setReturnUrl("http://localhost/cashback-justin/payment/status")
	->setCancelUrl("http://localhost/cashback-justin/payment/status");

	$payment = new Payment();
	$payment->setIntent('Sale')
	->setPayer($payer)
	->setRedirectUrls($redirect_urls)
	->setTransactions(array($transaction));

	try {
	$payment->create($this->_api_context);
	} catch (\PayPal\Exception\PPConnectionException $ex) {
	if (\Config::get('app.debug')) {
	    echo "Exception: " . $ex->getMessage() . PHP_EOL;
	    $err_data = json_decode($ex->getData(), true);
	    exit;
	} else {
	    die('Some error occur, sorry for inconvenient');
	}
	}

	foreach($payment->getLinks() as $link) {
	if($link->getRel() == 'approval_url') {
	    $redirect_url = $link->getHref();
	    break;
	}
	}

	// add payment ID to session
	Session::put('paypal_payment_id', $payment->getId());

	if(isset($redirect_url)) {
	// redirect to paypal
	return Redirect::away($redirect_url);
	}

	return Redirect::route('original.route')
	->with('error', 'Unknown error occurred');
	}



	public function getPaymentStatus()
	{
	// Get the payment ID before session clear
	$payment_id = Session::get('paypal_payment_id');

	// clear the session payment ID
	Session::forget('paypal_payment_id');

	if (empty(Input::get('PayerID')) || empty(Input::get('token'))) {
	return Redirect::route('original.route')
	    ->with('error', 'Payment failed');
	}

	$payment = Payment::get($payment_id, $this->_api_context);

	// PaymentExecution object includes information necessary 
	// to execute a PayPal account payment. 
	// The payer_id is added to the request query parameters
	// when the user is redirected from paypal back to your site
	$execution = new PaymentExecution();
	$execution->setPayerId(Input::get('PayerID'));

	//Execute the payment
	$result = $payment->execute($execution, $this->_api_context);

	if ($result->getState() == 'approved') { // payment made
	return Redirect::route('original.route')
	    ->with('success', 'Payment success');
	}
	return Redirect::route('original.route')
	->with('error', 'Payment failed');

*/


	}


}