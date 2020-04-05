<?php
$str = '';
$str .= '<input type="hidden" id="total_no_of_orders" name="total_no_of_orders" value="'.$no_of_order.'" />
      <table class="table table-striped">
        <thead>
          <tr>
            <th>Product details</th>
            <th>date of purchase</th>
            <th>Product price</th>
            <th>cashback earned</th>
          </tr>
        </thead>
        <tbody>';

        if(!empty($order_info->toArray())){
          //echo '<pre>';print_r($order_info->toArray());
            
            foreach($order_info as $key=>$order_dtlas){

                $product_image = (($order_dtlas->image_url !='')?$order_dtlas->image_url: url().'/public/uploads/no-image.png');

                $str .= '<tr>
                          <td class="cart-description">
                            <div class="cart-img pull-left" style="background:url('.$product_image.') no-repeat center center/contain;"></div>
                            <h4>'.$order_dtlas->product_name.'</h4>
                          </td>
                          <td>'.date("d-m-Y",strtotime($order_dtlas->transaction_date)).'</td>
                          <td>$'.$order_dtlas->sale_amount.'</td>
                          <td>$'.$order_dtlas->cashback_amount.'</td>
                        </tr>';
            }
       }else{
          $str .= "<tr><td colspan='4'><li class='noprodFound'><span class='icon-202381'><span class='path1'></span><span class='path2'></span><span class='path3'></span><span class='path4'></span><span class='path5'></span><span class='path6'></span><span class='path7'></span><span class='path8'></span><span class='path9'></span><span class='path10'></span><span class='path11'></span><span class='path12'></span><span class='path13'></span><span class='path14'></span><span class='path15'></span><span class='path16'></span><span class='path17'></span></span><h4>No order found.</h4></li></td></tr>";
       }
     
     
  $str .='</tbody>
        </table>';
echo $str;