<?php namespace App\Http\Controllers\Frontend; /* path of this controller*/
// Define Model
use App\Model\Emailnotification; /* Model name*/
use App\Model\Emailnotification_Siteuser; /* Model name*/
use App\Model\User; /* Model name*/
use App\Model\Banner; /* Model name*/
use App\Model\Category;/*Model Name*/
use App\Model\SiteUser;/*Model Name*/
use App\Model\SiteUserReferId;/*Model Name*/
use App\Model\Invitefriend;/*Model Name*/
use App\Model\Userrefer;/*Model Name*/
use App\Model\Walletdetails;/*Model Name*/
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
use App\Model\Vendorcategories;
use App\Model\Vendordetails;


//use Socialize;
use App\Model\Address; 
use App\Model\Previousemail; /* Model name*/

class HomeController extends BaseController {

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

/*==========================================Cj vendor section starts============================================================*/


  /******************************Search autocomplete in header******************************************/

  public function postStoreSearchAutocomplete(){

    $data=Request::all();
    $searchvalue=$data["searchval"];

   // echo "<pre>";
    //print_r($data);exit();

    // $first=Vendorcategories::where("name","like",$searchvalue."%")->where("status",1)->select("name");

    // $searchdata=Vendordetails::where("advertisername","like",$searchvalue."%")->select("advertisername")->union($first)->get();

    $searchdata=Vendorcategories::where("name","like",$searchvalue."%")->where("status",1)->select("name as advertisername")->get();
    
    return view('frontend.home.storesearchautocompleteajax',compact('searchdata'));

  }


  /**************************** Vendor list by category ********************************/

  public function getVendorlist($catid){
    $encodedcatid=$catid;
    $module_head  = "Vendor list";
    $title  = "View Vendor list";
    $catiddecoded=base64_decode($catid);
    $vendorcat=Vendorcategories::where('status',1)->get();
    $name="Checkout Saver";

    $result=Request::all();

    $start='A';
    $end='Z';

    $popularvendordetails=Vendordetails::with('vendorcategories')->where('id','>',0)->where('popularvendor','=',1)->orderBy('advertisername','asc')->get();
    //$vendordetails=Vendordetails::with('vendorcategories')->where('vendorcategories_id',$catiddecoded)->orderBy('advertisername','asc')->get(); 

     for($i=$start;$i<$end;$i++){
     
       $vendordetails[$i]=Vendordetails::with('vendorcategories')->where('advertisername','like',$i."%")->where('vendorcategories_id',$catiddecoded)->where('id','>',0)->orderBy('advertisername','asc')->get();
    }
   $vendordetails[$i]=Vendordetails::with('vendorcategories')->where('advertisername','like',"Z%")->where('vendorcategories_id',$catiddecoded)->where('id','>',0)->orderBy('advertisername','asc')->get();

    /****************Get user details ******************************/

    $userprofileheadinfo=Customhelpers::getUserDetails();

    return view('frontend.home.allstores',compact('vendordetails','module_head','title','vendorcat','name','encodedcatid','popularvendordetails','userprofileheadinfo'));
  }

  /********************************* all store list ************************************/

  public function getAllstores(){

    $encodedcatid='';
    $module_head  = "Vendor list";
    $title  = "View Vendor list";

    $vendorcat=Vendorcategories::where('status',1)->get();
    $name1="cashback";

    $result=Request::all();

    //============User serarch in header autocomplete=============

    if(!empty($result["user-search-value"]))
    {

            $usersearchvalue=$result["user-search-value"];


            $catcount=Vendorcategories::where("name",'like', $usersearchvalue."%")->where('status',1)->count();
            if($catcount>0)
            {

              $catdetails=Vendorcategories::where("name",'like',$usersearchvalue."%")->where('status',1)->get();
              foreach ($catdetails as $key => $value) 
              {
              $vendorcatid=$value->id;
              }

              $whereheadraw=("( advertisername like '$usersearchvalue%' or vendorcategories_id ='$vendorcatid') "); 
            }
            else
            {
              $whereheadraw=("( advertisername like '$usersearchvalue%') ");
            }


    }
    else{
      $usersearchvalue="";
    }
    
    

    $start='A';
    $end='Z';

    $popularvendordetails=Vendordetails::with('vendorcategories')->where('id','>',0)->where('popularvendor','=',1)->orderBy('advertisername','asc')->get();

    $catid='';
    /****************Get user details ******************************/

    $userprofileheadinfo=Customhelpers::getUserDetails();

    for($i=$start;$i<$end;$i++){
     


      if(!empty($usersearchvalue))
      {

             $vendordetails[$i]=Vendordetails::with('vendorcategories')->where('advertisername','like',$i."%")->where('id','>',0)->whereRaw($whereheadraw)->orderBy('advertisername','asc')->get();


      }
      else{

        $vendordetails[$i]=Vendordetails::with('vendorcategories')->where('advertisername','like',$i."%")->where('id','>',0)->orderBy('advertisername','asc')->get();
      }

       
    }



  if(!empty($usersearchvalue))
  {

             $vendordetails[$i]=Vendordetails::with('vendorcategories')->where('advertisername','like',"Z%")->where('id','>',0)->whereRaw($whereheadraw)->orderBy('advertisername','asc')->get();


  }

  else{
    $vendordetails[$i]=Vendordetails::with('vendorcategories')->where('advertisername','like',"Z%")->where('id','>',0)->orderBy('advertisername','asc')->get();
  }



    return view('frontend.home.allstores',compact('vendordetails','module_head','title','vendorcat','name1','popularvendordetails','catid','encodedcatid','userprofileheadinfo'));
  }


  public function getStoreDetails($id,$vendorname){

    $module_head  = "Vendor details";
    $title  = "View Vendor details";

    $userprofileheadinfo=Customhelpers::getUserDetails();

    $store_details = Vendordetails::where('id',$id)->where('is_deleted',0)->first();
    if(count($store_details) > 0){
      $store_details = $store_details->toArray();
    }
    $similar_vendors = Vendordetails::where('vendorcategories_id',$store_details['vendorcategories_id'])->where('is_deleted',0)->where('id','!=',$id)->get();
    if(count($similar_vendors) > 0){
      $similar_vendors = $similar_vendors->toArray();
    }

    $vendor_details_left_content = Sitesetting::where("name","vendor_details_left_content")->where("not_visible",0)->get();
    /*echo '<pre>';
    print_r($similar_vendors);
    exit();*/


    return view('frontend.home.storedetails',compact('id','module_head','title','userprofileheadinfo','store_details','similar_vendors','vendor_details_left_content'));

  }

  /************************************* search by letter and word with sorting *************************************/

  public  function getSearchByLetter(){

  $data=Request::all();

  $searchbyletter=ucfirst($data['searchbyletter']);
  $searchbyword=trim($data['searchbyword']);
  $usersearchvalue=trim($data['usersearchvalue']);
  $searchtype=$data['searchtype'];
  $catid=$data['catid'];
  $sortdata=$data['sortdata'];


if(!empty($usersearchvalue)){

        $catcount=Vendorcategories::where("name",'like', $usersearchvalue."%")->where('status',1)->count();
            if($catcount>0){

              $catdetails=Vendorcategories::where("name",'like',$usersearchvalue."%")->where('status',1)->get();
              foreach ($catdetails as $key => $value) {
              $vendorcatid=$value->id;
              }

              //$whererawshd=("( advertisername like '$usersearchvalue%' or vendorcategories_id ='$vendorcatid') "); 
              $whererawshd=("( vendorcategories_id ='$vendorcatid') "); 
            }
            else{
              //$whererawshd=("( advertisername like '$usersearchvalue%') ");
            }
      }
      else{
        $whererawshd=("( id !='' ) ");
      }

  if($searchbyletter=='All'){
    
    if($searchbyword!=''){

        $catcount=Vendorcategories::where("name",'like', $searchbyword."%")->where('status',1)->count();
            if($catcount>0){

              $catdetails=Vendorcategories::where("name",'like',$searchbyword."%")->where('status',1)->get();
              foreach ($catdetails as $key => $value) {
              $vendorcatid=$value->id;
              }

              $whereraw=("( advertisername like '$searchbyword%' or vendorcategories_id ='$vendorcatid') "); 
            }
            else{
              $whereraw=("( advertisername like '$searchbyword%') ");
            }
      }
      else{
        $whereraw=("( id !='' ) ");
      }

      

    if($catid!=''){
      $decodedcatid=base64_decode($catid);
      if($sortdata=='atoz'){

            $start='A';
            $end='Z';  
            for($i=$start;$i<$end;$i++){

                $vendordetails[$i]=Vendordetails::with('vendorcategories')->where('id','>',0)->where('vendorcategories_id',$decodedcatid)->where('advertisername','like',$i."%")->whereRaw($whereraw)->orderBy('advertisername','asc')->get();
              }
              $vendordetails[$i]=Vendordetails::with('vendorcategories')->where('id','>',0)->where('vendorcategories_id',$decodedcatid)->where('advertisername','like',"Z%")->whereRaw($whereraw)->orderBy('advertisername','asc')->get();
       }
       else if($sortdata=='salecommission'){


                $vendordetails=Vendordetails::with('vendorcategories')->where('id','>',0)->where('vendorcategories_id',$decodedcatid)->whereRaw($whereraw)->orderBy('salecommission_orderby','desc')->get();
                

       }
    }
    else{
       if($sortdata=='atoz'){

            $start='A';
            $end='Z'; 
            for($i=$start;$i<$end;$i++){

              $vendordetails[$i]=Vendordetails::with('vendorcategories')->where('id','>',0)->whereRaw($whereraw)->whereRaw($whererawshd)->where('advertisername','like',$i."%")->orderBy('advertisername','asc')->get();
            }
            $vendordetails[$i]=Vendordetails::with('vendorcategories')->where('id','>',0)->whereRaw($whereraw)->whereRaw($whererawshd)->where('advertisername','like',"Z%")->orderBy('advertisername','asc')->get();
      }
       else if($sortdata=='salecommission'){

            

              $vendordetails=Vendordetails::with('vendorcategories')->where('id','>',0)->whereRaw($whereraw)->whereRaw($whererawshd)->orderBy('salecommission_orderby','desc')->get();
            
            
       }
    }

    
  }
  else{

    
    

      if($searchbyletter!='' && $searchbyword!=''){

            $catcount=Vendorcategories::where("name",'like', $searchbyword."%")->where('status',1)->count();
            if($catcount>0){

              $catdetails=Vendorcategories::where("name",'like',$searchbyword."%")->where('status',1)->get();
              foreach ($catdetails as $key => $value) {
              $vendorcatid=$value->id;
              }

              $whereraw=("advertisername like '$searchbyletter%' and ( advertisername like '$searchbyword%' or vendorcategories_id ='$vendorcatid') "); 
            }
            else{
              $whereraw=("advertisername like '$searchbyletter%' and ( advertisername like '$searchbyword%') ");
            }

        
      }
      else if($searchbyletter!=''){

        $whereraw=("advertisername like '$searchbyletter%'");
      }



      if($catid!=''){

        $decodedcatid=base64_decode($catid);
        if($sortdata=='atoz'){

            $start='A';
            $end='Z';
  
           for($i=$start;$i<$end;$i++){

            if($searchbyletter==$i){

            $vendordetails[$i]=Vendordetails::with('vendorcategories')->where('id','>',0)->where('advertisername','like',$i."%")->whereRaw($whereraw)->where('vendorcategories_id',$decodedcatid)->orderBy('advertisername','asc')->get();

              }
           }
           if($searchbyletter==$i){

           $vendordetails[$i]=Vendordetails::with('vendorcategories')->where('id','>',0)->where('advertisername','like',"Z%")->whereRaw($whereraw)->where('vendorcategories_id',$decodedcatid)->orderBy('advertisername','asc')->get();
            }

         }
         else if($sortdata=='salecommission'){

          $start='A';
          $end='Z';

          for($i=$start;$i<$end;$i++){

            

              $vendordetails=Vendordetails::with('vendorcategories')->where('id','>',0)->whereRaw($whereraw)->where('vendorcategories_id',$decodedcatid)->orderBy('salecommission_orderby','desc')->get();
               
             }
             
         }

      }
      else{
        if($sortdata=='atoz'){

          $start='A';
          $end='Z';

          for($i=$start;$i<$end;$i++){


           if($searchbyletter==$i){

            $vendordetails[$i]=Vendordetails::with('vendorcategories')->where('id','>',0)->where('advertisername','like',$i."%")->whereRaw($whereraw)->whereRaw($whererawshd)->orderBy('advertisername','asc')->get();
           } 
          
          }

          if($searchbyletter==$i){

          $vendordetails[$i]=Vendordetails::with('vendorcategories')->where('id','>',0)->where('advertisername','like',"Z%")->whereRaw($whererawshd)->whereRaw($whereraw)->orderBy('advertisername','asc')->get();
          }
          

        }
        else if($sortdata=='salecommission'){

          $start='A';
          $end='Z';

          

          

          $vendordetails=Vendordetails::with('vendorcategories')->where('id','>',0)->whereRaw($whereraw)->whereRaw($whererawshd)->orderBy('salecommission_orderby','desc')->get();
            

           
          
        }
        
        
      }

        

    

  
  }
    return view('frontend.home.allstoresajax',compact('vendordetails','searchbyletter','searchbyword','sortdata'));

   //return response()->json(array('vendordetails'=> $vendordetails), 200);
  }

/*==========================================Cj vendor section ends============================================================*/








