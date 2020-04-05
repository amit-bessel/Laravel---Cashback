<?php namespace App\Http\Controllers\Admin;

use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;

use App\Model\Category; /* Model name*/
use App\Model\SubCategory; /* Model name*/
use App\Model\Product;
use App\Model\Brand; /* Model name*/
use App\Model\BrandCategory;
use App\User;
use App\Helper\CropAvatar;

use App\Http\Requests;
use App\Helper\helpers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Request;
use Input; /* For input */
use Validator;
use Session;
use DB;
use Mail;
use Image;


class CategoryController extends BaseController {

   /**
    * Display a listing of the resource.
    *
    * @return Response
    */
    public function __construct() {
  		parent::__construct();
  		
    }
    
	/********************************************************************************
	 *								GET CATEGORY LIST								*
	 *******************************************************************************/
    function getList()
    {
		//echo "Test";
		//exit;
        $module_head 		= "Category List";
        $category_class 	= "active";
        $record_per_page    = 100;
        $search_key 		= Request::query('search_key');
        $active 			= Request::query('active');
		$title				= "Category Management";
        $where 				= '';
		$main_category_slugs= $this->main_category_slugs;
		$sl_no				= 1;

		if(Request::input('page')!=''){
			$sl_no = (Request::input('page')*100)+1;
		}

        if($search_key != '')
		{
            $where 			= "`name` LIKE '%".$search_key."%' AND ";
        }
        if($active != '')
		{
            $where 		   .= "`status`= '".$active."' AND ";
        }
        
		$where 		   .= '1';
        $categories		= Category::whereRaw($where)->orderBy('name','ASC')->where('parent_id','=','0')->paginate($record_per_page);
		
        return view('admin.category.category_list',compact(
														'categories','category_class',
														'module_head','search_key',
														'active','title','sl_no','main_category_slugs'
													)
					);
    }

	/********************************************************************************
	 *									ADD CATEGORY 								*
	 *******************************************************************************/
	function getAdd()
	{
        $category_class 	= "active";
        $module_head 		= "Add Category Details";
		$title				= "Add Category";
		return view('admin.category.add_category_details',compact(
															'module_head',
															'category_class',
															'title'
														)
					);
    }
	
	function postAdd()
	{
		$category_class 	= "active";
        $module_head 		= "Add Category Details";
		$data				= Request::all();
		if(count($data['gender'])>1){
			$gender = 3;
		}
		else{
			foreach ($data['gender'] as $value) {
				$gender = $value;
			}
		}

		$picture =  '';
		if(!empty($data['upload_banner_image']) ){
			$picture = $data['upload_banner_image'];
		}

		$picture_women = '';
		if(!empty($data['upload_banner_image_women'])){
			$picture_women = $data['upload_banner_image_women'];
		}

		$current_created_slug=$new_created_slug = preg_replace('/[^\da-z]/i', '-', strtolower($data['name']));
        $new_created_slug=$current_created_slug=trim(preg_replace('/-+/', '-', $new_created_slug), '-');

		$add_category		= Category::create([

								'name'          => trim($data['name']),
								'slug'			=> $new_created_slug,
								'gender_cat'	=> $gender,
								'featured_image'=> $picture,
								'image_url_men'=> $data['image_url_men'],
								'featured_image_women'=> $picture_women,
								'image_url_women'=> $data['image_url_women'],
								'image_caption_men'=> $data['image_caption_men'],
								'image_caption_women'=> $data['image_caption_women'],
								'status'       	=> $data['status'],
								'created_at'	=> date('Y-m-d H:i:s'),
								'updated_at'	=> date('Y-m-d H:i:s')
							]);
		
		Session::flash('success_message', 'Category has been added successfully.'); 
        Session::flash('alert-class', 'alert alert-success'); 
        return redirect('admin/category/list');
    }
	
