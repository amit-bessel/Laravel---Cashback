@extends('../layout/frontend_template')
@section('content')
    

<script>
window.fbAsyncInit = function() {
    // FB JavaScript SDK configuration and setup
    FB.init({
      appId      : '192140794605793', // FB App ID
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
  function fbAsyncInit() {
  FB.init({
   appId      : '945452675588407',
   status     : true, // check login status
   cookie     : true, // enable cookies to allow the server to access the session
   xfbml      : true  // parse XFBML
  });
 }

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
          }else if(response==2){
            $("#login_error_msg").html('<div class="alert alert-danger" >Your Status is inactive.</div>');
          }else if(response==0){
                  $("#login_error_msg").html('<div class="alert alert-danger" >Some error occured.</div>');
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
    
 <!-- maincontent -->
    <section class="maincontent">
        <div class="container">
            <hr class="special-divider">
            <!-- common-headerblock -->
            <div class="common-headerblock text-center">
                <h4 class="text-uppercase">user login</h4>
                <p>Nulla at velit eget nulla accumsan interdum</p>
            </div>
            <!--\\ common-headerblock -->
            <div class="col-sm-6 col-sm-offset-3 formblock">
              <div class="sub-formblock clearfix">
              @if(Session::has('failure_message'))
                  <p class="alert {{ Session::get('alert-class', 'alert-danger') }}">{{ Session::get('failure_message') }}</p>
                @endif
      
                @if(Session::has('success_message'))
                  <p class="alert {{ Session::get('alert-class', 'alert-success') }}">{{ Session::get('success_message') }}</p>
                @endif
                <h6>Registered customer</h6>
                <p class="mb25">Nam et condimentum mi. Vivamus magna odio, maximus in congue sed venenatis.</p>
                {!! Form::open(['url' => 'signin-user','method'=>'POST', 'files'=>true,'class'=>'row-fluid','id'=>'signin_user']) !!}
                  <div class="form-group">
                    <label>Email Address</label>
                    <input type="email" class="form-control" placeholder="eg., xxx@companyname.com" name="email" id="email" autocomplete="off">  
                  </div>
                  <div class="form-group">
                  <?php
                    if(isset($id) && $id != ""){
                      ?>
                      <input type="hidden" name="id" value="<?php echo $id; ?>">
                      <?php
                    }
                  ?>
                    <label>Password</label>
                    <a href="<?php echo url(); ?>/forgot-password" class="btn-link forgot-link pull-right">Forgot Password?</a>
                    <input type="password" placeholder="&#x25cf;&nbsp;&#x25cf;&nbsp;&#x25cf;&nbsp;&#x25cf;&nbsp;&#x25cf;" class="form-control" name="password" id="password" autocomplete="off">  
                  </div>
                  <div class="submitbtn-group">
                     <input type="submit" class="btn btn-primary pull-left" value="login" name="login">                      
                  </div>  
               {!! Form::close() !!}
               
               <div id="status"></div>

<!-- Facebook login or logout button -->
<a class="fb-bg text-white ptb-10 plr-20 mr-10" href="javascript:void(0)" onClick="facebookLogin()"><b><i class="fa fa-facebook" aria-hidden="true"></i>  Login </b></a>
<!-- <br/>
<a href="javascript:void(0);" onclick="fbLogin()" id="fbLink">fb login</a> -->

<!-- Display user profile data -->
<div id="userData"></div>
               
               
              </div>  
            </div>
        </div>
    </section>
    
    <!-- maincontent -->


@stop

