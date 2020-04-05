<?php namespace App\Http\Controllers\Admin;

use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;

use App\Model\Country; /* Model name*/
use App\Model\Category; /* Model name*/
use App\Model\Sitesetting; /* Model name*/
use App\Model\BusinessList; /* Model name*/
use App\Model\Testimonial; /* Model name*/
use App\Model\Topbanner; /* Model name*/

use App\User;
use App\Http\Requests;
use App\Helper\helpers;
use App\Helper\CropAvatar;


use App\Http\Controllers\Admin\BaseController;
use Illuminate\Support\Facades\Request;
use Intervention\Image\Facades\Image; // Use this if you want facade style code
use Input; /* For input */
use Validator;
use Session;
use DB;
use Mail;

class TopbannerController extends BaseController {

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
	
	public function topbannerList()
	{
		//echo 'ok';exit;
    	$module_head 		= "Banner Management";
        $topbanner_class 		= "active";
        $record_per_page 	= 20;
        $title				= "Banner Management";
        $banner_arr			= Topbanner::orderBy('id','ASC')->get();
		
		return view('admin.topbanner.topbanner_list',compact(
														'banner_arr',
														'module_head',
														'topbanner_class',
														'record_per_page',
														'title'
													)
					);
	}
	public function editBanner($id)
	{
		$module_head 		= "Edit Banner";
        $topbanner_class 		= "active";
        $title				= "Banner Management";
		
		$banner_arr = Topbanner::where("id",$id)->first();
		if(count($banner_arr) == 0)
		{
			Session::flash('failure_message', 'This news does not exists'); 
			Session::flash('alert-class', 'alert alert-error'); 
			return redirect('admin/topbanner-list');
		}
		
		return view('admin.topbanner.edit_banner',compact(
														'module_head',
														'topbanner_class',
														'title',
														'banner_arr'
													)
					);
	}
	public function updateBanner()
	{
		if(Request::isMethod('post'))
		{
			$data = Request::all();
			
			//echo'<pre>';print_r($data);exit;
			$banner = Topbanner::find($data['banner_id']);

			if($banner->banner_image!=$data['upload_banner_image'] && $data['upload_banner_image']!=''){
				
				if($banner->banner_image!=''){
					if(file_exists("uploads/banner_image/big/".$banner->banner_image)){
						/********** delete old image from folder ************/
						@unlink("uploads/banner_image/big/".$banner->banner_image);
						/********** delete old image from folder ************/
					}
					if(file_exists("uploads/banner_image/thumb/".$banner->banner_image)){
						/********** delete old image from folder ************/
						@unlink("uploads/banner_image/thumb/".$banner->banner_image);
						/********** delete old image from folder ************/
					}
				}
				$banner->banner_image 		= $data['upload_banner_image'];				
			}
			
			if($banner->banner_image_women!=$data['upload_banner_image_women'] && $data['upload_banner_image_women']!=''){
				
				if($banner->banner_image_women!=''){
					if(file_exists("uploads/banner_image/big/".$banner->banner_image_women)){
						/********** delete old image from folder ************/
						@unlink("uploads/banner_image/big/".$banner->banner_image_women);
						/********** delete old image from folder ************/
					}
					if(file_exists("uploads/banner_image/thumb/".$banner->banner_image_women)){
						/********** delete old image from folder ************/
						@unlink("uploads/banner_image/thumb/".$banner->banner_image_women);
						/********** delete old image from folder ************/
					}
				}
				$banner->banner_image_women = $data['upload_banner_image_women'];					
			}
			
			$banner->banner_url_men 	= $data['banner_url_men'];
			$banner->banner_url_women 	= $data['banner_url_women'];

			$banner->save();
			
			Session::flash('success_message', 'Banner updated successfully'); 
			Session::flash('alert-class', 'alert alert-success'); 
			return redirect('admin/topbanner-list');
        }
	}
	public function addBanner()
	{
		$module_head 		= "Add Banner";
        $topbanner_class 		= "active";
        $title				= "Banner Management";
		
		return view('admin.topbanner.add_topbanner',compact(
														'module_head',
														'topbanner_class',
														'title'
													)
					);
	}

