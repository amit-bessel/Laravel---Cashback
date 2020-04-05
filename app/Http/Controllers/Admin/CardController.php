<?php namespace App\Http\Controllers\Admin; /* path of this controller*/

use App\Model\Sitesetting; /* Model name*/
use App\Model\AdminUserCard; /* Model name*/
use App\Model\LastMinuteHotelPrice;
use App\Model\HomePageLastMinuteHotel;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Request;
use Input; /* For input */
use Validator;
use Session;
use Imagine\Image\Box;
use Image\Image\ImageInterface;
use Illuminate\Pagination\Paginator;
use DB;


require_once('vendor/Stripe/init.php');

use Stripe\Stripe;
use Stripe\Charge;
use Stripe\Customer;
use Stripe\Stripe_CardError;
use Stripe\Stripe_InvalidRequestError;


class CardController extends BaseController {

   /**
    * Display a listing of the resource.
    *
    * @return Response
    */
   public function __construct() {
      parent::__construct();
      //view()->share('sitesetting_class','active');
    }
    
    public function getList(){

      $module_head                  = "Card List";
      $admin_card_details_class     = "active";
      $title                        = "Card List";
      $record_per_page              = 20;
      /*echo "test";
      exit;*/
      $admin_card_details           = AdminUserCard::orderBy('primary_card',1)->paginate($record_per_page);
      
      return view('admin.cards.index',compact(
                            'admin_card_details_class',
                            'module_head',
                            'title',
                            'admin_card_details'
                          )
          );
    }

    public function getAdminCardDetails(){

      $module_head                  = "Card Details";
      $admin_card_details_class     = "active";
      $title                        = "Card Details";
      /*echo "test";
      exit;*/
      return view('admin.cards.card_details',compact(
                            'admin_card_details_class',
                            'module_head',
                            'title'
                          )
          );
    }

