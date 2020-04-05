<?php namespace App\Http\Controllers\Frontend; /* path of this controller*/
// Define Model
use \DOMDocument;

use App\Model\User; /* Model name*/
use App\Model\Category; /* Model name*/
use App\Model\SubCategory; /* Model name*/
use App\Model\Product; /* Model name*/
use App\Model\Productnew; /* Model name*/
use App\Model\MyProduct; /* Model name*/
use App\Model\Brand; /* Model name*/
use App\Model\OrderHistory; /* Model name*/
use App\Model\Vendor; /* Model name*/
use App\Model\Sitesetting; /* Model name*/
use App\Model\SiteUser; /* Model name*/

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
use App\Model\Vendorcategories;
use App\Model\Vendordetails;
use DB;
use Hash;
use Auth;
use Cookie;
use App\Helper\helpers;
use Redirect;
use Lang;
use App;
use Customhelpers;
class UserCronController extends Controller {


/**############### CJ Vendor Import ##############**/

  public function getCJVendorList(){

    $myfile = fopen("logs1.txt", "a");
    $txt = "pid=";
    fwrite($myfile, "\n". $txt);
    fclose($myfile);

      $page=1; $count = 10;$records_per_page = 99;
        // Loop infinetely until all products insert in Database
      while(1){

        if($page<=$count){

         // $api_key = '00886b33ffd47cd19a92c2cd3e4b361be0b9254b9c68080ff736ce10f059ab9abcdf9970fbf630557ccc6cf5f5d3c620917725e4906537bd0ad99f0d937530c173/4722073a461553c252f68ac909b4cc3290b836c5f6a5059635989bd2881f680e82d09b6e84a07442148411ed84a6d0f0eebf5a19f362d02d967f359e5bd76449';

          $Sitesetting_name_cjapikey=Sitesetting::where("name","cjapikey")->where("not_visible",0)->get();
          $Sitesetting_name_cjapikey_count=Sitesetting::where("name","cjapikey")->where("not_visible",0)->count();

          if($Sitesetting_name_cjapikey_count>0){

          $cjapikey=$Sitesetting_name_cjapikey[0]->value;
          }
          else{
          $cjapikey="";
          }
          

          $api_key = $cjapikey;


          $ch = curl_init();

          curl_setopt($ch, CURLOPT_URL, "https://link-search.api.cj.com/v2/link-search?website-id=8457053&advertiser-ids=joined&records-per-page=$records_per_page&page-number=$page");

          curl_setopt($ch, CURLOPT_HEADER,false);
          curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization:$api_key"));

          //curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization:$api_key"));
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
          $result = curl_exec($ch);

          $xml = new \SimpleXMLElement($result);
         // echo "1<pre>";print_r($xml);exit; 
          $item = (array)($xml);
          $item = (array)($item['links']);
          /*echo "<pre>";
          print_r($item);exit();*/
           
          //$item_array = array
          if(isset($item['link'])){

            $all_vendor_arr = (array)$item['link'];

            $totalMatched = $item['@attributes']['total-matched'];
            $count = ceil($totalMatched/$records_per_page);
          }
          else{
            $all_vendor_arr = array();
          }
          
          if(count($all_vendor_arr)>0){

          	//Vendorcategories::truncate();
             foreach($all_vendor_arr as $key => $vendor_info){

                $vendor_details = (array)$vendor_info;
                $myfile = fopen("logs.txt", "a");
                $txt = "page=".$page." id= ".$vendor_details['advertiser-id'];
                fwrite($myfile, "\n". $txt);
                fclose($myfile);
                $this->addCJVendors($vendor_details);
             }
          }
          else{
                $txt = "page=".$page." id= ".count($all_vendor_arr);
                fwrite($myfile, "\n". $txt);
                fclose($myfile);
          }
        }
        $page++;
        if($page==$count)
          break;
      }
  }

