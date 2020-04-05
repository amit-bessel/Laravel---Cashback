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
use DB;
use Hash;
use Auth;
use Cookie;
use App\Helper\helpers;
use Redirect;
use Lang;
use App;

class CronController extends BaseController {

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

   public function getTest(){
        echo "string";
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Headers: Authorization, Content-Type' );
        header('Access-Control-Allow-Methods: PUT,POST,DELETE ,OPTIONS');
        echo json_encode(
                    array(
                        'status'    => 0,
                        'userdetails' => array(),
                    )
                );
   }
   
   ################(CJ CATEGORY LIST)####################
   public function getCategories()
   {
      echo "Frontend Cron";
      $api_key = "00d56a8f98dc66917627e83a0fa68958e97d73efcc6a04686979a18022dda03523ae71c1c072993a08ffc74f4b7b8da84c3cf01f177d2a27dcf080ec43c0474cb5/2745c0bf046feb7caec3514be7fd865b1136f07dd1549652328bc1a4a8dbfe6b0864e6777a8c41c53fcde4cf1393fa5382b5a1da50fbb9c7dcef29bb22b14a81";
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, "https://product-search.api.cj.com/v2/product-search?website-id=8270175&records-per-page=30&serviceable-area=US");
      //curl_setopt($ch, CURLOPT_URL, "https://support-services.api.cj.com/v2/categories");
      curl_setopt($ch, CURLOPT_HEADER,false); 

      //curl_setopt($ch, CURLOPT_HTTPGET, true); 
      //curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
      curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization:$api_key"));
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      $result = curl_exec($ch);
      $xml = new \SimpleXMLElement($result);
      /*echo "<pre>";
      print_r($xml->products['product']);*/

      $xml_arr_data  = (array)($xml->products);
      $categories_arr= (array)$xml_arr_data['product']; 
      echo "<pre>";
      print_r($categories_arr);
      exit;


      $main_category_array = array();
      $sub_category_array  = array();
      foreach($categories_arr as $key => $category_info){
        
         $category_info = (array)$category_info;

         $each_category_array = explode('>',$category_info['advertiser-category']);
         


         /* If $key == 0 and current_category is not 'Undefined' then directly put the 
            value to the main_category array.

            Also chekcing 'each_category' is 'Undfined' or not.
          */
         if($key==0){
            if(trim($each_category_array[0])!='Undefined'){
               $main_category_array[trim($each_category_array[0])] = trim($each_category_array[0]);
               
               /* Here we are taking 'each_category_array' 0th value as main category and other index as its sub category. 
               If 'each_category_array' size is greater than 0, then create the 'sub_category_array' of particular 'main_category_array'.

               If 'each_category_array' will be greater than 0, then only we can create sub category array.
               */

               if(count($each_category_array)>0){
                  foreach($each_category_array as $cat_key => $each_category_name)

                     /*Here 'cat_key' need to grater than 0, because we are taking 0th index value as main category that why we are skiping the 0th index value by checking 'each_category_array' index. */

                     if($cat_key>0){
                        $sub_category_array[trim($each_category_array[0])][] = trim($each_category_name);
                     }
                     
               }
            }
               
         }
         else{
            /*Here using same logic as in its 'if' part but here we are checking that same value is existing or not in 'main_category_array' to avoid duplicate creation of categories and sub categories.*/
            if(trim($each_category_array[0])!='Undefined'){
               if(!in_array(trim($each_category_array[0]),$main_category_array)){
                  $main_category_array[trim($each_category_array[0])] = trim($each_category_array[0]);
                  if(count($each_category_array)>0){

                     foreach($each_category_array as $cat_key => $each_category_name){
                        if($cat_key>0){
                           if(!empty($sub_category_array[$each_category_array[0]])){
                              if(!in_array($each_category_name, $sub_category_array[$each_category_array[0]])){
                                 $sub_category_array[trim($each_category_array[0])][] = trim($each_category_name);
                              }
                           }
                           else{
                              $sub_category_array[trim($each_category_array[0])][] = trim($each_category_name);
                           }
                        }
                     }

                  }
               }
            }
            
         }
      }
      echo "<pre>";
      print_r($main_category_array);
      echo "<br />";
      echo "<pre>";
      print_r($sub_category_array);

      /*Insert category and sub category in database */
      /* Fetching all categories from database */
      echo "<br />";
      $db_cat_array = array();
      $db_categories_array = Category::get();
      if(count($db_categories_array)>0){

         $db_categories_array = $db_categories_array->toArray();

         foreach($db_categories_array as $db_category_info){
            $db_cat_array[$db_category_info['id']] = $db_category_info['name'];
         }
      }

      echo "<pre>";
      print_r($db_cat_array);
      echo "<br />";

      foreach($sub_category_array as $category_key=>$sub_catgeory_arr){

         echo "<pre>";
         print_r($sub_catgeory_arr);

         /*Checking category alredy exists or not. Here "category_key" is main category.*/
         $check_category = Category::where('name',$category_key)->get()->first();

         if(count($check_category)>0){// Category exists.
            $category_id = $check_category->id;
         }
         else{ // Category not exists.
            $create_category = Category::create([
               'name'         => $category_key,
               'created_at'   => date('Y-m-d H:i:s')
            ]);
            $category_id   = $create_category->id;
         }
         /* Here, Insert categories. */
         foreach($sub_catgeory_arr as $sub_category){

            $check_sub_category_name_in_category      = Category::where('name',$sub_category)->get()->first();
            $check_sub_category_name_in_sub_category  = SubCategory::where('name',$sub_category)->get()->first();

            if(count($check_sub_category_name_in_category)==0 || count($check_sub_category_name_in_sub_category)==0){
               $create_sub_category = SubCategory::create([
                  'category_id'  => $category_id,
                  'name'         => $sub_category,
                  'created_at'   => date('Y-m-d H:i:s')
               ]);
            }

         }
         /*--------------------------*/
         exit;
      }
      /*---------------------------------------*/
      // foreach($sub_category_array as $category_key=>$sub_catgeory_arr){
      //    if(count($db_cat_array)>0){
      //       if(in_array($category_key,$db_cat_array)){
      //          /*If category exists in 'db_cat_array' then fetch all sub categories of that category*/
      //          $db_sub_categories_array = SubCategory::where('name',$category_key)->get()->toArray();
      //          $db_sub_cat_array = array();
      //          foreach($db_sub_categories_array as $db_sub_category_key => $db_sub_category_info){
      //             $db_sub_cat_array[] = $db_sub_category_info['name'];
      //          }
      //          /*---------------------------------------------------------------------------------*/

      //          /* Inserting sub category to 'sub_categories' table, if new sub category found.*/
      //             foreach($sub_catgeory_arr as $sub_category_info){
      //                /*checking new category is found or not*/
      //                if(!in_array($sub_category_info['name'], $db_sub_cat_array)){
      //                   /*$inset_sub_category = SubCategory::create([
      //                      ]);*/
      //                }
      //                /*-------------------------------------*/
      //             }
                  
