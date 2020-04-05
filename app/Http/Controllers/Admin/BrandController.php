<?php namespace App\Http\Controllers\Admin;

use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;

use App\Model\Country; /* Model name*/
use App\Model\Category; /* Model name*/
use App\Model\Sitesetting; /* Model name*/
use App\Model\BusinessList; /* Model name*/
use App\Model\Testimonial; /* Model name*/
use App\Model\Topbanner; /* Model name*/
use App\Model\Brand; /* Model name*/
use App\Model\Product;
use App\Model\BrandCategory;
use App\Helper\CropAvatar;

use App\Book;
use App\User;
use App\Http\Requests;
use App\Helper\helpers;

use App\Http\Controllers\Admin\BaseController;
use Illuminate\Support\Facades\Request;
/*use Intervention\Image\Facades\Image; // Use this if you want facade style code*/
use Input; /* For input */
use Validator;
use Session;
use DB;
use Mail;
use Hash;
use Auth;
use Cache;
use Cookie;
use Image;

class BrandController extends BaseController {

  /**
    * Display a listing of the resource.
    *
    * @return Response
    */
    public function __construct() {
    	parent::__construct();
		
    }
    
	/**************************************************************/
	/*                       BANNER MANAGEMENT                    */
	/**************************************************************/
	
	public function getList()
	{
		//echo 'ok';exit;
    	$module_head 		= "Brand Management";
        $brand_class 		= "active";
        $record_per_page 	= 100;
        $search_key 		= Request::query('search_key');
        $active 			= Request::query('active');
        $title				= "Brand Management";
        $where 				= '';

        if($search_key != '')
		{
            $where 			= "`brand_name` LIKE '%".$search_key."%' AND ";
        }
        if($active != '')
		{
            $where 		   .= "`status`= '".$active."' AND ";
        }
        
		$where 		   .= '1';

        $brand_arr			= Brand::whereRaw($where)->orderBy('id','ASC')->get();
		
		return view('admin.brands.brand_list',compact(
														'brand_arr',
														'module_head',
														'search_key',
														'active',
														'brand_class',
														'record_per_page',
														'title'
													)
					);
	}
	function getProductList()
    {
		$brand_id = Request::input('brand_id');
        $brand_arr = Brand::where('id', $brand_id)->get()->first();

        $module_head 		= "Map Product To ". $brand_arr->name ."";
        $map_class 		    = "active";
        $record_per_page    = 100;
        $search_key 		= Request::query('search_key');
		$title				= "Map Product";
        $where 				= '';
		
		$sl_no				= 1;

		if(Request::input('page')!=''){
			$sl_no = (Request::input('page')*$record_per_page)+1;
		}

        if($search_key != '')
		{
            $where 			= "(`name` LIKE '%".$search_key."%' OR `advertiser-category` LIKE '%".$search_key."%' OR `description` LIKE '%".$search_key."%') AND";
        }

		$where 		   .= '`brand_id` = 0';
        $products		= Product::whereRaw($where)->orderBy('id','ASC')->offset(Request::input('offset'))->limit(100)->get();
        $no_of_product = Product::whereRaw($where)->orderBy('id','ASC')->count();

        return view('admin.brands.product',compact(
														'products',
														'module_head','search_key','map_class',
														'active','title','sl_no','no_of_product'
													)
					);
    }


    function getBrandProductList($brand_id="")
    {
    	 //$brand_id = Request::input('brand_id');
    	 $offset = Request::input('offset');
    	 $page = Request::input('page');

        $module_head 		= "Brand Management";
        $brand_class 		= "active";
        $record_per_page    = 100;
        $brand_id 			= $brand_id;
		$title				= "Brand Management";
        $where 				= '';
		
		$sl_no				= 1;

		//echo $brand_id;

		if($page !=''){
			$sl_no = $page*($record_per_page)+1;
		}

        if($brand_id != '')
		{
            $where 			= "`brand_id` = '".$brand_id."'";
        }

		$where 		   .= '';
        $products		= Product::whereRaw($where)->orderBy('id','ASC')->offset($offset)->limit(100)->get();
        $no_of_product = Product::whereRaw($where)->orderBy('id','ASC')->count();
		
        return view('admin.brands.brand_product',compact(
														'products',
														'module_head','brand_id','map_class',
														'active','title','sl_no','no_of_product'
													)
					);
    }


