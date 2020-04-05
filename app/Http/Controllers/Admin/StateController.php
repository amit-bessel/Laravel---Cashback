<?php namespace App\Http\Controllers\Admin;

use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Model\City; /* Model name*/
use App\Model\Zone; /* Model name*/
use App\Model\Country;
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


class StateController extends BaseController {

   /**
    * Display a listing of the resource.
    *
    * @return Response
    */
    public function __construct() {
        parent::__construct();
    }
    /********************* Hotel List *************************/
    function state_list($country_id)
    {
		$country_class = "active";
        $country_name = Country::where('id',$country_id)->first(['name']);
		$module_head = "States of ".$country_name->name;
		
        $record_per_page = 20;
        //$countries = Country::orderBy('name','ASC')->paginate($record_per_page);

        $search_key = Request::query('search_key');
        $active = Request::query('active');

        $where = '';
        if($search_key != ''){
            $where = "`name` LIKE '%".$search_key."%' AND ";
        }
        if($active != ''){
            $where .= "`status`= '".$active."' AND ";
        }
        $where .= '1';

        $states = Zone::whereRaw($where)->where('country_id',$country_id)->with(array('countryrelation'))->orderBy('name','ASC')->paginate($record_per_page);
       // echo $cities[0]['country_relation']->name;
       //echo "<pre>";   print_r($states);  echo "</pre>";exit;
        return view('admin.state.state_list',compact(
                'states','country_class','country_id','module_head','search_key','featured','active','count_featured_country'));
    }

    function add_state($country_id){
        $country_class = "active";
		$country_name = country::where('id',$country_id)->first(['name']);
        $module_head = "Add State to ".$country_name->name;
        $countries= Country::all();
   //  echo "<pre>";print_r($countries);exit;
        if(Request::isMethod('post')){
            $city_details = Input::all();
            $city= Zone::create([
                'name'           => $city_details['name'],
                'country_id'     => $country_id,
             ]);
        
            $lastInsertedId = $city->id;
            Session::flash('success_message', 'State details has been saved successfully.'); 
            Session::flash('alert-class', 'alert alert-success'); 
            return redirect('admin/state-list/'.$country_id);
        }
        return view('admin.state.add_state',compact('countries','country_id','module_head','country_class'));
    
    }

    function edit_state($country_id='',$state_id=''){
		
        $country_class = "active";
		$country_name = country::where('id',$country_id)->first(['name']);
        $module_head = "Edit State of ".$country_name->name;
        $state_details = Zone::where('id', '=', $state_id)->first();
		
		
        if(Request::isMethod('post')){
            //echo "<pre>";print_r($_POST);exit;
            $state_details 		= Input::all();

            $city 				= Zone::find($state_id);
            $city=  Zone::where('id',$state_id)
						->update([
							'name'           => $state_details['name'],
							'country_id'     => $country_id,
						]);
            Session::flash('success_message', 'State details has been saved successfully.'); 
            Session::flash('alert-class', 'alert alert-success'); 
            return redirect('admin/state-list/'.$country_id);
        }
        $countries= Country::all();
        return view('admin.state.edit_state',compact('country_id','countries','state_details','module_head','country_class'));
    }


    function change_status(){
        $post_data  = Request::all();
        $status = $post_data['this_val'];
        $id = $post_data['this_id'];
        $state = Zone::find($id);
        $state->status = $status;
        $state->save();
        echo "1";exit();
    }

    function delete_city_details($country_id='',$id=''){
        $city = City::find($id);
        $city->delete();
        Session::flash('success_message', 'City details has been deleted successfully.'); 
        Session::flash('alert-class', 'alert alert-success'); 
        return redirect('admin/city-list/'.$country_id);

    }
    function change_featured(){
        $post_data  = Request::all();
        $feature = $post_data['this_val'];
        $id = $post_data['this_id'];
        
        $count_featured_country = City::where('is_featured', '=', 1)->count();
        
        $country = City::find($id);
        
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
	
	
	function check_state_availability(){
		$get_data  = Request::all();
		/*echo "<pre>";
		print_r($get_data);
		exit;*/
		if($get_data['state_id']!='')
		{
			$check_availability	=   Zone::whereNotIn('id',[$get_data['state_id']])
										->where('country_id',$get_data['country_id'])
										->where('name',$get_data['name'])
										->count();
		}
		else
		{
			$check_availability	=   Zone::where('country_id',$get_data['country_id'])
										->where('name',$get_data['name'])
										->count();
		}
		
		if($check_availability>0)
		{
			echo 1;
		}
		else
		{
			echo 0;	
		}
		exit();
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
