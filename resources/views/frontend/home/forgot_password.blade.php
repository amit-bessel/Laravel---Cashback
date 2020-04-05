@extends('../layout/template')
@section('content')
<body>
{!! Form::open(['url' => 'forgot-password','method'=>'POST', 'files'=>true,'class'=>'row-fluid','id'=>'forgot_password']) !!}



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

  <a href="<?php echo url();?>" class="sign-form-close"><i class="la la-times-circle-o"></i></a>

  <div class="sign-form-content">

<div class="heading">
  <h2>Forgot Password</h2>
  @if(Session::has('failure_message'))
  <p class="errormsg"> {{ Session::get('failure_message') }}</p>
  @endif
  @if(Session::has('success_message'))
  <p class="succmsg">{{ Session::get('success_message') }}</p>
  @endif
</div>

<div class="sign-form forgot-sign-form">

<div class="form-group">
<input type="email" class="form-input" name="email" id="email">
<span class="bar"></span>
<label class="input-label">Email Address</label>
</div>



<div class="btn-holder d-flex align-items-center justify-content-between">



<button type="submit" class="btn btn-ylw">Submit <i class="la la-arrow-right"></i></button>
<div class="other-signin-info-text">Already have an account? <a href="<?php echo url();?>/login">Sign In</a></div>

</div>

</div><!--end sign-form-->




  </div><!--end sign-form-content-->

</div><!--end sign-form-content-->





</div>
</div><!--end sign-form-container-->

</div>

{!! Form::close() !!}
  @stop

