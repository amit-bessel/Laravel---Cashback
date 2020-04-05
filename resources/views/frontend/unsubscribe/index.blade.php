@extends('../layout/template')
@section('content')


<?php
$useremail=$_REQUEST['useremail'];
$type=$_REQUEST['type'];
$action='user/unsubscribe?useremail='.$useremail.'&type='.$type;
?>
{!! Form::open(['url' => $action,'method'=>'POST', 'files'=>true,'class'=>'row-fluid','id'=>'unsubscribeuser']) !!}
@if(Session::has('failure_message'))
                  <p class="alert {{ Session::get('alert-class', 'alert-danger') }}">{{ Session::get('failure_message') }}</p>
                @endif
                <p id="login_error_msg"></p>
                @if(Session::has('success_message'))
                  <p class="alert {{ Session::get('alert-class', 'alert-success') }}">{{ Session::get('success_message') }}</p>
                @endif


  <input type="hidden" name="useremail" value="<?php echo $useremail;?>">
  <input type="hidden" name="type" value="<?php echo $type;?>">             

<!-- <h1 style="text-align: center;">Please give what is reason for Unsubscribe?  </h1>
<div style="margin-top: 100px; width: 50%; margin: 0 auto; margin-bottom: 10px;">
<div class="form-group">
<label>Message</label>
<textarea name="message" rows="6" cols="30" class="form-control" id="message"></textarea>
<span class="text-danger">{{ $errors->first('message') }}</span>
</div>

<div class="submitbtn-group">
<input type="submit" class="btn btn-primary pull-left" value="Unsubscribe" name="unsubscribe">                      
</div>  
</div> -->


<div class="sign-form-back">

<div class="sign-form-container signIn-form-container">
<div class="sign-form-wrap d-flex align-items-center signIn-form-wrap">

<div class="sign-form-left d-flex align-items-center align-self-stretch">

<div class="sign-form-left-content">
<img src="<?php echo url(); ?>/public/frontend/images/logo_2.png" alt="logo">

  <div class="copyright-text">
    Copyright © 2018<br>
    <span class="blue-text">Checkout Saver.</span>
  </div>

</div><!--end sign-form-left-content-->
</div><!--end sign-form-left-->

<div class="sign-form-content-wrap d-flex align-items-center align-self-stretch">

  <a href="#" class="sign-form-close"><i class="la la-times-circle-o"></i></a>

  <div class="sign-form-content">

<div class="heading">
  <h2>Please give what is reason for Unsubscribe?</h2>
</div>

<div class="sign-form forgot-sign-form">

<div class="form-group">
<textarea name="message" rows="6" cols="30" class="form-input" id="message"></textarea>
<span class="bar"></span>
<span class="text-danger">{{ $errors->first('message') }}</span>
<label class="input-label">Message</label>
</div>



<div class="btn-holder d-flex align-items-center justify-content-between">
<input type="submit" class="btn btn-ylw" value="Unsubscribe" name="unsubscribe">
</div>

</div><!--end sign-form-->




  </div><!--end sign-form-content-->

</div><!--end sign-form-content-->





</div>
</div><!--end sign-form-container-->

</div>




{!! Form::close() !!}

@stop