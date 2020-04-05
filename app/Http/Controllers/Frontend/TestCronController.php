<?php namespace App\Http\Controllers\Frontend; /* path of this controller*/
// Define Model
use App\Model\Newsletter;  /* Model name*/
use App\Model\Blog;  /* Model name*/
use App\Model\Country;/*Model Name*/
use App\Model\Zone;/*Model Name*/
use App\Model\City;/*Model Name*/
use App\Model\Sitesetting;
use App\Model\SiteUser;
use App\Model\Cmspage; /* Model name*/
use App\Model\Topbanner; /* Model name*/
use App\Model\User; /* Model name*/
use App\Model\Category; /* Model name*/
use App\Model\SubCategory; /* Model name*/
use App\Model\Product; /* Model name*/
use App\Model\Productnew; /* Model name*/
use App\Model\Brand; /* Model name*/
use App\Model\Test; /* Model name*/

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
use Cart;
use App\Model\Subscription;
use Redirect;
use Lang;
use App;
use File;
//use Socialize;
use App\Model\Address; 

class TestCronController extends BaseController {

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

   
  

  function getProductsCustomList()
  {
      $ftp_server="datatransfer.cj.com";
      $ftp_user_name="4603669";
      $ftp_user_pass="ym+yXvfx";

        $conn_id = ftp_connect($ftp_server);
        // login with username and password
        $login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);

        $myfile = fopen("cjlogs.txt", "a");
        $txt = "$conn_id login_result=$login_result";
        fwrite($myfile, "\n". $txt);
        fclose($myfile);
        // Report all errors
        

        $local_file = 'linksharedata/'.$vendor_mid.'_3267514_mp.xml.gz';
        $server_file = '/'.$vendor_mid.'_3267514_mp.xml.gz';

        // try to download $server_file and save to $local_file
        if (ftp_get($conn_id, $local_file, $server_file, FTP_BINARY)) {
            echo "Successfully written to $local_file\n";
            $myfile = fopen("logs.txt", "a");
            $txt = "Successfully written to $local_file\n";
            fwrite($myfile, "\n". $txt);
            fclose($myfile);
        }
        else {
            echo "There was a problem\n";
            $myfile = fopen("logs.txt", "a");
            $txt = "There was a problem\n";
            fwrite($myfile, "\n". $txt);
            fclose($myfile);
        }

        // Raising this value may increase performance
        $buffer_size = 4096; // read 4kb at a time
        $out_file_name = str_replace('.gz', '', $local_file); 

        // Open our files (in binary mode)
        $file = gzopen($local_file, 'rb');
        $out_file = fopen($out_file_name, 'wb'); 

        // Keep repeating until the end of the input file
        while (!gzeof($file)) {
            fwrite($out_file, gzread($file, $buffer_size));            
        }

        // Files are done, close files
        fclose($out_file);
        gzclose($file);

        ########### Same file xml parsing and perfrom for same #####################

        $xml=simplexml_load_file('cjdata/Caliroots-Product_Catalogue_Caliroots_COM.xml') or die("Error: Cannot create object");       
        $items = (array)($xml);
        echo "<pre>";print_r($items);exit;

        if(count($items)>0){

          $advertiserids = '4522611';
          
          foreach($items['product'] as $key => $each_product){
            $product_details = (array)($each_product);
            
            $myfile = fopen("cjlogs.txt", "a");
            $txt = $product_details['sku'];
            fwrite($myfile, "\n". $txt);
            fclose($myfile);

            $this->doAddOrEditProducts1($product_details,$advertiserids);
            //exit;
          }
          exit;
          //
        }


      $all_advertiser_ids = array('4616199','3848495','4522611','4531654','1775620','3687358','4503047','3679021','2217875','4626082','3522174','4567657','3863211','3831921','1108426','2125713','4445684','3853787','3852850');

