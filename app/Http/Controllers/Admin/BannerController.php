<?php namespace App\Http\Controllers\Admin; /* path of this controller*/


use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Http\Controllers\Admin\BaseController;
use App\Model\SiteUser;
use Illuminate\Support\Facades\Request;
use Input; /* For input */
use Validator;
use Session;
use Illuminate\Pagination\Paginator;
use DB;
use Auth;
use Redirect;

use App\Helper\helpers;
use App\Model\Banner;
use App\Model\Cmspage;
use Imagine\Image\Box;
use Image\Image\ImageInterface;
use Intervention\Image\Facades\Image; // Use this if you want facade style code

class BannerController extends BaseController {
    
   
    public function getBannerList() /************ BANNER LIST *************/
    {
        $module_head 		= "Banner List";
        $banner_class 		= "active";
        $title				= "Banner List";
        $where 				= '';
        
        $banner_arr         = Banner::get(); //FETCH BANNER LIST
        return view('admin.banner.bannerlist',compact('module_head','banner_class','title','banner_arr'));
    }
    public function getAjaxBannerList() /************ AJAX BANNER LIST *************/
    {
        if(Request::isMethod('post'))
        {
            $data 			 = Request::all();
            
            $columns = array(
            0 =>'id',
            1 => 'page_name',
            2 =>'banner_heading',
            );
            
			
            $whereRaw 		 = "1=1";
            $whereRaw_tot	 = "1=1";
            
            if( !empty($data['search']['value']) ) {   // if there is a search parameter, $requestData['search']['value'] contains 	search parameter
                $whereRaw.=" AND ( page_name LIKE '%".$data['search']['value']."%' )";
                $whereRaw_tot.=" AND ( page_name LIKE '%".$data['search']['value']."%' )";
            }
            
            
            $banner_Arr	= Banner::whereRaw($whereRaw)->skip($data['start'])->take($data['length'])->orderBy($columns[$data['order'][0]['column']],$data['order'][0]['dir'])->get();
			
			
            $totalRecords       = Banner::whereRaw($whereRaw_tot)->get()->count();
            
            $all_banner_Arr = array();
            $i = 0;
            if($banner_Arr->count()>0)
            {
                foreach($banner_Arr as $banner_list)
                {
                    $all_banner_Arr[$i] = $banner_list->toArray();
                    $all_banner_Arr[$i]['page_name'] = ucfirst($banner_list->page_name);
                    
                    if(($banner_list->banner_image!="") && (file_exists("public/uploads/banner/thumb/".$banner_list->banner_image)))
                    {
                        $all_banner_Arr[$i]['banner_thumb_image'] = '<img src="'.url().'/public/uploads/banner/thumb/'.$banner_list->banner_image.'" style="height:200px; width:300px;">';
                    }
                    else
                    {
                        $all_banner_Arr[$i]['banner_thumb_image'] = '<img src="'.url().'/public/no-image.jpg">';
                    }
                    
					// if(($banner_list->is_deletable == '1')) //delete will show if multiple home page banner available
					// {
					// 	$all_banner_Arr[$i]['action'] = '<a href="'.url().'/admin/banner/edit-banner/'.$banner_list->id.'" class="btn" ><i class="fa fa-pencil-square-o" aria-hidden="true" title="Edit"></i></a><a href="javascript:void(0);" class="btn" onclick="delete_banner('.$banner_list->id.');" ><i class="fa fa-trash-o" aria-hidden="true" title="Delete"></i></a>';
					// }
					//else
					//{
						$all_banner_Arr[$i]['action'] = '<a class="view-icon" href="javascript:void(0);" data-toggle="modal" data-target="#myModal'.$banner_list->id.'"><i class="fa fa-eye" aria-hidden="true" title="View Description"></i></a><a class="edit-icon" href="'.url().'/admin/banner/edit-banner/'.$banner_list->id.'" class="btn" ><i class="fa fa-pencil-square-o" aria-hidden="true" title="Edit"></i></a><a class="delete-icon" href="javascript:void(0)" onclick="delete_banner('.$banner_list->id.');"><i class="fa fa-trash-o" aria-hidden="true"></i></a>';
					//}
                    $i++;
                }
            }
            
            $json_data = array(
            "draw"            => intval( $data['draw'] ),
            "recordsTotal"    => intval( $totalRecords ),
            "recordsFiltered" => intval($totalRecords),
            "data"            => $all_banner_Arr   // total data array
            );
            
            return json_encode($json_data);
        }
    }
    public function getAddBanner()
    {
        $module_head 		= "Add Banner";
        $banner_class 		= "active";
        $title				= "Add Banner";
        
        $added_banner_page  = Banner::get();
        $page_arr			= array();
        
        if($added_banner_page->count()>0)
        {
            foreach($added_banner_page as $added_banner)
            {
                $page_arr[] = $added_banner->page_name;
            }
        }
        $min_height = 500;
        $min_width = 1000;
        
        //$cms_page_arr 		=  Cmspage::where("status","1")->orderBy('slug','asc')->get();
        $cms_page_arr=array();
        
        return view('admin.banner.banner_add',compact('module_head','banner_class','title','cms_page_arr','min_height','min_width'));
    }
    public function postAddBanner()
    {
        if(Request::isMethod('post'))
        {
            $data 		= Request::all();
			
			/***************** SERVER SIDE VALIDATION -start *****************/
            
            $validator = Validator::make($data, [
           // 'page_name' => 'required',
            'banner_heading' => 'required|max:50',
            'banner_text' => 'required|max:200',
            'banner_image' => 'required|mimes:jpeg,bmp,png,jpg'
            ]);
            
            if ($validator->fails()) {
                
                return redirect('admin/banner/add-banner')
                ->withErrors($validator)
                ->withInput();
            }
            
			/***************** SERVER SIDE VALIDATION -end *****************/
            
            if(!empty($data['banner_image']))
            {
                $image = Input::file('banner_image');
                $destinationPath = 'public/uploads/banner';
                
                $filename  = time() . '.' . $image->getClientOriginalExtension();
                
                $thumb_image_path = public_path('uploads/banner/thumb/' . $filename);
                
                // Image::make($image->getRealPath())->resize(203, 184,
                // function ($constraint) {
                //     $constraint->aspectRatio();
                // })
                // ->resizeCanvas(203, 184)
                // ->save($thumb_image_path);

                Image::make($image->getRealPath())->save($thumb_image_path);
                
                
                Input::file('banner_image')->move($destinationPath, $filename);
            }
            
			
			// $tot_home_banner = Banner::where("page_name","home")->get()->count(); //total home page banner
			
			// if(($data['page_name'] == 'home') && ($tot_home_banner>=1))
			// {
			// 	$is_deletable = '1';
			// }
			// else
			// {
			// 	$is_deletable = '0';
			// }
            
            $banner 	= Banner::create([
            //'page_name' 		=> filter_var($data['page_name'], FILTER_SANITIZE_STRING),
            'page_name'         => "Home",
            'banner_image' 		=> $filename,
            'banner_heading' 	=> filter_var($data['banner_heading'], FILTER_SANITIZE_STRING),
            'banner_text' 		=> filter_var($data['banner_text'], FILTER_SANITIZE_STRING),
            //'banner_link' 		=> filter_var($data['banner_link'], FILTER_SANITIZE_STRING),
			'is_deletable' 		=> '0'
            ]);
            Session::flash('success_message', 'Banner successfully saved');
            Session::flash('alert-class', 'alert alert-success');
            return redirect('admin/banner/banner-list');
        }
    }
    
