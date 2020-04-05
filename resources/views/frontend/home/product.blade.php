<input type="hidden" id="total_no_of_products" name="total_no_of_products" value="{{$no_of_product}}" />
<input type="hidden" id="max_price_range" name="max_price_range" value="{{$max_price}}" />
<input type="hidden" id="fix_max_price" name="fix_max_price" value="{{$fix_max_price}}" />
<input type="hidden" id="minprice" name="minprice" value="{{$minprice}}" />
<?php
$str = "<ul>";
        if(!empty($products->toArray())){

            foreach($products as $product_dtlas){

                // Create Slug
                $new_created_slug = preg_replace('/[^\da-z]/i', '-', strtolower($product_dtlas->name));
                $new_created_slug=trim(preg_replace('/-+/', '-', $new_created_slug), '-');

                //cashback amount
                $cashback_amount = ($product_dtlas->price*$product_dtlas->percentage)/100;
                if($product_dtlas->percentage){
                  $cashback_div = "<p class='cashback-price green-text'>Cashback Price:$".number_format($cashback_amount,2)."</p>";
                }else{
                  $cashback_div = "";
                }
                
                $product_image = (($product_dtlas->image_url !='')?$product_dtlas->image_url: url().'/public/uploads/no-image.png');
                /*<div class='img-product' style='background: url(".$product_dtlas->image_url.") no-repeat center center/contain'>*/

                $str .= "<li title='".$product_dtlas->name."'><a href='".url()."/product_details/".base64_encode($product_dtlas->id)."/".$new_created_slug."'>";
                if($product_dtlas->percentage!=''){
                    $str .= "<div class=\"main-discount\">".$product_dtlas->percentage."%</div>";
                }
                $str .= "<div class='img-product'><img src='".$product_image."' />
                </div>
                <div class='prod-info listingpriceblock'>
                    <h6>".$product_dtlas->name."</h6>
                    <p class='price-block'>$".number_format($product_dtlas->price,2)."</p>
                    ".$cashback_div."
                </div>
                </a></li>";
            }
       }
       else{
          $str .= "<li class='noprodFound'><span class='icon-202381'><span class='path1'></span><span class='path2'></span><span class='path3'></span><span class='path4'></span><span class='path5'></span><span class='path6'></span><span class='path7'></span><span class='path8'></span><span class='path9'></span><span class='path10'></span><span class='path11'></span><span class='path12'></span><span class='path13'></span><span class='path14'></span><span class='path15'></span><span class='path16'></span><span class='path17'></span></span><h4>No product found.</h4></li>";
       }
     $str .= "</ul>";
     echo $str;