	/********************************************************************************
	 *									EDIT CATEGORY 								*
	 *******************************************************************************/
    function getEdit($id='')
	{
        $category_class 		= "active";
        $module_head 			= "Edit Category Details";
		$category_id			= $id;
        $category_details 		= Category::where('id', '=', $id)->first();
        $title					= "Edit Category";
        $sub_cat_as_cat 		= 0;
		return view('admin.category.edit_category_details',compact(
			'module_head',
			'category_id',
			'category_class',
			'category_details',
			'sub_cat_as_cat',
			'title'));
    }
	function postEdit($id='')
	{
		$data				= Request::all();
		/*echo "<pre>";
		print_r($data);
		exit;*/
		$category_id			= $id;
		
		if(count($data['gender'])>1){
			$gender = 3;
		}
		else{
			foreach ($data['gender'] as $value) {
				$gender = $value;
			}
		}

		$picture = $data['old_category_image_men'];

		if(!empty($data['upload_banner_image']) ){
			$picture = $data['upload_banner_image'];

	        if(file_exists("uploads/category_image/".$data['old_category_image_men'])){
      			@unlink(public_path('uploads/category_image/thumb/' . $data['old_category_image_men']));
      		}
		}

		$picture_women = $data['old_category_image_women'];
		if(!empty($data['upload_banner_image_women'])){
			$picture_women = $data['upload_banner_image_women'];
			
	        if(file_exists("uploads/category_image/".$data['old_category_image_women'])){
      			@unlink(public_path('uploads/category_image/thumb/' . $data['old_category_image_women']));
      		}
		}

		$edit_category		= Category::where('id', $category_id)
									->update([
										'name'          => trim($data['name']),
										//'slug'			=> $new_created_slug,
										'gender_cat'	=> $gender,
										'featured_image'=> $picture,
										'image_url_men'=> $data['image_url_men'],
										'featured_image_women'=> $picture_women,
										'image_url_women'=> $data['image_url_women'],
										'image_caption_men'=> $data['image_caption_men'],
										'image_caption_women'=> $data['image_caption_women'],
										'status'       	=> $data['status'],
										'created_at'	=> date('Y-m-d H:i:s'),
										'updated_at'	=> date('Y-m-d H:i:s')
									]);

		if($data['sub_cat_as_cat']==1){
			Session::flash('success_message', 'Sub category has been updated successfully.'); 
	        Session::flash('alert-class', 'alert alert-success'); 
	        return redirect('admin/category/child-category-list/'.base64_encode($data['hid_category_id']));
		}else{
			Session::flash('success_message', 'Category has been updated successfully.'); 
        	Session::flash('alert-class', 'alert alert-success'); 
        	return redirect('admin/category/list');
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

            $path = 'uploads/category_image/thumb/';
            $crop = new CropAvatar(null, $json_cropped_data,$_FILES['file'],$path);
            $uploaded_img_name = $crop -> getResult();
        }
        else{
            $image = Input::file('file');
            $filename  = time() . '.' . $image->getClientOriginalExtension();

            $path = 'uploads/category_image/thumb/' . $filename;

            Image::make($image->getRealPath())->save($path);
            $uploaded_img_name = $filename;

        }

        echo $uploaded_img_name;
        exit();

    }
	
	/********************************************************************************
	 *					CHECK CATEGORY EXISTS OR NOT								*
	 *******************************************************************************/
	function getCheck(){
		//echo "getCheckCategory";
		$data				= Request::all();
		/*echo "<pre>";
		print_r($data);
		exit;*/
		$where_raw = "1=1";
		$where_raw .= " AND `name` = '".$data['category_name']."'";
		if($data['hid_category_id']!=""){
			$where_raw .= " AND `id`!= '".$data['hid_category_id']."'";
		}
		$category_details 	= Category::whereRaw($where_raw)->first();
		if(count($category_details)>0){
			echo 1;
		}
		else{
			echo 0;
		}
		exit;
	}
	