      foreach($all_advertiser_ids as $advertiser_ids){

      $page=1; $count = 1;$records_per_page = 999;
      
        // Loop infinetely until all products insert in Database
        while(1){

            if($page<=$count){

                //$api_key = "0096f97da97d7f7f77f616beb92b0466438a097175820b767d38696046101d83d491a3f18e9047ad82b3b4dd970c408d47fe30e54de632c1d17cf9bff4adef7b11/560970577ab1820ef72b171da72a26ad2ea91b03804cd005a47448ed2499ed59fdec7662ea1251a3df8f2b6443f17adffdba775b14dd2c4a55c15fff5a1523c1";
                $api_key = "0099dae78746f8c19db37c2794a6fe6b2a26fe416a1f3faad3b00b1f36bd91c6d239ccbd461ac72dd0c1f5fb8a1708cd7e73586e343d80ec49ac975d00f1065dcf/0d1c83989aaabe4d432b74700d9f26e0cbbbe53536fe84ff4f44535fa5de13745a8244ed5c224f128ae01ce96749862cc9bd41abd9740c9d62f6f0ca758d0ae1";

                $ch = curl_init();

                curl_setopt($ch, CURLOPT_URL, "https://product-search.api.cj.com/v2/product-search?website-id=8299079&advertiser-ids=$advertiser_ids&page-number=$page&records-per-page=$records_per_page");

                curl_setopt($ch, CURLOPT_HEADER,false); 

                curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization:$api_key"));
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $result = curl_exec($ch);
                $xml = new \SimpleXMLElement($result);

                $txt = "https://product-search.api.cj.com/v2/product-search?website-id=8273790&advertiser-ids=joined&page-number=$page&records-per-page=$records_per_page";
               
                /*echo "<pre>";
                print_r($xml);exit;*/

                $all_products_arr = array();
                if(!empty($xml->products)){
                   $xml_arr_data  = (array)($xml->products);
                   $all_products_arr= (array)$xml_arr_data['product'];

                   // Count all the matches record and define count
                   $totalMatched = $xml_arr_data['@attributes']['total-matched'];
                   $count = ceil($totalMatched/$records_per_page);

                   $main_category_array = array();$sub_category_array  = array();

                   foreach($all_products_arr as $key => $category_info){

                     $category_info = (array)$category_info;

                      if(isset($category_info['advertiser-category']) && $category_info['advertiser-category']!=""){

                        $output = substr($category_info['advertiser-category'], 1, -1);                      

                         $each_category_array_main = explode('>',$output);
                         $each_category_array = array_reverse($each_category_array_main);
                      }
                      else{
                        //$check_in_category = 1;
                        $each_category_array = array();
                      }
                      
                     /* echo "<pre>"; print_r($each_category_array_main); exit;*/

                      if(count($each_category_array)>0){
                         $check_in_category = 1;

                         foreach($each_category_array as $cat_key => $category_name){               
                             
                               $check_sub_category_exists = Category::where('name',trim($category_name))->orderBy('parent_id', 'asc')->get()->first();

                               if(count($check_sub_category_exists)>0){

                                  // Call add or edit function 
                                  $this->doAddOrEditProducts1($category_info,$check_sub_category_exists->id);

                                  $check_in_category = 0;
                                  break;
                               }                
                         }  // End of foreach

                         // if category is not matched then add or edit the product with general category.
                         if($check_in_category==1){
                            // Call add or edit function 
                           $this->doAddOrEditProducts1($category_info,1);
                            
                         }
                      }
                      else{
                        
                         // Call add or edit function 
                         $this->doAddOrEditProducts1($category_info,1);
                      }               
                   }
                }
            }
            else{

                // Check the products which are not updated, delete those products whose timestamp is greater than 1 days
                $product_ids = Product::select('id')->whereRaw('updated_at < DATE_SUB(NOW(), INTERVAL 1 DAY)')->where('api','CJ')->get()->toArray();
                if(!empty($product_ids)){
                   foreach ($product_ids as $key => $product_id) {

                      Product::where('id', $product_id)->delete();

                   }
                }

                break;
            }

            //sleep(30);
            echo $page.'....<br>';
            $page++;
            if($page==12)
            break;
      }
  }