  /* search by name */

 /* public  function getSearchByName(){

  $data=Request::all();

  $searchdata=$data['searchresult'];
  $searchtype=$data['searchtype'];
  $catid=$data['catid'];
  $catcount=Vendorcategories::where("name",'like', $searchdata."%")->where('status',1)->count();
  if($catcount>0){

      $catdetails=Vendorcategories::where("name",'like',$searchdata."%")->where('status',1)->get();
      foreach ($catdetails as $key => $value) {
      $vendorcatid=$value->id;
      }
      if($catid!=''){
        $decodedcatid=base64_decode($catid);

        $whereraw=("vendorcategories_id='$decodedcatid' and ( advertisername like '$searchdata%'  or vendorcategories_id ='$vendorcatid' ) ");
        $vendordetails=Vendordetails::with('vendorcategories')->WhereRaw($whereraw)->orderBy('advertisername','asc')->get();

      }
      else{
        $vendordetails=Vendordetails::with('vendorcategories')->where('id','>',0)->where('advertisername','like',$searchdata.'%')->orWhere('vendorcategories_id',$catid)->orderBy('advertisername','asc')->get();
      }
      

  
  }
  else{
    if($catid!=''){
      $decodedcatid=base64_decode($catid);
      $vendordetails=Vendordetails::with('vendorcategories')->where('id','>',0)->where('advertisername','like',$searchdata.'%')->where('vendorcategories_id',$decodedcatid)->orderBy('advertisername','asc')->get();
    }
    else{
      $vendordetails=Vendordetails::with('vendorcategories')->where('id','>',0)->where('advertisername','like',$searchdata.'%')->orderBy('advertisername','asc')->get();
    }
    
 
  }

  

   return response()->json(array('vendordetails'=> $vendordetails), 200);
  }
*/






/********************* Index page *****************************************/

   public function index(){


      /*$sessionvalue = Session::get('menwomen');
      $popular_products = Product::whereHas('product_category',function($q) use ($sessionvalue){
                                                $q->whereIn('gender_cat',array($sessionvalue,3));
                                        })->where('popular_product',1)->get();
      
      $home_page_banner_details = HomePageDetail::where('gender',$sessionvalue)->get();
      if(count($home_page_banner_details)>0){
        $home_page_banner_details = $home_page_banner_details->toArray();
      }
      
    
      $home_page_sliders = Topbanner::where('is_active',1)->get();
      
      if(count($home_page_sliders)>0){
        $home_page_sliders = $home_page_sliders->toArray();
      }
    
      $our_clients = Vendor::orderBy('percentage','DESC')->limit(8)->get();
      if(count($our_clients)>0){
        $our_clients = $our_clients->toArray();
      }
     

      return view('frontend.home.home',compact('sessionvalue','popular_products','home_page_banner_details','our_clients','home_page_sliders'));*/
      
 

      /*

    $module_head  = "Vendor list";
    $title  = "View Vendor list";

    $vendorcat=Vendorcategories::where('status',1)->get();
    $name1="cashback";

    $result=Request::all();
    

      
      if(!empty($result['searchresult'])){
         $name=trim($result['searchresult']);

         $catcount=Vendorcategories::where("name",'like', $name."%")->count();
         if($catcount>0){

          $catdetails=Vendorcategories::where("name",'like',$name."%")->get();
          foreach ($catdetails as $key => $value) {
            $catid=$value->id;
          }
          
          $whereraw=("id >0 and ( advertisername like '$name%'  or vendorcategories_id ='$catid' ) ");
         }
         else{
          $whereraw=("id >0 and ( advertisername like '$name%'  ) ");
         }

          
        $vendordetails=Vendordetails::with('vendorcategories')->WhereRaw($whereraw)->paginate(1);
        $pagination = $vendordetails->appends ( array (
            'searchresult' => Input::get ( 'searchresult' ) ,
            
            ) );
      }
      
    
    else{

      $vendordetails=Vendordetails::with('vendorcategories')->where('id','>',0)->where('api','cj')->paginate(10);
    }
    */

    if (Session::has('user_id')){

      /****************Get user details ******************************/

        $userprofileheadinfo=Customhelpers::getUserDetails();

    }
    else{
         $userprofileheadinfo='';
    }

    /******************List of popular vendor*********************************/

    $popularvendordetails=Vendordetails::with('vendorcategories')->where('id','>',0)->where('popularvendor','=',1)->orderBy('advertisername','asc')->get();


    /*****************************Data from site setting table ********************************************/

    $data["sitesettings"]["homebannertitle"]=Sitesetting::where("name","homebannertitle")->where("not_visible",0)->get();
    $data["sitesettings"]["homebannerdescription"]=Sitesetting::where("name","homebannerdescription")->where("not_visible",0)->get();
    
    $data["sitesettings"]["homesignupdescription"]=Sitesetting::where("name","homesignupdescription")->where("not_visible",0)->get();
    $data["sitesettings"]["homesignuptitle"]=Sitesetting::where("name","homesignuptitle")->where("not_visible",0)->get();

    $data["sitesettings"]["homeprivatecouponsdescription"]=Sitesetting::where("name","homeprivatecouponsdescription")->where("not_visible",0)->get();
    $data["sitesettings"]["homeprivatecouponstitle"]=Sitesetting::where("name","homeprivatecouponstitle")->where("not_visible",0)->get();

    $data["sitesettings"]["homegiftcardsdescription"]=Sitesetting::where("name","homegiftcardsdescription")->where("not_visible",0)->get();
    $data["sitesettings"]["homegiftcardstitle"]=Sitesetting::where("name","homegiftcardstitle")->where("not_visible",0)->get();

    $data["sitesettings"]["homemakemoneydescription"]=Sitesetting::where("name","homemakemoneydescription")->where("not_visible",0)->get();
    $data["sitesettings"]["homemakemoneytitle"]=Sitesetting::where("name","homemakemoneytitle")->where("not_visible",0)->get();

    $data["sitesettings"]["homelifetimecommissiondescription"]=Sitesetting::where("name","homelifetimecommissiondescription")->where("not_visible",0)->get();
    $data["sitesettings"]["homelifetimecommissiontitle"]=Sitesetting::where("name","homelifetimecommissiontitle")->where("not_visible",0)->get();


    $data["sitesettings"]["homebannerunderprivatecouponsdescription"]=Sitesetting::where("name","homebannerunderprivatecouponsdescription")->where("not_visible",0)->get();
    $data["sitesettings"]["homebannerunderprivatecouponstitle"]=Sitesetting::where("name","homebannerunderprivatecouponstitle")->where("not_visible",0)->get();

     $data["sitesettings"]["homebannerundergiftcardsdescription"]=Sitesetting::where("name","homebannerundergiftcardsdescription")->where("not_visible",0)->get();
    $data["sitesettings"]["homebannerundergiftcardstitle"]=Sitesetting::where("name","homebannerundergiftcardstitle")->where("not_visible",0)->get();


    $data["sitesettings"]["homebannerundercashbackdescription"]=Sitesetting::where("name","homebannerundercashbackdescription")->where("not_visible",0)->get();
    $data["sitesettings"]["homebannerundercashbacktitle"]=Sitesetting::where("name","homebannerundercashbacktitle")->where("not_visible",0)->get();


     $data["sitesettings"]["homebannerundersavemoneydescription"]=Sitesetting::where("name","homebannerundersavemoneydescription")->where("not_visible",0)->get();
    $data["sitesettings"]["homebannerundersavemoneytitle"]=Sitesetting::where("name","homebannerundersavemoneytitle")->where("not_visible",0)->get();

    $data["sitesettings"]["homebannerlearnmorelink"]=Sitesetting::where("name","homebannerlearnmorelink")->where("not_visible",0)->get();
    
    //return view('frontend.home.index',compact('vendordetails','module_head','title','vendorcat','name1','catid'));

      //return view('frontend.home.index',compact('name','vendorcat'));


    /*******************************Banner images**********************************************/

    $banner=Banner::where("page_name","Home")->where("is_deletable",'0')->get();


    return view('frontend.home.indexfinal',compact('popularvendordetails','data','userprofileheadinfo','banner'));
    

   }