	public function postUploadImage()
    {
        //echo "<pre>";
        $data = Request::all();
        /*print_r($data);
        echo "<hr>";
        exit;*/
        //$image_file_name = Input::file('file');
       /* print_r($image_file_name);
        
        echo "<hr>";*/

        $crop_data_arr = array();
        $crop_data_arr['x'] = $data['dataX'];
        $crop_data_arr['y'] = $data['dataY'];
        $crop_data_arr['height'] = $data['dataHeight'];
        $crop_data_arr['width'] = $data['dataWidth'];
        $crop_data_arr['rotate'] = $data['dataRotate'];

        $json_cropped_data = json_encode($crop_data_arr);
        //$json_cropped_data = '{"x":244.80000000000015,"y":652.8000000000001,"height":1958.4000000000003,"width":1958.4000000000003,"rotate":90}';
        //exit; 
        if($data['dataHeight']>0 && $data['dataWidth']>0){
        	$path = 'uploads/banner_image/big/';
            $crop = new CropAvatar(null, $json_cropped_data,$_FILES['file'],$path);
            $uploaded_img_name = $crop -> getResult();
        }
        else{


            $image = Input::file('file');
            $filename  = time() . '.' . $image->getClientOriginalExtension();

            $path = 'uploads/banner_image/big/' . $filename;
           
            Image::make($image->getRealPath())->save($path);
            $uploaded_img_name = $filename;

        }

        echo $uploaded_img_name;
        exit();

    }


	public function postBanner()
	{
		if(Request::isMethod('post'))
		{
			$data = Request::all();

			//echo "<pre>";print_r($data);exit;
			$filename = $data['upload_banner_image'];
			$filename_women = $data['upload_banner_image_women'];

			/*if(!empty($data['banner_image']))
			{
				$image = Input::file('banner_image');
				$filename  = time() . '.' . $image->getClientOriginalExtension(); 
				$big_image_path = 'uploads/banner_image/big/' . $filename;
				Image::make($image->getRealPath())->resize(960, 260,
                        function ($constraint) {
                            $constraint->aspectRatio();
                        })->resizeCanvas(960, 260)->save($big_image_path);
			}

			if(!empty($data['banner_image_women']))
			{
				$image = Input::file('banner_image_women');
				$filename_women  = time() . '_women.' . $image->getClientOriginalExtension(); 
				$big_image_path = 'uploads/banner_image/big/' . $filename_women;
				Image::make($image->getRealPath())->resize(960, 260,
                        function ($constraint) {
                            $constraint->aspectRatio();
                        })->resizeCanvas(960, 260)->save($big_image_path);
			}*/
			
			$banner = Topbanner::create([
									'banner_image' 		=> $filename,
									'banner_image_women'=> $filename_women,
									'banner_url_men'	=> $data['banner_url_men'],
									'banner_url_women' 	=> $data['banner_url_women']
								]);
				
			Session::flash('success_message', 'Banner successfully saved'); 
			Session::flash('alert-class', 'alert alert-success'); 
			return redirect('admin/topbanner-list');
        }
	}
	public function deleteBanner($banner_id)
	{
		$banner_arr = Topbanner::where("id","=",$banner_id)->first();
		/********** delete image from folder ************/
		//@unlink("uploads/banner_image/thumb/".$banner_arr->image);
		if($banner_arr->banner_image!='' && file_exists("uploads/banner_image/big/".$banner_arr->banner_image)){
			@unlink("uploads/banner_image/big/".$banner_arr->banner_image);
		}
		if($banner_arr->banner_image_women!='' && file_exists("uploads/banner_image/big/".$banner_arr->banner_image_women)){
			@unlink("uploads/banner_image/big/".$banner_arr->banner_image_women);
		}
		/********** delete image from folder ************/
		
		Topbanner::destroy($banner_id);
		Session::flash('success_message', 'Banner deleted successfully.'); 
		Session::flash('alert-class', 'alert alert-success'); 
		return redirect('admin/topbanner-list');
	}

	public function topbannerChangeStatus()
	{
		$post_data  = Request::all();

        $status = $post_data['status'];
        $id = $post_data['id'];
        $banner = Topbanner::find($id);
        $banner->is_active = $status;
        $banner->save();
        echo "1"; exit();
	}
}
