@extends('../layout/frontend_template')
@section('content')


<div class="comn-main-wrap-inr">
<div class="custom-card sucs-msg-card">
<div class="custom-card-body">
<div class="buysucs-msg-content d-flex align-items-center justify-content-center">	
<div>
	<div class="buysucs-msg-icon"><img src="<?php echo url('');?>/public/frontend/images/icons/sucs-check.svg"></div>



<h2 class="buysucs-msg-text-1">Your Order is Successful</h2>

<?php
if(!empty($userprofileheadinfo)){
	

?>	
<h3 class="buysucs-msg-text-2">Your Wallet Balance : <span><?php echo "$".number_format($userprofileheadinfo->wallettotalamount,2);?></span></h3>

<?php
}
?>
<h3 class="buysucs-msg-text-2">Your Transaction Id : <span><?php echo $externalRefID;?></span></h3>
<h3 class="buysucs-msg-text-2">Your Reference Order Id : <span><?php echo $referenceOrderID; ?></span></h3>
</div>
</div>
</div>
</div>
</div>
@stop