  public function addCJVendors($vendor_details){

    if(!empty($vendor_details["category"])){
    $categoryname=$vendor_details["category"];
    }
    else{
    $categoryname='';
    }
   
    if(!empty($vendor_details["advertiser-id"])){

      $advertiserid=$vendor_details["advertiser-id"];
    }
    else{
      $advertiserid=='';
    }

   	if(!empty($vendor_details["advertiser-name"])){
      $advertisername=$vendor_details["advertiser-name"];
    }
    else{
      $advertisername='';
    }

   	if(!empty($vendor_details["language"])){

      $language=$vendor_details["language"];
    }
    else{
      $language='';
    }
   	
    if(!empty($vendor_details["description"])){
      $description=$vendor_details["description"];
    }
    else{
      $description='';
    }
   	
    if(!empty($vendor_details["destination"])){
      $destination=$vendor_details["destination"];
    }
    else{
      $destination='';
    }
   	
    if(!empty($vendor_details["link-id"])){
      $linkid=$vendor_details["link-id"];
    }
    else{
      $linkid='';
    }
   	
    if(!empty($vendor_details["link-name"])){
      $linkname=$vendor_details["link-name"];
    }
    else{
      $linkname='';
    }

    if(!empty($vendor_details["link-code-html"])){
      $linkcodehtml=$vendor_details["link-code-html"];
    }
    else{
      $linkcodehtml='';
    }

    if(!empty($vendor_details["link-code-javascript"])){
      $linkcodejavascript=$vendor_details["link-code-javascript"];
    }
    else{
      $linkcodejavascript='';
    }
    if(!empty($vendor_details["sale-commission"])){
      $salecommission=$vendor_details["sale-commission"];

      $str=$salecommission;
      $ar=explode("-", $str);
      $count=count($ar);
      $max=$ar[$count-1];
      $max1=str_replace("%", "", $max);
      $max1=str_replace("USD", "", $max1);
      $salecommission_orderby=$max1;


    }
    else{
      $salecommission='Unavailable';
      $salecommission_orderby=0.0;
    }
   	
   	
   	if(!empty($vendor_details["clickUrl"])){
   		 	$clickUrl=$vendor_details["clickUrl"];
   	}
   	else{
   		$clickUrl='';
   	}
  
    
   	$datetime= Customhelpers::Returndatetime();

   	$catcount=Vendorcategories::where('name',$categoryname)->count();

   	if($catcount==0){
   		$id=Vendorcategories::create(['name'=>$categoryname,'created_at'=>$datetime,'updated_at'=>$datetime])->id;
   	}
   	else{

   		$vendorcat=Vendorcategories::where('name',$categoryname)->get();
   		$id=$vendorcat[0]->id;
   	}
   	
    $vendorinfocount=Vendordetails::where('vendorcategories_id',$id)->where('advertiserid',$advertiserid)->where('api','cj')->count();

    if($vendorinfocount==0){

      Vendordetails::create(['vendorcategories_id'=>$id,'advertiserid'=>$advertiserid,'advertisername'=>$advertisername,'language'=>$language,'description'=>$description,'destination'=>$destination,'linkid'=>$linkid,'linkname'=>$linkname,'salecommission'=>$salecommission,'clickurl'=>$clickUrl,'created_at'=>$datetime,'updated_at'=>$datetime,'api'=>'cj','salecommission_orderby'=>$salecommission_orderby,'linkcodehtml'=>$linkcodehtml,'linkcodejavascript'=>$linkcodejavascript]);
    } 		
  }

  public function getHitBackgroud(){
  
    //echo system('kill 2890');


    $cmd = "wget -bq --spider ".url()."/cjallvendor";
    //$cmd = "wget -bq --spider http://localhost/cashback/cron/delete-duplicate";
    echo $pid = shell_exec(escapeshellcmd($cmd));

    $myfile = fopen("logs.txt", "a");
    $txt = "pid=".$pid;
    fwrite($myfile, "\n". $txt);
    fclose($myfile);
  }

}

  

?>