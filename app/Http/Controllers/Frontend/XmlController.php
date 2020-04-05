<?php namespace App\Http\Controllers\Frontend; /* path of this controller*/
// Define Model
use App\Model\Product; /* Model name*/
use App\Model\Productnew; /* Model name*/

use App\Http\Requests;
use App\Http\Controllers\Controller;    
use Illuminate\Support\Facades\Request;
use Mail;
use Input; /* For input */
use Validator;
use Session;
use Imagine\Image\Box;
use Image\Image\ImageInterface;
use Illuminate\Pagination\Paginator;
use App\Model\UtilityCuretedFeature;
use DB;
use Hash;
use Auth;
use Cookie;
use App\Helper\helpers;
use Redirect;


class XmlController extends BaseController {

   public function __construct() 
   {
      parent::__construct();
          
      $obj = new helpers();
      view()->share('obj',$obj);
   }
   /**
    * Display a listing of the resource.
    *
    * @return Response
    */

  public function generateProdcutXmlReport()
  {

    $xmlString = '<?xml version="1.0" encoding="UTF-8"?>
    <urlset xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" 
        xmlns:image="http://www.google.com/schemas/sitemap-image/1.1" 
        xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd" 
        xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

    $yesterday_date = date('Y-m-d',strtotime("-1 days")).' 00:00:00';
    //exit;

    $product_xml  = Product::with('product_category')->where('updated_at','>',$yesterday_date)->get();
    if(count($product_xml)>0){
      $product_xml = $product_xml->toArray();
    }
   /* echo "<pre>";
    print_r($product_xml);
    exit;*/
    foreach($product_xml as $v)
    {
        $details_url = url().'/product_details/'.base64_encode($v["id"]).'/'.$v["name"];
        $details_url = htmlspecialchars($details_url);
        $details     = unserialize($v['description']);

        if(!empty($details['long'])){
          //$dtl = implode(",",$details['long']);
            $dtl = $details['long'];
        }else{
            $dtl = $details['short'];
        }

        $xmlString .= '<url>';
        $xmlString .= '<Product_Name>'.htmlspecialchars($v["name"]).'</Product_Name>';
        $xmlString .= '<Vendor_Name>'.htmlspecialchars($v["advertiser-name"]).'</Vendor_Name>';
        $xmlString .= '<Category>'.htmlspecialchars($v["product_category"]["name"]).'</Category>';
        $xmlString .= '<Currency>'.htmlspecialchars($v["currency"]).'</Currency>';
        $xmlString .= '<Price>'.htmlspecialchars($v["price"]).'</Price>';
        $xmlString .= '<Description>'.htmlspecialchars($dtl).'</Description>';
        $xmlString .= '<Image_Url>'.htmlspecialchars($v["image_url"]).'</Image_Url>';
        $xmlString .= '<Detials_Url>'.$details_url.'</Detials_Url>';
        $xmlString .= '</url>';
        //break;
    }

    $xmlString .= '</urlset>';

    $dom = new \DOMDocument;
    $dom->preserveWhiteSpace = FALSE;
    $dom->loadXML($xmlString);
    $dom->save('sitemap.xml');
  }
   
}