    function getBrandProductListAjax($brand_id="")
    {
    	 echo 'offset==>>'.$offset = Request::input('offset');
    	 echo 'page==>>'.$page = Request::input('page');

        $module_head 		= "Brand Management";
        $brand_class 		= "active";
        $record_per_page    = 100;
        $brand_id 			= $brand_id;
		$title				= "Brand Management";
        $where 				= '';
		
		$sl_no				= 1;

		echo $brand_id;

		if($page !=''){
			$sl_no = $page*($record_per_page)+1;
		}

        if($brand_id != '')
		{
            $where 			= "`brand_id` = '".$brand_id."'";
        }

		$where 		   .= '';
        $products		= Product::whereRaw($where)->orderBy('id','ASC')->offset($offset)->limit(100)->get();
        $no_of_product = Product::whereRaw($where)->orderBy('id','ASC')->count();

        return view('admin.brands.brand_product_list',compact(
														'products',
														'module_head','brand_id','map_class',
														'active','title','sl_no','no_of_product'
													)
					);

    }


	public function getSearchBrand($brand_id='')
	{
        $brand_arr = Brand::where('id', $brand_id)->get()->first();

		$module_head 		= "Map Product To ".$brand_arr->brand_name." Brand";
        $map_class 		= "active";
        $record_per_page 	= 100;
        $title				= "Map Product";
		
		return view('admin.brands.map_product',compact(
                                                        'brand_id',
														'module_head',
														'map_class',
														'record_per_page',
														'title'
													)
					);

	}
	public function getEdit($id)
	{
		$module_head 		= "Edit Brand";
        $brand_class 		= "active";
        $title				= "Brand Management";
		
		$brand_arr = Brand::where("id",$id)->first();
		if(count($brand_arr) == 0)
		{
			Session::flash('failure_message', 'This brand does not exist'); 
			Session::flash('alert-class', 'alert alert-error'); 
			return redirect('admin/brands/list');
		}
		
		return view('admin.brands.edit_brand',compact(
														'module_head',
														'brand_class',
														'title',
														'brand_arr'
													)
					);
	}
	public function postEdit($id)
	{

		if(Request::isMethod('post'))
		{
			$data = Request::all();
            $picture = $data['old_brand_image'];
            
	        if(!empty($data['upload_banner_image'])){
	            $picture = $data['upload_banner_image'];
	            if($data['old_brand_image']){
                    @unlink(public_path('uploads/brand_image/thumb/' . $data['old_brand_image']));
                }
	        }
	        
			Brand::where('id', '=', $id)->update(['brand_name' => $data['name'], 'description' => $data['description'], 'featured_image' => $picture,'link'=>$data['link'], 'status' => $data['status']]);

			Session::flash('success_message', 'Brand updated successfully'); 
			Session::flash('alert-class', 'alert alert-success'); 
			return redirect('admin/brands/list');
        }
	}
	public function getAdd()
	{
		$module_head 		= "Add Brand";
        $brand_class 		= "active";
        $title				= "Brand Management";
		
		return view('admin.brands.add_brand',compact(
														'module_head',
														'brand_class',
														'title'
													)
					);
	}


	function getCheck(){
		//echo "getCheckCategory";
		$data				= Request::all();
		/*echo "<pre>";
		print_r($data);
		exit;*/
		$where_raw = "1=1";
		$where_raw .= " AND `brand_name` = '".$data['brand_name']."'";
		if($data['hid_category_id']!=""){
			$where_raw .= " AND `id`!= '".$data['hid_category_id']."'";
		}
		$brand_details 	= Brand::whereRaw($where_raw)->first();
		if(count($brand_details)>0){
			echo 1;
		}
		else{
			echo 0;
		}
		exit;
	}
	public function postAdd()
	{
		if(Request::isMethod('post'))
		{
			$data = Request::all();
			
	        $picture = "";
	        if(!empty($data['upload_banner_image'])){
	            $picture = $data['upload_banner_image'];
	        }
			$banner = Brand::create([
									'brand_name' 	=> $data['name'],
									'link'          => $data['link'],  
                                    'description'   => $data['description'],
                                    'featured_image'=> $picture,
									'status' 		=> $data['status'],
								]);
				
			Session::flash('success_message', 'Brand successfully saved'); 
			Session::flash('alert-class', 'alert alert-success'); 
			return redirect('admin/brands/list');
        }
	}