    public function postAdminCardDetails(){

      $data = Request::all();
      /*echo "<pre>";
      print_r($data);*/
      
      // Get Access token
      $access_token = Request::header('Access-Token');  
      
      // Get Stripe token
      //$stripe_token = Request::input('stripe_token');
      //Stripe::setApiKey("sk_test_O8Q98yrLmapZDzheBUYMVouj");
      Stripe::setApiKey($this->fetchStripSecretAPI());
      $stripe_token = \Stripe\Token::create(array(
          "card" => array(
            "number" => $data['card_no'],
            "exp_month" => $data['exp_month'],
            "exp_year" => $data['exp_year'],
            "cvc" => $data['exp_cvc'],
            "name" =>  $data['name_on_card']
          )
        ));

      try
          {
        // Get Logged user id
        $user_id = Session::get('login_82e5d2c56bdd0811318f0cf078b78bfc');
        
        // check if the user has atleast one card saved
        $user_card_query = AdminUserCard::select('cust_id','card_name')->where('user_id',$user_id);
        $save_card_cnt = $user_card_query->count();
        
        if($save_card_cnt==0){
          $primary_card = 1;
          
          // Create Customer and get details for that customer
          $customer = Customer::create(array(
            'card'  => $stripe_token
          ));
          
          $customer_id  = $customer->id;
          $card_id    = $customer->default_source;
          $card_type    = $customer->sources->data[0]->brand;
          $last4      = $customer->sources->data[0]->last4;
          $exp_month    = $customer->sources->data[0]->exp_month;
          $card_name    = $customer->sources->data[0]->name;
          $exp_year     = $customer->sources->data[0]->exp_year;
          
        }
        else{
          $primary_card = 0;
          
          // fetch customer id
          $user_card_details = $user_card_query->first()->toArray();
          
          try
              {

            $customer = \Stripe\Customer::retrieve($user_card_details['cust_id']);
            $stripe_card_details = $customer->sources->create(array("source" => $stripe_token));
            //print_r($stripe_card_details->name);exit;
            
            $customer_id  = $user_card_details['cust_id'];
            $card_id    = $stripe_card_details->id;
            $card_type    = $stripe_card_details->brand;
            $last4      = $stripe_card_details->last4;
            $exp_month    = $stripe_card_details->exp_month;
            $card_name    = $stripe_card_details->name;
            $exp_year     = $stripe_card_details->exp_year;
          }
          catch (\Stripe\Error\InvalidRequest $e) 
          {
          // Invalid parameters were supplied to Stripe's API
            $error = $e->getMessage();
            //return response()->json(['status'=>0,'msg' => $error]); 
            Session::flash('success_message', $error); 
            Session::flash('alert-class', 'alert alert-danger'); 
            return redirect('admin/cards/list');
          } 
          catch (\Stripe\Error\Authentication $e) 
          {
            $error = $e->getMessage();
            //return response()->json(['status'=>0,'msg' => $error]);
            Session::flash('success_message', $error); 
            Session::flash('alert-class', 'alert alert-danger'); 
            return redirect('admin/cards/list');
          } 
          catch (\Stripe\Error\ApiConnection $e) 
          {
          // Network communication with Stripe failed
            $error = $e->getMessage();
            //return response()->json(['status'=>0,'msg' => $error]);
            Session::flash('success_message', $error); 
            Session::flash('alert-class', 'alert alert-danger'); 
            return redirect('admin/cards/list');
          } 
          catch (\Stripe\Error\Base $e)
          {
          // Display a very generic error to the user, and maybe send yourself an email
            $error = $e->getMessage();
            //return response()->json(['status'=>0,'msg' => $error]); 
            Session::flash('success_message', $error); 
            Session::flash('alert-class', 'alert alert-danger'); 
            return redirect('admin/cards/list');
          } 
          catch (\Stripe\Error\Api $e) 
          {
          // Stripe's servers are down!
            $error = $e->getMessage();
            //return response()->json(['status'=>0,'msg' => $error]); 
            Session::flash('success_message', $error); 
            Session::flash('alert-class', 'alert alert-danger'); 
            return redirect('admin/cards/list');
          }
          catch (\Stripe\Error\Card $e)
          {
          // Card Was declined
            $error = $e->getMessage();
            //return response()->json(['status'=>0,'msg' => $error]); 
            Session::flash('success_message', $error); 
            Session::flash('alert-class', 'alert alert-danger'); 
            return redirect('admin/cards/list');
          } 
          catch (Exception $e) 
          {
            $error = $e->getMessage();
            //return response()->json(['status'=>0,'msg' => $error]); 
            Session::flash('success_message', $error); 
            Session::flash('alert-class', 'alert alert-danger'); 
            return redirect('admin/cards/list');
          }
        }


        
        //print_r($customer);exit;

        
        // Add card details 
        AdminUserCard::create([
                    'user_id'       => $user_id,
                    'cust_id'       => $customer_id,
                    'card_id'       => $card_id,
                    'primary_card'  => $primary_card,
                    'card_type'     => $card_type,
                    'last4'         => $last4,
                    'exp_month'     => $exp_month,
                    'card_name'     => $card_name,
                    'exp_year'      => $exp_year,
                    'post_date'     => date('Y-m-d H:i:s')
                  ]);
        
        Session::flash('success_message', 'Your card is saved sucessfully.'); 
        Session::flash('alert-class', 'alert alert-success'); 
        return redirect('admin/cards/list');

      }
      catch (\Stripe\Error\InvalidRequest $e) 
      {
      // Invalid parameters were supplied to Stripe's API
        $error = $e->getMessage();
        //return response()->json(['status'=>0,'msg' => $error]); 
        Session::flash('success_message', $error); 
        Session::flash('alert-class', 'alert alert-danger'); 
        return redirect('admin/cards/list');
      } 
      catch (\Stripe\Error\Authentication $e) 
      {
        $error = $e->getMessage();
        //return response()->json(['status'=>0,'msg' => $error]); 
        Session::flash('success_message', $error); 
        Session::flash('alert-class', 'alert alert-danger'); 
        return redirect('admin/cards/list');
      } 
      catch (\Stripe\Error\ApiConnection $e) 
      {
        // Network communication with Stripe failed
        $error = $e->getMessage();
        //return response()->json(['status'=>0,'msg' => $error]); 
        Session::flash('success_message', $error); 
        Session::flash('alert-class', 'alert alert-danger'); 
        return redirect('admin/cards/list');
      } 
      catch (\Stripe\Error\Base $e)
      {
        // Display a very generic error to the user, and maybe send yourself an email
        $error = $e->getMessage();
        //return response()->json(['status'=>0,'msg' => $error]); 
        Session::flash('success_message', $error); 
        Session::flash('alert-class', 'alert alert-danger'); 
        return redirect('admin/cards/list');
      } 
      catch (\Stripe\Error\Api $e) 
      {
        // Stripe's servers are down!
        $error = $e->getMessage();
        //return response()->json(['status'=>0,'msg' => $error]); 
        Session::flash('success_message', $error); 
        Session::flash('alert-class', 'alert alert-danger'); 
        return redirect('admin/cards/list');
      }
      catch (\Stripe\Error\Card $e)
      {
        // Card Was declined
        $error = $e->getMessage();
        //return response()->json(['status'=>0,'msg' => $error]); 
        Session::flash('success_message', $error); 
        Session::flash('alert-class', 'alert alert-danger'); 
        return redirect('admin/cards/list');
      } 
      catch (Exception $e) 
      {
        $error = $e->getMessage();
        //return response()->json(['status'=>0,'msg' => $error]); 
        Session::flash('success_message', $error); 
        Session::flash('alert-class', 'alert alert-danger'); 
        return redirect('admin/cards/list');
      }
      exit;
    } 

