<?php namespace App\Http\Controllers\Frontend; /* path of this controller*/
// Define Model
use App\Model\Brand; /* Model name*/
use App\Model\Product;
use App\Model\BrandCategory;
use App\Model\Vendor;
use App\Model\Store;
use App\Model\CategoryStore;

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
use Illuminate\Pagination\LengthAwarePaginator;
use DB;
use Hash;
use Auth;
use Cookie;
use Cart;
use Redirect;
use Config;
//use Socialize;
use App\Model\Address; 


class PartnerController extends BaseController {

   /**
    * Display a listing of the resource.
    *
    * @return Response
    */
    public function __construct() 
    {
        parent::__construct();

    }
    
	/********************************************************************************
	 *								GET PARTNER LIST								*
	 *******************************************************************************/
	
    public function postList()
    {
        $total_vendors = Vendor::count();
        $stores_info = Store::where('status',1)->get();
        if(count($stores_info)>0){
            $stores_info = $stores_info->toArray();
        }

        $start='a';
        $end='z'; 
        $all_api_vendors=array();
        for($i=$start;$i<$end;$i++){
         
            $inv=Vendor::where("advertiser-name","like",$i."%")->where('status',1)
                     ->orderBy('advertiser-name', 'ASC')->get()->toArray();
            $all_api_vendors[$i]=$inv;
        }

        $inv=Vendor::where("advertiser-name","like","z%")->where('status',1)
                     ->orderBy('advertiser-name', 'ASC')->get()->toArray();
        $all_api_vendors['z']=$inv;

        $inv=Vendor::where("advertiser-name","not like","[a-z]%")->where('status',1)
                     ->orderBy('advertiser-name', 'ASC')->get()->toArray();
        $all_api_vendors['special']=$inv;

		$all_vendors = $all_api_vendors;
		/*echo "<pre>";
        print_r($all_api_vendors);exit;*/

        $title = 'partners';
		
        return view('frontend.vendor.vendor-list',compact(
														'all_vendors','title','stores_info','total_vendors'
													)
					);
    }


    public function searchVendor()
    {
        $serch_vendor = Request::input('serch_vendor');
        $cat_id = Request::input('cat_id');

        $all_api_vendors=array();
        $like_word = range('a', 'z');
        $not_like = array('[a-z]');
        $array_like_word = array_merge($like_word,$not_like);

        foreach ($array_like_word as $key => $i) {

            $inv=Vendor::with(['get_store_category'=>function($q){
                                $q->select(['vendor_id','category_id']);
                }]);

            if($cat_id!=''){
                $inv = $inv->whereHas('get_store_category',function($q) use ($cat_id){
                                    $q->where('category_id',$cat_id);
                    });
            }
            if ($i === end($array_like_word)){
                $like = 'not like';
                $index_word = 'special';
            }else{
                $like = 'like';
                $index_word = $i;
            }
            $inv = $inv->where('status',1)->where("advertiser-name",$like,$i."%")->where("advertiser-name","like","%".addslashes($serch_vendor)."%")->orderBy('advertiser-name', 'ASC')->get()->toArray();

            $all_api_vendors[$index_word]=$inv;
        }
        $all_vendors = $all_api_vendors;

        $title = 'partners';
        
        return view('frontend.vendor.search-vendor-list',compact(
                                                        'all_vendors','title','serch_vendor'
                                                    )
                    );
    }

    public function sortVendorByCashback()
    {
        $sort_vendor = Request::input('value');
         
        $all_vendors = Vendor::where('status',1)->orderBy('percentage', 'DESC')->get()->toArray();

        $title = 'partners';
        //echo "<pre>";print_r($all_api_vendors);exit;
        return view('frontend.vendor.sort-vendor-by-cashback',compact(
                            'all_vendors','title','sort_vendor'
                        )
                    );
    }

