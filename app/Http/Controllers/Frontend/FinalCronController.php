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
use App\Model\DataFeed; /* Model name*/

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

class FinalCronController extends BaseController {

   public function __construct() 
   {
      parent::__construct();
      $obj = new helpers();
      view()->share('obj',$obj);
   }

  public function getFtpLinkshareDeltaDatafeed(){
      
    $all_vendor_mids = array('41162','40906','40572','40538','40530','40239','39593','39540','39289','39270','39098','39097','39100','39101','39095','38889','38801','38267','38752','38180','38095','38031','37981','37205','37206','37978','37938','37863','37635','37611','37119','36813','36592','24797','24359','41970','41342','36586');

    $ftp_server="aftp.linksynergy.com";
    $ftp_user_name="Ulab";
    $ftp_user_pass="4DW6hjPJ";

    $conn_id = ftp_connect($ftp_server);
    $login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);

    // turn passive mode on
    ftp_pasv($conn_id, true);

    foreach ($all_vendor_mids as $key1 => $vendor_mid) {

      $myfile = fopen("logs.txt", "a");
      $txt = "$key1 vendor_id=$vendor_mid";
      fwrite($myfile, "\n". $txt);
      fclose($myfile);

      $local_file = 'linksharedata/'.$vendor_mid.'_3267514_mp_delta.xml.gz';
      $server_file = '/'.$vendor_mid.'_3267514_mp_delta.xml.gz';
      $file_exists_check = ftp_size($conn_id, $server_file);

      if ($file_exists_check != -1){

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
    
        $out_file_name = base_path().'/linksharedata/'.$vendor_mid.'_3267514_mp_delta.xml';

        if(file_exists($out_file_name)){
          
          ########### Same file xml parsing and perfrom for same #####################
          $xml=simplexml_load_file($out_file_name) or die("Error: Cannot create object");
          
          $items = (array)($xml);
          //echo "<pre>";print_r($items);print_r(count($items['product']));exit;
          $vendor_details = (array)$items['header'];
          $createdOn = $vendor_details['createdOn'];
          $merchantId = $vendor_details['merchantId'];

          //check last updated date less than db updated date
          //$last_updated_date = Product::where('advertiser-id',$merchantId)->where('api',"LS")->max('updated_at');
          //echo "<pre>";print_r($last_updated_date.'====='.$createdOn);exit;

          //if(isset($items['product']) && (strtotime($createdOn) > strtotime($last_updated_date))){

  	      if(isset($items['product'])){

            if(count($items['product'])>0){

              if(is_array($items['product'])){

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

                        //Product::where('product_id',$details['@attributes']['product_id'])->where('api','LS')->delete();
                        continue;

                      }elseif($details['modification']== 'U') {
                        
                        $product_dtls = (array)($item);

                        $this->addLinkshareProducts($product_dtls,'updated',$vendor_details);
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
                                $this->addLinkshareProducts($product_dtls,$check_sub_category_exists->id,$vendor_details);

                                $check_in_category = 0;
                                break;
                             }                
                       }

                       // if category is not matched then add or edit the product with general category.
                       if($check_in_category==1){

                          // Call add or edit function 
                         $this->addLinkshareProducts($product_dtls,1,$vendor_details);                        
                       }
                    }
                    else{
                      // Call add or edit function 
                      $this->addLinkshareProducts($product_dtls,1,$vendor_details);
                    }
                  }
                }
              }else{

                $product_dtls = (array)$items['product'];
                $this->addLinkshareProducts($product_dtls,1,$vendor_details);
              }

            }else{
              $myfile = fopen("logs.txt", "a");
              $txt = "no product found\n";
              fwrite($myfile, "\n". $txt);
              fclose($myfile);
            }
          }
        }
      }     
    }
    ftp_close($conn_id);
    exit();
  }
   
  public function addLinkshareProducts($product_info,$cat_id,$vendor_details){
            
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
      //$pro_dtls_chk_qry = Product::where('product_id',$product_info['@attributes']['product_id'])->where('api','LS');
      
      $currency_details= (array)$product_info['price'];
      $currency = $currency_details['@attributes']['currency'];
      // if the product is exists update the product else add the product 

      if(((isset($product_info['modification']) && $product_info['modification']=='U') || $cat_id == 'updated')){

         //$updatedetails = $pro_dtls_chk_qry->get()->first();

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

          
          if($update_product){
            $myfile = fopen("logs.txt", "a");
            $txt = "update_id=".$update_product;
            fwrite($myfile, "\n". $txt);
            fclose($myfile);
          }else{

            if($cat_id == 'updated'){
              $cat_id = 1;
            }

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
              fwrite($myfile, "\n". $txt);
              fclose($myfile);
          }
      }
      else{

        if($cat_id == 'updated'){
          $cat_id = 1;
        }

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
          fwrite($myfile, "\n". $txt);
          fclose($myfile);
      }
  }

  public function getHitLSDataFeedBG(){

    $cmd = "wget -bq --spider ".url()."/update-linkshare-products";
    echo $id = shell_exec(escapeshellcmd($cmd));
  }

  public function getFtpCjDataFeed()
  {
      $ftp_server="datatransfer.cj.com";
      $ftp_user_name="4603669";
      $ftp_user_pass="ym+yXvfx";

      $conn_id = ftp_connect($ftp_server);
      // login with username and password
      $login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);
      // turn passive mode on
      ftp_pasv($conn_id, true);

      $getCjVendorListnames = DataFeed::where('status',1)->select(['name','vendor_id'])->get();

      if($getCjVendorListnames->count()>0){
        $getCjVendorListnames = $getCjVendorListnames->toArray();

        foreach($getCjVendorListnames as $key=>$cjVendorNames){

          $local_file = 'cjdata/'.$cjVendorNames['name'];
          $server_file = '/outgoing/productcatalog/194592/'.$cjVendorNames['name'];

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
          $out_file_name = base_path().'/'.$out_file_name;

          if(file_exists($out_file_name)){

            $xml=simplexml_load_file($out_file_name) or die("Error: Cannot create object");       
            $items = (array)($xml);
            //echo "<pre>";print_r($items);exit;

            if(count($items)>0){
              
              foreach($items['product'] as $key => $each_product){

                $product_details = (array)($each_product);
                $advertiserids = 0;

                $vendor_details = Vendor::where('advertiser-name','LIKE','%'.$product_details["programname"].'%')->select(['advertiser-id'])->get()->first();

                if($vendor_details){
                  $vendor_details = $vendor_details->toArray();
                  $advertiserids = $vendor_details['advertiser-id'];
                }

                //echo "<pre>";print_r($vendor_details);exit;
                $myfile = fopen("cjlogs.txt", "a");
                $txt = 'product_sku='.$product_details['sku'];
                fwrite($myfile, "\n". $txt);
                fclose($myfile);

                $this->doAddOrEditProducts($product_details,$advertiserids);
              }
            }
          }
        }

        exit('completed all job');
      }
  }

  ################(CJ PRODUCT LIST)####################    
  public function doAddOrEditProducts($product_details,$advertiserids)
  {
      $category_id = 1;
      $product_description = ($product_details['description']);
      
      $advertiser_category_array = array('main'=>'','sub'=>'');
      if(isset($product_details['advertisercategory'])){

        $advertiser_category = ($product_details['advertisercategory']);
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
        $manufacturer_name = $product_details['manufacturer'];
      }
      else if($advertiserids=='4567657'){
        $manufacturer_name = $product_details['publisher'];
      }

      // Check if the product is already exists
      $pro_dtls_chk_qry = Product::where('sku',$product_details['sku'])->where('api','CJ')->select(['updated_at'])->get();

      if($pro_dtls_chk_qry->count()>0){
        
        $pro_dtls_chk_qry = $pro_dtls_chk_qry->first();
        $last_updated_date = $pro_dtls_chk_qry->updated_at;
        $createdOn = $product_details['lastupdated'];

        // if the product is exists update the product else add the product     
        if(strtotime($createdOn) > strtotime($last_updated_date)){

          $update_product = Product::where('sku', $product_details['sku'])->update([
              'name'               => $product_details['name'],
              'advertiser-id'      => $advertiserids,
              'advertiser-name'    => $product_details['programname'],
              'advertiser-category'=> serialize($advertiser_category_array),
              'in-stock'           => ($product_details['instock']=='yes')?1:0,
              'sku'                => $product_details['sku'],
              'buy_url'            => $product_details['buyurl'],
              'image_url'          => $product_details['imageurl'],
              'currency'           => $product_details['currency'],
              'price'              => $product_details['price'],
              'description'        => $product_description,
              'retail_price'       => isset($product_details['retailprice'])?$product_details['retailprice']:0,
              'api'                => 'CJ',
              'manufacturer_name'  => $manufacturer_name,
              'updated_at'         => date('Y-m-d H:i:s')
          ]);

          $myfile = fopen("cjlogs.txt", "a");
          $txt = 'updated_id='.$update_product;
          fwrite($myfile, "\n". $txt);
          fclose($myfile);
        }
      }else{

        $insert_product = Product::create([
              'category_id'        => $category_id,
              'name'               => $product_details['name'],
              'advertiser-id'      => $advertiserids,
              'advertiser-name'    => $product_details['programname'],
              'advertiser-category'=> serialize($advertiser_category_array),
              'in-stock'           => ($product_details['instock']=='yes')?1:0,
              'sku'                => $product_details['sku'],
              'buy_url'            => $product_details['buyurl'],
              'image_url'          => $product_details['imageurl'],
              'currency'           => $product_details['currency'],
              'price'              => $product_details['price'],
              'description'        => $product_description,
              'retail_price'       => isset($product_details['retailprice'])?$product_details['retailprice']:0,
              'api'                => 'CJ',
              'manufacturer_name'  => $manufacturer_name,
              'created_at'         => date('Y-m-d H:i:s')
          ]);

        $myfile = fopen("cjlogs.txt", "a");
        $txt = 'insert_id='.$insert_product->id;
        fwrite($myfile, "\n". $txt);
        fclose($myfile);
      }
  }

  public function getBgHitCjDataFeed(){

    $cmd = "wget -bq --spider ".url()."/update-cj-products";
    echo $id = shell_exec(escapeshellcmd($cmd));
  }
}