	/********************************************************************************
	 *							REMOVE CATEGORY										*
	 *******************************************************************************/
	function getRemove($id=""){
		$this->getRemoveAllSubcategoryAndMapTheirProduct($id);

		/*$cmd = "wget -bq --spider ".url()."/admin/category/remove-all-subcategory-and-map-their-product/".$id;
		shell_exec(escapeshellcmd($cmd));*/

		/*$category_details = Category::find($id);

		if(file_exists("uploads/category_image/".$category_details->featured_image)){
			@unlink(public_path('uploads/category_image/' . $category_details->featured_image));
	        @unlink(public_path('uploads/category_image/thumb/' . $category_details->featured_image));
	    }
        $category_details->delete();*/
        /*Check any product belongs to this category or not, if belongs then assign it to general category */
        //Product::where('category_id', '=', $id)->update(['category_id' => 1]);
        /***************************************************************************************/
        Session::flash('success_message', 'Category has been removed successfully.'); 
        Session::flash('alert-class', 'alert alert-success'); 
        return redirect('admin/category/list');
	
	}

	/*
	*	Checking sub category present or not while deleting a ctaegory, If present 
	*	then delete all subcategory and map their respective product to General category.
	*/

	public function getRemoveAllSubcategoryAndMapTheirProduct($category_id=""){

        $category_details		= Category::where('id',$category_id)->first();
        if(file_exists("uploads/category_image/".$category_details->featured_image)){
			@unlink(public_path('uploads/category_image/' . $category_details->featured_image));
	        @unlink(public_path('uploads/category_image/thumb/' . $category_details->featured_image));
	    }
        $category_details->delete();
        /*Check any product belongs to this category or not, if belongs then assign it to general category */
        Product::where('category_id', '=', $category_id)->update(['category_id' => 1]);
		
		$sub_category_array = Category::where('parent_id',$category_id)->get();
		/*echo "<pre>";
		print_r($sub_category_array);*/
		//exit;
		if(count($sub_category_array)>=0){
			
			foreach($sub_category_array as $key => $subcategory){
				
				Self::getRemoveAllSubcategoryAndMapTheirProduct($subcategory->id);
			
			}

			
		}

		return 1;
	}

	
	/********************************************************************************
	 *							CHANGE STATUS										*
	 *******************************************************************************/
	function getStatus(){
		
		$post_data  = Request::all();
        $status 	= $post_data['this_val'];
        $id 		= $post_data['this_id'];
        $category 	= Category::find($id);
        $category->status = $status;
        $category->save();
        echo "1";
		exit();
	
	}

	/********************************************************************************
	 *							GET SUB CATEGORY LIST								*
	 *******************************************************************************/
    function getChildCategoryList($category_id="")
    {
		/*echo $category_id;
		exit;*/
		$category_id 		= base64_decode($category_id);
		$title				= "Sub Category Management";
        $category_class 	= "active";
        $record_per_page    = 100;
        $search_key 		= Request::query('search_key');
        $active 			= Request::query('active');
        $main_category_slugs= $this->main_category_slugs;
        $category_info		= Category::where('id',$category_id)->first();
        if(count($category_info)==0){
        	return view('errors.404');
        }
        else{
        	$category_info->toArray();
        }
       /* echo "<pre>";
        print_r($categories);
        exit;*/
		
		$module_head 		= ucfirst($category_info['name'])." Sub Category List";
        $where 				= '';
		
		$sl_no				= 1;

		if(Request::input('page')!=''){
			$sl_no = (Request::input('page')*100)+1;
		}

        if($search_key != '')
		{
            $where 			= "`name` LIKE '%".$search_key."%' AND ";
        }
        if($active != '')
		{
            $where 		   .= "`status`= '".$active."' AND ";
        }

		$where 		   .= '1';
        $categories		= Category::where('parent_id',$category_id)->whereRaw($where)->orderBy('name','ASC')->paginate($record_per_page);
        
        /****	Category layer max layer=4(Bhaskar) *****/
        $count_layer = 1;
        $count_layer =  $this->countCategoryLayer($category_id,$count_layer);
		/*----------------------------------------------*/

        return view('admin.category.sub_category_list',compact(
														'categories','category_class',
														'module_head','search_key',
														'active','title','sl_no','category_id','count_layer','main_category_slugs'
													)
					);
    }