    public function getEditBanner($id)
    {
		
        $module_head 		= "Edit Banner";
        $banner_class 		= "active";
        $title				= "Edit Banner";
		
		$id   				= filter_var($id, FILTER_SANITIZE_STRING);
				
		$banner_details		=  Banner::where("id",$id)->first();
        
        $cms_page_arr 		=  Cmspage::where("status","1")->get();
        
        if(!empty($banner_details) == 0)
        {
            Session::flash('failure_message', 'Banner not available.');
            Session::flash('alert-class', 'alert alert-error');
            return redirect('admin/banner/banner-list');
        }
        if($banner_details['page_name'] == 'home')
        {
            $min_height = 500;
            $min_width = 1000;
        }
        else
        {
            $min_height = 500;
            $min_width = 1000;
        }
        
        return view('admin.banner.banner_edit',compact('module_head','banner_class','title','banner_details','cms_page_arr','min_height','min_width'));
    }
    public function postEditBanner()
    {
        if(Request::isMethod('post'))
        {
            $data 			 			= Request::all();
            $destinationPath 			= 'public/uploads/banner';
			$banner_id	 				= filter_var($data['banner_id'], FILTER_SANITIZE_STRING);
			
			/***************** SERVER SIDE VALIDATION -start *****************/
            
            $validator = Validator::make($data, [
            'banner_heading' => 'required|max:50',
            'banner_text' => 'required|max:200',
            ]);
            
            if ($validator->fails()) {
                
                return redirect('admin/banner/edit-banner/'.$banner_id)
                ->withErrors($validator)
                ->withInput();
            }
            
			/***************** SERVER SIDE VALIDATION -end *****************/
            
            
            $banner_details				= Banner::where("id",$banner_id)->first();
            
            $filename 					= "";
            
            if(!empty($data['banner_image']))
            {
                /*$image = Input::file('banner_image');
                
                $filename  = time() . '.' . $image->getClientOriginalExtension();
                
                $thumb_image_path = public_path('uploads/banner/thumb/' . $filename);
                
                


                Image::make($image->getRealPath())->save($thumb_image_path);
                
                
                Input::file('banner_image')->move($destinationPath, $filename);*/

                 // if(Request::hasFile('file')){

            $file = $data['banner_image'];
            $filename = time() . '.' .$file->getClientOriginalName();
            $path = public_path().'/uploads/banner/thumb/';
            $file->move($path, $filename);
        // }
                
                /********************* REMOVE OLD FILES *************************/
                
                if(file_exists("public/uploads/banner/thumb/".$banner_details->image_name))
                {
                    @unlink("public/uploads/banner/thumb/".$banner_details->image_name); //delete old image
                }
                if(file_exists("public/uploads/banner/".$banner_details->image_name))
                {
                    @unlink("public/uploads/banner/".$banner_details->image_name); //delete old image
                }
            }
            
            $banner_arr 				= Banner::find($banner_id);
            
            if($filename!="")
            {
                $banner_arr->banner_image  	= $filename;
            }
            if($banner_arr->page_name == 'home')
            {
                $banner_arr->banner_subheading    = filter_var($data['banner_subheading'], FILTER_SANITIZE_STRING);
            }
            $banner_arr->banner_heading = filter_var($data['banner_heading'], FILTER_SANITIZE_STRING);
            $banner_arr->banner_text 	= filter_var($data['banner_text'], FILTER_SANITIZE_STRING);
            
            $banner_arr->save();
            
            Session::flash('success_message', 'Banner successfully updated.');
            Session::flash('alert-class', 'alert alert-success');
            return redirect('admin/banner/banner-list');
        }
    }
    public function getDeleteBanner($id)
    {
		$id   				= filter_var($id, FILTER_SANITIZE_STRING);
		
		$banner_count 		= Banner::where("id",$id)->get()->count();
		if($banner_count == 0)
        {
            Session::flash('failure_message', 'Banner not available.');
            Session::flash('alert-class', 'alert alert-error');
            return redirect('admin/banner/banner-list');
        }
		
        $banner_details 	 = Banner::where("id",$id)->first();
        $banner 			 = Banner::find($id);
        $banner->delete();
        Session::flash('success_message', 'Banner successfully deleted.');
        Session::flash('alert-class', 'alert alert-success');
        return redirect('admin/banner/banner-list');
    }
    
}