   /******************** User registration ********************************/

   public function getSignupUser($buy_url="")
   {

         $refereduser = 0;
         
        /* If super affiliate code share by admin is already used in front end */
          if(!empty($_GET['shareutype'])&& !empty($_GET['scd'])){

           $shareutype = ($_GET['shareutype']);
           $encodedsuperaffiliatecode = ($_GET['scd']);
           $superaffiliatecode=base64_decode($encodedsuperaffiliatecode);
           $codecnt=Userrefer::where('refercode',$superaffiliatecode)->count(); // Check refer code exist or not in db

           if($codecnt==0){

              //nothing
              
           }
           else{

                $link=url('')."/signup?shareutype=".$shareutype."&scd=".$encodedsuperaffiliatecode;
                Session::flash('failure_message', 'You have alraedy used this  refer code.');
                return redirect('/login');
           }

         }

    /*If link share by user is already used in frontend */
    if(!empty($_GET['linkurl'])&& !empty($_GET['tk'])){

        $shareremembertoken = $_GET['tk'];
        
        $tokencount=Invitefriend::where('remember_token',$shareremembertoken)->count();

        if($tokencount==0){
          Session::flash('failure_message', 'This link is already used.');
          return redirect('/login');

        }
      
    }
      /////////////// added by surajit  start//////////////////
    if(!empty($buy_url) && !empty($_GET['tk'])){
        $shareremembertoken = $_GET['tk'];
        $tokencount=Invitefriend::where('remember_token',$shareremembertoken)->count();
        if($tokencount==0){
          Session::flash('failure_message', 'This link is already used.');
          return redirect('/login');
        }
      }

      if(!empty($buy_url) && !empty($_GET['tk'])){
      $actual_link = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
      $shareurl = $actual_link;
      $refercode = $buy_url;
      $remembertoken = $_GET['tk'];
      $refereduser = 1;
      Session::put('refercode',$refercode);
      Session::put('shareurl',$shareurl);
      Session::put('shareremembertoken',$remembertoken);
    }
    if(!empty($buy_url)){
      $actual_link = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
      $shareurl = $actual_link;
      $refercode = $buy_url;
      $refereduser = 1;
      Session::put('refercode',$refercode);
      Session::put('shareurl',$shareurl);
    }



      /////////////////////// Added by surajit end ////////////////////
      /* Put in session link share by user in frontend */
    
    if(!empty($_GET['linkurl']) && !empty($_GET['tk'])){
      $actual_link = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
      $shareurl=$actual_link;
      $linkurl=$_GET['linkurl'];
      $remembertoken=$_GET['tk'];
      Session::put('linkurl',$linkurl);
      Session::put('shareurl',$shareurl);
      Session::put('shareremembertoken',$remembertoken);
    }

    /*  Put in session super affiliate code share by admin */
    if(!empty($_GET['shareutype']) && !empty($_GET['scd'])){
      $shareutype=$_GET['shareutype'];
      $scd=$_GET['scd'];
      Session::put('shareutype',$shareutype);
      Session::put('scd',$scd);
    }
      //$result = Country::all();
     if (Session::has('user_id'))
      {
        return redirect('user/my-profile');
      }
      $result =""; 
      $title="Checkout Saver";


      /***************Call getfbgoogleidfromdb () function to Get fb id and google id from database**************************/

      $sitesettingsar=$this->getfbgoogleidfromdb();
      $fbappid=$sitesettingsar["fbappid"];
      $googleclient_id=$sitesettingsar["googleclient_id"];
      $googlecookiepolicy=$sitesettingsar["googlecookiepolicy"];
      

      /**********************Added new code by Gopi**************************************/
      if(!empty($refercode)){
         $refercode=$refercode;
      }
      else{
        $refercode="";
      }
     
      $countsiteUserreferid=SiteUserReferId::where('referid',$refercode)->where('status',1)->count();
      if($countsiteUserreferid>0){

        $siteuserreferdetails=SiteUserReferId::where('referid',$refercode)->where('status',1)->get();
        $siteuserid=$siteuserreferdetails[0]->siteuser_id;

        $siteusercount=SiteUser::where("is_deleted",0)->where("status",1)->where("id",$siteuserid)->count();

        if($siteusercount>0){

          $siteuserdetails=SiteUser::where("is_deleted",0)->where("status",1)->where("id",$siteuserid)->get();
        }
        else{
          $siteuserdetails=array();

        }


      }
      else{
        $siteuserdetails=array();
        $siteusercount=0;
      }

		  return view('frontend.home.registration', compact('result','buy_url','title','fbappid','googleclient_id','googlecookiepolicy','refereduser','siteusercount','siteuserdetails'));
   }

   /******************Comming Soon**************************/

   public function commingsoon(){

    $title='Comming Soon';

    return view('frontend.home.commingsoon',compact('title'));

   }


   /**************** User Login ************************/

   public function getLogin($buy_url="")
   {
      if(!empty($_GET['userbuy']) && !empty($_GET['utid'])){

        $userbuy=$_GET['userbuy'];
        $utid=$_GET['utid'];
        Session::put('userbuy',$userbuy);
        Session::put('utid',$utid);
      }
      
     
      if (Session::has('user_id'))
      {
        
        return redirect('user/my-profile');
      }
      
              
      if(Session::has('buy_url')){
          $buy_url = (Session::get('buy_url'));
          Session::forget('buy_url');
      }

      $title="User Login";
      $data='';

      /***************Call getfbgoogleidfromdb () function to Get fb id and google id from database**************************/

      $sitesettingsar=$this->getfbgoogleidfromdb();
      $fbappid=$sitesettingsar["fbappid"];
      $googleclient_id=$sitesettingsar["googleclient_id"];
      $googlecookiepolicy=$sitesettingsar["googlecookiepolicy"];
      

		return view('frontend.home.login',compact('buy_url','title','userbuy','fbappid','googleclient_id','googlecookiepolicy'));
   }

   /***************Get fb id and google id from database**************************/

   public function getfbgoogleidfromdb(){




      $fbappiddata=Sitesetting::where("name","fbappid")->where("not_visible",'0')->get();
      $fbappidcount=Sitesetting::where("name","fbappid")->where("not_visible",'0')->count();
      if($fbappidcount>0){
        $fbappid=$fbappiddata[0]->value;
      }
      else{
        $fbappid="";
      }

      $googleclient_iddata=Sitesetting::where("name","googleclient_id")->where("not_visible",'0')->get();
      $googleclient_idcount=Sitesetting::where("name","googleclient_id")->where("not_visible",'0')->count();
      if($googleclient_idcount>0){

         $googleclient_id=$googleclient_iddata[0]->value;
      }
      
      $googlecookiepolicydata=Sitesetting::where("name","googlecookiepolicy")->where("not_visible",'0')->get();
      $googlecookiepolicycount=Sitesetting::where("name","googlecookiepolicy")->where("not_visible",'0')->count();
      if($googlecookiepolicycount>0){
         $googlecookiepolicy=$googlecookiepolicydata[0]->value;
      }

      return array("fbappid"=>$fbappid,"googleclient_id"=>$googleclient_id,"googlecookiepolicy"=>$googlecookiepolicy);

   }

   public function getForgotPassword()
   {
		return view('frontend.home.forgot_password');
   }


   /*************************My profile************************************/

    public function  my_profile()
   {
      $this->authenticateUser();
      if (Session::has('user_id')){
        $pd_id = Session::get('product_id');
        if($pd_id!=''){
          return redirect('product/click/'.$pd_id);
        }
        $vendor_id = Session::get('vendor_id');
        if($vendor_id!=''){
          return redirect('stores/click/'.$vendor_id);
        }
      
        $result='';

        /**********************Email notification on or off by user***************************/

        $emailnotification=Emailnotification_Siteuser::with('emailnotifications')->where("siteusers_id",Session::get('user_id'))->get();
        $emailnotificationcount=Emailnotification_Siteuser::with('emailnotifications')->where("siteusers_id",Session::get('user_id'))->count();

        $is_user_exists = SiteUser::where('id','=',Session::get('user_id'))->where('is_deleted','=','0')->where('status','=','1')->first();

        $SiteUserReferId=SiteUserReferId::where("siteuser_id",Session::get('user_id'))->get();

        $datetime= Customhelpers::Returndatetime(); 

        $sitesettings['info']['profilewithdrawdetailsdescription']=Sitesetting::where("name","profilewithdrawdetailsdescription")->where('not_visible'
          ,'0')->get();
        $sitesettings['info']['profilewithdrawdetailstitle']=Sitesetting::where("name","profilewithdrawdetailstitle")->where('not_visible'
          ,'0')->get();

        /****************Get user details ******************************/

        $userprofileheadinfo=Customhelpers::getUserDetails();
        return view('frontend.home.user-profile-personal',compact('is_user_exists','result','SiteUserReferId','userprofileheadinfo','datetime','sitesettings','emailnotification','emailnotificationcount'));
      }
      else{
       return redirect('login');
      }
    
   }

