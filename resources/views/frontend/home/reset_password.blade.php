@extends('../layout/template')
@section('content')
<body>
<form name="frm_reset_pasword" id="frm_reset_pasword" method="POST" action=""> 
  <input type="hidden" name="_token" value="{{csrf_token()}}"/>  


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

 <!--  <a href="#" class="sign-form-close"><i class="la la-times-circle-o"></i></a> -->

  <div class="sign-form-content">

<div class="heading">
  <h2>Reset User Password</h2>
  @if(Session::has('failure_message'))
  <p class="errormsg"> {{ Session::get('failure_message') }}</p>
  @endif
  @if(Session::has('success_message'))
  <p class="succmsg">{{ Session::get('success_message') }}</p>
  @endif
</div>

<div class="sign-form forgot-sign-form">

<div class="form-group">
<input type="password" class="form-input" name="password" id="password">
<span class="bar"></span>
<label class="input-label">Password</label>
</div>


<div class="form-group">
<input type="password" class="form-input" name="retype_password" id="retype_password">
<span class="bar"></span>
<label class="input-label">Confirm Password</label>
</div>



<div class="btn-holder d-flex align-items-center justify-content-between">



<button type="submit" class="btn btn-signform">Submit <i class="la la-arrow-right"></i></button>
<div class="other-signin-info-text">Already have an account? <a href="<?php echo url();?>/login">Sign In</a></div>

</div>

</div><!--end sign-form-->




  </div><!--end sign-form-content-->

</div><!--end sign-form-content-->





</div>
</div><!--end sign-form-container-->

</div>

</form>
  @stop