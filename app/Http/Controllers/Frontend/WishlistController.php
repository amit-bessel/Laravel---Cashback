<?php namespace App\Http\Controllers\Frontend; /* path of this controller*/
// Define Model
use App\Model\Newsletter;  /* Model name*/
use App\Model\Blog;  /* Model name*/
use App\Model\Country;/*Model Name*/
use App\Model\Zone;/*Model Name*/
use App\Model\City;/*Model Name*/
use App\Model\Sitesetting;
use App\Model\SiteUser;
use App\Model\Adverts; /* Model name*/
use App\Model\Brand; /* Model name*/
use App\Model\Gender; /* Model name*/
use App\Model\Banner; /* Model name*/
use App\Model\Age; /* Model name*/
use App\Model\Material; /* Model name*/
use App\Model\Size; /* Model name*/
use App\Model\Bracelet; /* Model name*/
use App\Model\Condition; /* Model name*/
use App\Model\Style; /* Model name*/
use App\Model\Dialcolor; /* Model name*/
use App\Model\Boxpaper; /* Model name*/
use App\Model\Currency; /* Model name*/
use App\Model\Post; /* Model name*/
use App\Model\AdvertImage; /* Model name*/
use App\Model\Favourite; /* Model name*/
use App\Model\PaymentHistory; /* Model name*/
use App\Model\Category;
use App\Model\Product;
use App\Model\Wishlist;

use App\Http\Requests;
use App\Http\Controllers\Controller;    
use Illuminate\Support\Facades\Request;
use Mail;
use Input; /* For input */
use Validator;
use Session;

use Intervention\Image\Facades\Image; // Use this if you want facade style code

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
use Config;
//use Socialize;
use App\Model\Address; 

class WishlistController extends BaseController {
    public function __construct() 
    {
        parent::__construct();

    }

    public function addToWishlist($id){

        $id = base64_decode($id);

        if(Session::has('user_id')){ 
           //print_r(Session::has('user_id'));exit;
            $user_id = Session::get('user_id');
            $check_already_exist = Wishlist::where('user_id','=',$user_id)->where('product_id','=',$id)->first();
            //print_r($id);exit;
            if(count($check_already_exist) == 0){
              $wishlist = new Wishlist;
              $product_details = Product::where('id', '=', $id)->first()->toArray();
              $wishlist->user_id = $user_id;
              $wishlist->product_id = $id;
              $wishlist->product_name = $product_details['name'];
              $wishlist->save();
            }
            
           return redirect('/my-wishlist');

        }else{
            $buy_url = '';
            return view('frontend.home.login',compact('id','buy_url'));
        }
    }

    public function myWishlist(){

        if(Session::has('user_id')){
             $user_id = Session::has('user_id');
             $product_details = Wishlist::with('product_wishlist')->where('user_id','=',$user_id)->get();
             if(count($product_details)>0){
                 $product_details =  $product_details->toArray();
               //  print_r($product_details);exit;
             }
             $total_no_of_products = Wishlist::with('product_wishlist')->where('user_id','=',$user_id)->count();
           return view('frontend.home.user-wishlist',compact('total_no_of_products'));
        }
    }

