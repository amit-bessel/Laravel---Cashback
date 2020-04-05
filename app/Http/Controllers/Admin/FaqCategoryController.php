<?php namespace App\Http\Controllers\Admin; /* path of this controller*/

use App\Model\Faq; /* Model name*/
use App\Model\FaqCategory; /* Model name*/
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

class FaqCategoryController extends BaseController {

   /**
    * Display a listing of the resource.
    *
    * @return Response
    */
    public function __construct() 
    {
        parent::__construct();
        view()->share('faq_class','active');
    }
   public function index()
   {
        $limit = 10;
		$faqcategory = DB::table('faqcategories')->orderBy('id','DESC')->paginate($limit);
        //echo '<pre>';print_r($vitamins); exit;
	    $faqcategory->setPath('faqcategory');
        return view('admin.faqcategory.index',compact('faqcategory'),array('title'=>'Faq Category Management','module_head'=>'Faq Category Listing'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $FaqCategory = FaqCategory::all();

        return view('admin.faqcategory.create',array('title'=>'Faq Category Management','module_head'=>'Add Faq Category','FaqCategory'=>$FaqCategory));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $faq=Request::all();
        $faqcat = Request::input('faqcat');
        $datetime= Customhelpers::Returndatetime();
        
        //print_r($faq); exit;
        //Faq::create($faq);
        DB::table('faqcategories')->insert(
            ['name' => $faqcat, 'created_at' => $datetime,'updated_at' => $datetime]
        );
        Session::flash('success', 'FAQ added successfully'); 
        return redirect('admin/faqcategory');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
       $faq=Faq::find($id);
       return view('admin.faq.show',compact('faq'));
    }

    /* update faq categories */

    public function edit($id)
    {
        $faq=FaqCategory::find($id);
        return view('admin.faqcategory.edit',compact('faq'),array('title'=>'Edit Faq Category','module_head'=>'Edit Faq Category'));
    }

   
    public function update(Request $request, $id)
    {
        //
           
           $faqUpdate=Request::all();
           $faqcategory=FaqCategory::find($id);
           $datetime= Customhelpers::Returndatetime();
           $faqUpdate['updated_at']=$datetime;
           $faqcategory->update($faqUpdate);
           Session::flash('success', 'FAQ CATEGORY updated successfully'); 
           return redirect('admin/faqcategory');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response	7278876384
     */
    public function destroy($id)
    {
        //
        FaqCategory::find($id)->delete();
        Session::flash('success', 'FAQ CATEGORY deleted successfully'); 
        return redirect('admin/faqcategory');
    }
    
    /* FOR FRONT END  FAQ SHOWING */
	
	public function faqList()
    {
        //ehco "ee"; exit;
       $allfaq =  DB::table('faqs')->get();
       return view('frontend.faq.faq',compact('allfaq'),array('title'=>'Miramix FAQ','module_head'=>'Miramix FAQ'));
    }


    
}