      //          /*-----------------------------------------------------------------------------*/
      //       }
      //       else{

      //       }
      //    }
         
      // }

      /*---------------------------------------------*/
      exit;
   }

   ################(CJ PRODUCT LIST)####################    
   public function getProducts()
   {

      $page=11; $count = 11;$records_per_page = 999;

        // Loop infinetely until all products insert in Database
        while(1){

            if($page<=$count){

                $api_key = "0096f97da97d7f7f77f616beb92b0466438a097175820b767d38696046101d83d491a3f18e9047ad82b3b4dd970c408d47fe30e54de632c1d17cf9bff4adef7b11/560970577ab1820ef72b171da72a26ad2ea91b03804cd005a47448ed2499ed59fdec7662ea1251a3df8f2b6443f17adffdba775b14dd2c4a55c15fff5a1523c1";
                $ch = curl_init();

                curl_setopt($ch, CURLOPT_URL, "https://product-search.api.cj.com/v2/product-search?website-id=8273790&advertiser-ids=joined&page-number=$page&records-per-page=$records_per_page");

                curl_setopt($ch, CURLOPT_HEADER,false); 

                curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization:$api_key"));
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $result = curl_exec($ch);
                $xml = new \SimpleXMLElement($result);

                $myfile = fopen("logs.txt", "a");
                $txt = "https://product-search.api.cj.com/v2/product-search?website-id=8273790&advertiser-ids=joined&page-number=$page&records-per-page=$records_per_page";
                fwrite($myfile, "\n". $txt);
                fclose($myfile);
               
                echo "<pre>";
                print_r($xml->products);exit;

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

                      $output = substr($category_info['advertiser-category'], 1, -1);
                      

                      $each_category_array_main = explode('>',$output);
                      $each_category_array = array_reverse($each_category_array_main);
                     /* echo "<pre>"; print_r($each_category_array_main); exit;*/

                      if(count($each_category_array)>0){
                         $check_in_category = 1;

                         foreach($each_category_array as $cat_key => $category_name){               
                             
                               $check_sub_category_exists = Category::where('name',trim($category_name))->orderBy('parent_id', 'asc')->get()->first();

                               if(count($check_sub_category_exists)>0){

                                  // Call add or edit function 
                                  $this->doAddOrEditProducts($category_info,$check_sub_category_exists->id);

                                  $check_in_category = 0;
                                  break;
                               }                
                         }  // End of foreach

                         // if category is not matched then add or edit the product with general category.
                         if($check_in_category==1){

                            // Call add or edit function 
                            $this->doAddOrEditProducts($category_info,1);
                            
                         }
                      }
                      else{
                         
                         // Call add or edit function 
                         $this->doAddOrEditProducts($category_info,1);
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
            $page++;

         /*if($page==5)
            break;*/
      }

      exit;
      

   }

    ################(CJ PRODUCT LIST)####################    
   public function doAddOrEditProducts($category_info,$cat_id)
   {
      // Check if the product is already exists
      $pro_dtls_chk_qry = Product::where('sku',$category_info['sku'])->where('api','CJ');

      // if the product is exists update the product else add the product     
      if($pro_dtls_chk_qry->count()){

         $update_product = Product::where('sku', $category_info['sku'])->update([
            'name'               => $category_info['name'],
            'ad-id'              => $category_info['ad-id'],
            'advertiser-id'      => $category_info['advertiser-id'],
            'advertiser-name'    => $category_info['advertiser-name'],
            'in-stock'           => ($category_info['in-stock']==true)?1:0,
            'sku'                => $category_info['sku'],
            'buy_url'            => $category_info['buy-url'],
            'image_url'          => $category_info['image-url'],
            'currency'           => $category_info['currency'],
            'price'              => $category_info['price'],
            'retail_price'       => $category_info['retail-price'],
            'api'                => 'CJ',
            'manufacturer_name'  => $category_info['manufacturer-name'],
            'updated_at'         => date('Y-m-d H:i:s')
         ]);

        $myfile = fopen("productlogs.txt", "a");
        $txt = $category_info['sku'];
        fwrite($myfile, "\n". $txt);
        fclose($myfile);

      }
      else{

         $insert_product = Product::create([
            'category_id'        => $cat_id,
            'name'               => $category_info['name'],
            'ad-id'              => $category_info['ad-id'],
            'advertiser-id'      => $category_info['advertiser-id'],
            'advertiser-name'    => $category_info['advertiser-name'],
            'advertiser-category'=> $category_info['advertiser-category'],
            'in-stock'           => ($category_info['in-stock']==true)?1:0,
            'sku'                => $category_info['sku'],
            'buy_url'            => $category_info['buy-url'],
            'image_url'          => $category_info['image-url'],
            'currency'           => $category_info['currency'],
            'price'              => $category_info['price'],
            'retail_price'       => $category_info['retail-price'],
            'api'                => 'CJ',
            'manufacturer_name'  => $category_info['manufacturer-name'],
            'created_at'         => date('Y-m-d H:i:s')
         ]);

        $myfile = fopen("productlogs.txt", "a");
        $txt = $category_info['sku'];
        fwrite($myfile, "\n". $txt);
        fclose($myfile);
      }
   }

################(CJ PRODUCT LIST)####################  
   function getProductsCustomList()
   {

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

      
   public function doAddOrEditProducts1($category_info,$cat_id)
   {
      $advertiser_category = (array)($category_info['advertiser-category']);
      $advertiser_category_array = array('main'=>'','sub'=>$advertiser_category);

      $product_description = (array)($category_info['description']);
      $product_description_array = array('short'=>'','long'=>$product_description);
      // Check if the product is already exists
      $pro_dtls_chk_qry = Product::where('sku',$category_info['sku'])->where('api','CJ');
      echo $category_info['sku'].'<br>';              
      // if the product is exists update the product else add the product     
      if($pro_dtls_chk_qry->count()){

         $update_product = Product::where('sku', $category_info['sku'])->update([
            'name'               => $category_info['name'],
            'ad-id'              => $category_info['ad-id'],
            'advertiser-id'      => $category_info['advertiser-id'],
            'advertiser-name'    => $category_info['advertiser-name'],
            'advertiser-category'=> serialize($advertiser_category_array),
            'in-stock'           => ($category_info['in-stock']==true)?1:0,
            'sku'                => $category_info['sku'],
            'buy_url'            => $category_info['buy-url'],
            'image_url'          => $category_info['image-url'],
            'currency'           => $category_info['currency'],
            'price'              => $category_info['price'],
            'description'        => serialize($product_description_array),
            'retail_price'       => $category_info['retail-price'],
            'api'                => 'CJ',
            'manufacturer_name'  => $category_info['manufacturer-name'],
            'updated_at'         => date('Y-m-d H:i:s')
         ]);
      }
      else{

         $insert_product = Product::create([
            'category_id'        => $cat_id,
            'name'               => $category_info['name'],
            'ad-id'              => $category_info['ad-id'],
            'advertiser-id'      => $category_info['advertiser-id'],
            'advertiser-name'    => $category_info['advertiser-name'],
            'advertiser-category'=> serialize($advertiser_category_array),
            'in-stock'           => ($category_info['in-stock']==true)?1:0,
            'sku'                => $category_info['sku'],
            'buy_url'            => $category_info['buy-url'],
            'image_url'          => $category_info['image-url'],
            'currency'           => $category_info['currency'],
            'description'        => serialize($product_description_array),
            'price'              => $category_info['price'],
            'retail_price'       => $category_info['retail-price'],
            'api'                => 'CJ',
            'manufacturer_name'  => $category_info['manufacturer-name'],
            'created_at'         => date('Y-m-d H:i:s')
         ]);

      }
   }
################(CJ PRODUCT LIST)####################  



   ######################## Link Share#############################

   /* Get product from linkshare api */
   public function getLinkshareProduct(){
      
      $all_vendor_mids = array('36586','41970','41342','41162','41306','40906','40572','40538','40530','40504','40239','39593','39540','39289','39270','39098','39097','39100','39101','39095','38889','38801','38267','38752','41064','38180','38095','38031','37981','37205','37206','37978','37938','37863','37635','37611','37119','36813','36780','36592','35291','24797','24359');

     foreach($all_vendor_mids as $vendor_mids){

      $page=1; $count = 1;$records_per_page = 100;

      // Loop infinetely until all products insert in Database
      while(1){     
         if($page<=$count){
            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, "http://productsearch.linksynergy.com/productsearch?token=418e7e72ff09e2ff6a95a8dd52ce8e62a82fc12678963ed7245da8096610356a&mid=$vendor_mids&max=$records_per_page&pagenumber=$page");

            curl_setopt($ch, CURLOPT_HEADER,false); 

            //curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization:$api_key"));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $result = curl_exec($ch);
            $xml = new \SimpleXMLElement($result);
            
            /*echo "<pre>";
            print_r($xml);exit;*/

            $item = (array)($xml);
             
            //$item_array = array
            if(isset($item['item'])){
              $all_products_arr = $item['item'];
            }
            else{
              $all_products_arr = array();
            }
            
           
            if(count($all_products_arr)>0){

               //$check_in_category = 1;
               $totalMatched = $item['TotalMatches'];
               $count = ceil($totalMatched/$records_per_page);
               $main_category_array = array();$sub_category_array  = array();

               foreach($all_products_arr as $key => $product_info){

                  $product_dtls = (array)($product_info);
                  $category_info = (array)($product_dtls['category']);

                  if(isset($category_info) && $category_info!=""){

                     if (!is_object($category_info['secondary'])) {

                        $secondary_category = $category_info['secondary'];

                        if(isset($secondary_category) && $secondary_category!=""){

                          $secondary_category_array = explode('~~',$secondary_category);
                          $primary_category_array = (array)($category_info['primary']);
                          $all_category = array_merge($primary_category_array,$secondary_category_array);
                          $category_array = array_reverse($all_category);
                        }
                        else{
                          $category_array = (array)($category_info['primary']);
                        }
                     }
                     else{
                        //$check_in_category = 1;
                        $category_array = (array)($category_info['primary']);
                     }
                     
                  }
                  else{
                        //$check_in_category = 1;
                     $category_array = array();
                  }
                  

                  if(count($category_array)>0){
                     $check_in_category = 1;

                     foreach($category_array as $cat_key => $category_name){               
                         
                           $check_sub_category_exists = Category::where('name',$category_name)->orderBy('parent_id', 'asc')->get()->first();

                           if(count($check_sub_category_exists)>0){

                              // Call add or edit function 
                              $this->addLinkshareProducts($product_dtls,$check_sub_category_exists->id);

                              $check_in_category = 0;
                              break;
                           }                
                     }  // End of foreach

                     // if category is not matched then add or edit the product with general category.
                     if($check_in_category==1){

                        // Call add or edit function 
                       $this->addLinkshareProducts($product_dtls,1);                        
                     }
                  }
                  else{
                    // Call add or edit function 
                    $this->addLinkshareProducts($product_dtls,1);
                  }
                  //exit;
               }
            }
         }
         else{
            // Check the products which are not updated, delete those products whose timestamp is greater than 1 days
            /*$product_ids = Product::select('id')->whereRaw('updated_at < DATE_SUB(NOW(), INTERVAL 1 DAY)')->get()->toArray();
            if(!empty($product_ids)){
               foreach ($product_ids as $key => $product_id) {
                  //Product::where('id', $product_id)->delete();
               }
            }*/

            break;
         }
         //sleep(30);
         echo $page.'....<br>';
         $page++;
         if($page==11)
            break;
      }
    }
            
   }
   /*---------------------------------*/
  public function addLinkshareProducts($product_info,$cat_id){

    $advertiser_category = (array)($product_info['category']);
    $advertiser_category_array = array('main'=>(array)$advertiser_category['primary'],'sub'=>(array)$advertiser_category['secondary']);

    $product_description = (array)($product_info['description']);
    $product_description_array = array('short'=>(array)$product_description['short'],'long'=>(array)$product_description['long']);

      // Check if the product is already exists
      $pro_dtls_chk_qry = Product::where('sku',$product_info['sku'])->where('api','LS');
      echo $product_info['sku'].'<br>';
      // if the product is exists update the product else add the product     
      if($pro_dtls_chk_qry->count()){

         $update_product = Product::where('sku', $product_info['sku'])->update([
            //'category_id'        => $cat_id,
            'name'               => $product_info['productname'],
            'ad-id'              => $product_info['mid'],
            'advertiser-id'      => $product_info['linkid'],
            'advertiser-name'    => $product_info['merchantname'],
            'advertiser-category'=> serialize($advertiser_category_array),
            'in-stock'           => 1,
            'sku'                => $product_info['sku'],
            'buy_url'            => $product_info['linkurl'],
            'image_url'          => $product_info['imageurl'],
            'currency'           => 'USD',
            'description'        => serialize($product_description_array),
            'price'              => $product_info['price'],
            'sale_price'         => $product_info['saleprice'],
            'api'                => 'LS',
            'updated_at'         => date('Y-m-d H:i:s')
         ]);

      }
      else{

         $insert_product = Product::create([

            'category_id'        => $cat_id,
            'name'               => $product_info['productname'],
            'ad-id'              => $product_info['mid'],
            'advertiser-id'      => $product_info['linkid'],
            'advertiser-name'    => $product_info['merchantname'],
            'advertiser-category'=> serialize($advertiser_category_array),
            'in-stock'           => 1,
            'sku'                => $product_info['sku'],
            'buy_url'            => $product_info['linkurl'],
            'image_url'          => $product_info['imageurl'],
            'currency'           => 'USD',
            'description'        => serialize($product_description_array),
            'price'              => $product_info['price'],
            'sale_price'         => $product_info['saleprice'],
            'api'                => 'LS',
            'created_at'         => date('Y-m-d H:i:s')

         ]);
      }
   }

public function getDeleteDuplicate(){

  $all_pro = DB::table("products")->where('product_id','!=','9223372036854775807')->select(DB::raw("COUNT(id) count, id"))->groupBy("product_id")->havingRaw("COUNT(id) > 1")->get();
  //echo '<pre>';print_r($all_pro);exit;
  foreach ($all_pro as $key => $value) {
    Product::where('id', $value->id)->delete();
  }

}

public function getHitBackgroud(){
  
  echo system('kill 2890');


  $cmd = "wget -bq --spider ".url()."/linkshare";
  //$cmd = "wget -bq --spider http://localhost/cashback/cron/delete-duplicate";
  echo $pid = shell_exec(escapeshellcmd($cmd));
}

 public function getFtpLinkshareDatafeed(){

      //$all_vendor_mids = array('41342','41162','40906','40572','40538','40530','40239','39593','39540','39289','39270','39098','39097','39100','39101','39095','38889','38801','38267','38752','38180','38095','38031','37981','37205','37206','37978','37938','37863','37635','37611','37119','36813','36592','24797','24359','41970','36586');
      
      $all_vendor_mids = array('38267','38752','38180','38095','38031','37981','37205','37206','37978','37938','37863','37635','37611','37119','36813','36592','24797','24359','41970','36586');

      $ftp_server="aftp.linksynergy.com";
      $ftp_user_name="Ulab";
      $ftp_user_pass="4DW6hjPJ";

      foreach ($all_vendor_mids as $key1 => $vendor_mid) {

        $conn_id = ftp_connect($ftp_server);
        // login with username and password
        $login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);

        $myfile = fopen("logs.txt", "a");
        $txt = "$key1 vendor_id=$vendor_mid";
        fwrite($myfile, "\n". $txt);
        fclose($myfile);
        // Report all errors
        

        $local_file = 'linksharedata/'.$vendor_mid.'_3267514_mp_delta.xml.gz';
        $server_file = '/'.$vendor_mid.'_3267514_mp_delta.xml.gz';
        $file_exists_check = ftp_size($conn_id, $server_file);

        if ($file_exists_check != -1){
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
              // Read buffer-size bytes
              // Both fwrite and gzread and binary-safe
              fwrite($out_file, gzread($file, $buffer_size));            
          }

          // Files are done, close files
          fclose($out_file);
          gzclose($file);

          ########### Same file xml parsing and perfrom for same #####################
          //$feed = file_get_contents('linksharedata/40906_3267514_mp_delta.xml');
          //$xml = simplexml_load_string($feed);
          
          $xml=simplexml_load_file($out_file_name) or die("Error: Cannot create object");
          
          $items = (array)($xml);
          //echo "<pre>";print_r($items);exit;
          $vendor_details = (array)$items['header'];
          
          if(isset($items['product'])){
            if(count($items['product'])>0){

              $myfile = fopen("logs.txt", "a");
              $txt = "total product ".count($items['product']);
              fwrite($myfile, "\n". $txt);
              fclose($myfile);

              foreach ($items['product'] as $key => $item) {
                
                if($key>=0){
              
                  $details = (array)$item;

                  ############# Checking mofication is delect loop start from starting ###########
                  if(isset($details['modification'])){
                    if($details['modification']== 'D'){
                      $myfile = fopen("logs.txt", "a");
                      $txt = "keyI $key ".$details['@attributes']['product_id'];
                      fwrite($myfile, "\n". $txt);
                      fclose($myfile);
                      Product::where('product_id',$details['@attributes']['product_id'])->where('api','LS')->delete();
                      //exit($details['@attributes']['product_id']);
                      continue;
                    }
                  }   

                  $myfile = fopen("logs.txt", "a");
                  $txt = "keyO $key ".$details['@attributes']['product_id'];
                  fwrite($myfile, "\n". $txt);
                  fclose($myfile);
                  $main_category_array = array();$sub_category_array  = array();

                  $product_dtls = (array)($item);
                  $category_info = (array)($product_dtls['category']);

                  $myfile = fopen("logs.txt", "a");
                  //$txt = "$key1  $key  product_id=".$product_dtls['@attributes']['product_id'];
                  $txt = "$key  product_id=".$product_dtls['@attributes']['product_id'];
                  fwrite($myfile, "\n". $txt);
                  fclose($myfile);

                  if(isset($category_info) && $category_info!=""){

                     if (!empty($category_info['secondary'])) {

                        $secondary_category = $category_info['secondary'];

                        if(isset($secondary_category) && $secondary_category!=""){

                          $secondary_category_array = explode('~~',$secondary_category);
                          $primary_category_array = (array)($category_info['primary']);
                          $all_category = array_merge($primary_category_array,$secondary_category_array);
                          $category_array = array_reverse($all_category);
                        }
                        else{
                          $category_array = (array)($category_info['primary']);
                        }
                     }
                     else{
                        //$check_in_category = 1;
                        $category_array = (array)($category_info['primary']);
                     }
                     
                  }
                  else{
                        //$check_in_category = 1;
                     $category_array = array();
                  }
                  

                  if(count($category_array)>0){
                     $check_in_category = 1;

                     foreach($category_array as $cat_key => $category_name){             
                         
                           $check_sub_category_exists = Category::where('name',$category_name)->orderBy('parent_id', 'asc')->get()->first();

                           if(count($check_sub_category_exists)>0){

                              // Call add or edit function 
                              $this->addLinkshareProducts1($product_dtls,$check_sub_category_exists->id,$vendor_details);

                              $check_in_category = 0;
                              break;
                           }                
                     }  // End of foreach

                     // if category is not matched then add or edit the product with general category.
                     if($check_in_category==1){

                        // Call add or edit function 
                       $this->addLinkshareProducts1($product_dtls,1,$vendor_details);                        
                     }
                  }
                  else{
                    // Call add or edit function 
                    $this->addLinkshareProducts1($product_dtls,1,$vendor_details);
                  }
                }              
                  
              }
              
            }else{
              $myfile = fopen("logs.txt", "a");
              $txt = "no product found\n";
              fwrite($myfile, "\n". $txt);
              fclose($myfile);
            }
          }
          //exit('die');
        // close the connection
        }
      ftp_close($conn_id);
    }
  }

   
    public function addLinkshareProducts1($product_info,$cat_id,$vendor_details){

      $error_print = error_reporting(E_ALL);
            
      $advertiser_category = (array)($product_info['category']);
      $advertiser_category_array = array('main'=>isset($advertiser_category['primary'])?$advertiser_category['primary']:'','sub'=>isset($advertiser_category['secondary'])?$advertiser_category['secondary']:'');

      $product_description = (array)($product_info['description']);
      $product_description_array = array('short'=>isset($product_description['short'])?$product_description['short']:'','long'=>isset($product_description['long'])?$product_description['long']:'');

      ########### other attribuates ##################
      if(isset($product_info['attributeClass'])){

        $AllAttributes = (array)($product_info['attributeClass']);
        $Product_Type = isset($AllAttributes['Product_Type'])?$AllAttributes['Product_Type']:'';
        $Size = isset($AllAttributes['Size'])?$AllAttributes['Size']:'';
        $Color = isset($AllAttributes['Color'])?$AllAttributes['Color']:'';
        $Gender = isset($AllAttributes['Gender'])?$AllAttributes['Gender']:'';
        $Age = isset($AllAttributes['Age'])?$AllAttributes['Age']:'';
      }else{
        $Product_Type = '';
        $Size = '';
        $Color = '';
        $Gender = '';
        $Age = '';
      }
      ########### other attribuates ##################

      ################# SHiping details check ###############
      if(isset($product_info['shipping'])){

        $shippingdetails = (array)($product_info['shipping']);
        $json  = json_encode($shippingdetails);
        $configData = json_decode($json, true);
        $shippingdetails = serialize($configData);
      }else{
        $shippingdetails = '';
      }
      ################# SHiping details check ###############

      ################## brand check ##########################
      if(isset($product_info['brand'])){

        $check_brand_exists = Brand::where('brand_name',$product_info['brand'])->first();

        if(count($check_brand_exists)>0){
          $brandId = $check_brand_exists->id;
        }else{
          $brandId = 0;
        }

      }else{
          $brandId = 0;
      }
      ################## brand check ##########################

      // Check if the product is already exists
      $pro_dtls_chk_qry = Product::where('product_id',$product_info['@attributes']['product_id'])->where('api','LS');
      
      $currency_details= (array)$product_info['price'];
      $currency = $currency_details['@attributes']['currency'];
      // if the product is exists update the product else add the product 

      if((isset($vendor_details['modification']) && $vendor_details['modification']=='U') || ($pro_dtls_chk_qry->count()>0)){

         $updatedetails = $pro_dtls_chk_qry->get()->first();

         $update_product = Product::where('product_id', $product_info['@attributes']['product_id'])->update([
            //'category_id'      => $cat_id,
            'product_id'         => $product_info['@attributes']['product_id'],
            'name'               => $product_info['@attributes']['name'],
            'advertiser-id'      => $vendor_details['merchantId'],
            'advertiser-name'    => $vendor_details['merchantName'],
            'advertiser-category'=> serialize($advertiser_category_array),
            'in-stock'           => ($product_info['shipping']->availability==true)?1:0,
            'sku'                => $product_info['@attributes']['sku_number'],
            'buy_url'            => $product_info['URL']->product,
            'image_url'          => $product_info['URL']->productImage,
            'currency'           => $currency,
            'description'        => serialize($product_description_array),
            'price'              => $product_info['price']->retail,
            'sale_price'         => $product_info['price']->sale,
            'brand_id'           => $brandId,
            'product_type'       => $Product_Type,
            'color'              => $Color,
            'size'               => $Size,
            'gender'             => $Gender,
            'age'                => $Age,
            'shiping_details'    => $shippingdetails,
            'api'                => 'LS',
            'updated_at'         => date('Y-m-d H:i:s')
         ]);

          $myfile = fopen("logs.txt", "a");
          $txt = "update_id=".$update_product['id'];
          $txt .= " error $error_print";
          fwrite($myfile, "\n". $txt);
          fclose($myfile);

      }
      else{

         $insert_product = Product::create([

            'category_id'        => $cat_id,
            'product_id'         => $product_info['@attributes']['product_id'],
            'name'               => $product_info['@attributes']['name'],
            'advertiser-id'      => $vendor_details['merchantId'],
            'advertiser-name'    => $vendor_details['merchantName'],
            'advertiser-category'=> serialize($advertiser_category_array),
            'in-stock'           => ($product_info['shipping']->availability==true)?1:0,
            'sku'                => $product_info['@attributes']['sku_number'],
            'buy_url'            => $product_info['URL']->product,
            'image_url'          => $product_info['URL']->productImage,
            'currency'           => $currency,
            'description'        => serialize($product_description_array),
            'price'              => $product_info['price']->retail,
            'sale_price'         => $product_info['price']->sale,
            'brand_id'           => $brandId,
            'product_type'       => $Product_Type,
            'color'              => $Color,
            'size'               => $Size,
            'gender'             => $Gender,
            'age'                => $Age,
            'shiping_details'    => $shippingdetails,
            'api'                => 'LS',
            'created_at'         => date('Y-m-d H:i:s'),
            'updated_at'         => date('Y-m-d H:i:s')

         ]);

          $myfile = fopen("logs.txt", "a");
          $txt = "insert_id=".$insert_product['id'];
          $txt .= " error $error_print";
          fwrite($myfile, "\n". $txt);
          fclose($myfile);
      }
    }



  public function getLinkshareVendorList(){

      //$all_vendor_mids = array('41342','41162','40906','40572','40538','40530','40239','39593','39540','39289','39270','39098','39097','39100','39101','39095','38889','38801','38267','38752','38180','38095','38031','37981','37205','37206','37978','37938','37863','37635','37611','37119','36813','36592','24797','24359','41970','36586');
      
      $all_vendor_mids = array('38267','38752','38180','38095','38031','37981','37205','37206','37978','37938','37863','37635','37611','37119','36813','36592','24797','24359','41970','36586');

      $ftp_server="aftp.linksynergy.com";
      $ftp_user_name="Ulab";
      $ftp_user_pass="4DW6hjPJ";

      foreach ($all_vendor_mids as $key1 => $vendor_mid) {

        $conn_id = ftp_connect($ftp_server);
        // login with username and password
        $login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);

        $myfile = fopen("logs.txt", "a");
        $txt = "$key1 vendor_id=$vendor_mid";
        fwrite($myfile, "\n". $txt);
        fclose($myfile);
        // Report all errors
        

        $local_file = 'linksharevendor/'.$vendor_mid.'_3267514_mp_deltatemplate.txt.gz';
        $server_file = '/'.$vendor_mid.'_3267514_mp_deltatemplate.txt.gz';
        $file_exists_check = ftp_size($conn_id, $server_file);

        if ($file_exists_check != -1){
            // try to download $server_file and save to $local_file
          if (ftp_get($conn_id, $local_file, $server_file, FTP_BINARY)) {
              //echo "Successfully written to $local_file\n";
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
              // Read buffer-size bytes
              // Both fwrite and gzread and binary-safe
              fwrite($out_file, gzread($file, $buffer_size));            
          }

          // Files are done, close files
          fclose($out_file);
          gzclose($file);
          $i=0;
          $fh = fopen($out_file_name,'r');
          while ($line = fgets($fh)) {
            // <... Do your work with the line ...>
            print_r($line); echo '<br>';
            echo $i.'<br>';
            $i++;
          }
          fclose($fh);
          exit;
          $xml=simplexml_load_file($out_file_name) or die("Error: Cannot create object");
          
          $items = (array)($xml);
          echo "<pre>";print_r($items);exit;
        }
        ftp_close($conn_id);
      }
  }
    /**############### Link Sharer Vendor Import ##############**/
   public function getLinkshareVendorList1(){

        $api_key = 'Bearer a9c48f6a96c092614790aa6349e698b9';
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, "https://api.rakutenmarketing.com/advertisersearch/1.0");

        curl_setopt($ch, CURLOPT_HEADER,false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization:$api_key"));

        //curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization:$api_key"));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        $xml = new \SimpleXMLElement($result);
         echo "1<pre>";print_r($xml);exit;
        $item = (array)($xml);
         
        //$item_array = array
        if(isset($item['midlist'])){
          $all_arr = (array)$item['midlist'];
          $all_vendor_arr = (array)$all_arr['merchant'];
        }
        else{
          $all_vendor_arr = array();
        }
       
        if(count($all_vendor_arr)>0){

           foreach($all_vendor_arr as $key => $vendor_info){

              $vendor_details = (array)$vendor_info;
              $this->addLinkshareVendors($vendor_details);
           }
        }
   }
   /*---------------------------------*/
   public function addLinkshareVendors($vendor_info){

      // Check if the product is already exists
      $pro_dtls_chk_qry = Vendor::where('advertiser-id',$vendor_info['mid'])->where('api','LS');
      // if the product is exists update the product else add the product     
      if($pro_dtls_chk_qry->count()){

         $update_vendor = Vendor::where('advertiser-id', $vendor_info['mid'])->update([
            
            'advertiser-id'      => $vendor_info['mid'],
            'advertiser-name'    => $vendor_info['merchantname'],
            'api'                => 'LS',
            'updated_at'         => date('Y-m-d H:i:s')
         ]);

      }
      else{

         $insert_vendor = Vendor::create([

            'advertiser-id'      => $vendor_info['mid'],
            'advertiser-name'    => $vendor_info['merchantname'],
            'api'                => 'LS',
            'updated_at'         => date('Y-m-d H:i:s')

         ]);
      }
   }
   /**############### Link Sharer Vendor Import ##############**/




   /**############### CJ Vendor Import ##############**/
   public function getCJVendorList(){

      $page=43; $count = 43;$records_per_page = 99;
        // Loop infinetely until all products insert in Database
      while(1){

        if($page<=$count){

          $api_key = '00886b33ffd47cd19a92c2cd3e4b361be0b9254b9c68080ff736ce10f059ab9abcdf9970fbf630557ccc6cf5f5d3c620917725e4906537bd0ad99f0d937530c173/4722073a461553c252f68ac909b4cc3290b836c5f6a5059635989bd2881f680e82d09b6e84a07442148411ed84a6d0f0eebf5a19f362d02d967f359e5bd76449';
          $ch = curl_init();

          curl_setopt($ch, CURLOPT_URL, "https://link-search.api.cj.com/v2/link-search?website-id=7893798&advertiser-ids=joined&records-per-page=$records_per_page&page-number=$page");

          curl_setopt($ch, CURLOPT_HEADER,false);
          curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization:$api_key"));

          //curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization:$api_key"));
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
          $result = curl_exec($ch);
          $xml = new \SimpleXMLElement($result);
         // echo "1<pre>";print_r($xml);exit; 
          $item = (array)($xml);
          $item = (array)($item['links']);
           
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

             foreach($all_vendor_arr as $key => $vendor_info){

                $vendor_details = (array)$vendor_info;
                $myfile = fopen("logs.txt", "a");
                $txt = "page=".$page." id= ".$vendor_details['advertiser-id'];
                fwrite($myfile, "\n". $txt);
                fclose($myfile);
                $this->addCJVendors($vendor_details);
             }
          }
        }
        $page++;
        if($page==100)
          break;
      }
   }
   /*---------------------------------*/
   public function addCJVendors($vendor_info){
      // Check if the product is already exists
      $pro_dtls_chk_qry = Vendor::where('advertiser-id',$vendor_info['advertiser-id'])->where('api','CJ');
      // if the product is exists update the product else add the product  
     // echo "<pre>";
      $vendor_details = $pro_dtls_chk_qry->get();
    
      preg_match('/src="([^"]+)"/', $vendor_info['link-code-html'], $match);
     // print_r($match[1]);exit;
      if (!isset($match[1])) {
             $match[1] = '';
          }

      $url = $match[1];

      if($vendor_details[0]['image'] == 'no-image.png' && $url != '')
      {
           $newimg = $url; 
      }
      else
      {
          $newimg = $vendor_details[0]['image'];
      }
      
      if($pro_dtls_chk_qry->count()){

         $update_vendor = Vendor::where('advertiser-id', $vendor_info['advertiser-id'])->update([
            
            'image'              => $newimg,
            'api_image'          => $url,
            'api'                => 'CJ',
            'updated_at'         => date('Y-m-d H:i:s')
         ]);

      }
      else{

          $insert_vendor = Vendor::create([

            'advertiser-id'      => $vendor_info['advertiser-id'],
            'advertiser-name'    => $vendor_info['advertiser-name'],
            'vendor_url'         => isset($vendor_info['clickUrl'])?$vendor_info['clickUrl']:'',
            'site_url'           => isset($vendor_info['destination'])?$vendor_info['destination']:'',
            'image'              => $newimg,
            'api_image'          => $url,
            'percentage'         => str_replace("%","",$vendor_info['sale-commission']),
            'description'        => isset($vendor_info['description'])?$vendor_info['description']:'',
            'short_description'  => isset($vendor_info['description'])?$vendor_info['description']:'',
            'api'                => 'CJ',
            'updated_at'         => date('Y-m-d H:i:s')

          ]);
      }
   }
   /**############### CJ Vendor Import ##############**/

   public function getCjvendorbghit(){
    //echo "string";
    $cmd = "wget -bq --spider ".url()."/cjvendor";
    echo $id = shell_exec(escapeshellcmd($cmd));
  }

  public function getReportApiForLinkShare(){

    $api_key = 'Basic alNuVGFWdVBwWnZJTUxmWnRXcU5Hb2QzeXMwYTpud0taZ0RIYVZPMmE4Z2hucjZpR1RUNU43REVh';
    $grant_type ='password';
    $username = 'Ulab';
    $password = '!Temp1337';
    $scope = '3267514';

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, "https://api.rakutenmarketing.com/token");

    curl_setopt($ch, CURLOPT_HEADER,false); 

    curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization:$api_key"));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=$grant_type&username=$username&password=$password&scope=$scope");
    $result = curl_exec($ch);

    $decode_data = json_decode($result);
    $access_token = $decode_data->access_token;

    if($access_token){

      $authorization_key = 'Bearer '.$access_token;
      $yesterday_date = date('Y-m-d',strtotime("-1 days"));
      $page=1;$records_per_page =100;

      while(1){

          $url ='https://api.rakutenmarketing.com/events/1.0/transactions?limit='.$records_per_page.'&page='.$page.'&transaction_date_start='.urlencode($yesterday_date.' 00:00:00').'&transaction_date_end='.urlencode($yesterday_date.' 23:59:59');

          $header = array("Authorization: ".$authorization_key);

          $ch = curl_init();
          curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
          curl_setopt($ch, CURLOPT_URL, $url);
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
          curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.0.3705; .NET CLR 1.1.4322)');

          $retValue = curl_exec($ch);

          $decode_order_data = json_decode($retValue);
          //echo "<pre>";print_r($retValue);exit;
          if(empty($decode_order_data)){
            break;
          }else{
            foreach ($decode_order_data as $key => $order_data) {
              $order_info = (array)$order_data;

              if($order_info['is_event'] == 'N' && (!empty($order_info))){
                //INSER DATA TO ORDER HISTORY TABLE
                $this->doAddOrEditOrderHistroy($order_info);
              }
            }
          }
          $page++;
          if($page==100)
            break;
      }
    }
  }

  //Insert Code at order table
  public function doAddOrEditOrderHistroy($order_info)
  {
      // Check if the order is already exists
      $order_dtls_chk_qry = OrderHistory::where('order_id',$order_info['order_id'])->where('etransaction_id',$order_info['etransaction_id']);
      if(isset($order_info['advertiser_id'])){
        $vendor_details = Vendor::where('advertiser-id',$order_info['advertiser_id'])->select(['percentage'])->first()->toArray();
        $cashback_amount = round(($order_info['sale_amount']*$vendor_details['percentage']/100),2);

        $user_details = SiteUser::where('id',$order_info['u1'])->select(['cashback','email','name','last_name'])->first()->toArray();
        $total_cashback = $user_details['cashback']+$cashback_amount;

        $update_order = SiteUser::where('id', $order_info['u1'])->update(['cashback'  => $total_cashback]);

      }else{
        $cashback_amount = 0;
      }
      
      // if the order is exists update the order else add the order     
      if($order_dtls_chk_qry->count()){

         $update_order = OrderHistory::where('order_id', $order_info['order_id'])->update([
            'etransaction_id'  => $order_info['etransaction_id'],
            'advertiser_id'    => $order_info['advertiser_id'],
            'sid'              => $order_info['sid'],
            'offer_id'         => $order_info['offer_id'],
            'sku_number'       => $order_info['sku_number'],
            'sale_amount'      => $order_info['sale_amount'],
            'quantity'         => $order_info['quantity'],
            'commissions'      => $order_info['commissions'],
            'cashback_amount'  => $cashback_amount,
            'process_date'     => date('Y-m-d H:i:s',strtotime($order_info['process_date'])),
            'transaction_date' => date('Y-m-d H:i:s',strtotime($order_info['transaction_date'])),
            'transaction_type' => $order_info['transaction_type'],
            'product_name'     => $order_info['product_name'],
            'user_id'          => $order_info['u1'],
            'currency'         => $order_info['currency'],
            'created_at'       => date('Y-m-d H:i:s')
         ]);
      }else{

         $insert_order = OrderHistory::create([
            'etransaction_id'  => $order_info['etransaction_id'],
            'advertiser_id'    => $order_info['advertiser_id'],
            'sid'              => $order_info['sid'],
            'order_id'         => $order_info['order_id'],
            'offer_id'         => $order_info['offer_id'],
            'sku_number'       => $order_info['sku_number'],
            'sale_amount'      => $order_info['sale_amount'],
            'quantity'         => $order_info['quantity'],
            'commissions'      => $order_info['commissions'],
            'cashback_amount'  => $cashback_amount,
            'process_date'     => date('Y-m-d H:i:s',strtotime($order_info['process_date'])),
            'transaction_date' => date('Y-m-d H:i:s',strtotime($order_info['transaction_date'])),
            'transaction_type' => $order_info['transaction_type'],
            'product_name'     => $order_info['product_name'],
            'user_id'          => $order_info['u1'],
            'currency'         => $order_info['currency'],
            'created_at'       => date('Y-m-d H:i:s')
         ]);


         /**************************SEND MAIL -start *****************************/
        
          $site = Sitesetting::where(['name' => 'email'])->first();
          $admin_users_email = $site->value;

          $user_name = $user_details['name'].' '.$user_details['last_name'];
          $user_email = $user_details['email'];
          
          $subject = "Casback added Front Mode wallet";
          $message_body = "Casback $".$cashback_amount." added into your Front Mode wallet<br><br>";
          $message_body .= "Purchase Amount            :$".$order_info['sale_amount']."<br>";
          $message_body .= "Cashback Amount            :$".$cashback_amount."<br>";
          $message_body .= "Product Name            :".$order_info['product_name']."<br>";
          $message_body .= "Quantity           :".$order_info['quantity']."<br>";
          $message_body .= "Order Id      :".$order_info['order_id']."<br>";
          $message_body .= "Payment Date      :".$order_info['transaction_date']."<br>";
          
          $mail = Mail::send(['html' => 'admin.emailtemplate.send_mail'], array('message_body' => $message_body, 'user_name'=>$user_name), function($message) use ($admin_users_email, $user_email,$user_name,$subject)
          {
            $message->from($admin_users_email,'Front Mode');
      
            $message->to($user_email)->subject($subject);
          });

          $subjectA = $user_name." wallet $".$cashback_amount." cashback added";
          $message_bodyA = "Casback $".$cashback_amount." added into ".$user_name." Front Mode wallet<br><br>";
          $message_bodyA .= "User name            :".$user_name."<br>";
          $message_bodyA .= "Purchase Amount            :$".$order_info['sale_amount']."<br>";
          $message_bodyA .= "Cashback Amount            :$".$cashback_amount."<br>";
          $message_bodyA .= "Eran Commissions            :$".$order_info['commissions']."<br>";
          $message_bodyA .= "Product Name            :".$order_info['product_name']."<br>";
          $message_bodyA .= "Quantity           :".$order_info['quantity']."<br>";
          $message_bodyA .= "Order Id      :".$order_info['order_id']."<br>";
          $message_bodyA .= "Payment Date      :".$order_info['transaction_date']."<br>";
          
          
          $mail = Mail::send(['html' => 'admin.emailtemplate.send_mail'], array('message_body' => $message_bodyA, 'user_name'=>'Admin'), function($message) use ($admin_users_email,$subjectA)
          {
            $message->from($admin_users_email,'Front Mode');
      
            $message->to($admin_users_email)->subject($subjectA);
          });
        
          /**************************SEND MAIL -start *****************************/ 
      }
  }

  /**############### CJ Report Import ##############**/
   public function getReportApiForCj(){

      $end_date = date('Y-m-d');
      $start_date = date('Y-m-d',strtotime($end_date . ' - 1 month'));



     /* $start_date = '2017-05-20';
      $end_date = '2017-06-19';*/

      $api_key = '00886b33ffd47cd19a92c2cd3e4b361be0b9254b9c68080ff736ce10f059ab9abcdf9970fbf630557ccc6cf5f5d3c620917725e4906537bd0ad99f0d937530c173/4722073a461553c252f68ac909b4cc3290b836c5f6a5059635989bd2881f680e82d09b6e84a07442148411ed84a6d0f0eebf5a19f362d02d967f359e5bd76449';
      $ch = curl_init();

      curl_setopt($ch, CURLOPT_URL, "https://commission-detail.api.cj.com/v3/commissions?date-type=posting&start-date=".urlencode($start_date)."&end-date=".urlencode($end_date));

      curl_setopt($ch, CURLOPT_HEADER,false);
      curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization:$api_key"));
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      $result = curl_exec($ch);

      $xml = new \SimpleXMLElement($result);
     
      $item = (array)($xml);
      $commissions = (array)($item);
      $commissions = (array)(isset($item['commissions'])?$item['commissions']:'');

        //echo "1<pre>";print_r($commissions);exit; 

       if(!empty($commissions)){

          foreach ($commissions as $key => $order_info) {
           
            if($key=='@attributes')
              continue;

            $order_info = (array)$order_info;

            //echo "<pre>";print_r($order_info['cid']);exit;
            $order_dtls_chk_qry = OrderHistory::where('order_id',$order_info['order-id']);
            if($order_dtls_chk_qry->count()==0)
            {

                if(isset($order_info['cid'])){
                  $vendor_details = Vendor::where('advertiser-id',$order_info['cid'])->select(['percentage'])->first()->toArray();
                  $cashback_amount = round(($order_info['sale-amount']*$vendor_details['percentage']/100),2);

                  $user_details = SiteUser::where('id',$order_info['sid'])->select(['cashback','email','name','last_name'])->first()->toArray();
                  $total_cashback = $user_details['cashback']+$cashback_amount;

                  $update_order = SiteUser::where('id', $order_info['sid'])->update(['cashback'  => $total_cashback]);

                }else{
                  $cashback_amount = 0;
                }

                // Call Item-Detail API to get details
                $ch = curl_init();

                curl_setopt($ch, CURLOPT_URL, "https://commission-detail.api.cj.com/v3/item-detail/".$order_info['original-action-id']);

                curl_setopt($ch, CURLOPT_HEADER,false);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization:$api_key"));
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $result = curl_exec($ch);

                $xml = new \SimpleXMLElement($result);
               
                $item = (array)($xml);
                $item_details = (array)($item);
                $item_details = (array)(isset($item_details['item-details'])?$item['item-details']:'');
                

                $quantity = 0;$sku_number = '';

                if(!empty($item_details)){
                  $item_details = (array)$item_details['item'];

                  $sku_number = $item_details['sku'];
                  $quantity = $item_details['quantity'];
                }

                //echo "<pre>";print_r($sku_number);exit; 



                $insert_order = OrderHistory::create([
                'advertiser_id'    => $order_info['cid'],
                'sid'              => $order_info['sid'],
                'order_id'         => $order_info['order-id'],
                'sale_amount'      => $order_info['sale-amount'],
                'commissions'      => $order_info['commission-amount'],
                'cashback_amount'  => $cashback_amount,
                'process_date'     => date('Y-m-d H:i:s',strtotime($order_info['posting-date'])),
                'transaction_date' => date('Y-m-d H:i:s',strtotime($order_info['event-date'])),
                'transaction_type' => $order_info['action-type'],
                'product_name'     => $order_info['action-tracker-name'],
                'user_id'          => $order_info['sid'],
                'commission_id'    => $order_info['commission-id'],
                'sku_number'       => $sku_number,
                'quantity'         => $quantity,
                'created_at'       => date('Y-m-d H:i:s')
                ]);



                /**************************SEND MAIL -start *****************************/
            
                  $site = Sitesetting::where(['name' => 'email'])->first();
                  $admin_users_email = $site->value;

                  $user_name = $user_details['name'].' '.$user_details['last_name'];
                  $user_email = $user_details['email'];
                  
                  $subject = "Casback added Front Mode wallet";
                  $message_body = "Casback $".$cashback_amount." added into your Front Mode wallet<br><br>";
                  $message_body .= "Purchase Amount            :$".$order_info['sale-amount']."<br>";
                  $message_body .= "Cashback Amount            :$".$cashback_amount."<br>";
                  $message_body .= "Product Name            :".$order_info['action-tracker-name']."<br>";
                  $message_body .= "Order Id      :".$order_info['order-id']."<br>";
                  $message_body .= "Payment Date      :".date('Y-m-d H:i:s',strtotime($order_info['event-date']))."<br>";
                  
                  $mail = Mail::send(['html' => 'admin.emailtemplate.send_mail'], array('message_body' => $message_body, 'user_name'=>$user_name), function($message) use ($admin_users_email, $user_email,$user_name,$subject)
                  {
                    $message->from($admin_users_email,'Front Mode');
              
                    $message->to($user_email)->subject($subject);
                  });

                  $subjectA = $user_name." wallet $".$cashback_amount." cashback added";
                  $message_bodyA = "Casback $".$cashback_amount." added into ".$user_name." Front Mode wallet<br><br>";
                  $message_bodyA .= "User name            :".$user_name."<br>";
                  $message_bodyA .= "Purchase Amount            :$".$order_info['sale-amount']."<br>";
                  $message_bodyA .= "Cashback Amount            :$".$cashback_amount."<br>";
                  $message_bodyA .= "Eran Commissions            :$".$order_info['commission-amount']."<br>";
                  $message_bodyA .= "Product Name            :".$order_info['action-tracker-name']."<br>";
                  $message_bodyA .= "Order Id      :".$order_info['order-id']."<br>";
                  $message_bodyA .= "Payment Date      :".date('Y-m-d H:i:s',strtotime($order_info['event-date']))."<br>";
                  
                  
                  $mail = Mail::send(['html' => 'admin.emailtemplate.send_mail'], array('message_body' => $message_bodyA, 'user_name'=>'Admin'), function($message) use ($admin_users_email,$subjectA)
                  {
                    $message->from($admin_users_email,'Front Mode');
              
                    $message->to($admin_users_email)->subject($subjectA);
                  });
            
                /**************************SEND MAIL -start *****************************/ 

            }



          }

       }




      
   }
    /**############### ###################################### **/
}