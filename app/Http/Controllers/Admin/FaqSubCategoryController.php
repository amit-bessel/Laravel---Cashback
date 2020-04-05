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

class FaqSubCategoryController extends BaseController {

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
		//$faq = DB::table('faqs')->orderBy('id','DESC')->paginate($limit);

        $faqsubcategory = FaqCategory::with('faqrelation2')->orderBy('id','DESC')->paginate($limit);  

        
	    $faqsubcategory->setPath('faq');
        //echo '<pre>';print_r($faqsubcategory); exit;
        return view('admin.faqsubcategory.index',compact('faqsubcategory'),array('title'=>'FAQ Sub Category Management','module_head'=>'Faq Sub Category'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $FaqCategory = FaqCategory::where("parentid",'0')->get(); // only faq categories 
        

        return view('admin.faqsubcategory.create',array('title'=>'FAQ Sub Category Management','module_head'=>'Add FAQ Sub Category','FaqCategory'=>$FaqCategory));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $datetime= Customhelpers::Returndatetime();
        $faq=Request::all();
        $subcategory = Request::input('subcategory');
        $faqcatid = Request::input('faqcatid');
        
        $updated_at=$datetime;
        $created_at=$datetime;
        


        $FaqCategory = FaqCategory::create(['parentid'=>$faqcatid,'name' => $subcategory,'updated_at' => $updated_at,'created_at' => $created_at]);

        

        Session::flash('success', 'FAQ Subcategory added successfully'); 
        return redirect('admin/faq');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    /*
    public function show($id)
    {
       $faq=Faq::find($id);
       return view('admin.faq.show',compact('faq'));
    }*/

    /* Update the faq  */
    
    public function edit($id)
    {
        $faq=Faq::find($id);
        $FaqCategory = FaqCategory::all();
        return view('admin.faq.edit',compact('faq'),array('title'=>'Edit FAQ','module_head'=>'Edit FAQ','FaqCategory'=>$FaqCategory));
    }

   
    public function update(Request $request, $id)
    {
        //
           $faqUpdate=Request::all();
           $faq=Faq::find($id);
           $faqUpdate['answer']=htmlentities($faqUpdate['answer']);
           $datetime= Customhelpers::Returndatetime();
           $faqUpdate['updated_at']=$datetime;
           $faq->update($faqUpdate);
           Session::flash('success', 'FAQ updated successfully'); 
           return redirect('admin/faq');
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
        Faq::find($id)->delete();
        Session::flash('success', 'FAQ deleted successfully'); 
        return redirect('admin/faq');
    }
    
    /* FOR FRONT END  FAQ SHOWING */
	
	public function faqList()
    {
        //ehco "ee"; exit;
       $allfaq =  DB::table('faqs')->get();
       return view('frontend.faq.faq',compact('allfaq'),array('title'=>'Miramix FAQ','module_head'=>'Miramix FAQ'));
    }

    /********************************************************************************
     *                               FAQ VIEW DETAILS                                  *
     *******************************************************************************/
    
    public function show($id='')
    {



        $faq_class             = "active";
        $module_head            = "View FAQ Details";
        $faq_id                = $id;
        $faq_details           = Faq::with('faqCategory')->where('id', '=', $id)->first();

        if(!$faq_details){
            Session::flash('failure_message', 'FAQ not found.'); 
            Session::flash('alert-class', 'alert alert-danger'); 
            return redirect('admin/faq');
            exit;
        }
        $title  = "View FAQ Details";
        return view('admin.faq.view_faq_details',compact(
            'module_head',
            'faq_id',
            'faq_class',
            'faq_details',
            'title'
            ));
    }
}