      exit;
  }

  ################(CJ PRODUCT LIST)####################    
  public function doAddOrEditProducts1($category_info,$advertiserids)
  {
      
      $category_id = 1;
      $product_description = ($category_info['description']);
      
      $advertiser_category_array = array('main'=>'','sub'=>'');
      if(isset($category_info['advertisercategory'])){

        $advertiser_category = ($category_info['advertisercategory']);
        $advertiser_category_array = array('main'=>'','sub'=>$advertiser_category);
        $each_category_array_main = explode('>',$advertiser_category);
        $each_category_array = array_reverse($each_category_array_main);

        if(count($each_category_array)>0){
           $check_in_category = 1;

           foreach($each_category_array as $cat_key => $category_name){             
               
                 $check_sub_category_exists = Category::where('name',trim($category_name))->orderBy('parent_id', 'asc')->get()->first();

                 if(count($check_sub_category_exists)>0){
                    $category_id = $check_sub_category_exists->id;
                  
                    $check_in_category = 0;
                    break;
                 }                
           }  // End of foreach

           // if category is not matched then add or edit the product with general category.
           if($check_in_category==1){
              $category_id = 1;
              
           }
        }
        else{
           $category_id = 1;
        }
      }
      $manufacturer_name = '';
      if($advertiserids=='3522174' || $advertiserids=='2125713' ||  $advertiserids=='3852825' || $advertiserids=='4522611'){
        $manufacturer_name = $category_info['manufacturer'];
      }
      else if($advertiserids=='4567657'){
        $manufacturer_name = $category_info['publisher'];
      }

      /*$product_description = (array)($category_info['description']);
      $product_description_array = array('short'=>'','long'=>$product_description);*/
      // Check if the product is already exists
      $pro_dtls_chk_qry = Product::where('sku',$category_info['sku'])->where('api','CJ');
      //echo $category_info['sku'].'<br>';              
      // if the product is exists update the product else add the product     
      if($pro_dtls_chk_qry->count()){

         $update_product = Product::where('sku', $category_info['sku'])->update([
            'name'               => $category_info['name'],
            //'ad-id'              => $category_info['ad-id'],
            'advertiser-id'      => $advertiserids,
            'advertiser-name'    => $category_info['programname'],
            'advertiser-category'=> serialize($advertiser_category_array),
            'in-stock'           => ($category_info['instock']=='yes')?1:0,
            'sku'                => $category_info['sku'],
            'buy_url'            => $category_info['buyurl'],
            'image_url'          => $category_info['imageurl'],
            'currency'           => $category_info['currency'],
            'price'              => $category_info['price'],
            'description'        => $product_description,
            'retail_price'       => isset($category_info['retailprice'])?$category_info['retailprice']:0,
            'api'                => 'CJ',
            'manufacturer_name'  => $manufacturer_name,
            'updated_at'         => date('Y-m-d H:i:s')
         ]);
      }
      else{

         $insert_product = Product::create([
            'category_id'        => $category_id,
            'name'               => $category_info['name'],
            //'ad-id'              => $category_info['ad-id'],
            'advertiser-id'      => $advertiserids,
            'advertiser-name'    => $category_info['programname'],
            'advertiser-category'=> serialize($advertiser_category_array),
            'in-stock'           => ($category_info['instock']=='yes')?1:0,
            'sku'                => $category_info['sku'],
            'buy_url'            => $category_info['buyurl'],
            'image_url'          => $category_info['imageurl'],
            'currency'           => $category_info['currency'],
            'price'              => $category_info['price'],
            'description'        => $product_description,
            'retail_price'       => isset($category_info['retailprice'])?$category_info['retailprice']:0,
            'api'                => 'CJ',
            'manufacturer_name'  => $manufacturer_name,
            'created_at'         => date('Y-m-d H:i:s')
         ]);

      }
  }

  public function insertCjProduct(){
    //echo "string";
    $cmd = "wget -bq --spider ".url()."/getProductsCustomList";
    echo $id = shell_exec(escapeshellcmd($cmd));
  }

  public function processConveterApi(){
    
    $currency_arr = array('AUD','EUR','GBP','USD');
    $access_key = '8bbcd402b8b0cd4ccf318cd863c5bda7';
    $currencies = 'USD';
    $source_code = 'AUD';
    /*$api_url = 'https://apilayer.net/api/live?access_key='.$currency_ayy.'&currencies='.$currencies.'&source='.$source_code.'&format=1';

      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $api_url);
      curl_setopt($ch, CURLOPT_HEADER,false);
      //curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization:$api_key"));
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      $jsondata = curl_exec($ch);
      curl_close($ch);*/

    $jsondata = '{
        "success":true,
        "terms":"https:\/\/currencylayer.com\/terms",
        "privacy":"https:\/\/currencylayer.com\/privacy",
        "timestamp":1495006147,
        "source":"AUD",
        "quotes":{
          "AUDUSD":0.74003,
          "EURUSD":1.10926,
          "GBPUSD":1.294717,
          "USDUSD":1
        }
      }';

    $decode_data = json_decode($jsondata);
    $source = $decode_data->source;
    $exchange_data = (array)($decode_data->quotes);
    /*$AUD = $decode_data->quotes->AUDUSD;
    $EUR = $decode_data->quotes->EURUSD;
    $GBP = $decode_data->quotes->GBPUSD;
    $USD = $decode_data->quotes->USDUSD;*/
    
    $limit = 1500; $offset = 0;
    $product_count = Test::count();
    while ($product_count>$offset) {
      $product_details = Test::select(['id','original_currency','original_price','original_sale_price'])->where('currency','=','')->offset($offset)->limit($limit)->get()->toArray();
      //echo "<pre>";print_r($product_details);exit;
      foreach ($product_details as $key => $product_detail) {
          $currency_code = $product_detail['original_currency'];
          $price = $product_detail['original_price']*$exchange_data[$currency_code.'USD'];
          $sale_price = $product_detail['original_sale_price']*$exchange_data[$currency_code.'USD'];

          $update_product = Test::where('id',$product_detail['id'])->update([
                              'price'               => $price,
                              //'original_price'      => $product_detail['price'],
                              'currency'            => 'USD',
                              //'original_currency'   => $product_detail['currency'],
                              'sale_price'          => $sale_price
                              //'original_sale_price' => $product_detail['sale_price']
                          ]);

          $myfile = fopen("logs.txt", "a");
          $txt = "id= ".$product_detail['id'];
          fwrite($myfile, "\n". $txt);
          fclose($myfile);
      }
      $offset = $limit+$offset;
    }
  }
   
  public function currencyConveterApi(){
    //echo "string";
    $cmd = "wget -bq --spider ".url()."/back-conveter";
    echo $id = shell_exec(escapeshellcmd($cmd));
  }

   public function abc(){
    
    $currency_arr = array('AUD','EUR','GBP','USD');
    $access_key = '8bbcd402b8b0cd4ccf318cd863c5bda7';
    $currencies = 'USD';
    $source_code = 'AUD';
    $api_url = 'http://demo.uiplonline.com/cashback/staging/api/user/purchase-info?page=1&limit=3&user_id=23';

      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $api_url);
      curl_setopt($ch, CURLOPT_HEADER,false);
     // curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization:$api_key"));
      curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
      curl_setopt($ch, CURLOPT_USERPWD, "apiadmin:apiadmin"); //Your credentials goes here
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      $jsondata = curl_exec($ch);
      curl_close($ch);
      //print_r(json_decode($jsondata));exit;
    }
}