    /********************************************************************************
	 *									ADD SUB CATEGORY 							*
	 *******************************************************************************/
	function getAddSubCategory($category_id="")
	{
		$category_id 		= base64_decode($category_id);
        $category_class 	= "active";
        
		$category_info		= Category::where('id',$category_id)->first()->toArray();
		$module_head 		= ucfirst($category_info['name'])." : Add Sub Category Details";
		$title				= ucfirst($category_info['name'])." : Add Sub Category";

		return view('admin.category.add_sub_category_details',compact(
															'module_head',
															'category_class',
															'title','category_id'
														)
					);
    }
	
	function postAddSubCategory($category_id="")
	{
		$category_id 		= base64_decode($category_id);
		$category_class 	= "active";
        $module_head 		= "Add Sub Category Details";
		$data				= Request::all();
		/*echo "<pre>";
		print_r($data);
		exit;*/

		if(count($data['gender'])>1){
			$gender = 3;
		}
		else{
			foreach ($data['gender'] as $value) {
				$gender = $value;
			}
		}

		$current_created_slug=$new_created_slug = preg_replace('/[^\da-z]/i', '-', strtolower($data['name']));
        $new_created_slug=$current_created_slug=trim(preg_replace('/-+/', '-', $new_created_slug), '-');

		$add_category		= Category::create([
								'parent_id'		=> $category_id,
								'name'          => trim($data['name']),
								'slug'			=> $new_created_slug,
								'gender_cat'    => $gender,
								'status'       	=> $data['status'],
								'created_at'	=> date('Y-m-d H:i:s'),
								'updated_at'	=> date('Y-m-d H:i:s')
							]);
		
		Session::flash('success_message', 'Sub Category has been added successfully.'); 
        Session::flash('alert-class', 'alert alert-success'); 
        return redirect('admin/category/child-category-list/'.base64_encode($category_id));
    }

    /********************************************************************************
	 *								EDIT SUB CATEGORY 								*
	 *******************************************************************************/
    function getEditSubCategory($category_id='',$sub_category_id="")
	{
		$category_id 			= base64_decode($category_id);
		$sub_category_id 		= base64_decode($sub_category_id);
        $category_class 		= "active";
        $category_info			= Category::where('id',$category_id)->first()->toArray();
        $module_head 			= ucfirst($category_info['name'])." : Edit Sub Category Details";
        $title					= ucfirst($category_info['name'])." : Edit Sub Category";
        $category_details 		= Category::where('id', '=', $sub_category_id)->first();
        $sub_cat_as_cat 		= 0;
        /*	Checking if category ==accessories then, treat is like main category  */

        $main_category_slugs= $this->main_category_slugs;
       
        if(in_array($category_details->slug,$main_category_slugs)){

	        $module_head 			= "Edit Category Details";
	        $category_details 		= Category::where('id', '=', $sub_category_id)->first();
	        $title					= "Edit Category";
	        $sub_cat_as_cat 		= 1;

			return view('admin.category.edit_category_details',compact(
				'module_head',
				'category_id',
				'category_class',
				'category_details',
				'sub_cat_as_cat',
				'title'));
        }
        else{
        	return view('admin.category.edit_sub_category_details',compact(
			'module_head',
			'category_id',
			'category_class',
			'category_details',
			'sub_category_id',
			'sub_cat_as_cat',
			'title'));
        }
        /*------------------------------------------------------------------------*/
		
    }
	function postEditSubCategory($category_id='',$sub_category_id="")
	{
		$data				= Request::all();
		$category_id 		= base64_decode($category_id);
		$sub_category_id 	= base64_decode($sub_category_id);
		/*echo "<pre>";
		print_r($data);
		exit;*/

		if(count($data['gender'])>1){
			$gender = 3;
		}
		else{
			foreach ($data['gender'] as $value) {
				$gender = $value;
			}
		}

		$current_created_slug=$new_created_slug = preg_replace('/[^\da-z]/i', '-', strtolower($data['name']));
        $new_created_slug=$current_created_slug=trim(preg_replace('/-+/', '-', $new_created_slug), '-');

		$edit_category		= Category::where('id', $sub_category_id)
									->update([
										'name'          => trim($data['name']),
										'slug'			=> $new_created_slug,
										'status'       	=> $data['status'],
										'gender_cat'    => $gender,
									]);
		
		Session::flash('success_message', 'Sub category has been updated successfully.'); 
        Session::flash('alert-class', 'alert alert-success'); 
        return redirect('admin/category/child-category-list/'.base64_encode($category_id));
    }