   /***********************Change email notification on or off in profile page***********************/

   public function changeEmailNotification(){

     if (Session::has('user_id'))
      {

            $data=Request::all();

            //echo "<pre>";
            //print_r($data);

            $flag=0;

            $datetime= Customhelpers::Returndatetime(); 

            $siteuserid=Session::get('user_id');

            if(!empty($data['notify'])){

                Emailnotification_Siteuser::where("siteusers_id",Session::get('user_id'))->delete();

                foreach ($data['notify'] as $key => $value) {

                  $notifydata=explode(",", $value);
                  $slug=$notifydata[0];
                  $status=$notifydata[1];
                  $emailnotify=Emailnotification::where("slug",$slug)->where("status",1)->get();
                  $emailnotifications_id=$emailnotify[0]->id;
                
                  Emailnotification_Siteuser::create(['siteusers_id'=>$siteuserid,'emailnotifications_id'=>$emailnotifications_id,'status'=>$status,'updated_at'=>$datetime]);
                   $flag=1;

                }
                if($flag){

                  Session::flash('success_message', 'You have successfully changed email notification status.');
                  return redirect('user/my-profile');
                }
                else{
                  Session::flash('failure_message', 'There is some problem.');
                  return redirect('user/my-profile');
                }
                

            }
            else
            {
                  Session::flash('failure_message', 'There is some problem.');
                  return redirect('user/my-profile');
            }


            
      }
      else
      {
         return redirect('login');
      }

   }


   public function  transaction()
   {
    $this->authenticateUser();
     if (Session::has('user_id')){
        
        $is_user_exists = SiteUser::where('id','=',Session::get('user_id'))->where('is_deleted','=','0')->first();
        return view('frontend.home.user-profile-passbook-purchased',compact('is_user_exists'));
     }
     else{
       return redirect('login');
     }
    
   }

   public function cashback()
   {
      $this->authenticateUser();
     if (Session::has('user_id')){  
        
        $is_user_exists = SiteUser::where('id','=',Session::get('user_id'))->where('is_deleted','=','0')->first();
        $is_user_banck_details = SiteUserBankAccount::where('site_user_id','=',$is_user_exists['id'])->first();
       // print_r($is_user_exists['id']); 
        return view('frontend.home.user-profile-cashbackbalance',compact('is_user_exists','is_user_banck_details'));
     }
     else{
       return redirect('login');
     }
    
   }
  
   public function getCheckEmailAvailability()
   {
    $data = Request::all();
    $email = $data['email'];
    if($data['id'] == "")
    echo $user_arr_count = SiteUser::where("email","=",$email)->where("is_deleted","=",'0')->get()->count();
    else
    echo $user_arr_count = SiteUser::where("email","=",$email)->where("id","!=",$data['id'])->where("is_deleted","=",'0')->get()->count();
    exit();
   }

   /*****************************User registration ********************************/

