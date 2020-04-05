<?php
namespace App\Http\Controllers\Frontend;


use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Request;
use Input;              /* For input */
use Validator;
use Session;
use Imagine\Image\Box;
use Image\Image\ImageInterface;

use DB;
use Hash;
use Mail;
use App\Model\FaqCategory; /* Model name*/
use Customhelpers;

class FaqController extends BaseController {
    
    public function __construct() 
    {
	   parent::__construct();
	  
    }
    
    public function faqList()
    {
        //ehco "ee"; exit;
       //$allfaq =  DB::table('faqs')->get();
       $allfaq=FaqCategory::with('faqs')->get();
       /****************Get user details ******************************/

         $userprofileheadinfo=Customhelpers::getUserDetails();
       return view('frontend.faq.faq',compact('allfaq'),array('title'=>'FAQ','module_head'=>'FAQ','userprofileheadinfo'=>$userprofileheadinfo));
    }
    
}
?>