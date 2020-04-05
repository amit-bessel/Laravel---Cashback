<?php namespace App\Http\Controllers\Service;

use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Model\SiteUser; /* Model name*/
use App\Model\Brand; /* Model name*/
use App\Model\Category;
use App\Model\Product;
use App\Model\Wishlist;
use App\Model\OrderHistory; /* Model name*/
use App\Model\Vendor; /* Model name*/

use App\Book;
use App\User;
use App\Http\Requests;
use App\Helper\helpers;

use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Request;
use Input; /* For input */
use Validator;
use Session;
use DB;
use Mail;
use Hash;
use Auth;
use Cache;


class UserController extends BaseController {

   /**
    * Display a listing of the resource.
    *
    * @return Response
    */
    public function __construct() {
  		parent::__construct();
  		//header('Access-Control-Allow-Origin: *');
		//header( 'Access-Control-Allow-Headers: Authorization, Content-Type' );
    }

    public function getUserDetails(){
    	
    	header('Access-Control-Allow-Origin: *');
		header( 'Access-Control-Allow-Headers: Authorization, Content-Type' );

    	$data = Request::all();
    	if(isset($data['user_id'])){

    		if(is_numeric($data['user_id'])){

		    	$user_id = $data['user_id'];

		    	$user_details = SiteUser::select(['title','name as first_name','last_name','email','contact','country','cashback','created_at'])->where('status',1)->where('id',$user_id)->first()->toArray();
		    	
		    	$array_data = array('error'=>0,'userdetails'=>$user_details);

		    	if(!empty($user_details)){
			    	return json_encode($array_data);
			    }else{
			    	return json_encode(array(
		    			'error'=>1,
		    			'msg'=>'No record found'
		    		));
			    }
    		}else{
    			return json_encode(array(
		    			'error'=>1,
		    			'msg'=>'user id should be number'
		    		));
    		}
    	}else{
    		return json_encode(array(
		    			'error'=>1,
		    			'msg'=>'Wrong parameter pass'
		    		));
    	}
    	exit;
    }

    public function getPurchaseHistory(){
    	
    	//header('Access-Control-Allow-Credentials = true');
    	header('Access-Control-Allow-Origin: *');
		header( 'Access-Control-Allow-Headers: Authorization, Content-Type' );
		//header('Access-Control-Allow-Methods: GET,PUT,POST,DELETE');
		
    	$data = Request::all();
    	if(isset($data['limit']) && isset($data['page'])){

    		if(is_numeric($data['limit']) && is_numeric($data['page'])){
    			$offset = ($data['page']-1)*$data['limit'];
		    	$limit = $data['limit'];

		    	$sql = OrderHistory::leftJoin('site_users', 'site_users.id', '=', 'order_history.user_id')
                                ->select('site_users.name as first_name','site_users.last_name','order_history.*');

		    	if(isset($data['user_id'])){
		    		if(is_numeric($data['user_id'])){
		    			$sql = $sql->where('user_id',$data['user_id']);
		    		}else{
		    			return json_encode(array(
					    			'error'=>1,
					    			'msg'=>'User id should be number'
					    		));
		    		}
		    	}

		    	$purchase_details = $sql->offset($offset)->limit($limit)->get()->toArray();
		   
		    	$array_data = array('error'=>0,'purchasedetails'=>$purchase_details);

		    	if(!empty($purchase_details)){
			    	return json_encode($array_data);
			    }else{
			    	return json_encode(array(
				    			'error'=>1,
				    			'msg'=>'No record found'
				    		));
			    }
    		}else{
    			return json_encode(array(
		    			'error'=>1,
		    			'msg'=>'Page or limit should be number'
		    		));
    		}
    	}else{
    		return json_encode(array(
		    			'error'=>1,
		    			'msg'=>'Wrong parameter passed'
		    		));
    	}
    	exit;
    }

