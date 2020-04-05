@extends('../layout/frontend_template')
@section('content')
<div class="comn-main-wrap">
<div class="form-horizontal">   
	
<?php

if(!empty($SiteUser)){


?>
<h3 style="text-align: center; color: blue">Wallet balance : <?php echo $SiteUser->wallettotalamount;?>$</h3>



<?php
}?>
<?php
$str="<a href='".url('')."/user/my-profile'>Click this link to set paypal id</a>"; 
?>
<?php if($SiteUser->paypalid==''){?>
<h3 style="text-align: center; color: blue"><?php echo $str;?></h3>
<?php } ?>
<h3 style="text-align: center; color: blue">Withdraw cashback balance</h3>
<h3 style="text-align: center; color: blue"><a href="<?php echo url();?>/user/auto-purchase-giftcard">Auto payment</a></h3>
<div style="margin-top: 100px; width: 50%; margin: 0 auto;">
	@if(Session::has('failure_message'))
                  <p class="alert {{ Session::get('alert-class', 'alert-danger') }}">{{ Session::get('failure_message') }}</p>
                @endif
                <p id="login_error_msg"></p>
                @if(Session::has('success_message'))
                  <p class="alert {{ Session::get('alert-class', 'alert-success') }}">{{ Session::get('success_message') }}</p>
                @endif
{!! Form::open(['url' => 'user/withdrawcashbackamount','method'=>'POST', 'files'=>true,'class'=>'row-fluid','id'=>'withdrawcashbackamount']) !!}
                
                
                  <div class="form-group">
                    <label>Withdrawl Amount</label>
                    <input type="text" class="form-control" placeholder="10" name="amount" id="amount" autocomplete="off">  
                  </div>
                  
                  <div class="submitbtn-group">
                     <input type="submit" class="btn btn-primary pull-left" value="Withdraw amount" name="submit">                      
                  </div>  
               {!! Form::close() !!}
           </div><br/><br/><br/>

 </div>
	</div>
	
@stop
    
    