@extends('../layout/template')
@section('content')

<body>
<!-- fb login -->
 <script type="text/javascript">
  
  window.fbAsyncInit = function() {
    // FB JavaScript SDK configuration and setup
    FB.init({
      appId      : '<?php echo $fbappid;?>', // FB App ID
      status     : true, // check login status
      cookie     : true,  // enable cookies to allow the server to access the session
      xfbml      : true,  // parse social plugins on this page
      version    : 'v2.8' // use graph api version 2.8
    });
    
    // Check whether the user already logged in
    FB.getLoginStatus(function(response) {
        if (response.status === 'connected') {
            //display user data
            getFbUserData();
        }
    });
};

// Load the JavaScript SDK asynchronously
(function(d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) return;
    js = d.createElement(s); js.id = id;
    js.src = "//connect.facebook.net/en_US/sdk.js";
    fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));

// Facebook login with JavaScript SDK
function fbLogin() {

    FB.login(function (response) {
        if (response.authResponse) {
            // Get and display the user profile data
            getFbUserData();
        } else {
            document.getElementById('status').innerHTML = 'User cancelled login or did not fully authorize.';
        }
    }, {scope: 'email'});
}

// Fetch the user profile data from facebook
function getFbUserData(){
    return false;
    FB.api('/me', {locale: 'en_US', fields: 'id,first_name,last_name,email,link,gender,locale,picture'},
    function (response) {
       
       $.ajax({
        type  : 'POST',
        url   : '<?php echo url(); ?>/fblogin',
        data  : {fb_id: response.id,first_name:response.first_name,last_name:response.last_name,email:response.email},
        async : false,
        success : function(response){

            if(response == 1)
            {
               window.location.href = '<?php echo url();?>/user/my-profile';
            }
            if(response ==2)
              {
                // alert("You have already registered.");
              }
          }
        });
       
       
       
    });
}

// Logout from facebook
function fbLogout() {
    FB.logout(function() {
        document.getElementById('fbLink').setAttribute("onclick","fbLogin()");
        document.getElementById('fbLink').innerHTML = 'fb login';
        document.getElementById('userData').innerHTML = '';
        document.getElementById('status').innerHTML = 'You have successfully logout from Facebook.';
    });
}


/*==========FB Login=========*/
 //  function fbAsyncInit() {
 //  FB.init({
 //   appId      : '276125126250737',
 //   status     : true, // check login status
 //   cookie     : true, // enable cookies to allow the server to access the session
 //   xfbml      : true  // parse XFBML
 //  });
 // }

 function facebookLogin() {
    FB.login(
         function(response) {
    if (response.status== 'connected') {
     FB.api('/me?fields=name,email', function(response) {
         console.log(response);
          // console.log('Good to see you, ' + response.email + '.');
          $('.cs-loader').removeClass('hideloader');
          response._token='{!! csrf_token() !!}';
          $.post( '<?php echo url(); ?>/fblogin',response , function(response) {

          var redirect_url='<?php echo url();?>/user/my-profile';
          $('.cs-loader').addClass('hideloader');
          if (response==1) {
            window.location.href = redirect_url;
          }else if(response==0){
            $("#login_error_msg").html('<p class="alert alert-class alert-danger" >Your Status is inactive.</p>');
            $(window).scrollTop(0);
          }else if(response==2){
              $("#login_error_msg").html('<div class="alert alert-danger" >Some error occured.</div>');
          }
          else if(response==3){
              $("#login_error_msg").html('<div class="alert alert-danger" >Your account is deleted.</div>');
          }else{
            $("#login_error_msg").html('<div class="alert alert-danger" >Facebook Email Required</div>');
          }

   //window.location.href=response;
   //console.log("Response: "+response);
   });  
     /*
      $('#userInfo').html(response.name + ' ' + response.location.name);
        });
 
        FB.api("/me/picture?width=200&redirect=0&type=normal&height=200", function (response) {
           if (response && !response.error) {
       
             console.log('PIC ::', response);
             
           }*/
        });
  
    }
   }
   ,{
 scope: "email,public_profile,user_location"
     }
  );
 }
 fbAsyncInit();
 
 
  function fblogOut() {
  FB.logout(function(response) {
   console.log('logout :: ', response);
   
  });
 }
