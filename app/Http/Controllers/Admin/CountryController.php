<?php namespace App\Http\Controllers\Admin;

use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Model\Country; /* Model name*/
use App\Book;
use App\User;
use App\Http\Requests;
use App\Helper\helpers;

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


class CountryController extends BaseController {

   /**
    * Display a listing of the resource.
    *
    * @return Response
    */
    public function __construct() {
  
    }
    /********************* Hotel List *************************/
    function country_list()
    {
        $module_head 		= "Country List";
        $country_class 		= "active";
        $record_per_page 	= 20;
        $search_key 		= Request::query('search_key');
        $featured 			= Request::query('featured');
        $active 			= Request::query('active');

        $where 				= '';
		
        if($search_key != '')
		{
            $where 			= "`name` LIKE '%".$search_key."%' AND ";
        }
        if($active != '')
		{
            $where 		   .= "`status`= '".$active."' AND ";
        }
		 $where 		   .= '1';
        $countries 			= Country::whereRaw($where)->orderBy('name','ASC')->paginate($record_per_page);
      
        $count_featured_country = Country::where('is_featured', '=', 1)->count();
		
        return view('admin.country.country_list',compact(
														'countries','country_class',
														'module_head','search_key',
														'featured',
														'active',
														'count_featured_country'
													)
					);
    }


	function add_country_details()
	{
        $country_class 	= "active";
        $module_head 	= "Add Country Details";

        if(Request::isMethod('post'))
		{
            $country_detls = Input::all();
			/*echo "<pre>";
			print_r($country_detls);
			exit;*/
			$insert_country_data				= array();
			$insert_country_data['name']		= $country_detls['country_name'];
			$insert_country_data['iso_code_2']	= $country_detls['iso_code'];
			$insert_country_data['status']		= $country_detls['country_status'];
			$insert_country_data['last_update']	= date('Y-m-d H:i:s');
			
            Country::create($insert_country_data);
            ########## Upload Image Section Starts############
            if (Input::hasFile('country_image'))
            {
                $obj = new helpers();
                $country_image = Input::file('country_image');
                //echo "<pre>";print_r($hotel_image);exit();
                $destinationPath = 'uploads/country_logo/'; // upload path                
                $thumb_path = 'uploads/country_logo/thumb/';
                $medium = 'uploads/country_logo/medium/';

                $extension = $country_image->getClientOriginalExtension(); // getting image extension                
                $country_image_name = rand(111111111,999999999).'.'.$extension; // renameing image                    
                $country_image->move($destinationPath, $country_image_name);                    
                $obj->createThumbnail($country_image_name,51,51,$destinationPath,$thumb_path);
                $obj->createThumbnail($country_image_name,300,100,$destinationPath,$medium);

                $country->county_image = $country_image_name;

            }
			
            ########## Upload Image Section Ends############

            Session::flash('success_message', 'Country details has been saved successfully.'); 
            Session::flash('alert-class', 'alert alert-success'); 
            return redirect('admin/country-list/');
        }

        
      
		return view('admin.country.add_country_details',compact(
															'module_head',
															'country_class'
														)
					);
    }
	
    function edit_country_details($id=''){
        $country_class = "active";
        $module_head = "Edit Country Details";


        if(Request::isMethod('post')){
            $country_detls = Input::all();

            //echo $hotel_details['state_id'];exit();

            $country = Country::find($id);
            $country->status = $country_detls['country_status'];
            $country->is_featured = $country_detls['is_featured'];        


            ########## Upload Image Section Starts############
            if (Input::hasFile('country_image'))
            {
                $obj = new helpers();
                $country_image = Input::file('country_image');
                //echo "<pre>";print_r($hotel_image);exit();
                $destinationPath = 'uploads/country_logo/'; // upload path                
                $thumb_path = 'uploads/country_logo/thumb/';
                $medium = 'uploads/country_logo/medium/';

                $extension = $country_image->getClientOriginalExtension(); // getting image extension                
                $country_image_name = rand(111111111,999999999).'.'.$extension; // renameing image                    
                $country_image->move($destinationPath, $country_image_name);                    
                $obj->createThumbnail($country_image_name,51,51,$destinationPath,$thumb_path);
                $obj->createThumbnail($country_image_name,300,100,$destinationPath,$medium);

                $country->county_image = $country_image_name;

            }
            $country->last_update = date('Y-m-d H:i:s');
            $country->save();
            ########## Upload Image Section Ends############



            Session::flash('success_message', 'Country details has been saved successfully.'); 
            Session::flash('alert-class', 'alert alert-success'); 
            return redirect('admin/country-list/');
        }



        $country_details = Country::where('id', '=', $id)->first();
        $count_featured_country = Country::where('is_featured', '=', 1)->count();
        
        if(!isset($country_details)){
            Session::flash('failure_message', 'No such country exists of this id.'); 
            Session::flash('alert-class', 'alert alert-danger'); 
            return redirect('admin/country-list/');
        }else{            
            //$hotel_images_val = HotelImage::where('hotel_id',$id)->get();
            return view('admin.country.edit_country_details',compact(
                'module_head',
                'country_class',
                'country_details','count_featured_country'));
        }
    }