    public function getUserWishlist(){
    	
    	header('Access-Control-Allow-Origin: *');
		header( 'Access-Control-Allow-Headers: Authorization, Content-Type' );

    	$data = Request::all();
    	if(isset($data['limit']) && isset($data['page']) && isset($data['user_id'])){

    		if(is_numeric($data['limit']) && is_numeric($data['page']) && is_numeric($data['user_id'])){

    			$offset = ($data['page']-1)*$data['limit'];
		    	$limit = $data['limit'];
		    	$limit = $data['user_id'];

		    	$wishlist_details = Wishlist::leftJoin('site_users', 'site_users.id', '=', 'wishlists.user_id')
                                	->leftJoin('products', 'products.id', '=', 'wishlists.product_id')
                                	->select('site_users.name as first_name','site_users.last_name','products.image_url','products.buy_url','wishlists.user_id','wishlists.product_id','wishlists.product_name')->where('user_id',$user_id)->offset($offset)->limit($limit)->get()->toArray();

		    	$array_data = array('error'=>0,'wishlist'=>$wishlist_details);

		    	if(!empty($wishlist_details)){
			    	return json_encode($array_data);
			    }else{
			    	return json_encode(array(
		    			'error'=>1,
		    			'msg'=>'No record found'
		    		));
			    }
    		}else{
    			return json_encode(array(
		    			'error'=>1,
		    			'msg'=>'Page or limit or user id should be number'
		    		));
    		}
    	}else{
    		return json_encode(array(
		    			'error'=>1,
		    			'msg'=>'Wrong parameter passed'
		    		));
    	}
    	exit;
    }

    public function getUserCashbackAmount(){
    	header('Access-Control-Allow-Origin: *');
		header( 'Access-Control-Allow-Headers: Authorization, Content-Type' );

    	$data = Request::all();
    	if(isset($data['user_id'])){

    		if(is_numeric($data['user_id'])){
    			
		    	$user_details = SiteUser::select(['id as user_id','title','name as first_name','last_name','cashback'])->where('status',1)->where('id',$data['user_id'])->get()->toArray();
		    	
		    	$array_data = array('error'=>0,'userdetails'=>$user_details);

		    	if(!empty($user_details)){
			    	return json_encode($array_data);
			    }else{
			    	return json_encode(array(
		    			'error'=>1,
		    			'msg'=>'No record found'
		    		));
			    }
    		}else{
    			return json_encode(array(
		    			'error'=>1,
		    			'msg'=>'user id should be number'
		    		));
    		}
    	}else{
    		return json_encode(array(
		    			'error'=>1,
		    			'msg'=>'Wrong parameter pass'
		    		));
    	}
    	exit;
    }

    public function getVendorDetails(){
    	
    	header('Access-Control-Allow-Origin: *');
		header( 'Access-Control-Allow-Headers: Authorization, Content-Type' );

    	$data = Request::all();
    	if(isset($data['limit']) && isset($data['page'])){

    		if(is_numeric($data['limit']) && is_numeric($data['page'])){

    			$offset = ($data['page']-1)*$data['limit'];
		    	$limit = $data['limit'];

		    	$vendor_details = Vendor::select(['advertiser-name','vendor_url','percentage'])->where('status',1)->offset($offset)->limit($limit)->get()->toArray();

		    	$array_data = array('error'=>0,'vendorlist'=>$vendor_details);

		    	if(!empty($vendor_details)){
			    	return json_encode($array_data);
			    }else{
			    	return json_encode(array(
		    			'error'=>1,
		    			'msg'=>'No record found'
		    		));
			    }
    		}else{
    			return json_encode(array(
		    			'error'=>1,
		    			'msg'=>'Page or limit should be number'
		    		));
    		}
    	}else{
    		return json_encode(array(
		    			'error'=>1,
		    			'msg'=>'Wrong parameter passed'
		    		));
    	}
    	exit;
    }
	
}
