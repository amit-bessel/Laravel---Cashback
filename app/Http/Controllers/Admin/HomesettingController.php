<?php namespace App\Http\Controllers\Admin; /* path of this controller*/

use App\Model\Sitesetting; /* Model name*/
use App\Model\LastMinuteHotelPrice;
use App\Model\HomePageLastMinuteHotel;
use App\Model\DepartureCity;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Request;
use Input; /* For input */
use Validator;
use Session;
use Imagine\Image\Box;
use Image\Image\ImageInterface;
use Illuminate\Pagination\Paginator;
use DB;

class HomesettingController extends BaseController {

   /**
    * Display a listing of the resource.
    *
    * @return Response
    */
   public function __construct() {
    parent::__construct();
		$site_settings_check = Sitesetting::where("name","=","stripe_public_key")->count();
		if($site_settings_check>0)
		{
			$site_settings_arr = Sitesetting::where("name","=","stripe_public_key")->first()->toArray();
			$stripe_public_key = $site_settings_arr['value'];
		}
		else
		{
			$stripe_public_key = "";
		}
      view()->share('stripe_public_key', $stripe_public_key);
		view()->share('sitesetting_class','active');
    }
   public function index()
   {

       $data=Request::all();

       $limit = 10;

       if(!empty($data["search_key"])){

          $search_key=trim($data["search_key"]);
          
          $sitesettings = DB::table('sitesettings')->where('not_visible','=','0')->where('homepagevisiblestatus','=','1')->where('name','like',$search_key."%")->orderBy('id','desc')->paginate($limit);
           $pagination = $sitesettings->appends ( array (
                'search_key' => Input::get ( 'search_key' ) ,
                
                ) );
       }
       else{
        $search_key='';
        $sitesettings = DB::table('sitesettings')->where('not_visible','=','0')->where('homepagevisiblestatus','=','1')->orderBy('id','desc')->paginate($limit);
       }
       
		
        //echo '<pre>';print_r($vitamins); exit;
	    $sitesettings->setPath('homesetting');
        return view('admin.homesetting.index',compact('sitesettings','search_key'),array('title'=>'Home Settings Management','module_head'=>'Home Settings'));

    }

    public function edit($id)
    {
        $sitesettings=Sitesetting::find($id);
        //echo   $sitesettings ; exit;
        return view('admin.homesetting.edit',compact('sitesettings'),array('title'=>'Edit Home Settings','module_head'=>'Edit Home Settings'));
    }

   
    public function update(Request $request, $id)
    {
       $sitesettingUpdate=Request::all();
           $sitesetting=Sitesetting::find($id);
           //$cmsUpdate['description']=htmlentities($cmsUpdate['description']);
           if (Input::hasFile('image'))
            {
              $destinationPath = 'uploads/share_image/'; // upload path
              $thumb_path = 'uploads/share_image/thumb/';
              $extension = Input::file('image')->getClientOriginalExtension(); // getting image extension
              $fileName = rand(111111111,999999999).'.'.$extension; // renameing image
              Input::file('image')->move($destinationPath, $fileName); // uploading file to given path
              // $this->create_thumbnail($thumb_path,$fileName,$extension); 
              $sitesettingUpdate['value']=$fileName;

              // unlink old photo
              @unlink('uploads/share_image/'.Request::input('share_icon'));
            }
            elseif(Request::input('share_icon')!='')
               $sitesettingUpdate['value']=Request::input('share_icon');

           $sitesetting->update($sitesettingUpdate);
           Session::flash('success', 'Home settings updated successfully'); 
           return redirect('admin/homesetting');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response	7278876384
     */
    public function destroy($id)
    {
        Sitesetting::find($id)->delete();
        Session::flash('success', 'Site setting deleted successfully'); 
        return redirect('admin/homesetting');
    }
    
}
