<?php namespace App\Http\Controllers\Admin;

use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;



use App\Model\User; /* Model name*/
use App\Model\Vendorcategories; /* Model name*/
use App\Model\Vendordetails; /* Model name*/
use Customhelpers;


use App\Book;

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
use Cookie;
use Yajra\Datatables\Datatables;

require_once('vendor/Stripe/init.php');

use Stripe\Stripe;
use Stripe\Charge;
use Stripe\Customer;
use Stripe\Stripe_CardError;
use Stripe\Stripe_InvalidRequestError;

class VendorsController extends BaseController {

	/********************************************************************************
     *                              GET VENDOR LIST                                   *
     *******************************************************************************/

    function getList()
    {
    	$vendor=Vendordetails::with('vendorcategories')->get();

    	

       $module_head        = "Vendors List";
        $user_class         = "active";
        $search_key         = Request::query('search_key');
        $active             = Request::query('active');
        $title              = "Vendors Management";
        return view('admin.allvendor.index',compact('user_class',
                                                        'module_head','search_key','search_email','search_contact',
                                                        'active','title'
                                                    )
                    );
    }


    /********************************************************************************
     *                          AJAX DATATABLE LISTING VENDORS                                *
     *******************************************************************************/

    function ajaxVendorsList(){

        //===get post data 
        $data       = Request::all();

        $search_key         =  Request::query('search_key');
        $active             =  Request::query('active');
        $where = "";

        

        //  if($search_key != ''){
        //         $where          .= "(`firstname` LIKE '%".$search_key."%' OR CONCAT(firstname,' ',lastname) LIKE '%".$search_key."%' OR `lastname` LIKE '%".$search_key."%' OR `email` LIKE '%".$search_key."%' OR `phoneno` LIKE '%".$search_key."%') AND ";
        //  }
        // if($active != ''){
        //     $where         .= "`status`= '".$active."' AND ";
        // }

        $where         .= 'is_deleted=0';
        


        //$all_patients =  SiteUser::select(['id', 'firstname','lastname','email','phoneno','status','profileimage'])->whereRaw($where)->orderBy('id', 'DESC');

        //add columns
       $columns = array(

                0=>'id' ,
                1 =>'advertisername',
                2 =>'advertiserid', 
                3 =>'catname',
                4=>'linkname',
                5=>'salecommission_orderby',
                6=>'api',
                7=>'popularvendor'
               
            );

       //add order by 

       if($search_key!=''){


	       	$catcount=Vendorcategories::where("name",'like', $search_key."%")->where('status',1)->count();
			if($catcount>0){

				$catdetails=Vendorcategories::where("name",'like',$search_key."%")->where('status',1)->get();
				foreach ($catdetails as $key => $value) {
				$vendorcatid=$value->id;
				}

				$whereraw=("( advertisername like '$search_key%' or vendorcategories_id ='$vendorcatid') "); 
			}
			else{
				$whereraw=("( advertisername like '$search_key%') ");
			}

			$all_vendors = Vendordetails::with('vendorcategories')->whereRaw($where)->whereRaw($whereraw)->orderBy($columns[$data['order'][0]['column']],$data['order'][0]['dir']);

       }
       else{

       	$all_vendors = Vendordetails::with('vendorcategories')->whereRaw($where)->orderBy($columns[$data['order'][0]['column']],$data['order'][0]['dir']);
       }

      
        return Datatables::of($all_vendors)
        ->addColumn('checkbox_td', function ($all_vendors) {
                return '<input type="checkbox"  recordType="multipleRecord" multipleRecord="'.$all_vendors->id.'" />';
            })
        
        ->addColumn('action', function ($all_vendors) {
                return '<a href="javascript:void(0);" onclick="userJs.vendorremove('.$all_vendors->id.')"><i class="fa fa-trash-o" aria-hidden="true"></i></a>&nbsp;<a href="'.url().'/admin/vendor/edit/'.$all_vendors->id.'"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>';
            })
         ->editColumn('catname', function ($all_vendors) {
                $catname = $all_vendors->vendorcategories->name;
                return $catname;

            })
         ->editColumn('advertisername', function ($all_vendors) {
                $advertisername = "<a href='".$all_vendors->clickurl."' target='_blank'>".$all_vendors->advertisername."</a>";
                return $advertisername;

            })
         ->editColumn('popularvendor', function ($all_vendors) {
            if($all_vendors->popularvendor == 1){
                $status_html = '<select style="width:94%;" name="status" id="popular_'.$all_vendors->id.'" onchange="userJs.changeVendorPopularStatus(this.value,'.$all_vendors->id.')"><option value="1" selected>Yes</option><option value="0">No</option></select><br /><span class="alert-success" id="success_status_span_'.$all_vendors->id.'" style="display:none;"></span>';
            }else{
                $status_html = '<select style="width:94%;" name="status" id="popular_'.$all_vendors->id.'" onchange="userJs.changeVendorPopularStatus(this.value,'.$all_vendors->id.')"><option value="1" >Yes</option><option value="0" selected>No</option></select><br /><span class="alert-success" id="success_status_span_'.$all_vendors->id.'" style="display:none;"></span>';

            }
            return $status_html;
            })
        ->make(true);
    }