	function check_country_isocode()
	{
		$data	= Request::all();
		$country_name	= $data['country_name'];
		$iso_code		= $data['iso_code'];
		$from_page		= $data['from_page'];
		
		/*******		When adding the country.	******/
		
		if($from_page=='ADD')
		{
			/**********	Checking country with parivular name already exists or not. ***********/
			$check_country_name	= Country::where('name',$country_name)->first();
			$count_country_name	= count($check_country_name);
			
			/**********	Checking country with parivular name already exists or not. ***********/
			$check_iso_code		= Country::where('iso_code_2',$iso_code)->first();
			$count_iso_code		= count($check_iso_code);
			
			if(($count_country_name>0) && ($count_iso_code>0))
			{
				echo 'BOTH ARE EXISTS';
			}
			else if($count_country_name>0)
			{
				echo 'COUNTRY NAME EXISTS';
			}
			else if($count_iso_code>0)
			{
				echo 'ISO CODE EXISTS';
			}
			else
			{
				echo 'BOTH AVAILABLE';
			}
			
		}
		
		/*******	When editing a country details.	******/
		
		else if($from_page=='EDIT')
		{
			/**********	Checking country with parivular name already exists or not. ***********/
			$check_country_name	= Country::where('name',$country_name)->first();
			$count_country_name	= count($check_country_name);
			
			/**********	Checking country with parivular name already exists or not. ***********/
			$check_iso_code		= Country::where('iso_code_2',$iso_code)->first();
			$count_iso_code		= count($check_country_name);
			
			if(($check_iso_code>0) || ($count_iso_code>0))
			{
				echo 1;
			}
			else
			{
				echo 0;
			}
		}
		exit;
	}
	
    function change_status(){
        $post_data  = Request::all();
        $status = $post_data['this_val'];
        $id = $post_data['this_id'];
        $country = Country::find($id);
        $country->status = $status;
        $country->save();
        echo "1";exit();
    }


    function change_featured(){
        $post_data  = Request::all();
        $feature = $post_data['this_val'];
        $id = $post_data['this_id'];
        
        $count_featured_country = Country::where('is_featured', '=', 1)->count();
        
        $country = Country::find($id);
        
            if($feature==1){
                if($count_featured_country>=6){
                    echo 6;
                }
                else{
                    if($country->status==0 && $country->county_image==''){
                        echo "Not Image Nor Active";
                    }
                    else if($country->status==0){
                        echo "Not Active";
                    }
                    else if($country->county_image==''){
                        echo "Not Image";
                    }
                    else if($country->status==1 && $country->county_image!=''){
                        $country->is_featured = $feature;
                        $country->save();
                        echo "1";
                    }
                }
            }
            if($feature==0){
                $country->is_featured = $feature;
                $country->save();
                echo "1";
            }
        
        exit();
    }

	function set_departure_country(){
		$country_id  				= Request::input('country_id');
		$departure_country_value  	= Request::input('departure_country_value');
		
		Country::where('id', $country_id)
			   ->update(array(
							'departure_country' => $departure_country_value
						)
				);
		echo 1;
	}

    /*function select_state()
    {
        $state_details=Request::all();
        $country_id = $state_details['country_id'];
        $state_id = isset($state_details['state_id']) ? $state_details['state_id'] : '';

        //echo $state_id;exit();
        //echo "1111";exit();
        $state_arr = DB::table('zones')->where('country_id', $country_id)->get();
        ?>
        <option value="">Select State</option>
        <?php 
        if(!empty($state_arr))
        {
            foreach($state_arr as $state_list)
            {
                ?>
                <option <?php echo $state_id == $state_list->id ? "selected='selected'" : ''; ?> value="<?php echo $state_list->id; ?>"><?php echo $state_list->name; ?></option>
                <?php
            }
        }
        exit();
    }*/

}
