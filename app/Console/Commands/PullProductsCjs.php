<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

// Define Model
use App\Model\Category; /* Model name*/
use App\Model\Product; /* Model name*/

use App\Http\Requests;
use App\Http\Controllers\Controller;    
use Illuminate\Support\Facades\Request;
use Validator;
use Session;
use Illuminate\Pagination\Paginator;
use DB;
use Cookie;
use App\Helper\helpers;
use App\Model\Subscription;
use Redirect;

class PullProductsCjs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pull:products';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Pull products from CJ';

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
        $page=1; $count = 1;$records_per_page = 999;

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
               
               /* echo "<pre>";
                print_r($xml->products);exit;*/

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
                else{
                    $myfile = fopen("failurelogs.txt", "a");
                    $txt = "https://product-search.api.cj.com/v2/product-search?website-id=8273790&advertiser-ids=joined&page-number=$page&records-per-page=$records_per_page";
                    fwrite($myfile, "\n". $txt);
                    fclose($myfile);
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


}
