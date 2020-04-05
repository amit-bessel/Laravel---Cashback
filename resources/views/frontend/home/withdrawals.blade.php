<?php
$str = '';
$str .= '<input type="hidden" id="total_no_of_orders1" name="total_no_of_orders1" value="'.$no_of_withdrawals.'" />
      <table class="table table-striped">
        <thead>
          <tr>
            <th>withdraw details</th>
            <th>date of withdraw</th>
            <th>cash withdrawn</th>
            <th>withdraw status</th>
          </tr>
        </thead>
        <tbody>';

        if(!empty($withdrawals_info->toArray())){
          //echo '<pre>';print_r($order_info->toArray());
            
            foreach($withdrawals_info as $key=>$order_dtlas){

                $status  = $order_dtlas->payment_status=='paid'?'success':'pending';
                $product_image = (($order_dtlas->image_url !='')?$order_dtlas->image_url: url().'/public/uploads/no-image.png');

                $str .= '<tr>
                          <td class="cart-description                            
                          <div class="cart-img wallet-img pull-left" style="background:url('.url().'/public/frontend/images/small-wallet.png) no-repeat center center/contain;"></div>
                            <h4>Blue Kiruna Slim-Fit Piqué Blazer</h4>
                          </td>
                          <td>'.date("d-m-Y",strtotime($order_dtlas->created_at)).'</td>
                          <td>$'.$order_dtlas->amount.'</td>
                          <td>'.$status.'</td>
                        </tr>';
            }
       }else{
          $str .= "<tr><td colspan='4'><li class='noprodFound'><span class='icon-202381'><span class='path1'></span><span class='path2'></span><span class='path3'></span><span class='path4'></span><span class='path5'></span><span class='path6'></span><span class='path7'></span><span class='path8'></span><span class='path9'></span><span class='path10'></span><span class='path11'></span><span class='path12'></span><span class='path13'></span><span class='path14'></span><span class='path15'></span><span class='path16'></span><span class='path17'></span></span><h4>No order found.</h4></li></td></tr>";
       }
     
     
  $str .='</tbody>
        </table>';
echo $str;