    /********************************************************************************
	 *								COUNT CATEGORY LAYER 							*
	 *******************************************************************************/

    public function countCategoryLayer($category_id="",$count_layer=""){
    	
    	$categories		= Category::where('id',$category_id)->first();
		if($categories->parent_id==0)
			return $count_layer;
		else{
			$count_layer++;
			return Self::countCategoryLayer($categories->parent_id,$count_layer);
		}

    }

    /********************************************************************************
	 *									CHECK SUB CATEGORY 							*
	 *******************************************************************************/
	function getSubCategoryAvailability($category_id="",$sub_category_id="")
	{
		$data = Request::all();
		/*echo "<pre>";
		print_r($data);
		exit;*/
		$name = $data['name'];
		$sub_category_arr_count = Category::where("name","=",$name)->get()->count();
		if($sub_category_arr_count>0){
			echo "false";
		}
		else{
			echo "true";
		}
		exit();
    }

    /********************************************************************************
	 *							REMOVE SUB CATEGORY									*
	 *******************************************************************************/
	function getRemoveSubCategory($category_id="",$sub_category_id=""){
		$category_id 		= base64_decode($category_id);
		$sub_category_id 	= base64_decode($sub_category_id);

		$this->getRemoveAllSubcategoryAndMapTheirProduct($sub_category_id);
		/*echo system('kill 6008');
		$cmd = "wget -bq --spider ".url()."/admin/category/remove-all-subcategory-and-map-their-product/".$sub_category_id;
		$response = shell_exec(escapeshellcmd($cmd));*/

		/*$filename = 'category.txt';
		$filehandle = fopen($filename, 'w');
		fwrite($filehandle, $cmd);
		fwrite($filehandle, $response);
		fclose($filehandle);*/
		/*$category_details 	= Category::find($sub_category_id);
        $category_details->delete();
        Product::where('category_id', '=', $sub_category_id)->update(['category_id' => 1]);*/
        Session::flash('success_message', 'Sub category has been removed successfully.'); 
        Session::flash('alert-class', 'alert alert-success'); 
        return redirect('admin/category/child-category-list/'.base64_encode($category_id));
	
	}
	
	/********************************************************************************
	 *							CHANGE SUB STATUS									*
	 *******************************************************************************/
	function getSubCategoryStatus(){
		
		$post_data  = Request::all();
        $status 	= $post_data['this_val'];
        $id 		= $post_data['this_id'];
        $category 	= Category::find($id);
        $category->status = $status;
        $category->save();
        echo "1";
		exit();
	
	}

	/********************************************************************************
	 *							CATEGORY WISE BARND LIST									*
	 *******************************************************************************/

	########################### CATEGORY WISE MAP PRODUCT ##########################
	public function getSearchCategoryProduct($category_id='')
	{
		
        $map_category_class = "active";
        $record_per_page 	= 100;
        $title				= "Map Product";
        $srch_brand_id		= Request::query('srch_brand_id');

		$category_arr = Category::where('id', $category_id)->get()->first();
        $module_head  = "Map Product To ". $category_arr->name ." Category";
        
        $brand_array = Brand::orderBy('brand_name','ASC')->get();
		if(count($brand_array)>0){
			$brand_array	= $brand_array->toArray();
		}
		
		return view('admin.category.map_product',compact(
														'category_id',
														'module_head',
														'brand_array',
														'srch_brand_id',
														'map_category_class',
														'record_per_page',
														'title'
													)
					);

	}
	########################### CATEGORY WISE MAP PRODUCT ##########################