	public function postUploadImage()
    {
        $data = Request::all();
        /*print_r($data);
        exit;*/
        $crop_data_arr = array();
        $crop_data_arr['x'] = $data['dataX'];
        $crop_data_arr['y'] = $data['dataY'];
        $crop_data_arr['height'] = $data['dataHeight'];
        $crop_data_arr['width'] = $data['dataWidth'];
        $crop_data_arr['rotate'] = $data['dataRotate'];

        $json_cropped_data = json_encode($crop_data_arr);
        
        if($data['dataHeight']>0 && $data['dataWidth']>0){

            $path = 'uploads/brand_image/thumb/';
            $crop = new CropAvatar(null, $json_cropped_data,$_FILES['file'],$path);
            $uploaded_img_name = $crop -> getResult();
        }
        else{
            $image = Input::file('file');
            $filename  = time() . '.' . $image->getClientOriginalExtension();

            $path = 'uploads/brand_image/thumb/' . $filename;

            Image::make($image->getRealPath())->save($path);
            $uploaded_img_name = $filename;

        }

        echo $uploaded_img_name;
        exit();

    }

	public function getDelete($id)
	{
		$brand = Brand::find($id);
        $brand->delete();
        Session::flash('success_message', 'Brand has been deleted successfully.'); 
        Session::flash('alert-class', 'alert alert-success'); 
        return redirect('admin/brands/list/');
	}


	public function getProductDelete($id)
	{
		$category_details   = Product::find($id);

        $brand_id           = $category_details->brand_id;
        $category_id        = $category_details->category_id;

        Product::where('id', '=', $id)->update(['brand_id' => '']);

        $this->checkProductExistWithOldBrandCategory($brand_id,$category_id);
		
        Session::flash('success_message', 'Map product has been removed successfully.'); 
        Session::flash('alert-class', 'alert alert-success'); 
        return redirect('admin/brands/list/');
	}

	public function postBrandChangeStatus()
	{
		$post_data  = Request::all();
        $status = $post_data['status'];
        $id = $post_data['id'];
        $banner = Brand::find($id);
        $banner->status = $status;
        $banner->save();
        echo "1";exit();
	}

	public function postRemoveAll(){

		$post_data  = Request::all();
		foreach($post_data['check_product'] as $id){

			$category_details   = Product::find($id);

	        $brand_id           = $category_details->brand_id;
	        $category_id        = $category_details->category_id;
	        
	        Product::where('id', '=', $id)->update(['brand_id' => '']);

	        $this->checkProductExistWithOldBrandCategory($brand_id,$category_id);

			
		}
		Session::flash('success_message', 'Map product has been removed successfully.'); 
        Session::flash('alert-class', 'alert alert-success'); 
        return redirect('admin/brands/list/');
	}

	function getSearch(){

    	$term = $_GET['term'];
    	$order_details_arr = array();
        $order_arr = Brand::where('brand_name','LIKE',$term.'%')->get();
        if(!empty($order_arr))
		{
			$order_arr = $order_arr->toArray();
			$i = 0;
			foreach($order_arr as $order_list)
			{
				$order_details_arr[$i]['key'] = $order_list['id'];
				$order_details_arr[$i]['value'] = $order_list['brand_name'];
				$i++;
			}
		}
		echo json_encode($order_details_arr);
		exit;

    }
    function postBrandAdd(){

    	$data = Request::all();
    	
    	foreach($data['check_product'] as $value){
    		$explodeId = explode('@', $value);
    		$productId = $explodeId[0];
    		$product 	= Product::find($productId);
    		$old_product_category = $product->category_id;

    		$category_id      = $product->category_id;;
	        /********************* Product New Brnad And Category *********************/
	        if($data['brand']!=0)
	            $this->productNewBrandCategory($data['brand'],$category_id);

    		Product::where('id', '=', $productId)->update(['brand_id' => $data['brand']]);

    		/*********** Checking Product Exist With Old Brand And Category ************/
        	if($data['brand']!=0)
            	$this->checkProductExistWithOldBrandCategory($data['brand'],$category_id);
    		/*$brand_cat_check = BrandCategory::where('brand_id', '=', $data['brand'])->where('category_id', '=', $explodeId[1])->count();
    		
    		if(($brand_cat_check==0) && ($explodeId[1]!=1)){
    			$insert_product = BrandCategory::create([
		            'brand_id'        => $data['brand'],
		            'category_id'     => $explodeId[1]
		          ]);
    		}*/
    	}

    	Session::flash('success_message', 'Products mapped successfully'); 
		Session::flash('alert-class', 'alert alert-success'); 
		return redirect('admin/brands/list');
    }
}
