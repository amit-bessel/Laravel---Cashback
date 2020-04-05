<?php namespace App\Http\Controllers\Admin;

use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;

use App\Model\Country; /* Model name*/
use App\Model\Category; /* Model name*/
use App\Model\Sitesetting; /* Model name*/
use App\Model\BusinessList; /* Model name*/
use App\Model\Testimonial; /* Model name*/
use App\Model\Topbanner; /* Model name*/
use App\Model\Brand; /* Model name*/
use App\Model\Product;
use App\Model\BrandCategory;
use App\Helper\CropAvatar;
use App\Model\Giftcard; /* Model name*/
use App\Model\Giftcarddetail; /* Model name*/
use App\Model\Giftcardimage; /* Model name*/

use App\Book;
use App\User;
use App\Http\Requests;
use App\Helper\helpers;
use Yajra\Datatables\Datatables;
use App\Http\Controllers\Admin\BaseController;
use Illuminate\Support\Facades\Request;

use Input; /* For input */
use Validator;
use Session;
use DB;
use Mail;
use Hash;
use Auth;
use Cache;
use Cookie;
use Image;
class GiftcardController extends BaseController {

	public function __construct() {
    	parent::__construct();
		
    }

    public function getList()
	{
		//echo 'ok';exit;
    	$module_head 		= "Gift Card Management";
        $gift_class 		= "active";
        $record_per_page 	= 10;
        $search_key 		= Request::query('search_key');
        $title				= "Gift Card Management";

        $giftcard = Giftcard::with('giftcardimages')->get();

        /*echo "<pre>";
        print_r($giftcard->toArray());
        exit();*/

        return view('admin.gift.card_list',compact(
														'module_head',
														'search_key',
														'active',
														'gift_class',
														'record_per_page',
														'title',
														'giftcard'
													)
					);


    }

    public function ajaxGiftcard(Request $request){
    	$data  = Request::all();
    	/*print_r($data);
    	exit();*/
    	$search_key =  Request::query('search_key');
    	$where = "";

    	if($search_key != ''){
                $where .= "(`brandname` LIKE '%".$search_key."%')";
         }
         	$columns = array(

                0 =>'brandname' ,
                1 =>'image',
                2 =>'displaystatus'
                
            );
         	if($where != '')
             	$allgiftcard = Giftcard::with('giftcardimages')->whereRaw($where)->orderBy($columns[$data['order'][0]['column']],$data['order'][0]['dir'])->get();
         	else
         		$allgiftcard = Giftcard::with('giftcardimages')->orderBy($columns[$data['order'][0]['column']],$data['order'][0]['dir'])->get();

         	/*print_r($allgiftcard);
    	exit();
*/
            return Datatables::of($allgiftcard)
             ->editColumn('status', function ($allgiftcard) {
	            if($allgiftcard->displaystatus == 1){
	                $status_html = '<label class="switch"><input type= "checkbox"  name="superaffiliate[]" id="superaffiliate'.$allgiftcard->id.'" class="superaffiliate" value="'.$allgiftcard->id.'" onclick="userJs.changegiftcardstatus('.$allgiftcard->id.')" checked="checked"/><span class="slider round"></span></label><div class="alert-success" id="giftcard_status_span_'.$allgiftcard->id.'" style="display:none;"></div>';
	            }else{
	                $status_html = '<label class="switch"><input type= "checkbox"  name="superaffiliate[]" id="superaffiliate'.$allgiftcard->id.'" class="superaffiliate" value="'.$allgiftcard->id.'" onclick="userJs.changegiftcardstatus('.$allgiftcard->id.')"/><span class="slider round"></span></label><div class="alert-success" id="giftcard_status_span_'.$allgiftcard->id.'" style="display:none;"></div>';

	            }
	            return $status_html;
            })
	        /*->editColumn('action', function ($allgiftcard) {
                return '';
            })*/
            ->editColumn('brandname', function ($allgiftcard) {
                $fullname = $allgiftcard->brandname;
                return $fullname;

            })
            ->editColumn('image', function ($allgiftcard) {
               return('<img src="'.$allgiftcard->giftcardimages[0]->imageurl.'">');
            })
            ->make(true);
    }

    public function ajaxGiftcardStatus(Request $request){

       
        $data=Request::all();

        $cardid=$data['cardid'];

        $giftcarddetails=Giftcard::find($cardid);

        if($giftcarddetails->displaystatus=='0'){

            $giftcarddetails->displaystatus='1';
            $giftcarddetails->save();
            echo "1";//enabled
            
        }
        else{
            $giftcarddetails->displaystatus='0';
            $giftcarddetails->save();
            echo "2";//disabled
        }

        
        exit();
    }

}
