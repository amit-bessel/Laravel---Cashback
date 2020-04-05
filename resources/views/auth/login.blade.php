@extends('admin.layout.layout')

@section('content')
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.19.0/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment-timezone/0.5.13/moment-timezone-with-data.js"></script> 
    <script>
      $(document).ready(function(){
         var timezone = moment.tz.guess();
         var time = $('#timezone').val(timezone);
         $(".alert-danger").delay(5000).fadeOut("slow");
      
      })
     
    </script>
  
<?php

/**********Get google recaptcha code from site settings***************/

if(!empty($data['googlerecaptchainfo'][0]->value)){ 
   $googlerecaptchacode=$data['googlerecaptchainfo'][0]->value;
}
else{
  $googlerecaptchacode="";
}


?>
  <div class="tab"><div class="tab_cell"><div class="container-fluid">
    <div class="row">
      <a class="btn btn-navbar" data-toggle="collapse" data-target=".navbar-inverse-collapse">
        <i class="icon-reorder shaded"></i></a>
        <!-- <a class="brand admin-logo" href="<?php //echo url();?>/admin/home"><img src="<?php //echo url();?>/public/backend/images/logo.png" alt="logo"></a> -->
      <div class="col-sm-8 col-md-6 col-sm-offset-2 col-md-offset-3">
        <div class="panel panel-default">
          <div class="panel-heading"><i class="fa fa-lock"></i>Login</div>
          <div class="panel-body">

            @include('admin.partials.errors')
           <!-- For For Got password Success mail -->
            @if(Session::has('success'))
              <div class="alert alert-success">
                  <button type="button" class="close" data-dismiss="alert">×</button>
                  <strong>{!! Session::get('success') !!}</strong>
              </div>
            @endif

              @if(Session::has('error'))
                <div class="alert alert-error">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    <strong>{!! Session::get('error') !!}</strong>
                </div>
              @endif

            @if(Session::has('failure_message'))
            <p class="alert {{ Session::get('alert-class', 'alert-danger') }}">{{ Session::get('failure_message') }}</p>
            @endif


            <form class="form-horizontal" role="form" method="POST" action="{{ url('/auth/login') }}" onsubmit="return get_action();">
              <input type="hidden" name="_token" value="{{ csrf_token() }}">
              <input type="hidden" name="timezone" id="timezone">
              <div class="form-group">
                <label class="col-md-4 control-label">E-Mail Address</label>
                <div class="col-md-7">
                  <input type="email" class="form-control" name="email"
                         value="" autofocus>
                </div>
              </div>

              <div class="form-group">
                <label class="col-md-4 control-label">Password</label>
                <div class="col-md-7">
                  <input type="password" class="form-control" name="password" value="">
                </div>
              </div>
				
			  <div class="form-group">
			    <label class="col-md-4 control-label"></label>
                <div class="col-md-7">
                  <script src="https://www.google.com/recaptcha/api.js" async defer></script>

                <!-- <script src='https://www.google.com/recaptcha/api.js'></script> <div class="g-recaptcha" data-sitekey="6Ld_JhAUAAAAAOyP6il7BeMgB66OWhd0ZFG1bky1" style="width:100%;"></div>  <div class="g-recaptcha" data-sitekey="6LchvikTAAAAAGf2K49752cDNBHg55Ed0foXEAX8" style="width:100%;"></div>  -->
					     <!-- <div class="g-recaptcha" data-sitekey="6LcHkSMUAAAAAA7hUpGijaGXEMSA4Agsv8ROpzUW" style="width:100%;"></div>  -->

               <div class="g-recaptcha" data-sitekey="<?php echo $googlerecaptchacode;?>"></div>
               
                </div>
              </div>

              <div class="form-group">
              <label class="col-md-4 control-label"></label>
                <div class="col-md-7">
                  <input type="checkbox" name="remember_me" value="1" >&nbsp;&nbsp;<label class="control-label">Remember Me</label>
                </div>
              </div>
				
              <div class="form-group">
                <div class="col-md-7 col-md-offset-4">
                  <div class="checkbox">
                    <!-- <label>
                      <input type="checkbox" name="remember"> Remember Me
                    </label> -->
                    <label>
                      <a href="<?php echo url().'/admin/forgotpassword'?>"> Forgot Password ? </a>
                    </label>
                  </div>
                </div>
              </div>

              <div class="form-group">
                <div class="col-md-6 col-md-offset-4">
                  <button type="submit" class="btn btn-blue">Login</button>
                </div>
              </div>
              <span id="captcha" style="color:red" />
            </form>
          </div>
        </div>
      </div>
    </div>
  </div></div></div>
<!-- <script type="text/javascript">
      var onloadCallback = function() { 
        grecaptcha.render('html_element', {
          'sitekey' : '6Ld1f0MUAAAAAOesp8FWS35UYqHh0wVW1MThs2eT'    
        });
      };
    </script>
 
    <form action="?" method="POST">
      <div id="html_element">ssss</div>
      <br>
      <input type="submit" value="Submit">
    </form>
    <script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit"
        async defer>
    </script> -->   

  
    
  

<style>
  .panel{
    box-shadow: 0 1px 1px rgb(34, 195, 225)
  }
</style>

<!-- <script type="text/javascript">
      
function get_action(form) 
{
  
    var v = grecaptcha.getResponse();
    if(v.length == 0)
    {
      
        document.getElementById('captcha').innerHTML="You can't leave Captcha Code empty";
        return false;
    }
    else
    {
      
        document.getElementById('captcha').innerHTML="Captcha completed";
        return true; 
    }
}
    </script>   -->
@endsection