    /* Update primary card */
    public function getUpdatePrimaryCard(){
      try
          {
        //Stripe::setApiKey("sk_test_O8Q98yrLmapZDzheBUYMVouj");
        Stripe::setApiKey($this->fetchStripSecretAPI());

        // Get Logged user id
        $user_id = Session::get('login_82e5d2c56bdd0811318f0cf078b78bfc');

        $id = Request::query('id');
        $user_card_dtls = AdminUserCard::select('cust_id','card_id')->where('id',$id)->first()->toArray();
        
        try {
          // Update primary Card in stripe
          $customer = \Stripe\Customer::retrieve($user_card_dtls['cust_id']);
          $customer->default_source=$user_card_dtls['card_id'];
          $customer->save(); 
          
          // Update primary Card in DB      
          AdminUserCard::where('user_id', $user_id)->update(array('primary_card' => 0));    // Update all primary card to 0 for logged in user
          
          AdminUserCard::where('id', $id)->update(array('primary_card' => 1));
          
          Session::flash('success_message', 'Primary card has been changed successfully.'); 
          Session::flash('alert-class', 'alert alert-success'); 
          return redirect('admin/cards/list');
        }
        catch (\Stripe\Error\InvalidRequest $e) 
        {
        // Invalid parameters were supplied to Stripe's API
          $error = $e->getMessage();
          //return response()->json(['status'=>0,'msg' => $error]); 
          Session::flash('success_message', $error); 
          Session::flash('alert-class', 'alert alert-danger'); 
          return redirect('admin/cards/list');
        } 
        catch (\Stripe\Error\Authentication $e) 
        {
          $error = $e->getMessage();
          //return response()->json(['status'=>0,'msg' => $error]);
          Session::flash('success_message', $error); 
          Session::flash('alert-class', 'alert alert-danger'); 
          return redirect('admin/cards/list'); 
        } 
        catch (\Stripe\Error\ApiConnection $e) 
        {
          // Network communication with Stripe failed
          $error = $e->getMessage();
          //return response()->json(['status'=>0,'msg' => $error]); 
          Session::flash('success_message', $error); 
          Session::flash('alert-class', 'alert alert-danger'); 
          return redirect('admin/cards/list');
        } 
        catch (\Stripe\Error\Base $e)
        {
          // Display a very generic error to the user, and maybe send yourself an email
          $error = $e->getMessage();
          //return response()->json(['status'=>0,'msg' => $error]); 
          Session::flash('success_message', $error); 
          Session::flash('alert-class', 'alert alert-danger'); 
          return redirect('admin/cards/list');
        } 
        catch (\Stripe\Error\Api $e) 
        {
          // Stripe's servers are down!
          $error = $e->getMessage();
          //return response()->json(['status'=>0,'msg' => $error]); 
          Session::flash('success_message', $error); 
          Session::flash('alert-class', 'alert alert-danger'); 
          return redirect('admin/cards/list');
        }
        catch (\Stripe\Error\Card $e)
        {
          // Card Was declined
          $error = $e->getMessage();
          //return response()->json(['status'=>0,'msg' => $error]); 
          Session::flash('success_message', $error); 
          Session::flash('alert-class', 'alert alert-danger'); 
          return redirect('admin/cards/list');
        } 
        catch (Exception $e) 
        {
          $error = $e->getMessage();
          //return response()->json(['status'=>0,'msg' => $error]); 
          Session::flash('success_message', $error); 
          Session::flash('alert-class', 'alert alert-danger'); 
          return redirect('admin/cards/list');
        }
        
      }
      catch (Exception $e) 
      {
        $error = $e->getMessage();
        //return response()->json(['status'=>0,'msg' => $error]); 
        Session::flash('success_message', $error); 
        Session::flash('alert-class', 'alert alert-danger'); 
        return redirect('admin/cards/list');
      }
      
    }   