</script> 
<!-- google login --> 
<link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet" type="text/css">
<script src="https://apis.google.com/js/api:client.js"></script>

<script>
  var googleUser = {};
  var startApp = function() {
    gapi.load('auth2', function(){
      // Retrieve the singleton for the GoogleAuth library and set up the client.
      auth2 = gapi.auth2.init({
        client_id: '<?php echo $googleclient_id;?>', 
        cookiepolicy: '<?php echo $googlecookiepolicy;?>',
        // Request scopes in addition to 'profile' and 'email'
        //scope: 'additional_scope'
      });
      attachSignin(document.getElementById('customBtn'));
    });
  };

  function attachSignin(element) {
    console.log(element.id);
    auth2.attachClickHandler(element, {},
        function(googleUser) {
          document.getElementById('name').innerText = "Signed in: " +
              googleUser.getBasicProfile().getName();


      $.post("<?php echo url(); ?>/account/google",{_token:'{!! csrf_token() !!}',name:googleUser.getBasicProfile().getName(),email:googleUser.getBasicProfile().getEmail(),id:googleUser.getBasicProfile().getId()} , function(response) {
      var redirect_url='<?php echo url();?>/user/my-profile';
          $('.cs-loader').addClass('hideloader');
          if (response==1) {
            window.location.href = redirect_url;
          }else if(response==0){
            $("#login_error_msg").html('<p class="alert alert-class alert-danger" >Your Status is inactive.</p>');
            $(window).scrollTop(0);
          }else if(response==2){
              $("#login_error_msg").html('<div class="alert alert-danger" >Some error occured.</div>');
          }
          else if(response==3){
              $("#login_error_msg").html('<div class="alert alert-danger" >Your account is deleted.</div>');
          }else{
            $("#login_error_msg").html('<div class="alert alert-danger" >Facebook Email Required</div>');
          }
      //console.log("Response: "+response);
      });






        }, function(error) {
          //alert(JSON.stringify(error, undefined, 2));
        });
  }
  </script>

{!! Form::open(['url' => 'user/signup-user','method'=>'POST', 'files'=>true,'id'=>'register_user']) !!}
<input type="hidden" name="hid_user_id" id="hid_user_id" value="">
<input type="hidden" name="editprofileurl" value="<?php echo url(); ?>/check-email-availability" id="editprofileurl">
<input type="hidden" name="csrftoken" value="{{csrf_token()}}" id="csrftoken">

<div class="sign-form-back">

<div class="sign-form-container">
<div class="sign-form-wrap d-sm-flex align-items-sm-center ">

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
    <?php
  if($siteusercount>0){

    ?>
  <div class="sign-form-user-info">
  <div class="sign-form-user-img">
    <?php if($siteuserdetails[0]->profileimage!=''){?>
    <img src="<?php echo url('')."/public/backend/profileimage/".$siteuserdetails[0]->profileimage; ?>">
    <?php
  }else {?>
  <img src="<?php echo url(); ?>/public/frontend/images/demo-prfl-img.jpg">
  <?php }?>
  </div>
  <h3>

    $ <?php
    if($siteuserdetails[0]->superaffiliateuser==1){?> 5 <?php } else {?>2.5 
    <?php }?> From <?php echo $siteuserdetails[0]->firstname;?>  <?php echo $siteuserdetails[0]->lastname;?>
      

    </h3>
  <h4>Create an account & claim your credit</h4>
  </div>
  <?php
 }
 ?>

<div class="heading">
  <?php
  if($siteusercount==0){?>
  <h3>Welcome to Checkout Saver</h3>
  <h2>Sign UP</h2>
  <?php } ?>
  @if(Session::has('failure_message'))
  <p class="errormsg"> {{ Session::get('failure_message') }}</p>
  @endif
  @if(Session::has('success_message'))
  <p class="succmsg">{{ Session::get('success_message') }}</p>
  @endif
