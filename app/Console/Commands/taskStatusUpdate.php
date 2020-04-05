<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;



use Illuminate\Support\Facades\Request;
use Illuminate\Pagination\Paginator;
use App\Helper\helpers;

use DB;
use Auth;
use DateTime;
use Mail;

use Config;
use App\Url;

use Session;
use Redirect;
use Customhelpers;
use App\Model\Sitesetting;

class TaskStatusUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:taskStatusUpdate';
    protected $name = 'command:taskStatusUpdate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update Status Of Task Every Fifteen Minutes.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->updateStatus();
    } 

    public function updateStatus() {
        
        $file = fopen("test1.txt","w");
        fwrite($file,"Hello World. Testing!");
        fclose($file);

        $myfile = fopen("logs.txt", "a");
        $txt = "asdBVGdrfhftjthgkhujliju";
        fwrite($myfile, "\n". $txt);
        fclose($myfile);
        // $payouts = new Payout();
        // $senderBatchHeader = new PayoutSenderBatchHeader();
        // $senderBatchHeader->setSenderBatchId(uniqid())
        // ->setEmailSubject("You have a payment");


        // $a=$payouts->setSenderBatchHeader($senderBatchHeader);
        // //$data = json_decode(stripslashes($_POST['data']));

        // $data=Sitesetting::where("name","thresoldvalue")->get();

        // foreach ($data as $key => $value) {
           
        //    $thresoldamount=$value->value;
        //    $thresoldamount = (double)$thresoldamount;
        // }

        // if($thresoldamount>0.0){

        //     $data=Withdrawldetails::where("amount",">=",$thresoldamount)->where("status",0)->get();

        //             $dataresult=$data;
        //             foreach($data as $d){

        //            // $userar=explode("-", $d);
        //             $id=$d->siteusers_id;
        //             $amount=$d->amount;
        //             $withdrawlid=$d->id;
        //             $userdata=SiteUser::find($id);
        //             $email=$userdata->email;    



        //             $senderitem = new  \PayPal\Api\PayoutItem();
        //                     $senderitem->setRecipientType('Email')
        //                     ->setNote('Thanks you.')
        //                     ->setReceiver($email)
        //                     ->setSenderItemId("item_1" . uniqid())
        //                     ->setAmount(new \PayPal\Api\Currency('{
        //                     "value":'.$amount.',
        //                     "currency":"USD"
        //                     }'));



        //             $a=$a->addItem($senderitem);


        //             }
        //             //print_r($a);exit();
        //             $request = clone $a;
        //             try {
        //             $output = $payouts->create(null, $this->_api_context);
        //             $status=1;
        //             } catch (Exception $ex) {
        //             echo "Error in payment";    
        //             //ResultPrinter::printError("Created Batch Payout", "Payout", null, $request, $ex);
        //             $status=0;
        //             exit(1);
        //             }

        //             if($status==1){

        //             foreach($dataresult as $d){

        //             $userar=explode("-", $d);
        //             $id=$userar[0];
        //             $amount=$userar[1];
        //             $withdrawlid=$userar[2];
        //             $userdata=SiteUser::find($id);
        //             $email=$userdata->email;

        //             $datetime= Customhelpers::Returndatetime();
        //             $withdrawldetails=Withdrawldetails::find($withdrawlid);
        //             $withdrawldetails->status=1; //paypal batch payment success
        //             $withdrawldetails->updated_at=$datetime;
        //             // $withdrawldetails->payoutbatchid=$payoutbatchid;
        //             // $withdrawldetails->batchstatus=$batch_status;
        //             // $withdrawldetails->senderbatchid=$sender_batch_id;
        //             $withdrawldetails->save(); // store withdrawl paypal details

        //             $siteuserdata=SiteUser::find($id);
        //             $wallettotalamount=$siteuserdata->wallettotalamount;
        //             $remainingamount=$wallettotalamount-$amount;
        //             $siteuserdata->wallettotalamount=$remainingamount;// debit for withdrawl from user wallet
        //             $siteuserdata->save();


        //             Walletdetails::create(['siteusers_id'=>$id,'purpose'=>'Withdrawl request','amount'=>$amount,'total'=>$amount,'currencycode'=>'USD','created_at'=>$datetime,'status'=>1]); // store debit information 


        //               /**************************SEND MAIL -start *****************************/

        //             $site = Sitesetting::where(['name' => 'email'])->first();
        //             $admin_users_email = $site->value;


        //             $user_name = $siteuserdata->firstname.' '.$siteuserdata->lastname;
        //             $user_email = $siteuserdata->email;


        //             $subject = "Payment for your withdrawl request from Checkout Saver";
        //             $message_body = "Admin has approved your withdrawl request in Checkout Saver.Your withdrawl amount is ".number_format($amount,2)." $ .";


        //             $mail = Mail::send(['html' => 'frontend.emailtemplate.send_mail'], array('message_body' => $message_body, 'user_name'=>$user_name), function($message) use ($admin_users_email, $user_email,$user_name,$subject)
        //             {
        //             $message->from($admin_users_email,'Checkout Saver');

        //             $message->to($user_email)->subject($subject);
        //             });

        //             /**************************SEND MAIL -end *****************************/ 


        //             }

        //             }

        //             return $output;


        //     }
     
        }
}
?>