   function postAddUser() 
   {
    $data = Request::all();

    /*echo "<pre>";
    print_r($data);
    exit;*/

    if(Request::isMethod('post'))
    {
      
      $datetime= Customhelpers::Returndatetime(); 
      $remember_token = uniqid();

      if(!empty($data['rcv_Nws'])){
         $rcv_Nws=$data['rcv_Nws'];
      }
      else{
         $rcv_Nws=0;
      }
      
      $data['name'] = str_replace(' ', '', $data['name']);  // replace space from first name

      $user = SiteUser::create([
                  

                  'firstname' => $data['name'],
                  'lastname'  =>  $data['last_name'],
                  //'address'  =>  $data['address'],
                  //'country'  =>  $data['country'],
                  //'city'  =>  $data['city'],
                  //'state'  =>  $data['state'],
                  //'zipcode'  =>  $data['zipcode'],
                  //'phoneno'  =>  $data['phone'],
                  'email'  =>  $data['email'],
                  'password'    => Hash::make($data['password']),
                  //'dob'=>$data['dob'],
                  'created_at' => $datetime,
                  'remember_token' => $remember_token,
                  'newsletterstatus'=>$rcv_Nws
                  

      ]);
      $insertedId = $user->id;
      $encodedinsertid=base64_encode($insertedId);



      //add the email into previous email table

        $count=Previousemail::where('email',$data['email'])->count();

        if($count==0){

            Previousemail::create(['email'=>$data['email'],'created_at'=>$datetime]);

        }

      $unique_id=uniqid();
      $uniquereferid=$data['name'].$unique_id;

      //$link=url('')."/signup?linkurl=".$encodedinsertid;

      /* update user share link */
      
      $link=url('')."/r/".$uniquereferid;
     
      
      $SiteUser=SiteUser::find($insertedId);
      $SiteUser->sharelink=$link;
      $SiteUser->save();

      // System auto generate unique refer code for user insert into table 
       
      SiteUserReferId::create(['siteuser_id'=>$insertedId,'referid'=>$uniquereferid,'refercode'=>$uniquereferid,'created_at' => $datetime]);

      $refercode=$data['refercode'];

      /* =======================Used when admin shares a super affiliate code =======================*/

      $flag=1;

        if(Session::has('shareutype') && Session::has('scd'))
        {
        
        
           $shareutype = (Session::get('shareutype'));
           $encodedsuperaffiliatecode = (Session::get('scd'));
           $superaffiliatecode=base64_decode($encodedsuperaffiliatecode);
           $codecnt=Userrefer::where('refercode',$superaffiliatecode)->count(); // Check refer code exist or not in db
        
             if($codecnt==0)
             {

                    // Check if user gives correct refer code
                  if($superaffiliatecode==$refercode)
                  {

                    $referby=base64_decode($shareutype);
                    $role=User::find($referby)->role;

                    Userrefer::create(['refercode'=>$refercode,'referto'=>$insertedId,'status'=>0,'created_at' => $datetime,'referby'=>$referby,'usertype'=>$role]);

                    Invitefriend::where("refercode",$refercode)->update(['status'=>1,'remember_token'=>'']);


                    $SiteUser=SiteUser::find($insertedId);

                    $walletamount=$SiteUser->wallettotalamount+5; // add bonus $5 when user uses the refer code
                    $SiteUser->wallettotalamount=$walletamount; // update the wallet amount

                    $SiteUser->superaffiliateuser=1;// Set a user is super affiliate 
                    $SiteUser->save();

                    Walletdetails::create(['siteusers_id'=>$insertedId,'purpose'=>'Bonus for using super affiliate code at the time of registration','amount'=>5,'total'=>5,'status'=>1,'currencycode'=>'USD','exchangerate'=>0,'type'=>1,'created_at'=>$datetime,'walletstatus'=>1,'purpose_state'=>1]); // insert into wallet details table as a type=1 means credit

                    Session::forget('shareutype');
                    Session::forget('scd');

                }
                else
                {
                    $flag=0;
                    $link=url('')."/signup?shareutype=".$shareutype."&scd=".$encodedsuperaffiliatecode;
                    Session::flash('failure_message', 'You have entered wrong refer code.');
                    SiteUser::find($insertedId)->delete();
                    return redirect($link);
                }
           }
           else
           {

                $link=url('')."/signup?shareutype=".$shareutype."&scd=".$encodedsuperaffiliatecode;
                Session::flash('failure_message', 'You have alraedy used this  refer code.');
                SiteUser::find($insertedId)->delete();
                return redirect("/login");
           }

           
           
        

      }
      /*************************Added by gopi****************************/

      /********************  Used when user enters  refer code not using any link ********************/

      else if(!empty($refercode)){
   
        $refercount=SiteUserReferId::where('referid',$refercode)->where('status','1')->count(); // If refer code is correct
        if($refercount>0)
        {

               $referdata=SiteUserReferId::where('referid',$refercode)->where('status','1')->first();
               $referby= $referdata->siteuser_id;
              Userrefer::create(['refercode'=>$refercode,'referto'=>$insertedId,'status'=>0,'created_at' => $datetime,'referby'=>$referby]);

              $SiteUserReferBy=SiteUser::find($referby);
              $superaffiliatestatus=$SiteUserReferBy->superaffiliateuser;

              if($superaffiliatestatus==1){

                $SiteUser=SiteUser::find($insertedId);

                $walletamount=$SiteUser->wallettotalamount+5; // add bonus $5 when user uses the refer code

                $SiteUser->wallettotalamount=$walletamount; // update the wallet amount

                //$SiteUser->superaffiliateuser=1;// Set a user is super affiliate 
                $SiteUser->save();

                Walletdetails::create(['siteusers_id'=>$insertedId,'purpose'=>'Bonus for using super affiliate code at the time of registration','amount'=>5,'total'=>5,'status'=>1,'currencycode'=>'USD','exchangerate'=>0,'type'=>1,'created_at'=>$datetime,'purpose_state'=>1,'walletstatus'=>1]); // insert into wallet details table as a type=1 means credit
              }
              else{

                $SiteUser=SiteUser::find($insertedId);
                $walletamount=$SiteUser->wallettotalamount+2.5; // add bonus $2.5 when user uses the refer code

                $SiteUser->wallettotalamount=$walletamount; // update the wallet amount

                //$SiteUser->superaffiliateuser=1;// Set a user is super affiliate 
                $SiteUser->save();

                Walletdetails::create(['siteusers_id'=>$insertedId,'purpose'=>'Bonus for using super affiliate code at the time of registration','amount'=>2.5,'total'=>2.5,'status'=>1,'currencycode'=>'USD','exchangerate'=>0,'type'=>1,'created_at'=>$datetime,'purpose_state'=>1,'walletstatus'=>1]); // insert into wallet details table as a type=1 means credit
              }

          

        }
        else{
          // Wrong refer code

           $flag=0;
           $SiteUser=SiteUser::find($insertedId);
           $email=$SiteUser->email;
           SiteUser::find($insertedId)->delete();
           Previousemail::where('email',$email)->delete();
           Session::flash('failure_message', 'You have entered wrong refer code.');
           return redirect("/signup");
        }
        
      }




      /* Used for link share by user */
      else if(Session::has('linkurl') && Session::has('shareremembertoken')){
          $linkurl = (Session::get('linkurl'));
          $shareremembertoken = (Session::get('shareremembertoken'));
          $referby=base64_decode($linkurl);
          Userrefer::create(['refercode'=>'','referto'=>$insertedId,'status'=>0,'created_at' => $datetime,'referby'=>$referby]);
          Invitefriend::where('remember_token',$shareremembertoken)->update(['remember_token'=>'']);
          Session::forget('linkurl');
          Session::forget('shareurl');
          Session::forget('shareremembertoken');
      }




      ///////////////// added by surajit start ////////////////////
      else if(Session::has('refercode') && Session::has('shareremembertoken')){
          $refercode = (Session::get('refercode'));
          $shareremembertoken = (Session::get('shareremembertoken'));
          $referby = Invitefriend::where('remember_token',$shareremembertoken)->where('refercode',$refercode)->select('siteusers_id')->first();
          $referby = $referby['siteusers_id'];
          $referedUserCode = SiteUserReferId::where('siteuser_id',$referby)->first();
          Userrefer::create(['refercode'=>'','referto'=>$insertedId,'status'=>1,'created_at' => $datetime,'referby'=>$referby]);
          Invitefriend::where('remember_token',$shareremembertoken)->update(['remember_token'=>'', 'status'=>1]);
          if($referedUserCode['referid'] == $refercode){
            $ReferedUser=SiteUser::find($referby);
            $superaffiliated = $ReferedUser['superaffiliateuser'];
            if($superaffiliated == 1){

              $SiteUser=SiteUser::find($insertedId);
              $walletamount=$SiteUser->wallettotalamount+5; // add bonus $5 when user uses the refer code
              $SiteUser->wallettotalamount=$walletamount; // update the wallet amount
              $SiteUser->save();
              Walletdetails::create(['siteusers_id'=>$insertedId,'purpose'=>'Bonus for using super affiliate code at the time of registration','purpose_state'=>'1','amount'=>5,'total'=>5,'status'=>1,'currencycode'=>'USD','exchangerate'=>0,'type'=>1,'created_at'=>$datetime,'walletstatus'=>1]); // insert into wallet details table as a type=1 means credit
            }
            if($superaffiliated == 0){
              $SiteUser=SiteUser::find($insertedId);
              $walletamount=$SiteUser->wallettotalamount+2.5; // add bonus $2.5 when user uses the refer code
              $SiteUser->wallettotalamount=$walletamount; // update the wallet amount
              $SiteUser->save();
              Walletdetails::create(['siteusers_id'=>$insertedId,'purpose'=>'Bonus for using normal user code at the time of registration','purpose_state'=>'1','amount'=>2.5,'total'=>2.5,'status'=>1,'currencycode'=>'USD','exchangerate'=>0,'type'=>1,'created_at'=>$datetime,'walletstatus'=>1]); // insert into wallet details table as a type=1 means credit
            }
           }
           else{
          // Wrong refer code

           $flag=0;
           $SiteUser=SiteUser::find($insertedId);
           $email=$SiteUser->email;
           SiteUser::find($insertedId)->delete();
           Previousemail::where('email',$email)->delete();
           Session::flash('failure_message', 'Error occurred.');
           return redirect("/signup");
           }

          Session::forget('refercode');
          Session::forget('shareurl');
          Session::forget('shareremembertoken');
      }
      else if(Session::has('refercode') && !(Session::has('shareremembertoken'))){

          $refercode = (Session::get('refercode'));
          $siteuserreferiddetails=SiteUserReferId::where('referid',$refercode)->get();
          $siteuserreferidcount=SiteUserReferId::where('referid',$refercode)->count();

          if($siteuserreferidcount>0){

          $referby=$siteuserreferiddetails[0]->siteuser_id;

        
           Userrefer::create(['refercode'=>'','referto'=>$insertedId,'status'=>1,'created_at' => $datetime,'referby'=>$referby]);


            $ReferedUser=SiteUser::find($referby);
            $superaffiliated = $ReferedUser['superaffiliateuser'];
            if($superaffiliated == 1){

              $SiteUser=SiteUser::find($insertedId);
              $walletamount=$SiteUser->wallettotalamount+5; // add bonus $5 when user uses the refer code
              $SiteUser->wallettotalamount=$walletamount; // update the wallet amount
              $SiteUser->save();
              Walletdetails::create(['siteusers_id'=>$insertedId,'purpose'=>'Bonus for using super affiliate code at the time of registration','purpose_state'=>'1','amount'=>5,'total'=>5,'status'=>1,'currencycode'=>'USD','exchangerate'=>0,'type'=>1,'created_at'=>$datetime,'walletstatus'=>1]); // insert into wallet details table as a type=1 means credit
            }
            if($superaffiliated == 0){
              $SiteUser=SiteUser::find($insertedId);
              $walletamount=$SiteUser->wallettotalamount+2.5; // add bonus $2.5 when user uses the refer code
              $SiteUser->wallettotalamount=$walletamount; // update the wallet amount
              $SiteUser->save();
              Walletdetails::create(['siteusers_id'=>$insertedId,'purpose'=>'Bonus for using normal user code at the time of registration','purpose_state'=>'1','amount'=>2.5,'total'=>2.5,'status'=>1,'currencycode'=>'USD','exchangerate'=>0,'type'=>1,'created_at'=>$datetime,'walletstatus'=>1]); // insert into wallet details table as a type=1 means credit
            }
           
          Session::forget('refercode');
          Session::forget('shareurl');
         }

         else{
          // Wrong refer code

           $flag=0;
           $SiteUser=SiteUser::find($insertedId);
           $email=$SiteUser->email;
           SiteUser::find($insertedId)->delete();
           Previousemail::where('email',$email)->delete();
           Session::flash('failure_message', 'Error occurred.');
           return redirect("/signup");
        }
          
      }

      ///////////////// Added by surajit end //////////////////////

      
      if($flag==1){ 

        /**************************SEND MAIL -start *****************************/
        
        $site = Sitesetting::where(['name' => 'email'])->first();
        $admin_users_email = $site->value;
        
        $users = SiteUser::where("id","=",$insertedId)->first();
        $user_name = $users->firstname.' '.$users->lastname;
        $user_email = $users->email;
        $encodeduserid=base64_encode($insertedId);
        $encodedemail=base64_encode($user_email);
        $subject = "Your Account has been created";
        $message_body = "Your Account has been created in Checkout Saver.<br/><a href='".url()."/user/active_account/".$remember_token."'>Click Here</a> to active your account.";
        
        
        $mail = Mail::send(['html' => 'frontend.emailtemplate.send_mail'], array('message_body' => $message_body, 'user_name'=>$user_name), function($message) use ($admin_users_email, $user_email,$user_name,$subject)
        {
          $message->from($admin_users_email,'Checkout Saver');
    
          $message->to($user_email)->subject($subject);
        });
        
        /**************************SEND MAIL -end *****************************/ 

        Session::flash('success_message', 'You have successfully registered. Please check your mail and activate your account.');
        return redirect('login');

      }

      else{
         //SiteUser::find($insertedId)->delete();
         Session::flash('failure_message', 'There have some error.');
         return redirect('/signup');
      }


       
      
      
    }
  }



  public function postAddNewslatterUser()
  {
    if(Request::ajax())
     {

          $email = $_POST['email'];
          $password = $_POST['password'];

          $user_email_already = SiteUser::where("email","=",$email)->get()->count();
          
          if($user_email_already == "")
          {
             $user = SiteUser::create([
                  'email'     => $email,
                  'password'    => Hash::make($password),
                  'created_at' => date('Y-m-d H:i:s'),
          
              ]);

             echo 1;
          }
        else{
              echo 'Email already exists.';
           }
      }

  }

  /*************Activate account**********************************/

  public function active_account($remember_code)
  {
    $datetime= Customhelpers::Returndatetime(); 
    $site_user = SiteUser::where("remember_token","=",$remember_code)->first();
    $site_user_count = SiteUser::where("remember_token","=",$remember_code)->count();
    if($site_user_count>0)
    {
      $user = SiteUser::find($site_user->id);
      $user->status = '1';
      $user->is_login = '1';
      $user->remember_token = '';
      
      $user->save();
      
      Session::put('user_id', $site_user->id);

       //email notification on off in profile
      $this->emailnotifyinprofile($site_user->id,$datetime);

     // Session::put('user_type', $site_user->user_type);
      
      /*$business_count = BusinessList::where("business_owner_id",$site_user->id)->count();
      if($business_count>0)
      {
        return redirect('businessowner/list-business');
      }
      else
      {
        return redirect('businessowner/add-new-business');
      }*/
      
      Session::flash('success_message', 'Your account has been activated.');

       $datetime= Customhelpers::Returndatetime();
      $created_at=$datetime;
      $Sitelastinsertid=$site_user->id;
      $referid=$Sitelastinsertid.$this->userreferid();

        //SiteUserReferId::create(['siteuser_id'=>$Sitelastinsertid,'referid'=>$referid,'created_at' => $created_at]);

        Userrefer::where('referto',$site_user->id)->update(['status'=>1]);

        $userrefercount=Userrefer::where("referto",$site_user->id)->count();

        if($userrefercount>0){

          $userreferdetails=Userrefer::where("referto",$site_user->id)->get();
          
          $referby=$userreferdetails[0]->referby;


          /**************************SEND MAIL -start Friend registration notification mail*****************************/

              //Check ReferralCommissionNotifications status in profile is on or off

          $emailnotification=Emailnotification::where('slug','ReferralCommissionNotifications')->where('status',1)->get();
          
          $emailnotification_count=Emailnotification::where('slug','ReferralCommissionNotifications')->where('status',1)->count();
          if($emailnotification_count>0){
              $emailnotification_id=$emailnotification[0]->id;
            

            $emailnotification_Siteuser=Emailnotification_Siteuser::where('siteusers_id',$referby)->where("emailnotifications_id",$emailnotification_id)->get();


            $emailnotification_Siteuser_count=Emailnotification_Siteuser::where('siteusers_id',$referby)->where("emailnotifications_id",$emailnotification_id)->count();

            if($emailnotification_Siteuser_count>0){

              $mailstatus=$emailnotification_Siteuser[0]->status;
              if($mailstatus==1){

                //$status=0;

                      $site = Sitesetting::where(['name' => 'email'])->first();
                      $admin_users_email = $site->value;

                      $frienduser = SiteUser::where("id","=",$site_user->id)->first();
                      $frienduser_name = $frienduser->firstname.' '.$frienduser->lastname;
                      $frienduser_email = $frienduser->email;


                      $users = SiteUser::where("id","=",$referby)->first();
                      $user_name = $users->firstname.' '.$users->lastname;
                      $user_email = $users->email;
                      $encodeduserid=base64_encode($referby);
                      $encodedemail=base64_encode($user_email);
                      $subject = "Your one friend registered today using your link.";
                      $message_body = "Your one friend registered today using your link.<br/>Friend's Name : ".$frienduser_name." , Friend's Email : ".$frienduser_email;


                      $mail = Mail::send(['html' => 'frontend.emailtemplate.send_mail'], array('message_body' => $message_body, 'user_name'=>$user_name), function($message) use ($admin_users_email, $user_email,$user_name,$subject)
                      {
                      $message->from($admin_users_email,'Checkout Saver');

                      $message->to($user_email)->subject($subject);
                      });


                
              }
              
            }
          } 


           /**************************SEND MAIL -start Friend registration notification mail end*****************************/


        }




       




       return redirect('login'); 
    }
    else
    {
      Session::flash('failure_message', 'The link is expired.');
        return redirect('login');
    }
  }



