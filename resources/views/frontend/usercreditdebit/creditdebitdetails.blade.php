@extends('../layout/frontend_template')
@section('content')

<div class="comn-main-wrap-inr">

<div class="main-heading">
    <h2>Statement</h2>
</div>
<?php
if($walletdetailscount>0){?>
<div class="custom-card statement-table-card">
<div class="table-responsive">
<table class="table comn-table">
    <thead class="thead-light">
      <tr>
         <th>Item</th>
         <th>Date</th>
         <th>Transaction Type</th>
         <th>Credit/Debit</th>
         <th>Status</th>
      </tr>
    </thead>
    <tbody>

      <?php
      foreach ($walletdetails as $key => $value) {
       
      
      ?>

      <tr>

      <td>

      <div class="item-data d-flex align-items-center">


     <div class="cd-status"><?php if($value->type==1) { echo "<span class='credit'><i class='fas fa-plus-circle'></i></span>"; } else if($value->type==0) { echo "<span class='debit'><i class='fas fa-minus-circle'></i></span>"; } ?></div>

     <div class="text">
     <!--  <div class="text1">Aliquam vestibulum</div>
      <div class="text2">Oder ID 1234569834</div> -->
      <div class="text1"><?php echo $value->itemdetails;?></div>
     </div>
      </div>


     </td>

     <td><?php echo $value->created_at;?></td>
    
        
      
        <td><?php echo $value->purpose;?> </td>

       

        <td><?php echo number_format ($value->total,2);?> <?php echo $value->currencycode;?></td>

        <td>
          <?php 
          if($value->walletstatus==1)
          {?>
              <a href="javascript:;" class="uncomn-tooltip approved-info-btn" data-toggle="tooltip" data-placement="top" title="Payout Date <?php echo $value->updated_at;?>">
              Approved <i class="fas fa-info-circle"></i>
              </a>
            <?php 
          } 
          else if($value->walletstatus==0)
          {?>
             <a href="javascript:;" class="uncomn-tooltip pending-info-btn" data-toggle="tooltip" data-placement="top" title="Created Date <?php echo $value->created_at;?>">
              Pending <i class="fas fa-info-circle"></i>
              </a>

        <?php 
          } ?>
        </td>


        
       
        
      </tr>
      

      <?php }?>

    </tbody>
  </table>
</div><!--end table-responsive-->

<div class="comn-pagi-sec">{!! $walletdetails->render() !!}</div>

</div>
<?php }else {?>
No record found
<?php }?>
</div>


 @stop