    /* Delete card */
    public function getRemoveCard(){
      try
        {
        //Stripe::setApiKey("sk_test_O8Q98yrLmapZDzheBUYMVouj");
        Stripe::setApiKey($this->fetchStripSecretAPI());
        
        $id = Request::input('id');
        $user_card_dtls = AdminUserCard::select('cust_id','card_id')->where('id',$id)->first()->toArray();
        
        // Delete Card from stripe
        try {
            // Use a Stripe PHP library method that may throw an exception....
          $customer = \Stripe\Customer::retrieve($user_card_dtls['cust_id']);
          $customer->sources->retrieve($user_card_dtls['card_id'])->delete();
          
          // Delete Card from DB
          AdminUserCard::where('id', $id)->delete();
          Session::flash('success_message', 'Your Card is deleted successfully.'); 
          Session::flash('alert-class', 'alert alert-danger'); 
          return redirect('admin/cards/list');

        } 
        catch (\Stripe\Error\InvalidRequest $e) 
        {
          // Invalid parameters were supplied to Stripe's API
          $error = $e->getMessage();
          Session::flash('success_message', $error); 
          Session::flash('alert-class', 'alert alert-danger'); 
          return redirect('admin/cards/list'); 
        } 
        catch (\Stripe\Error\Authentication $e) 
        {
          $error = $e->getMessage();
          Session::flash('success_message', $error); 
          Session::flash('alert-class', 'alert alert-danger'); 
          return redirect('admin/cards/list');
        } 
        catch (\Stripe\Error\ApiConnection $e) 
        {
          // Network communication with Stripe failed
          $error = $e->getMessage();
          Session::flash('success_message', $error); 
          Session::flash('alert-class', 'alert alert-danger'); 
          return redirect('admin/cards/list'); 

        } 
        catch (\Stripe\Error\Base $e)
        {
          // Display a very generic error to the user, and maybe send yourself an email
          $error = $e->getMessage();
          Session::flash('success_message', $error); 
          Session::flash('alert-class', 'alert alert-danger'); 
          return redirect('admin/cards/list'); 
        } 
        catch (\Stripe\Error\Api $e) 
        {
          // Stripe's servers are down!
          $error = $e->getMessage();
          Session::flash('success_message', $error); 
          Session::flash('alert-class', 'alert alert-danger'); 
          return redirect('admin/cards/list'); 

        }
        catch (\Stripe\Error\Card $e)
        {
          // Card Was declined
          $error = $e->getMessage();
          Session::flash('success_message', $error); 
          Session::flash('alert-class', 'alert alert-danger'); 
          return redirect('admin/cards/list');

        } 
        catch (Exception $e) 
        {
          $error = $e->getMessage();
          Session::flash('success_message', $error); 
          Session::flash('alert-class', 'alert alert-danger'); 
          return redirect('admin/cards/list');
        }
      }
      catch (Exception $e) 
      {
        $error = $e->getMessage();
        Session::flash('success_message', $error); 
        Session::flash('alert-class', 'alert alert-danger'); 
        return redirect('admin/cards/list');
      }
      
    }
}