    /********************** Generate user refer id **********************************/


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

  /******************************* User login ***********************************/

  function signinUser()
  {
    $datetime= Customhelpers::Returndatetime(); 
    if (Session::has('user_id'))
      {
      return redirect('user/my-profile');
    }
    
    if (Request::isMethod('post'))
    {

      if(Session::has('num_login_fail'))
      {
        if(Session::get('num_login_fail') == 3)
         {
           if(time() - Session::get('last_login_time') < 10*60*60 ) 
            {
               // alert to user wait for 10 minutes afer
                Session::flash('failure_message', 'You are block for 10 mins');
                return redirect('login/'.Request::input('buy_url'));
            }
            else
            {
              //after 10 minutes
               Session::put('num_login_fail',0);
            }
         }      
      }



        $product_id = Request::input('id');
        $site_user = SiteUser::where('email','=',Request::input('email'))->first();
        
        //Session::put('timezone',Request::input('timezone')); // saving to session
        
        
        if($site_user != NULL)
        {
          if(Hash::check(Request::input('password'),$site_user->password))
          {
              if($site_user->is_deleted == 1)
              {

                Session::flash('failure_message', 'Your account is deleted.');
                return redirect('login');
              }
             else if($site_user->status == 1)
              {
                  //print_r($site_user);exit;

                  
                  Session::put('user_id', $site_user->id);

                  //save the client ip address

                  $loginip=$_SERVER['REMOTE_ADDR'];
                  $userid=$site_user->id;
                  $siteuserdata=SiteUser::find($userid);

                  $siteuserdata->loginip=$loginip;
                  $siteuserdata->updated_at=$datetime;
                  $siteuserdata->is_login=1; // is_login status is used for if logout for one browser auto logout from other browser
                  $siteuserdata->save();
                 
                  
                 // Session::put('user_type', $site_user->user_type);
                  

                //===Gift Card====

                if (Session::has('userbuy') && Session::has('utid')){ 

                  $userbuy=Session::get('userbuy');
                  $utid=Session::get('utid');

                  if($userbuy=='giftcard' && !empty($userbuy) && !empty($utid)){

                    Session::forget('userbuy');
                    Session::forget('utid');
                    return redirect('brand?utid='.$utid);
                  }
                  else{
                    return redirect('user/my-profile');
                  }

                }
                /* Used for subscription */
                else if(Session::has('subtype')){

                  $subtype=Session::get('subtype');
                   Session::forget('subtype');
                  return redirect('user/unsubscribe?subtype=unsubscribe');
                }

                else if(Request::input('buy_url')!=''){
                    $redirect_url = base64_decode(Request::input('buy_url'));
                    return redirect($redirect_url.'&u1='.Session::get('user_id'));
                  }
                else if($product_id != ""){
                   
                    $product = Product::where('id', '=', $product_id)->first();

                    // Create Slug

                    $new_created_slug = preg_replace('/[^\da-z]/i', '-', strtolower($product->name));
                    $new_created_slug=trim(preg_replace('/-+/', '-', $new_created_slug), '-');
                    return redirect(url()."/product_details/".base64_encode($product->id)."/".$new_created_slug);
                }
                else
                return redirect('user/my-profile');
              }
              else
              {

                Session::flash('failure_message', 'Your account is not activated.');
                return redirect('login');
              }
          }
          else
          {
            // Add Count for wrong password
            if(Session::has('num_login_fail')){
              $num_login_fail = Session::get('num_login_fail');
              $num_login_fail++;
            }
            else
              $num_login_fail = 0;

            Session::put('num_login_fail',$num_login_fail);
            Session::put('last_login_time',time());


            Session::flash('failure_message', 'Invalid Email/Password!!');
            return redirect('login/'.Request::input('buy_url'));
          }
        }
        else
        {
          Session::flash('failure_message', 'Invalid Email/Password!!');
          return redirect('login/'.Request::input('buy_url'));
        }
    }
    /* Checking Buy_url session exists or not. If exists then will redirect to buy_url link.*/
      $buy_url = '';
      if(Session::has('buy_url')){
          $buy_url = (Session::get('buy_url'));
          Session::forget('buy_url');
      }

      /***************Call getfbgoogleidfromdb () function to Get fb id and google id from database**************************/

      $sitesettingsar=$this->getfbgoogleidfromdb();
      $fbappid=$sitesettingsar["fbappid"];
      $googleclient_id=$sitesettingsar["googleclient_id"];
      $googlecookiepolicy=$sitesettingsar["googlecookiepolicy"];

    return view('frontend.home.login',compact('buy_url','fbappid','googleclient_id','googlecookiepolicy'));
  }


  /*****************************************Forgot password************************************************/

  public function forgot_password()
  {
    if (Request::isMethod('post'))
    {
      $data = Request::all();
      $email = $data['email'];

      $is_user_exists = SiteUser::where('email','=',$email)
                    ->where('is_deleted','=','0')
                    ->count();
                    
        
      if($is_user_exists == 0)
      {
        Session::flash('failure_message', 'Invalid Email!!');
          return redirect('forgot-password');
      }
      else
      {
        
        /**************************SEND MAIL -start *****************************/
        $datetime= Customhelpers::Returndatetime();
        $site = Sitesetting::where(['name' => 'email'])->first();
        $admin_users_email = $site->value;
        
        //store forgot password datetime to valid for 24 hours reset link

        $reset_password_key = $this->get_unique_alphanumeric_no(16);
        SiteUser::where('email','=',$email)
                ->update(['reset_password_key'=>$reset_password_key,'forgotpassworddatetime'=>$datetime]);


        $user_detls = SiteUser::where('email','=',$email)->first();

        /***************** USER MAIL ******************/
        
        $user_name = $user_detls->firstname.' '.$user_detls->lastname;
        $user_email = $user_detls->email;
        
        $subject = "Reset Password";
        $message_body = "<a href='".url()."/reset-password/".base64_encode($reset_password_key)."'>Click Here to reset your password.</a>";
        
        
        $mail = Mail::send(['html' => 'frontend.emailtemplate.send_mail'], array('message_body' => $message_body, 'user_name'=>$user_name), function($message) use ($admin_users_email, $user_email,$user_name,$subject)
        {
          $message->from($admin_users_email,'Checkout Saver');
    
          $message->to($user_email)->subject($subject);
        });
        
        /************************** SEND MAIL -end *****************************/
        
        
         
         Session::flash('success_message', 'Please check your mail to reset password.');
          return redirect('forgot-password');

        
      }

    }
    return view('frontend.home.forgot_password');
  }

  /**************************Reset password****************************************/

  public function reset_password($reset_password_key = '')
  {
    $datetime= Customhelpers::Returndatetime();

    $reset_password_key = base64_decode($reset_password_key);
    
    $user_arr = SiteUser::where("reset_password_key",$reset_password_key)->first();
    
    $user_arr_count = SiteUser::where("reset_password_key",$reset_password_key)->count();
    
    //check forgot password datetime with current datetime to valid for 24 hours reset link

    $forgotpassworddatetime=$user_arr->forgotpassworddatetime;

    $currentdatetime=$datetime;

    $hourdiff = round((strtotime($currentdatetime) - strtotime($forgotpassworddatetime))/3600, 1);

    // $seconds = strtotime($date2) - strtotime($date1);
    // $hours = $seconds / 60 / 60;

    if($hourdiff>24){

      Session::flash('failure_message', 'The url is invalid because 24 hours has been exceeded.');
      return redirect('login');
    }

    if($user_arr_count>0)
    {
      
      $email = $user_arr->email;
      
      if(Request::isMethod('post'))
      {
        $data = Request::all();
        //Hash::make($data['password']),
        SiteUser::where('reset_password_key','=',$reset_password_key)
              ->update([
                'reset_password_key'  =>  '',
                'password'  =>  Hash::make($data['password']),
              ]);
        Session::flash('success_message', 'Password has been set successfully.');
        return redirect('login');
      }
      $is_user_exists = SiteUser::where('email','=',$email)
            ->where('reset_password_key','=',$reset_password_key)
            ->first();
      if($is_user_exists != null)
      {
        $is_valid_link = 1;
      }
      else
      {
        $is_valid_link = 0;
      }
      return view('frontend.home.reset_password',compact('is_valid_link'));
    }
    else
    {
      Session::flash('failure_message', 'The url has been expired.');
      return redirect('login');
    }
  }