    /********************************************************************************
	 *							CHANGE POPULAR VENDOR STATUS										*
	 *******************************************************************************/
	function getPopularStatus(){
		
		$post_data  = Request::all();
        $status 	= $post_data['this_val'];
        $id 		= $post_data['this_id'];
        $count=Vendordetails::where("popularvendor",1)->count();
        if($count==''){
        	$count=0;
        }
        if($count<10){
	        $user 	= Vendordetails::find($id);
	        $user->popularvendor = $status;
	        $user->save();
	        echo "1";
	        exit();
        }
        else{

        	if($status==0){
        	$user 	= Vendordetails::find($id);
	        $user->popularvendor = $status;
	        $user->save();
	        echo "1";
	        exit();
        	}
        	
        	echo "0";
        	exit();
        }
     
		
	
	}


    /********************************************************************************
     *                              REMOVE VENDOR                                     *
     *******************************************************************************/
    public function getRemove($id)
    {

        $vendor_details = Vendordetails::find($id);
        $vendor_details->is_deleted=1; //1:deleted 0:exist
        $vendor_details->save();
        /*if(!empty($user_details['profileimage'])){
            @unlink('backend/profileimage/'.$user_details['profileimage']);
        }*/
       // $user_details->delete();
       
        Session::flash('success_message', 'Vendor has been removed successfully.'); 
        Session::flash('alert-class', 'alert alert-success'); 
        return redirect('admin/vendor/list');
        
    }


    /********************************************************************************
     *                              VENDOR COMMISSION RATE EDIT                                  *
     *******************************************************************************/

    public function getEdit($id){

        $vendor=Vendordetails::find($id);

        $vendor_id                = $id;
        return view('admin.allvendor.edit',compact('vendor','vendor_id'),array('title'=>'Edit Vendor','module_head'=>'Edit Vendor'));

    }

    function postEdit($id='')
    {


        /* call custom helper */
        
        $datetime= Customhelpers::Returndatetime();
        $destinationPath           = 'public/uploads/brand/logo';
        $data               = Request::all();
        
        $vendor_id            = $id;

         $vendor_details                = Vendordetails::where("id",$vendor_id)->first();

         if(!empty($data['logo_image']))
            {
                $image = Input::file('logo_image');
                
                $filename  = time() . '.' . $image->getClientOriginalExtension();
                
                $thumb_image_path = public_path('uploads/brand/logo/' . $filename);
                
                // Image::make($image->getRealPath())->resize(203, 184,
                // function ($constraint) {
                //     $constraint->aspectRatio();
                // })
                // ->resizeCanvas(203, 184)
                // ->save($thumb_image_path);


                // Image::make($image->getRealPath())->save($thumb_image_path);
                
                
                Input::file('logo_image')->move($destinationPath, $filename);
                
                /********************* REMOVE OLD FILES *************************/
                
                if(file_exists("public/uploads/brand/logo/".$vendor_details->logo))
                {
                    @unlink("public/uploads/brand/logo/".$vendor_details->logo); //delete old image
                }
                
            }

            $update_vendor = Vendordetails::find($vendor_id);
            if(!empty($data['logo_image']))
            {
                $update_vendor->logo   = $filename;
            }
            $update_vendor->salecommission   = trim($data['salecommission']);
             $update_vendor->updated_at     = $datetime;
       /* $update_vendor        = Vendordetails::where('id', $vendor_id)
                                    ->update([
                                        'salecommission'          => trim($data['salecommission']),
                                       
                                        'updated_at'=>$datetime,
                                    ]);*/
        $update_vendor->save();

        Session::flash('success_message', 'Vendor details has been updated successfully.'); 
        Session::flash('alert-class', 'alert alert-success'); 
        return redirect('admin/vendor/list');
    }
}