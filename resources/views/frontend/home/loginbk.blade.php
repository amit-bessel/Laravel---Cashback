@extends('../layout/frontend_template')
@section('content')





    
<script>
window.fbAsyncInit = function() {
    // FB JavaScript SDK configuration and setup
    FB.init({
      appId      : '276125126250737', // FB App ID
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



/*=========Google Sign Up==================*/  


  // function onSignIn(googleUser) {
  //     alert("hh11"); 
  //   var profile = googleUser.getBasicProfile();
  //   console.log('ID: ' + profile.getId()); // Do not send to your backend! Use an ID token instead.
  //   console.log('Name: ' + profile.getName());
  //   console.log('Image URL: ' + profile.getImageUrl());
  //   console.log('Email: ' + profile.getEmail()); // This is null if the 'email' scope is not present.


  //   $.post("<?php //echo url(); ?>/account/google",{_token:'{!! csrf_token() !!}',name:googleUser.getBasicProfile().getName(),email:googleUser.getBasicProfile().getEmail(),id:googleUser.getBasicProfile().getId()} , function(response) {
  //     var redirect_url='<?php //echo url();?>/user/my-profile';
  //         $('.cs-loader').addClass('hideloader');
  //         if (response==1) {
  //           window.location.href = redirect_url;
  //         }else if(response==0){
  //           $("#login_error_msg").html('<p class="alert alert-class alert-danger" >Your Status is inactive.</p>');
  //           $(window).scrollTop(0);
  //         }else if(response==2){
  //             $("#login_error_msg").html('<div class="alert alert-danger" >Some error occured.</div>');
  //         }
  //         else if(response==3){
  //             $("#login_error_msg").html('<div class="alert alert-danger" >Your account is deleted.</div>');
  //         }else{
  //           $("#login_error_msg").html('<div class="alert alert-danger" >Facebook Email Required</div>');
  //         }
  //     //console.log("Response: "+response);
  //     });




  // }




</script>

  <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet" type="text/css">
  <script src="https://apis.google.com/js/api:client.js"></script>
  <script>
  var googleUser = {};
  var startApp = function() {
    gapi.load('auth2', function(){
      // Retrieve the singleton for the GoogleAuth library and set up the client.
      auth2 = gapi.auth2.init({
        client_id: '878133581836-1gscieefq8a0a0dp2ag7ton3pa0nigg4.apps.googleusercontent.com', 
        cookiepolicy: 'single_host_origin',
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


   <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.19.0/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment-timezone/0.5.13/moment-timezone-with-data.js"></script> 
    <script>
      $(document).ready(function(){
         var timezone = moment.tz.guess();
         var time = $('#timezone').val(timezone);
      
      })
     
    </script>
 <!-- maincontent -->
    <section class="maincontent">
        <div class="container">
            <hr class="special-divider">
            <!-- common-headerblock -->
            <div class="common-headerblock text-center">
                <h4 class="text-uppercase">user login</h4>
                
            </div>
            <!--\\ common-headerblock -->
            <div class="col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3 formblock" style="border: 2px solid #ccc; padding: 10px; margin:10px;">
              <div class="sub-formblock clearfix">
                @if(Session::has('failure_message'))
                  <p class="alert {{ Session::get('alert-class', 'alert-danger') }}">{{ Session::get('failure_message') }}</p>
                @endif
                <p id="login_error_msg"></p>
                @if(Session::has('success_message'))
                  <p class="alert {{ Session::get('alert-class', 'alert-success') }}">{{ Session::get('success_message') }}</p>
                @endif
                
                {!! Form::open(['url' => 'signin-user','method'=>'POST', 'files'=>true,'class'=>'row-fluid','id'=>'signin_user']) !!}
                <input type="hidden" name="buy_url" id="buy_url" value="{{$buy_url}}" />
                <input type="hidden" name="userbuy" id="userbuy" value="" />
                <input type="hidden" name="timezone" id="timezone">
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
              <a class="fb-bg text-white ptb-10 plr-20 mr-10 pull-left" href="javascript:void(0)" onClick="facebookLogin()" style="color: red !important"><b><i class="fa fa-facebook" aria-hidden="true"></i>  Login </b></a>

              <a href="<?php echo url(); ?>/signup/<?php echo $buy_url; ?>" class="btn-link forgot-link register-link pull-left">Register</a>
             
              <br/>

              

              <div id="gSignInWrapper">
              <span class="label">Sign in with:</span>
              <div id="customBtn" class="customGPlusSignIn">
              <span class="icon"></span>
              <span class="buttonText">Google</span>
              </div>
              </div>
              <div id="name"></div>
              <script>startApp();</script>

              <fb:login-button scope="public_profile,email" onlogin="checkLoginState();">fblogin
              </fb:login-button>

             <!--  <a href="javascript:void(0);" onclick="fbLogin()" id="fbLink">fb login</a> -->

              <!-- Display user profile data -->
              <div id="userData"></div>
               
               
              </div>  
            </div>
        </div>
    </section>
    
    <!-- maincontent -->

<style type="text/css">
    #customBtn {
      display: inline-block;
      background: white;
      color: #444;
      width: 190px;
      border-radius: 5px;
      border: thin solid #888;
      box-shadow: 1px 1px 1px grey;
      white-space: nowrap;
    }
    #customBtn:hover {
      cursor: pointer;
    }
    span.label {
      font-family: serif;
      font-weight: normal;
    }
    span.icon {
      background: url('/identity/sign-in/g-normal.png') transparent 5px 50% no-repeat;
      display: inline-block;
      vertical-align: middle;
      width: 42px;
      height: 42px;
    }
    span.buttonText {
      display: inline-block;
      vertical-align: middle;
      padding-left: 42px;
      padding-right: 42px;
      font-size: 14px;
      font-weight: bold;
      /* Use the Roboto font that is loaded in the <head> */
      font-family: 'Roboto', sans-serif;
    }
  </style>
@stop