  public function logout(){


    

    $userid=Session::get('user_id');
    $siteuserdata=SiteUser::find($userid);

    $siteuserdata->is_login=0; // is_login status is used for if logout for one browser auto logout from other browser
    $siteuserdata->save();

    session::flush();

    if(!empty($_GET['type'])){

      if($_GET['type']=='changepassword'){
        Session::flash('success_message', 'Password has been changed successfully.You need to relogin.');
      }
    }
    else{
      Session::flash('success_message', 'You have successfully logged out.');
    }
    return redirect('/login');
  }

/****************Change password*****************************************/

  public function changePassword(){

    $this->authenticateUser();
    if (Session::has('user_id')){

      /****************Get user details ******************************/

        $userprofileheadinfo=Customhelpers::getUserDetails();

      if (Request::isMethod('post')){
      
        

        $data = Request::all();
        $is_user_exists = SiteUser::where('id','=',Session::get('user_id'))
                    ->where('is_deleted','=','0')
                    ->first();
        if(Hash::check(Request::input('oldpassword'),$is_user_exists->password)){

           SiteUser::where('id','=',Session::get('user_id'))
              ->update([
                'password'  =>  Hash::make($data['password']),
              ]);
           
           Session::flash('success_message', 'Password has been changed successfully.');
           
           return redirect('user/logout?type=changepassword');
        }
        else{
          Session::flash('failure_message', 'Wrong old password.');
          return view('frontend.home.changepassword',compact('userprofileheadinfo'));
        }
        
      }
      else
      {
        return view('frontend.home.changepassword',compact('userprofileheadinfo'));
      }
    }
    else
      return redirect('login');
  }


/********** Edit paypal id  *************/

  public function editPaypalId(){

    if (Session::has('user_id')){

        if (Request::isMethod('post')){

              $data = Request::all();
              $paypalid=$data['paypalid'];
              $userid=Session::get('user_id');
              $checkemail=SiteUser::where("id","!=",$userid)->where("paypalid",$paypalid)->count();

              if($checkemail>0){
              Session::flash('failure_message', 'Your paypal email id is already exist.');
              return redirect('user/my-profile');
              }
              else{

                    SiteUser::where('id','=',Session::get('user_id'))
                  ->update([
                    
                    'paypalid'=>$data['paypalid']
                  ]);

                    Session::flash('success_message', 'Your paypal id has been edited successfully.');
                    return redirect('user/my-profile');
              }
         }
      }
    else{

      return redirect('login');
    }
    
  }


/********************Remove profile image************************************/

public function removeProfileImage(){

      if (Session::has('user_id')){

        $userid=Session::get('user_id');
        $siteuser=SiteUser::find($userid);
        if(!empty($siteuser['profileimage'])){

            @unlink('public/backend/profileimage/'.$siteuser['profileimage']);
        }  
        $siteuser->profileimage='';
        $siteuser->save();
        echo "1";
        exit();
      }
      else{
      return redirect('login');
      }
      

}


/****************Edit profile***************************/

