<?php namespace App\Http\Controllers\Admin; /* path of this controller*/

use App\Book;

use App\Model\Sitesetting;
use App\Model\Category; /* Model name*/
use App\Model\SubCategory; /* Model name*/
use App\Model\Product; /* Model name*/
use App\Model\Brand; /* Model name*/
use App\Model\BrandCategory; /* Model name*/
use App\Model\WithdrawalHistory; /* Model name*/
use App\Model\Previousemail; /* Model name*/
use App\Model\Module; /* Model name*/
use App\Model\Usersmodules; /* Model name*/
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Model\Notifications;
use App\Model\SiteUser;
use App\Model\User;
use Illuminate\Support\Facades\Request;
use Input; /* For input */
use Validator;
use Session;
use Illuminate\Pagination\Paginator;
use DB;
use Auth;
use Redirect;

use App\Helper\helpers;

use Customhelpers;

class BaseController extends Controller {

	public function __construct() 
    {
    
		

       if(!empty(Auth::id())){
        $userId = Auth::id();
        $Usersmodules=Usersmodules::with('modules','user')->where('users_id',$userId)->get();
        $userdetails=User::find($userId);
        $userrole=$userdetails->role;
        view()->share('Usersmodules', $Usersmodules);
        view()->share('userrole', $userrole);
       }
       
       /* All Site Settings List */

        $request_info  = WithdrawalHistory::where('payment_status','pending')->count();
        
        $sitesettings = DB::table('sitesettings')->get();
        $all_sitesetting = array();
        foreach($sitesettings as $each_sitesetting)
        {
            $all_sitesetting[$each_sitesetting->name] = $each_sitesetting->value; 
        }
        
	    view()->share('all_sitesetting',$all_sitesetting);

        /*Header menu slugs*/
        $this->main_category_slugs = array('clothing','shoes','accessories','watches','sport','grooming','sale');
        /*------------------*/
        view()->share('main_category_slugs',$this->main_category_slugs);
        view()->share('request_info',$request_info);
    }

	public function pr($array) //to print array
	{
		echo "<pre>";
		print_r($array);
	}

    public function  productNewBrandCategory($brand_id="",$category_id=""){
        //Check if the product categry is already to brand      
        if($category_id!=1){

            $brand_cat_cnt = BrandCategory::where('brand_id',$brand_id)->where('category_id', $category_id)->count();
            if($brand_cat_cnt==0){
                BrandCategory::create(['brand_id'=>$brand_id,'category_id'=> $category_id]); 
            }
        }
    }

    public function checkProductExistWithOldBrandCategory($old_brand_id="",$old_category_id=""){
        
        $pro_count = Product::where('brand_id',$old_brand_id)->where('category_id',$old_category_id)->count();

        if($pro_count==0){
            BrandCategory::where('brand_id',$old_brand_id)->where('category_id',$old_category_id)->delete();
        }
                    
    }

    public function mapProductBrandAndCategory($brand_id="",$old_category_id="",$new_category_id=""){
        
        //Check if the product categry is already to brand      
        if($brand_id!=0){

            $brand_cat_cnt = BrandCategory::where('brand_id',$brand_id)->where('category_id', $data['category_id'])->count();
            if($brand_cat_cnt==0){
                //echo $data['category_id'].'if'.$old_product_category.$brand_id;exit;
                BrandCategory::create(['brand_id'=>$brand_id,'category_id'=> $new_category_id]);   

                // Check if any product has same set of category and brand (if not delete that record)
                $pro_count = Product::where('brand_id',$brand_id)->where('category_id',$old_category_id)->count();
                if($pro_count==0)
                    BrandCategory::where('brand_id',$brand_id)->where('category_id',$old_category_id)->delete();

            }
            else{               
                $pro_count = Product::where('brand_id',$brand_id)->where('category_id',$old_category_id)->count();
                //echo $data['category_id'].'else'.$old_product_category.$brand_id;exit;
                if($pro_count==0)
                    BrandCategory::where('brand_id',$brand_id)->where('category_id',$old_category_id)->delete();
            }   
        }
    }

      public function fetchStripSecretAPI(){
        $stripe_info  = Sitesetting::select('value')->where('name','STRIPE_SECRET_API')->first()->toArray();
        return $stripe_info['value'];
      }

      public function fetchStripPublicAPI(){
        $stripe_info  = Sitesetting::select('value')->where('name','STRIPE_PUBLIC_API')->first()->toArray();
        return $stripe_info['value'];
      }

   /********************************************************************************
   *   CHECK USER EXISTS OR NOT AND CHECKING  PREVIOUS EMAIL MUST NOT BE USED                *
   *******************************************************************************/
  function postAdminuserCheck(){
    $data = Request::all();
    
    $datetime= Customhelpers::Returndatetime();

    $where_raw = "1=1";

    $where_raw .= " AND `email` = '".$data['email']."'";
    if($data['hid_user_id']!=""){


       $user=User::find($data['hid_user_id']);
       $dbemailid=$user->email;

       if($dbemailid==$data['email']){

            echo "true"; // When user edit the profile and gives same email id then update with same email id
            exit();
       }
       else{

        $Previousemail_details   =Previousemail::whereRaw($where_raw)->first();
            if(count($Previousemail_details)>0){

                echo "false";
                exit();
            }
            else{

            //Previousemail::create(['email'=>$data['email'],'created_at'=>$datetime]); // If new email does not exist in previous email table then insert 
               
                echo "true";
                exit();
            }

       }

      //$where_raw .= " AND `id`!= '".$data['hid_user_id']."'";
    }

   // $user_details   = User::whereRaw($where_raw)->first();

    //check previous email exist or not

    $Previousemail_details   =Previousemail::whereRaw($where_raw)->first();

    if(count($Previousemail_details)>0){
      echo "false";
      exit();
    }
    else{
     
      echo "true";
      exit();
    }
    
  }  

  function postSiteuserCheck(){
    $data = Request::all();
 
    $datetime= Customhelpers::Returndatetime();

    $where_raw = "1=1";

    $where_raw .= " AND `email` = '".$data['email']."'";

    if($data['hid_user_id']!=""){


      //$where_raw .= " AND `id`!= '".$data['hid_user_id']."'";

       $SiteUser=SiteUser::find($data['hid_user_id']);
       $dbemailid=$SiteUser->email;

       if($dbemailid==$data['email']){

            echo "true"; // When site user edit the profile and gives same email id then update with same email id
            exit();
       }
       else{

        $Previousemail_details   =Previousemail::whereRaw($where_raw)->first();
            if(count($Previousemail_details)>0){

                echo "false";
                exit();
            }
            else{

            
                echo "true";
                exit();
            }

       }


    }

   // $user_details   = User::whereRaw($where_raw)->first();

    //check previous email exist or not

    $Previousemail_details   =Previousemail::whereRaw($where_raw)->first();

    if(count($Previousemail_details)>0){
      echo "false";
      exit();
    }
    else{
      
      echo "true";
      exit();
    }
    
  } 
}