    public function getwishlistBrand(){
        if(Session::has('user_id')){
             $user_id = Session::get('user_id');
             $brand_id = Request::input('brand_id');

              $product_details = Wishlist::with('product_wishlist')->where('user_id','=',$user_id)->offset(Request::input('offset'))->limit(Request::input('limit'))->get();

              $count_product_details = Wishlist::with('product_wishlist')->where('user_id','=',$user_id)->count();
            

             if(count($product_details)>0){
                 $product_details =  $product_details->toArray();
                 //$all_brand_id = $product_details['brand_id'];
             }
             
           $str = '<input type="hidden" name="total_no_of_products" id="total_no_of_products" value="'.$count_product_details.'"';
           $dtl = '';
           $all_brand_id =array();

            if(count($product_details)>0){

             foreach ($product_details as $product) {

              $all_brand_id[] = $product['product_wishlist']['brand_id'];
              $details =unserialize($product['product_wishlist']['description']);
                if(!empty($details['long'])){
                  $dtl = $details['long'];
                }
                
               $str .= '<tr>
                <td data-title="Pick Product"><input type="checkbox"></td>
                <td class="cart-description" data-title="Product details">
                  <div class="cart-img pull-left" style="background:url('.$product['product_wishlist']['image_url'].') no-repeat center center/contain;"></div>
                  <h4>'.$product['product_wishlist']['name'].'</h4>
                  <p>'.$dtl.'</p>
                </td>
                <td data-title="date of purchase">
                  <a href='.$product['product_wishlist']['buy_url'].' class="btn btn-primary btn-block text-uppercase">Buy Now</a>
                  <a href='.url().'/remove-product/'.$product['product_id'].' class="btn btn-primary btn-block text-uppercase">remove</a>
                </td>                                    
              </tr>';
            }
          }
          else{
            $str .= '<tr>
                     <td data-title="Pick Product" align="center">No Product Found</td>
                  </tr>';
          }

          $selecthtml = '';

          if(!empty($all_brand_id)){
              $brand_details = Brand::whereIn('id',array_unique($all_brand_id))->select('id','brand_name')->get();
              if($brand_details){
                $brand_details_arr = $brand_details->toArray();
              }
              if(in_array(0, $all_brand_id)){
                $unknown_brand = 'Unknown';
              }

              if($brand_details_arr){

                $selecthtml = '<select class="form-control" onchange="get_brandChnage(this)">';
                $selecthtml .= '<option value="">All Brand</option>';
                if(isset($unknown_brand)){
                  $selecthtml .= '<option value="0">'.$unknown_brand.'</option>';
                }

                foreach ($brand_details_arr as $key => $value) {
                    $selecthtml .= '<option value="'.$value['id'].'">'.$value['brand_name'].'</option>';
                }

                $selecthtml .= '</select>';
              }
          }
          $html = array();
          $html['wishlist'] = $str;
          $html['select'] = $selecthtml;

          echo json_encode($html);
          /*echo '<pre>';print_r($html);exit;
          echo $str;*/
        }
    }

    public function getMyWishlist(){

        if(Session::has('user_id')){
             $user_id = Session::get('user_id');
             $brand_id = Request::input('brand_id');

             if($brand_id == ''){
                $product_details = Wishlist::with('product_wishlist')->where('user_id','=',$user_id)->offset(Request::input('offset'))->limit(Request::input('limit'))->get();

                $count_product_details = Wishlist::with('product_wishlist')->where('user_id','=',$user_id)->count();

             }else{
                $product_details = Wishlist::with(['product_wishlist'=>function($q){
                                        $q->select(['id','brand_id','name','description','image_url','buy_url']);
                                  }])->whereHas('product_wishlist',function($q) use ($brand_id){
                                                $q->where('brand_id',$brand_id);
                                })->where('user_id','=',$user_id)->offset(Request::input('offset'))->limit(Request::input('limit'))->get();

                $count_product_details = Wishlist::with(['product_wishlist'=>function($q){
                                              $q->select(['id','brand_id','name','description','image_url','buy_url']);
                                        }])->whereHas('product_wishlist',function($q) use ($brand_id){
                                                      $q->where('brand_id',$brand_id);
                                        })->where('user_id','=',$user_id)->count();
             }

             if(count($product_details)>0){
                 $product_details =  $product_details->toArray();
             }
             
           $str = '<input type="hidden" name="total_no_of_products" id="total_no_of_products" value="'.$count_product_details.'"';
           $dtl = '';
           $all_brand_id =array();

          if(count($product_details)>0){
            foreach ($product_details as $product) {

            $details =unserialize($product['product_wishlist']['description']);
              if(!empty($details['long'])){
                $dtl = $details['long'];
              }
              
               $str .= '<tr>
                <td data-title="Pick Product"><input type="checkbox"></td>
                <td class="cart-description" data-title="Product details">
                  <div class="cart-img pull-left" style="background:url('.$product['product_wishlist']['image_url'].') no-repeat center center/contain;"></div>
                  <h4>'.$product['product_wishlist']['name'].'</h4>
                  <p>'.$dtl.'</p>
                </td>
                <td data-title="date of purchase">
                  <a href='.$product['product_wishlist']['buy_url'].' class="btn btn-primary btn-block text-uppercase">Buy Now</a>
                  <a href='.url().'/remove-product/'.$product['product_id'].' class="btn btn-primary btn-block text-uppercase">remove</a>
                </td>                                    
              </tr>';
            }
          }
          else{
            $str .= '<tr>
                     <td data-title="Pick Product" align="center">No Product Found</td>
                  </tr>';
          }
          

          //echo '<pre>';print_r($product_details);exit;
          echo $str;
        }
    }
    public function removeProduct($id=""){
        $prod_id = $id;
        $user_id = Session::get('user_id');
        $wishlist = Wishlist::where('product_id','=',$prod_id)->where('user_id','=',$user_id)->delete();
        return redirect('/my-wishlist');
    }