  public function editProfile(){

    $this->authenticateUser();
    if (Session::has('user_id')){

      $is_user_exists = SiteUser::where('id','=',Session::get('user_id'))->where('is_deleted','=','0')->first();
      $SiteUserReferId=SiteUserReferId::where("siteuser_id",Session::get('user_id'))->get();
      //$result = Country::all();
      if (Request::isMethod('post'))
      {
        
        $data = Request::all();
        
        /* Checking refer code exist or not*/
        $userid=Session::get('user_id');
        if(!empty($data['refercode'])){

          $refercode=$data['refercode'];
         
          $whereraw=("siteuser_id !=".$userid);
          $countrefercode=SiteUserReferId::where("referid",$refercode)->where('siteuser_id', '!=',  $userid)->count();
          
        }
        else{
          $countrefercode=0;

        }

        $email=$data['email'];
        $checkemail=SiteUser::where("id","!=",$userid)->where("email",$email)->count();

        if($checkemail>0){
          Session::flash('failure_message', 'Your email id is already exist.');
          return redirect('user/my-profile');
        }

        

        if($countrefercode>0){

          Session::flash('failure_message', 'Refer code already exist.');
        return redirect('user/my-profile');
        }
         $datetime= Customhelpers::Returndatetime(); 
        SiteUser::where('id','=',Session::get('user_id'))
              ->update([
                'firstname' => $data['name'],
                'lastname'  =>  $data['last_name'],
                //'address'  =>  $data['address'],
                //'country'  =>  $data['country'],
                //'city'  =>  $data['city'],
                //'state'  =>  $data['state'],
               // 'zipcode'  =>  $data['zipcode'],
                //'phoneno'  =>  $data['phone'],
                'email'  =>  $data['email'],
                'updated_at'=>$datetime
                //'dob'=>$data['dob'],
                //'paypalid'=>$data['paypalid']
              ]);
        
        //add the email into previous email table

        $count=Previousemail::where('email',$data['email'])->count();

        if($count==0){

            Previousemail::create(['email'=>$data['email'],'created_at'=>$datetime]);

        }      


        $id=Session::get('user_id');

        if(!empty($data['refercode'])){
        SiteUserReferId::where('siteuser_id','=',$id)->update(['referid'=>$refercode]);
       }

        $siteuser=SiteUser::find($id);     

         $file = Request::file('prfl_image');
        
         //echo "<pre>";
         //print_r($file);exit();
        if(!empty($file)){

        $imagedetails = getimagesize($_FILES['prfl_image']['tmp_name']);

        $width = $imagedetails[0];
        $height = $imagedetails[1];
        if($height<200 || $width<200){

        Session::flash('failure_message', 'Image size should be min 200X200.');

        return redirect('user/my-profile');
        }


        $fileorgname=$file->getClientOriginalName();

        if($fileorgname!=''){

        $extension=strtolower($file->getClientOriginalExtension());

        $extensionar=array('jpg', 'jpeg', 'gif', 'png','bmp');


        if (in_array($extension, $extensionar))
        {

        //echo "Match found";exit();

       
        if(!empty($siteuser['profileimage'])){

            @unlink('public/backend/profileimage/'.$siteuser['profileimage']);
        }  

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


       /****************Get user details ******************************/

        $userprofileheadinfo=Customhelpers::getUserDetails();


        Session::flash('success_message', 'Profile has been edited successfully.');
        return redirect('user/my-profile');
      }
      else
      {
        /****************Get user details ******************************/

        //$userprofileheadinfo=Customhelpers::getUserDetails();
        //return view('frontend.home.edit_profile',compact('is_user_exists','SiteUserReferId','result','userprofileheadinfo'));
        return redirect('user/my-profile');
      }

        
    }
    else
      return redirect('login');
  }

  
  //=============Facebook Login Register=======//

  //social login in ajax

public function facebook(){
    $obj = new helpers();
    $name = Request::input('name');
    $email=(Request::input('email'));
    $id=(Request::input('id'));
    
    $name=explode(" ",$name);
    if(count($name)>0){
    $fname=$name[0];
    $lname=end($name);
      }else{
    $fname=$name; 
    $lname=$name;
      }
    if($email==''){
      return 0;
    }
    $username=strtolower($fname);
    $user_details = SiteUser::where(['email' => $email])->first();
    
    if(count($user_details)>0){
  //$membertype=Session::get('member_type');
    if($user_details->status==0){
     return 2;  
    }    
    else{
    //update social login field =======//
      $update_user  = SiteUser::where('id', $user_details->id)
                  ->update([
                    'is_social_login'     => 1,
                    
                    ]);
      Session::put('member_userid', $user_details->id);
      Session::put('member_user_email', $user_details->email);
      Session::put('member_user_name', $user_details->user_name);
      Session::put('is_social_login', 1);
      echo 1;
    
      }     
    }else{
  //create social users
        $insert_data = array(
    'user_name'=>$username, 
    'email'=>$email,
    //'first_name'=> $fname,
    //'last_name'=> $lname,
    'password'=>'',
    'status'=>1,
    'activation_code'=>'',
    'is_social_login'=>1,
    'created_at'=>date('Y-m-d H:i:s')
    );
    $user_id=SiteUser::insertGetId($insert_data);
      
  if($user_id){
    $user_details = SiteUser::where(['id' => $user_id])->first();
      Session::put('member_userid', $user_details->id);
    Session::put('member_user_email', $user_details->email);
        Session::put('member_user_name', $user_details->user_name);
    Session::put('is_social_login', 1);
      echo 1;
  }else{
      echo 2;
      
  }
  
    }
    
   }
  //=============ENd=============//

   /************************** Facebook login ****************************/

  function getFbLogin()
  {
    $datetime= Customhelpers::Returndatetime(); 

  	if (Request::isMethod('post'))
    {
  		$data = Request::input();
  		//echo "<pre>"; print_r($data);exit;
  		$existing_user_count = SiteUser::where("email",$data['email'])->get()->count();

  		if($existing_user_count == 0)
  		{
  			$existing_fbuser_count = SiteUser::where("fb_id",$data['id'])->get()->count();
  			
  			if($existing_fbuser_count>0)
  			{
  				$site_user = SiteUser::where("fb_id",$data['id'])->first();

          if($site_user->is_deleted == 1){
            return 3; // your account has been deleted
          }
          else if($site_user->status == 1){
            Session::put('fb_id', $data['id']);
            Session::put('user_id', $site_user->id);


            //save the client ip address

            $loginip=$_SERVER['REMOTE_ADDR'];
            $userid=$site_user->id;
            $siteuserdata=SiteUser::find($userid);

            $siteuserdata->loginip=$loginip;
            $siteuserdata->updated_at=$datetime;
            $siteuserdata->is_login=1;
            $siteuserdata->save();

            return 1;
          }else{
            //Session::flash('failure_message', 'Your account is not activated.');
            return 0;
          }
          
  			}
  			else
  			{
          $name=explode(" ",$data['name']);
          if(count($name)>0){
            $fname=$name[0];
            $lname=end($name);
          }else{
            $fname=$name; 
            $lname=$name;
          }
  				$user = SiteUser::create([
  					'login_type'  => "facebook",
  					'fb_id'   	=> $data['id'],
  					'firstname'      	=> $fname,
  					'lastname'   => $lname,
  					'email'     	=> $data['email'],
            'status'      => 1,
  					'created_at' 	=> $datetime,
            'is_login'=>1
  				]);
  				$insertedId = $user->id;


          //add the email into previous email table

          $count=Previousemail::where('email',$data['email'])->count();

          if($count==0){

          Previousemail::create(['email'=>$data['email'],'created_at'=>$datetime]);

          }

          //email notification on off in profile
          $this->emailnotifyinprofile($insertedId,$datetime);

          $encodedinsertid=base64_encode($insertedId);
          $link=url('')."/signup?linkurl=".$encodedinsertid;

          /* update user share link */
          $user=SiteUser::find($insertedId);
          $user->sharelink=$link;
          $user->save();

          Session::put('fb_id', $data['id']);
  				Session::put('user_id', $insertedId);

          //save the client ip address

          $loginip=$_SERVER['REMOTE_ADDR'];
          $userid=$insertedId;
          $siteuserdata=SiteUser::find($userid);

          $siteuserdata->loginip=$loginip;
          $siteuserdata->updated_at=$datetime;
          $siteuserdata->is_login=1;
          $siteuserdata->save();


  			}
  			return 1;
  			
  		}
  		else
  		{
  			$site_user = SiteUser::where("email",$data['email'])->first();
        if($site_user->is_deleted == 1){
            return 3; // your account has been deleted
          }
        if($site_user->status == 1){
          Session::put('fb_id', $data['id']);
          Session::put('user_id', $site_user->id);

          //save the client ip address

          $loginip=$_SERVER['REMOTE_ADDR'];
          $userid=$site_user->id;
          $siteuserdata=SiteUser::find($userid);

          $siteuserdata->loginip=$loginip;
          $siteuserdata->updated_at=$datetime;
          $siteuserdata->is_login=1;
          $siteuserdata->save();

          return 1;
        }else{
          //Session::flash('failure_message', 'Your account is not activated.');
          return 0;
        }
  		}
  	}
   // exit;
  }

  /********************** Google login  ********************************/

  public function getGoogleLogin(){

  $datetime= Customhelpers::Returndatetime();

    if (Request::isMethod('post'))
    {
      $data = Request::input();
      //echo "<pre>"; print_r($data);exit;
      $existing_user_count = SiteUser::where("email",$data['email'])->get()->count();

      if($existing_user_count == 0)
      {
        
        
          $name=explode(" ",$data['name']);
          if(count($name)>0){
            $fname=$name[0];
            $lname=end($name);
          }else{
            $fname=$name; 
            $lname=$name;
          }
          $user = SiteUser::create([
            'login_type'  => "google",
            'google_id'     => $data['id'],
            'firstname'       => $fname,
            'lastname'   => $lname,
            'email'       => $data['email'],
            'status'      => 1,
            'created_at'  => $datetime,
            'is_login'=>1
          ]);
          $insertedId = $user->id;

          //add the email into previous email table

          $count=Previousemail::where('email',$data['email'])->count();

          if($count==0){

          Previousemail::create(['email'=>$data['email'],'created_at'=>$datetime]);

          }

          //email notification on off in profile
          $this->emailnotifyinprofile($insertedId,$datetime);

          $encodedinsertid=base64_encode($insertedId);
          $link=url('')."/signup?linkurl=".$encodedinsertid;

          /* update user share link */
          $user=SiteUser::find($insertedId);
          $user->sharelink=$link;
          $user->save();

          Session::put('fb_id', $data['id']);
          Session::put('user_id', $insertedId);
          
          //save the client ip address

          $loginip=$_SERVER['REMOTE_ADDR'];
          $userid=$insertedId;
          $siteuserdata=SiteUser::find($userid);

          $siteuserdata->loginip=$loginip;
          $siteuserdata->updated_at=$datetime;
          $siteuserdata->save();

        return 1;
        
      }
      else
      {
        $site_user = SiteUser::where("email",$data['email'])->first();
        if($site_user->is_deleted == 1){
            return 3; // your account has been deleted
          }
        if($site_user->status == 1){
          Session::put('fb_id', $data['id']);
          Session::put('user_id', $site_user->id);

          //save the client ip address

          $loginip=$_SERVER['REMOTE_ADDR'];
          $userid=$site_user->id;
          $siteuserdata=SiteUser::find($userid);

          $siteuserdata->loginip=$loginip;
          $siteuserdata->updated_at=$datetime;
          $siteuserdata->is_login=1;
          $siteuserdata->save();

          return 1;
        }else{
          //Session::flash('failure_message', 'Your account is not activated.');
          return 0;
        }
      }
    }

   
  }

  function getSearchProduct(){

    //echo "======".$term = $_GET['term']; exit;
    $sessionvalue = Session::get('menwomen');
    $term = $_GET['term'];
    $order_details_arr = array();
    $order_arr = Brand::where('brand_name','LIKE','%'.$term.'%')->offset(0)->limit(5)->get();
    if(!empty($order_arr->toArray()))
    {
      $order_arr = $order_arr->toArray();
      $i = 0;
      foreach($order_arr as $order_list)
      {
        $order_details_arr[$i]['key'] = $order_list['id'];
        $order_details_arr[$i]['value'] = $order_list['brand_name'];
        $i++;
      }
    }else{
        $order_details_arr[0]['key'] = 0;
        $order_details_arr[0]['value'] = 'Sorry no brand found';
    }
    echo json_encode($order_details_arr);
    exit;
  }

  public function getCmsPageContent ($slug)
  {
    $cms_arr  = Cmspage::where('slug',$slug)->get();

    if(count($cms_arr )>0){
      $about_arr = $cms_arr->first()->toArray();
    }

    $title = $about_arr['page_title'];
    $meta_description_content=$about_arr['meta_description'];
    $keywords=$about_arr['meta_keyword'];
    $meta_title=$about_arr['meta_title'];
    /****************Get user details ******************************/

    $userprofileheadinfo=Customhelpers::getUserDetails();
    return view('frontend.home.about',compact('about_arr','active_menu','title','meta_description_content','keywords','meta_title','userprofileheadinfo'));
 
  }

  public function postEmailSubcribe()
  {
    $data = Request::all();
    $email_arr  = SubscriptionEmail::where('email',$data['emailId'])->get()->first();

    if(count($email_arr )>0){
      echo 0;
    }else{
      $user = SubscriptionEmail::create([
            'email'  => $data['emailId'],
            'created_at'  => date('Y-m-d H:i:s'),
          ]);
      echo 1;
    }
    exit;
  }

  /***************************Contact us start*********************************/

  function getContactUs()
  {

    // $site_email   = $this->get_site_email();
    // $site_contact = $this->get_site_contact();
    // $site_address = $this->get_site_address();

    // $resultGeoCode=file_get_contents('http://maps.google.com/maps/api/geocode/json?address='.urlencode($site_address).'&sensor=false');
    // $output= json_decode($resultGeoCode);
    // if($output->status == 'OK'){
    //     $latitude   = $output->results[0]->geometry->location->lat; //Returns Latitude
    //     $longitude  = $output->results[0]->geometry->location->lng; // Returns Longitude
    //     $location = $output->results[0]->formatted_address;
    // }

    // return view('frontend.home.contact',compact('site_email','site_contact','site_address','latitude','longitude'));
    
    $slug="contact-us";
    $cms_arr  = Cmspage::where('slug',$slug)->get();

    $page_title = $title = $meta_description_content = $keywords = $meta_title = '';
    $about_arr = array();
    if(count($cms_arr )>0){
      $about_arr = $cms_arr->first()->toArray();
        $title = $about_arr['page_title'];
        $meta_description_content=$about_arr['meta_description'];
        $keywords=$about_arr['meta_keyword'];
        $meta_title=$about_arr['meta_title'];
    }
   

   
    /****************Get user details ******************************/

    $userprofileheadinfo=Customhelpers::getUserDetails();
    return view('frontend.home.contactus',compact('about_arr','active_menu','title','meta_description_content','keywords','meta_title','userprofileheadinfo'));



  }

  public function postContactUs()
  {
    if(Request::isMethod('post'))
    {
      $data = Request::all();
     
      $user = Contact::create([
                  'name'        => $data['name'],
                  'phone'       => $data['phone'],
                  'email'       => $data['email'],
                  'message'     => $data['message'],
                  'created_at'  => date("Y-m-d"),
                  
      ]);
      
        /**************************SEND MAIL -start *****************************/
        
        $site = Sitesetting::where(['name' => 'email'])->first();
        $admin_users_email = $site->value;
        
        
        $user_name = ucfirst($data['name']);
        $user_email = $data['email'];
        
        /***************** USER MAIL ******************/
          
        
        $subject = $user_name." send a message.";

        if(!empty($data['phone']))
        {
          $message_body = "One user send a message. <br/>Name : ".$user_name."<br/> Email : ".$user_email."<br/> Phone : ".$data['phone']."<br/>Message : ".$data['message'];
        }
        else
        {
          $message_body = "One user send a message. <br/>Name : ".$user_name."<br/> Email : ".$user_email."<br/>Message : ".$data['message'];
        }

        
        
        
        $mail = Mail::send(['html' => 'frontend.emailtemplate.contact_mail'], array('message_body' => $message_body, 'user_name'=>$user_name), function($message) use ($admin_users_email, $user_email,$user_name,$subject)
        {
          $message->from($user_email,$user_name);

          $message->to($admin_users_email)->subject($subject);
        });
          
        /************************** SEND MAIL -end *****************************/
      Session::flash('success_message', 'Your mail is successfully sent.We will contact you soon.');
        return redirect('contact-us');
    }
  }

/***************************Contact us end*********************************/

  public function getHowItWorks(){
    $about_arr='';
    $title='howitworks';
    /****************Get user details ******************************/

    $userprofileheadinfo=Customhelpers::getUserDetails();
    return view('frontend.home.howitworks',compact('title','about_arr','userprofileheadinfo'));
  }

}