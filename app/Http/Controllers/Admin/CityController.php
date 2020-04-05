<?php namespace App\Http\Controllers\Admin;

use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Model\City; /* Model name*/
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


class CityController extends BaseController {

   /**
    * Display a listing of the resource.
    *
    * @return Response
    */
    public function __construct() {
  
    }
    /********************* Hotel List *************************/
    function getList()
    {
		$module_head      = "City List";
        $city_class       = "active";
        $record_per_page    = 20;
        $title              = "City Management";
        $city_arr         = City::orderBy('id','ASC')->get();
        
        return view('admin.city.city_list',compact('city_arr','module_head',
                                                        'city_class',
                                                        'record_per_page',
                                                        'title'));
    }

    function getAddCity()
    {
        $city_class = "active";
	    $module_head = "Add City";
		return view('admin.city.add_city',compact('module_head','city_class'));
    }

    function getEditCity($city_id='')
    {
        
        $city_class = "active";
        $module_head = "Edit City";
        $city_details = City::where('id', '=', $city_id)->first();
        return view('admin.city.edit_city',compact('city_details','module_head','country_class'));
    }

    function postAddCity($city_id='')
    {

       if(Request::isMethod('post'))
        {
            $city_details = Input::all();
            $city_eng = $city_details['name'];
            $city_arabic = $city_details['arabic_name'];
            $rowcount = City::where('city_eng', '=', $city_eng)->orWhere('city_arabic', '=', $city_arabic)->count();
           
              if($city_details['action'] === 'Save')
                {
                 if($rowcount>0)
                  {
                    Session::flash('failure_message', 'This value already added.');
                    return redirect('admin/city/add-city/');
                  }
                 else
                  {
                    $city= City::create([
                     'city_eng'        => $city_details['name'],
                     'city_arabic'     => $city_details['arabic_name'],
                     'is_active'       =>1,
                    ]);
                    $lastInsertedId = $city->id;
                    Session::flash('success_message', 'City details has been saved successfully.');
                  }  
                    
                }
               else
                {
                   $city = City::find($city_id);
                   $city = City::where('id',$city_id)
                            ->update([
                                'city_eng' => $city_details['name'],
                                'city_arabic' => $city_details['arabic_name'],
                            ]);
                   Session::flash('success_message', 'City details has been Updated successfully.'); 
                }
                Session::flash('alert-class', 'alert alert-success'); 
                return redirect('admin/city/list/');

              
            
        }
    }

    function postChangeStatus(){
        $post_data  = Request::all();
        $status = $post_data['status'];
        $id = $post_data['id'];
        $city = City::find($id);
        $city->is_active = $status;
        $city->save();
        echo "1";exit();
    }
 
 function getDeleteCity($id=''){
        $city = City::find($id);
        $city->delete();
        Session::flash('success_message', 'City has been deleted successfully.'); 
        Session::flash('alert-class', 'alert alert-success'); 
        return redirect('admin/city/list/');

    }

    function  getDeleteAll()
    {
        $post_id  = Request::all();
        $id = explode(",", $post_id['ids']);
        $city = City::whereIn('id',$id)->delete();
        echo "1";
        exit;
    }
	
	


}