    public function getSearchProduct(){

        $term = $_GET['term'];
        $order_details_arr = array();
        $order_arr = Wishlist::where('product_name','LIKE',$term.'%')->get();
        if(!empty($order_arr))
        {
            $order_arr = $order_arr->toArray();
            $i = 0;
            foreach($order_arr as $order_list)
            {
                $order_details_arr[$i]['key'] = $order_list['id'];
                $order_details_arr[$i]['value'] = $order_list['product_name'];
                $i++;
            }
        }
        echo json_encode($order_details_arr);
        exit;

    }

    function getSearchList()
    {
        $search_key         = Request::query('search_key');
        $user_id            = Session::get('user_id');
        $where              = '';

        if($search_key != '')
        {
            $where          = "`product_name` LIKE '%".addslashes($search_key)."%' AND ";
        }

        $where         .= '1';
        $product_details       = Wishlist::with('product_wishlist')->whereRaw($where)->where('user_id',$user_id)->orderBy('id','ASC')->get()->toArray();
        $str = "";
        $dtl = '';

        if(!empty($product_details)){

          foreach ($product_details as $product) {
          $details =unserialize($product['product_wishlist']['description']);
                    if(!empty($details['long'])){
                      $dtl = $details['long'];
                    }else{
                      $dtl = $details['short'];
                    }
            
           $str .= '<tr>
            <td data-title="Pick Product"><input type="checkbox"></td>
            <td class="cart-description" data-title="Product details">
              <div class="cart-img pull-left" style="background:url('.$product['product_wishlist']['image_url'].') no-repeat center center/contain;"></div>
              <h4>'.$product['product_wishlist']['name'].'</h4>
              <p>'.$dtl.'</p>
            </td>
            <td data-title="date of purchase">
              <a href='.$product['product_wishlist']['buy_url'].' class="btn btn-primary btn-block text-uppercase">Buy Now</a>
              <a href='.url().'/remove-product/'.$product['product_id'].' class="btn btn-primary btn-block text-uppercase">remove</a>
            </td>                                    
          </tr>';
          }
        }else{
          $str .= '<tr>
                     <td data-title="Pick Product" align="center">No Product Found</td>
                  </tr>';
        }

          echo $str;
    }


    public function getBuyNow($buy_url=""){
      
      if(Session::has('user_id')){

        $redirect_url = base64_decode($buy_url).'&u1='.Session::get('user_id');
        return redirect($redirect_url);

      }else{

        Session::put('buy_url', $buy_url);
        return redirect('/login');
      }
    
      
    }

}