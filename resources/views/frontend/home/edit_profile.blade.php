@extends('../layout/frontend_template')
@section('content')

<script type="text/javascript">
  
$(function() {
        $( "#dob" ).datepicker({
            dateFormat : 'yy-mm-dd',
            changeMonth : true,
            changeYear : true,
            yearRange: '-100y:c+nn',
            maxDate: '-1d'
        });
    });

</script>
<style>
      /* Always set the map height explicitly to define the size of the div
       * element that contains the map. */
      #map {
        height: 100%;
      }
      /* Optional: Makes the sample page fill the window. */
      html, body {
        height: 100%;
        margin: 0;
        padding: 0;
      }
    </style>
    <link type="text/css" rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500">

    <style>
      #locationField, #controls {
        position: relative;
        width: 480px;
      }
      #autocomplete {
        position: absolute;
        top: 0px;
        left: 0px;
        width: 99%;
      }
      .label {
        text-align: right;
        font-weight: bold;
        width: 100px;
        color: #303030;
      }
      #address {
        border: 1px solid #000090;
        background-color: #f0f0ff;
        width: 480px;
        padding-right: 2px;
      }
      #address td {
        font-size: 10pt;
      }
      .field {
        width: 99%;
      }
      .slimField {
        width: 80px;
      }
      .wideField {
        width: 200px;
      }
      #locationField {
        height: 20px;
        margin-bottom: 20px;
      }
      #street_number{ margin-bottom: 10px; }
    </style>

 <!-- maincontent -->
  <div class="comn-main-wrap">
    <section class="maincontent">
        <div class="container">
            <hr class="special-divider">
            <!-- common-headerblock -->
            <div class="common-headerblock text-center">
                <h4 class="text-uppercase">Edit your profile</h4>
            </div>
            <!--\\ common-headerblock -->

            <div class="row mt20">
             <div class="col-sm-4 col-md-3">
                  <button type="button" class="toggle-sidebar for-mob" data-toggle="collapse" data-target="#user-menu">Toggle User Menu<span class="total-bars"><span class="bar"></span><span class="bar"></span><span class="bar"></span></span></button>
                  <div class="user-menu" id="user-menu">
                  <div class="user-submenublock">  
                      <h5> <a href="{{ url('user/change-password') }}" style="color:black !important;">Change Password</a></h5>
                    </div>
                    <div class="user-submenublock">
                      <h5>My Profile</h5>
                      <ul>
                        <li><a href="{{ url('user/my-profile') }}">personal information</a></li>
                        <li><a href="">bank a/c details</a></li>
                      </ul>
                    </div>
                    <div class="user-submenublock">  
                      <h5>Passbook</h5>
                      <ul>
                        <li><a href="{{ url('user/transactions') }}">Transactions</a></li>
                        <li><a href="{{ url('user/cashback') }}">cashback balance</a></li>
                      </ul>
                    </div> 
                    <div class="user-submenublock">  
                      <h5> <a href="{{ url('my-wishlist') }}" style="color:black !important;">My Wishlist</a></h5>
                    </div> 
                  </div>
              </div>
            <div class="col-sm-8 col-md-9 right-userpanel">
              <div class="user-formpanel">
                <div class="alert alert-danger" id="email_error" style="display: none">
                    <span id="email_msg"></span>
                </div>
                @if(Session::has('failure_message'))
                  <p class="alert {{ Session::get('alert-class', 'alert-danger') }}">{{ Session::get('failure_message') }}</p>
                @endif
      
                @if(Session::has('success_message'))
                  <p class="alert {{ Session::get('alert-class', 'alert-success') }}">{{ Session::get('success_message') }}</p>
                @endif
               {!! Form::open(['url' => 'user/edit-profile','method'=>'POST', 'files'=>true,'class'=>'','id'=>'editprofile']) !!}
                  <div class="form-group">
                    <div class="row">
                      
                      <div class="col-sm-5">
                         <label>First Name</label> 
                         <input type="text" class="form-control" placeholder="eg., John" name="name" id="first_name" value="<?php echo $is_user_exists->firstname; ?>">
                      </div>
                      <div class="col-sm-5">
                         <label>Last Name</label> 
                         <input type="text" class="form-control" placeholder="eg., Doe" name="last_name" id="last_name" value="<?php echo $is_user_exists->lastname; ?>">
                      </div>
                    </div>  
                  </div>
                  <div class="form-group">
                    <div class="row">
                        <div class="col-sm-6">
                            <label>Email Address</label>
                            <input type="email" class="form-control" placeholder="eg., xxx@companyname.com" name="email" id="email" onblur="check_duplication_email('<?php echo $is_user_exists->id; ?>');" value="<?php echo $is_user_exists->email; ?>">  
                        </div>
                        <div class="col-sm-6">
                            <label>Phone Number</label>
                            <input type="text" class="form-control" placeholder="Enter your contact no." name="phone" id="phone"
                            value="<?php echo $is_user_exists->phoneno; ?>">
                        </div>
                    </div>                    
                  </div>
                  


                   <div class="control-group">
              <label class="control-label" for="basicinput">Address<span class="required" style="color: red">*</span></label>

                <div class="controls" style="margin-left: 0px !important">
                    
           <div id="locationField" class="control-group" style="margin: 0 auto; text-align: center; margin-top: 10px;">
            <input id="autocomplete" placeholder="Enter your address"
             onFocus="geolocate()" type="text" class="span8" style="margin: 0 auto; text-align: center;" name="address" value="<?php echo $is_user_exists->address;?>">
          </div> 
                </div>
            </div> 

           



            <div class="control-group" style="display: none;">
                <label class="control-label" for="basicinput">Street address <span class="required" style="color: red">*</span></label>

                <div class="controls">
                     <input class="span8" id="street_number"
              >
                </div>

                <div class="controls wideField">
                   <input class="field" id="route"
              name="address1" value="<?php echo $is_user_exists->address1;?>">
                </div>
            </div>

            




            <div class="form-group" style="margin-top:20px; ">
                    <div class="row">
                      
                      <div class="col-sm-5">
                         <label>City</label> 
                         <input type="text" class="form-control"  name="city" id="locality" value="<?php echo $is_user_exists->city;?>">
                      </div>
                      <div class="col-sm-5">
                         <label>State</label> 
                         <input type="text" class="form-control"  name="state" id="administrative_area_level_1" value="<?php echo $is_user_exists->state;?>">
                      </div>
                    </div>  
                  </div>


                     <div class="form-group" style="margin-top:10px; ">
                    <div class="row">
                      
                      <div class="col-sm-5">
                         <label>Zip code</label> 
                         <input type="text" class="form-control"  name="zipcode" id="postal_code" value="<?php echo $is_user_exists->zipcode;?>">
                      </div>
                      <div class="col-sm-5">
                         <label>Country</label> 
                         <input type="text" class="form-control"  name="country" id="country" value="<?php echo $is_user_exists->country;?>">
                      </div>
                    </div>  
                  </div>
                  <div class="form-group" style="margin-top:10px; ">
                    <div class="row">
                      
                      <div class="col-sm-5">
                         <label>Date of birth</label> 
                         <input type="text" class="form-control"  name="dob" id="dob" value="<?php echo $is_user_exists->dob;?>" readonly="readonly">
                
                      </div>
                      <div class="col-sm-5">
                         <label>Profile Image</label> 
                          {!! Form::file('profileimage');!!}
                          <?php
                          if($is_user_exists->profileimage!=''){?>
                          <img src="<?php echo url('')."/public/backend/profileimage/".$is_user_exists->profileimage; ?>" style="height: 60px; width: 60px;">
                          <?php }?>
                      </div>

                      
                    </div>  
                  </div>


                     <div class="form-group" style="margin-top:10px; ">
                    <div class="row">
                      
                      <div class="col-sm-5">
                         <label>Paypalid</label> 
                         <input type="text" class="form-control"  name="paypalid" id="paypalid" value="<?php echo $is_user_exists->paypalid;?>">
                
                      </div>
                      
                      
                    </div>  
                  </div>


                  <div class="form-group">
                              <div class="row">
                                  <!-- <div class="col-sm-6">
                                      <label>Paypal id</label>
                                      <input type="text" class="form-control" placeholder="Paypal id" name="" value="<?php //echo $is_user_exists->paypalid; ?>" disabled>
                                  </div> -->

                                 <div class="col-sm-6">
                                      <label>Refer id</label>
                                  
                                      <?php
                                      if(!empty($SiteUserReferId)){
                                        $count=0;
                                        foreach ($SiteUserReferId as $key => $value) {
                                          $count++;
                                          if($value->superaffiliate_status==0){


                                          ?>
                                          <input type="text" name="refercode" value="<?php echo $value->referid;?>" class="form-control">
                                         
                                          <?php
                                         }
                                         else if($value->superaffiliate_status==1){
                                          ?>
                                           <input type="text" name="refercode" value="<?php echo $value->referid;?>" class="form-control">
                                           
                                          <?php
                                         }
                                        }
                                      }
                                      if($count==0){
                                        echo "None";
                                      }
                                      ?>
                                  </div>
                                  
                                  
                              </div>
                          </div>



                  
                  <div class="submitbtn-group">
                     <input type="submit" class="btn btn-primary pull-left" name="submit" value="submit">                      
                  </div>  
               {!! Form::close() !!}
              </div>  
            </div>
             </div>
        </div>
    </section>
  </div>
    <!-- maincontent -->


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
              $('#email_error').show();
              $('#email_msg').html('Email already exists.').css( "color", "red" );
              
            }
            else{
               $('#email_error').hide();
               $('#email_msg').text('');
               
            }
          
          }
        });
      }
    }
  </script>


   <script>
      // This example displays an address form, using the autocomplete feature
      // of the Google Places API to help users fill in the information.

      // This example requires the Places library. Include the libraries=places
      // parameter when you first load the API. For example:
      // <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&libraries=places">

      var placeSearch, autocomplete;
      var componentForm = {
        street_number: 'short_name',
        route: 'long_name',
        locality: 'long_name',
        administrative_area_level_1: 'short_name',
        country: 'long_name',
        postal_code: 'short_name'
      };

      function initAutocomplete() {
        // Create the autocomplete object, restricting the search to geographical
        // location types.
        autocomplete = new google.maps.places.Autocomplete(
            /** @type {!HTMLInputElement} */(document.getElementById('autocomplete')),
            {types: ['geocode']});

        // When the user selects an address from the dropdown, populate the address
        // fields in the form.
        autocomplete.addListener('place_changed', fillInAddress);
      }

      function fillInAddress() {
        // Get the place details from the autocomplete object.
        var place = autocomplete.getPlace();

        for (var component in componentForm) {
          document.getElementById(component).value = '';
          document.getElementById(component).disabled = false;
        }

        // Get each component of the address from the place details
        // and fill the corresponding field on the form.
        for (var i = 0; i < place.address_components.length; i++) {
          var addressType = place.address_components[i].types[0];
          if (componentForm[addressType]) {
            var val = place.address_components[i][componentForm[addressType]];
            document.getElementById(addressType).value = val;
          }
        }
      }

      // Bias the autocomplete object to the user's geographical location,
      // as supplied by the browser's 'navigator.geolocation' object.
      function geolocate() {
        if (navigator.geolocation) {
          navigator.geolocation.getCurrentPosition(function(position) {
            var geolocation = {
              lat: position.coords.latitude,
              lng: position.coords.longitude
            };
            var circle = new google.maps.Circle({
              center: geolocation,
              radius: position.coords.accuracy
            });
            autocomplete.setBounds(circle.getBounds());
          });
        }
      }
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCuhEGF-qIg4B6Wj6DUdOtVfWlqELH-U3A&libraries=places&callback=initAutocomplete"
        async defer></script>

@stop