    /*public function searchVendorByCategory()
    {
        $cat_id = Request::input('value');
        $serch_vendor = '';

        $start='a';
        $end='z'; 
        $all_api_vendors=array();
        for($i=$start;$i<$end;$i++){
         
            $inv=CategoryStore::with(['get_store_category'=>function($q){
                                                $q->select(['id','advertiser-name','status','vendor_url']);
                    }])->whereHas('get_store_category',function($q) use ($i,$serch_vendor){
                                    $q->where("advertiser-name","like",$i."%");
                                    $q->where("advertiser-name","like","%".addslashes($serch_vendor)."%");
                                    $q->orderBy('advertiser-name', 'ASC');
                    })->where('category_id',$cat_id)
                    ->get()->toArray();

             $inv = CategoryStore::leftJoin('all_vendors', 'all_vendors.id', '=', 'category_stores.vendor_id')
                                ->select('category_stores.*','category_stores.id as pd_id','all_vendors.*')->where('all_vendors.advertiser-name','like',$i.'%')
                                ->where('category_stores.category_id' , '=', $cat_id)->where("all_vendors.advertiser-name","like","%".addslashes($serch_vendor)."%")->orderBy('all_vendors.advertiser-name', 'ASC')->get()->toArray();

            $all_api_vendors[$i]=$inv;
        }
        
        $inv = CategoryStore::leftJoin('all_vendors', 'all_vendors.id', '=', 'category_stores.vendor_id')
                                ->select('category_stores.*','category_stores.id as pd_id','all_vendors.*')->where('all_vendors.advertiser-name','like','z%')
                                ->where('category_stores.category_id' , '=', $cat_id)->where("all_vendors.advertiser-name","like","%".addslashes($serch_vendor)."%")->orderBy('all_vendors.advertiser-name', 'ASC')->get()->toArray();
        $all_api_vendors['z']=$inv;

        $inv=CategoryStore::leftJoin('all_vendors', 'all_vendors.id', '=', 'category_stores.vendor_id')
                                ->select('category_stores.*','category_stores.id as pd_id','all_vendors.*')->where('all_vendors.advertiser-name','not like','[a-z]%')
                                ->where('category_stores.category_id' , '=', $cat_id)->where("all_vendors.advertiser-name","like","%".addslashes($serch_vendor)."%")->orderBy('all_vendors.advertiser-name', 'ASC')->get()->toArray();

        $all_api_vendors['special']=$inv;

        $all_vendors = $all_api_vendors;
        $title = 'partners';
        //print_r($all_api_vendors);exit;
        return view('frontend.vendor.search-vendor-list',compact(
                                                        'all_vendors','title','serch_vendor'
                                                    )
                    );
    }*/ 

    public function getPartnerDetails($id="")
    {
        if(is_numeric($id)){
            
            $vendor_details =Vendor::with(['get_store_category'=>function($q){
                                $q->select(['vendor_id','category_id','name']);
                            }])->where('id',$id)->first()->toArray();
            $store_names        = '';
            $store_names_arr    = array();
            $related_store      = array();

            if(!empty($vendor_details['get_store_category'])){
                $cat_id_arr = array();
                foreach ($vendor_details['get_store_category'] as $key => $value) {
                   $cat_id_arr[] = $value['category_id'];
                   $store_names = $store_names.','.$value['name'];
                   $store_names_arr[] = $value['name'];
                }

                $related_store = Vendor::with(['get_store_category'=>function($q){
                                    $q->select(['vendor_id','category_id','name']);
                                    //$q->whereIn('category_id',$cat_id_arr));
                                 }])->whereHas('get_store_category',function($q) use ($cat_id_arr){
                                    $q->whereIn('category_id',$cat_id_arr);
                                })->orderBy(DB::raw('RAND()'))->where('id','!=',$id)->limit(8)->get()->toArray();

            }

            if(!empty($vendor_details)){
               $vendor_details['store_name'] = trim($store_names,',');
            }else{
                abort(404);
            }
        }else{
            abort(404);
        }
        
        //echo "<pre>";print_r($related_store);exit;
        $title = $vendor_details['advertiser-name'];
        
        return view('frontend.vendor.partners-details',compact(
                        'vendor_details','title','store_names_arr','related_store'
                          )
                    );
    }

    public function getCashbackDetails($id="")
    {
        if(is_numeric($id)){
            
            $vendor_detail =Vendor::where('id',$id)->first()->toArray();

            if(!empty($vendor_detail)){
               $vendor_details = $vendor_detail;
            }else{
                abort(404);
            }
        }else{
            abort(404);
        }
        
        //echo "<pre>";print_r($vendor_details);exit;
        $title = $vendor_details['advertiser-name'];
        
        return view('frontend.cashbackpage.percentage',compact(
                        'vendor_details','title'
                          )
                    );
    }


    function postSetSessionVendorId()
    {
        $data = Request::all();
        Session::put('vendor_id', $data['vendor_id']);
        Session::forget('product_id');
        return 1;
    }

    function postForgotSessionVendorId()
    {
        Session::forget('vendor_id');
        return 1;
    }
}