    ###################### GET PRODUCT LIST BY CATEGORY ####################
    function getProductList()
    {
    	$category_id = Request::input('cat_id');
    	$category_arr = Category::where('id', $category_id)->get()->first();

        $module_head 		= "Map Product To ". $category_arr->name ." Category";
        $map_category_class = "active";
        $record_per_page    = Request::query('record_per_page');
        $product_for		= Request::query('product_for');
        $search_key 		= Request::query('search_key');
        $srch_brand_id		= Request::query('srch_brand_id');
		$title				= "Map Product";
        $where 				= '';
		
		$sl_no				= 1;

		if(Request::input('page')!=''){
			$sl_no = (Request::input('page')*$record_per_page)+1;
		}

        if($search_key != '')
		{
            //$where 	= "(`name` LIKE '%".addslashes($search_key)."%' OR `advertiser-category` LIKE '%".addslashes($search_key)."%' OR `description` LIKE '%".addslashes($search_key)."%') AND ";
            $where 	= "(`name` LIKE '%".addslashes($search_key)."%') AND ";
        }

        if($srch_brand_id != '')
		{
            $where 		   .= "`brand_id`= '".$srch_brand_id."' AND ";
        }

		//$where 		   .= '`category_id` !='.$category_id.' ';
		$mycategory_id = 1;
		$where 		   .= '`category_id` ='.$mycategory_id.' ';

		/*if($category_arr->gender_cat==1)
        {
        	$where .= ' AND `gender` NOT IN ("female","Girls","Women")';
        }
        else if($category_arr->gender_cat==2){
        	$where .= ' AND `gender` NOT IN ("Boys","male","Men")';
        }*/

        if($product_for){

	        if($product_for==2)
	        {
	        	$where .= ' AND `gender` IN ("female","Girls","Women")';
	        }
	        else if($product_for==1){

	        	$where .= ' AND `gender` IN ("Boys","male","Men")';

	        }else if($product_for==3){

	        	$where .= ' AND `gender` NOT IN ("Boys","male","Men","female","Girls","Women")';
	        }
	    }else{

	    	if($category_arr->gender_cat==1)
	        {
	        	$where .= ' AND `gender` NOT IN ("female","Girls","Women")';
	        }
	        else if($category_arr->gender_cat==2){
	        	$where .= ' AND `gender` NOT IN ("Boys","male","Men")';
	        }
	    }

        $products		= Product::whereRaw($where)->offset(Request::input('offset'))->limit($record_per_page)->get();
        $no_of_product = Product::whereRaw($where)->orderBy('id','ASC')->count();
		//echo '<pre>';
		//print_r($products);exit;
        return view('admin.category.product',compact(
														'products',
														'module_head','search_key','map_category_class',
														'active','title','sl_no','no_of_product'
													)
					);
    }
    ###################### GET PRODUCT LIST BY CATEGORY ####################

    ########################### Product Category Change ####################
    function postProductCategoryAdd(){

    	$data = Request::all();
    	//echo "<pre>";print_r($data);exit;
    	if(isset($data['check_product'])){
    		
	    	foreach($data['check_product'] as $value){


	    		// Get product details
	    		$product 	= Product::find($value);
	    		$old_product_category = $product->category_id;

	    		$new_category_id      = $data['category_id'];
		        $old_category_id      = $product->category_id;
		        /********************* Product New Brnad And Category *********************/
		        if($product->brand_id!=0)
		            $this->productNewBrandCategory($product->brand_id,$new_category_id);

	    		Product::where('id', '=', $value)->update(['category_id' => $data['category_id']]);

	    		/*********** Checking Product Exist With Old Brand And Category ************/
	        	if($product->brand_id!=0)
	            	$this->checkProductExistWithOldBrandCategory($product->brand_id,$old_category_id);
	    	}
	    }

    	/* Cheking where to redirect main or subcategory(Bhaskar)  */
    	$page_redirect_url = 'admin/category/list';
    	$cat_rel_info = Category::where('id',$data['category_id'])->first();
    	if($cat_rel_info->parent_id!=0){
    		$page_redirect_url = 'admin/category/child-category-list/'.base64_encode($cat_rel_info->parent_id);
    	}
    	else{
    		$page_redirect_url = 'admin/category/list';
    	}
    	/**************************************************/
    	Session::flash('success_message', 'Products category mapped successfully'); 
		Session::flash('alert-class', 'alert alert-success'); 
		return redirect($page_redirect_url);
    }
     ########################### Product Category Change ####################