</div>

<div class="sign-form">

<div class="form-group">
<input type="text" class="form-input" name="name" id="first_name">
<span class="highlight"></span>
<span class="bar"></span>
<label class="input-label">First Name</label>
</div>

<div class="form-group">
<input type="text" class="form-input" name="last_name" id="last_name">
<span class="highlight"></span>
<span class="bar"></span>
<label class="input-label">Last Name</label>
</div>

<div class="form-group">
<input type="email" class="form-input" name="email" id="email">
<span class="bar"></span>
<label class="input-label">Email Address</label>

<span id="email_msg" style="display: none"></span>

</div>

<div class="form-group">
<input type="password" class="form-input" name="password" id="password">
<span class="bar"></span>
<label class="input-label">Password</label>
</div>

<div class="form-group">
<input type="password" class="form-input" name="cpassword" id="cpassword">
<span class="bar"></span>
<label class="input-label">Confirm Password</label>
</div>

<div class="form-group" style="display: <?php if($refereduser == 1)  echo 'none'; else echo 'block';?> ">
<input type="text" class="form-input" name="refercode" id="refercode">
<span class="bar"></span>
<label class="input-label">Referral Code <small>(Optional)</small></label>
</div>

<div class="btn-holder d-flex align-items-center justify-content-between">

       <div class="custom-checkbox">
        <input type="checkbox" name="rcv_Nws" id="rcvNws" value="1" checked="checked">
        <label for="rcvNws">I wish to receive Newsletter</label>
       </div>

       <button type="submit" class="btn btn-ylw">Sign Up <i class="la la-arrow-right"></i></button>

</div>

</div><!--end sign-form-->




{!! Form::close() !!}





<div class="or-divider-sec">
  <span>Or</span>
</div>

<div class="other-signin-sec">
<h4>sign in with</h4>

<div class="btn-holder">
  <a href="javascript:void(0)" class="btn fb-withSignin" onClick="facebookLogin()"><i class="fab fa-facebook-f"></i> facebook</a>
   <!-- <a href="#" class="btn gl-withSignin"><i class="fab fa-google-plus-g"></i> google plus</a> -->

<!--  <a class="fb-bg text-white ptb-10 plr-20 mr-10 pull-left" href="javascript:void(0)" onClick="facebookLogin()" style="color: red !important"><b><i class="fa fa-facebook" aria-hidden="true"></i>  Login </b></a> -->
    
    <div id="customBtn" class="customGPlusSignIn">

      <span class="buttonText btn gl-withSignin"><i class="fas fa-envelope"></i> Gmail</span>
    </div>

  <div id="name"></div>
  <script>startApp();</script>
    
    

</div>

<div class="other-signin-info-text">Already have an account? <a href="<?php echo url();?>/login">Sign In</a></div>
<div class="terms-note">By registering, you are agreeing to all <a href="<?php echo url();?>/t&c">Terms and Conditions</a> and the <a href="<?php echo url();?>/privacy-policy">Privacy Policy.</a></div>

</div><!--end other-signin-sec-->

  </div><!--end sign-form-content-->

</div><!--end sign-form-content-->





</div>
</div><!--end sign-form-container-->

</div>



   

<script>
    function check_duplication_email($id)
    {
      var email = $('#email').val();
      if(email!="")
      {
        $.ajax({
        type  : 'POST',
        url : '<?php echo url(); ?>/check-email-availability',
        data  : {email: email, id: $id , _token: "{{ csrf_token() }}",},
        async : false,
        success : function(response){
            if (response==1) {
              $('#email').val('');
              $('#email_msg').show();
              $('#email_msg').html('Email already exists.').css( "color", "red" );
              
            }
            else{
               $('#email_msg').hide();
               $('#email_msg').text('');
               
            }
          
          }
        });
      }
    }
  </script>

  <style type="text/css">
  #customBtn{display: inline-block;}

  </style>
   @stop