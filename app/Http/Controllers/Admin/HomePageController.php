<?php namespace App\Http\Controllers\Admin;

use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;

use App\Model\Category; /* Model name*/
use App\Model\SubCategory; /* Model name*/
use App\Model\Product; /* Model name*/
use App\Model\Brand; /* Model name*/
use App\Model\BrandCategory; /* Model name*/
use App\Model\HomePageDetail; /* Model name*/
use Intervention\Image\Facades\Image; // Use this if you want facade style code
use App\User;
use App\Http\Requests;
use App\Helper\helpers;
use App\Helper\CropAvatar;

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


class HomePageController extends BaseController {

   /**
    * Display a listing of the resource.
    *
    * @return Response
    */
    public function __construct() {
        parent::__construct();
    }
    
	
    function getList(){

    	$product_class 			= "active";
        $module_head 			= "Home Page Management";
        $title					= "Home Page Management";
        
        $home_page_details_for_men      = HomePageDetail::where('gender',1)->get();
        $home_page_details_for_women    = HomePageDetail::where('gender',2)->get();

        if(count($home_page_details_for_men)>0){
            $home_page_details_for_men = $home_page_details_for_men->toArray();
        }
        if(count($home_page_details_for_women)>0){
            $home_page_details_for_women = $home_page_details_for_women->toArray();
        }
        /*echo "<pre>";
        print_r($home_page_details_for_men);
        exit;*/
		return view('admin.homepage.home_page',compact(
			'module_head',
			'product_id',
			'product_class',
			'product_details',
			'title',
            'home_page_details_for_men','home_page_details_for_women'
            ));
    	
    	
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

            $path = 'uploads/home_page_images/';
            $crop = new CropAvatar(null, $json_cropped_data,$_FILES['file'],$path);
            $uploaded_img_name = $crop -> getResult();
        }
        else{
            $image = Input::file('file');
            $filename  = time() . '.' . $image->getClientOriginalExtension();

            $path = 'uploads/home_page_images/' . $filename;

            Image::make($image->getRealPath())->save($path);
            $uploaded_img_name = $filename;

        }

        echo $uploaded_img_name;
        exit();

    }

    function postSave(){

    	$data = Request::all();
    	/*echo "<pre>";
    	print_r($data);exit;*/
        foreach($data['decription'] as $key => $description){

            $update_data = HomePageDetail::where('id',$key)
                                        ->update([
                                            'description'=>$description,
                                            'cashback'=>$data['cashback'][$key],
                                            'link'=>$data['link'][$key]
                                        ]);

            if(!empty($data['upload_banner_image_'.$key]))
            {
                $file_input_name = $data['upload_banner_image_'.$key];
                $update_data = HomePageDetail::where('id',$key)
                                        ->update([
                                            'image'=>$file_input_name
                                        ]);
            }
        }
		Session::flash('success_message', 'Home page details updated successfully'); 
		Session::flash('alert-class', 'alert alert-success'); 
		return redirect('/admin/homepage/list');

    }

}
