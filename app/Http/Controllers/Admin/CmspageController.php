<?php namespace App\Http\Controllers\Admin; /* path of this controller*/

use App\Model\Cmspage; /* Model name*/
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
use Customhelpers;
class CmspageController extends BaseController {

   /**
    * Display a listing of the resource.
    *
    * @return Response
    */
    public function __construct() {
        parent::__construct();
      view()->share('cms_class','active');
    }
   public function index()
   {
        $limit = 20;
		$cms = DB::table('cmspages')->where('status',1)->orderBy('id','ASC')->paginate($limit);
        //echo '<pre>';print_r($vitamins); exit;
	    $cms->setPath('cms');
        return view('admin.cms.index',compact('cms'),array('title'=>'Content Management','module_head'=>'Contents'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.cms.create',array('title'=>'Content Management','module_head'=>'Add Content'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $cms=Request::all();
        //echo $title = Request::input('title'); exit;

        $page_name = Request::input('page_name');
        $page_title = Request::input('page_title');
        $description=htmlentities(Request::input('description'));
        $slug = $page_name;
        $meta_description=Request::input('meta_description');
        $meta_keyword=Request::input('meta_keyword');
        //$description=Request::input('description');
        $updated_at=Request::input('updated_at');
        $created_at=Request::input('created_at');
        
        echo $page_name;exit;
        //print_r($cms); exit;
        //Cms::create($cms);
        DB::table('cmspages')->insert(
            ['page_name' => $page_name,'page_title' =>$page_title, 'description' => $description,'slug' => $slug,'meta_name' => $meta_name,'meta_description' => $meta_description,'meta_keyword' => $meta_keyword,'updated_at' => $updated_at,'created_at' => $created_at]
        );
        Session::flash('success', 'Content added successfully'); 
        return redirect('admin/cms');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
       $cms=Cmspage::find($id);
       return view('admin.cms.show',compact('cms'));
    }

    public function edit($id)
    {
        $cms=Cmspage::find($id);
        return view('admin.cms.edit',compact('cms'),array('title'=>'Edit Content','module_head'=>'Edit Content'));
    }

   
    public function update(Request $request, $id)
    {
        //
            $datetime= Customhelpers::Returndatetime();
            $cmsUpdate=Request::all();

            $cms_update = Cmspage::where('id','=',$id)->update([
                                'page_name' => $cmsUpdate['page_name'],
                                'page_title' => $cmsUpdate['page_title'],
                                'meta_title' => $cmsUpdate['meta_title'],
                                'updated_at'=>$datetime,
                                'description_eng' => htmlentities($cmsUpdate['description_eng']),
                                'description_arabic' => htmlentities($cmsUpdate['description_arabic']),
                                //'slug'  => $cmsUpdate['page_name'],
                                'meta_description' => $cmsUpdate['meta_description'],
                                'meta_keyword'  => $cmsUpdate['meta_keyword']
                         ]);

            Session::flash('success', 'Content updated successfully'); 
            return redirect('admin/cms');
    }  
	
	
	public function change_cmspage_status()
	{
		$cmsUpdate	= Request::all();
		/*echo "<pre>";
		print_r($cmsUpdate);exit;*/
		Cmspage::where('id',$cmsUpdate['cms_page_id'])
				->update(array(
							   'status'	=> $cmsUpdate['status']
						   )
					   );
		echo 1;
		
	}
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response	7278876384
     */
}