	function getCategoryProductList($category_id="")
    {
    	$offset = Request::input('offset');
    	$page = Request::input('page');

    	$category_arr = Category::where('id', $category_id)->get()->first();

        $module_head 		= "Product List ". $category_arr->name ." Category";
        $brand_class 		= "active";
        $record_per_page    = 100;
        $category_id 			= $category_id;
		$title				= "Category Management";
        $where 				= '';
		
		$sl_no				= 1;

		if($page !=''){
			$sl_no = $page*($record_per_page)+1;
		}

        if($category_id != '')
		{
            $where 			= "`category_id` = '".$category_id."'";
        }

		$where 		   .= '';
        $products		= Product::whereRaw($where)->orderBy('id','ASC')->offset($offset)->limit(100)->get();
        $no_of_product = Product::whereRaw($where)->orderBy('id','ASC')->count();

        /*$cmd = "wget -bq --spider ".url()."/admin/category/category-update-brand?alldata=".$no_of_product;
		//$cmd = "wget -bq --spider http://localhost/cashback/cron/delete-duplicate";
		$pid = shell_exec(escapeshellcmd($cmd));*/

        return view('admin.category.category_product',compact(
														'products',
														'module_head','category_id','map_category_class',
														'active','title','sl_no','no_of_product'
													)
					);
    }

    function getCategoryProductListAjax($category_id="")
    {
    	$offset = Request::input('offset');
    	$page = Request::input('page');
    	$product_name = Request::input('product_name');

        $module_head 		= "Category Management";
        $brand_class 		= "active";
        $record_per_page    = 100;
        $category_id 			= $category_id;
		$title				= "Category Management";
        $where 				= '';
		
		$sl_no				= 1;

		if($page !=''){
			$sl_no = $page*($record_per_page)+1;
		}

        if($category_id != ''){
            $where 	= "`category_id` = '".$category_id."'";
        }
        if($product_name!=""){
            $where 	.= " AND `name` LIKE '%".$product_name."%'";
        }

		$where 		   .= '';
        $products		= Product::whereRaw($where)->orderBy('id','ASC')->offset($offset)->limit(100)->get();
        $no_of_product = Product::whereRaw($where)->orderBy('id','ASC')->count();

        return view('admin.category.category_product_list',compact(
														'products',
														'module_head','category_id','map_class',
														'active','title','sl_no','no_of_product'
													)
					);

    }


    ############### DELECT PRODUCT CLICK ON DELECT BUTTON #################
    public function getProductDelete($id)
	{
		$category_details   = Product::find($id);

        $brand_id           = $category_details->brand_id;
        $category_id        = $category_details->category_id;
        
		Product::where('id', '=', $id)->update(['category_id' => 1]);
		$this->checkProductExistWithOldBrandCategory($brand_id,$category_id);

        Session::flash('success_message', 'Map product has been removed successfully.'); 
        Session::flash('alert-class', 'alert alert-success'); 
        return redirect('admin/category/list/');
	}

	############### DELECT PRODUCT CLICK ON DELECT BUTTON #################
	public function postRemoveAllProduct(){

		$post_data  = Request::all();
		//print_r($post_data);exit;
		foreach($post_data['check_product'] as $id){
			$category_details   = Product::find($id);

	        $brand_id           = $category_details->brand_id;
	        $category_id        = $category_details->category_id;

			if($category_id != 1){
				Product::where('id', '=', $id)->update(['category_id' => 1]);
				if($brand_id!=0){
					$brandArray[]['brand_id']= $brand_id;
					$this->checkProductExistWithOldBrandCategory($brand_id,$category_id);
				}
			}
		}
		$brandArray[]['category_id'] = $category_id;
		$json_arr = json_encode($brandArray);
		/*$file = fopen("category.txt","w");
		echo fwrite($file,$json_arr);
		fclose($file);*/
		/*$cmd = "wget -bq --spider ".url()."/admin/category/category-update-brand?alldata=".$json_arr;
		$pid = shell_exec(escapeshellcmd($cmd));*/

		Session::flash('success_message', 'Map product has been removed successfully.'); 
        Session::flash('alert-class', 'alert alert-success'); 
        return redirect('admin/category/list/');
	}

	

}
