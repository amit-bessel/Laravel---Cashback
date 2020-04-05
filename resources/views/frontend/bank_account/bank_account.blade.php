@extends('../layout/frontend_template')
@section('content')
  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAbSl3o9V8U8rVMpk-HSP80k0zI2PWyai4&libraries=places&callback=initAutocomplete" async defer></script>
  <script>
  $(function() {
    var document_validation = true;
    <?php
    if(!empty($user_bank_data)){
      ?>
        document_validation = false;
      <?php
    }
    ?>  
    $("#stripe_bank_account").validate({
      ignore: [],
      // Specify the validation rules
      rules: {

        autocomplete: {
          required: true,
          
        },
        locality: {
          required: true,
        },
        administrative_area_level_1: {
          required: true,
        },
        country: {
          required: true,
        },
        postal_code: {
          required: true,
        },
        business_name: {
          required: true,
        },
        business_tax_id: {
          required: true,
        },
        dob: {
          required: true,
        },
        document: {
          required: document_validation,
          extension: "jpg|jpeg|png|pdf",
          //filesize : 5,
        },
        first_name: {
          required: true,
        },
        last_name: {
          required: true,
        },
        personal_id_number: {
          required: true,
        },
        ssn_last_4: {
          required: true,
        },
        account_holder_name: {
          required: true,
        },
        account_holder_type: {
          required: true,
        },
        routing_number: {
          required: true,
        },
        account_number: {
          required: true,
        },
        /*vendor_image:{
          extension: "png|jpeg|jpg"
        },*/

      },
    // Specify the validation error messages
      messages: {
        document:{
                 extension: 'Please upload jpeg, jpg, png and pdf file only.',
                 //filesize : 'File size should be less than 8 MB.'
        } ,
      },
      submitHandler: function(form) {
        form.submit();
      }
    });
  });
  </script>
  <script>
        ////////////////////////////////// ADDRESS AUTO COMPLETE - START /////////////////////////////////////

var placeSearch, autocomplete;
      var componentForm = {
        //street_number: 'short_name',
        //route: 'long_name',
        locality: 'long_name',
        administrative_area_level_1: 'short_name',
        country: 'short_name',
        //country_code: 'short_name',
        postal_code: 'short_name',
      };

      function initAutocomplete() {
        autocomplete = new google.maps.places.Autocomplete(
            /** @type {!HTMLInputElement} */(document.getElementById('autocomplete')),
            {types: ['geocode']});
        console.log(autocomplete);
        autocomplete.addListener('place_changed', fillInAddress);
      }

      function fillInAddress() {
        // Get the place details from the autocomplete object.
        var place = autocomplete.getPlace();
        console.log(place);
        for (var component in componentForm) {
          console.log(component);
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
        //console.log(document.getElementById(addressType).value);
      }
    }

    for (var component in componentForm) {
      if(document.getElementById(component).value == ''){
        document.getElementById(component).readOnly = false;
      }
      else{
        document.getElementById(component).readOnly = true;
        $("label[for="+document.getElementById(component).name+"]").hide();
          $("[name="+document.getElementById(component).name+"]").removeClass("error");
          //console.log(document.getElementById(component).name);
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

////////////////////////////////// ADDRESS AUTO COMPLETE -END ////////////////////////////////////////
</script>
  <?php //print_r($user_bank_data);?>

    <!-- maincontent -->
    <section class="maincontent">
      <div class="container">
          <hr class="special-divider">
          <!-- common-headerblock -->
          <div class="common-headerblock text-center">
              <h4 class="text-uppercase">Account details</h4>
          </div>
          <!--\\ common-headerblock -->
          <div class="row mt20">

            @include('frontend.includes.left')

            <div class="col-sm-8 col-md-9 right-userpanel">
              @if(Session::has('failure_message'))
                <p class="alert {{ Session::get('alert-class', 'alert-danger') }}">{{ Session::get('failure_message') }}</p>
              @endif
    
              @if(Session::has('success_message'))
                <p class="alert {{ Session::get('alert-class', 'alert-success') }}">{{ Session::get('success_message') }}</p>
              @endif
                <div class="user-formpanel">
                 
                  <div class="form-group1">
                      <h3 class="user-heading">Account Type</h3>
                      <!-- <label>Account Type</label></br> -->
                       <div class="row">
                          <div class="col-sm-4">
                            <div class="custom-radio"><input type="radio" name="account_type_rad" id="stripe" value="stripe"><label for="stripe">Stripe</label></div>
                          </div>
                          <div class="col-sm-4">
                            <div class="custom-radio"><input type="radio" name="account_type_rad" id="paypal" value="paypal"><label for="paypal">Paypal</label></div>
                          </div>
                         <!--  <div class="col-sm-4">
                            <div class="custom-radio"><input type="radio" name="account_type_rad" id="bank_account" value="bank_account"><label for="bank_account">Bank Account Details </label></div>
                          </div> -->
                      </div>                 
                  </div>

                </div>

                {!! Form::open(['url'=>'user/my-account-details/bank-details','method'=>'POST', 'files'=>true,'class'=>'','id'=>'stripe_account','name'=>'stripe_account']) !!}
                    <div class="user-formpanel section_block" id="stripe_div" style="display: none;">
                      <input type="hidden" class="form-control" name="account_type" value="">

                      <div class="total-form">
                        <div class="row">                      
                          <div class="col-sm-6 form-group">
                             <label>Public Key</label> 
                             <input type="text" class="form-control" placeholder="pk_live_7uOxt6uvIwLr6pnztH2jlwjH" name="publish_key" id="publish_key" value="<?php if(!empty($user_bank_data)) echo $user_bank_data['publish_key'];?>">
                          </div>
                          <div class="col-sm-6 form-group">
                             <label>Secret Key</label> 
                             <input type="text" class="form-control" placeholder="sk_live_gqUt1uUXUVJLEuRBhtfQRHgn" name="secret_key" id="secret_key" value="<?php if(!empty($user_bank_data)) echo $user_bank_data['secret_key'];?>">
                          </div>
                        </div>  
                      </div>
                      <div class="submitbtn-group">
                         <input type="submit" class="btn btn-primary pull-left" name="submit" value="submit">                      
                      </div> 
                    </div>
                     
                {!! Form::close() !!}

                {!! Form::open(['url'=>'user/my-account-details/bank-details','method'=>'POST', 'files'=>true,'class'=>'','id'=>'paypal_account','name'=>'paypal_account']) !!}
                    <div class="user-formpanel section_block" id="paypal_div" style="display: none;">
                      <input type="hidden" class="form-control" name="account_type" value="">

                      <div class="total-form">
                        <div class="row">                      
                          <div class="col-sm-6 form-group">
                             <label>Paypal Email</label> 
                             <input type="text" class="form-control" placeholder="" name="paypal_email" id="paypal_email" value="<?php if(!empty($user_bank_data)) echo $user_bank_data['paypal_email'];?>">
                          </div>                          
                        </div>  
                      </div>
                      <div class="submitbtn-group">
                         <input type="submit" class="btn btn-primary pull-left" name="submit" value="submit">                      
                      </div> 
                    </div>
                     
                {!! Form::close() !!}

                {!! Form::open(['url'=>'user/my-account-details/bank-details','method'=>'POST', 'files'=>true,'class'=>'','id'=>'paypal_account','name'=>'paypal_account']) !!}
                    <div class="user-formpanel section_block" id="paypal_div" style="display: none;">
                      <input type="hidden" class="form-control" name="account_type" value="">

                      <div class="total-form">
                        <div class="row">                      
                          <div class="col-sm-6 form-group">
                             <label>Paypal Email</label> 
                             <input type="text" class="form-control" placeholder="" name="paypal_email" id="paypal_email" value="<?php if(!empty($user_bank_data)) echo $user_bank_data['paypal_email'];?>">
                          </div>                          
                        </div>  
                      </div>
                      <div class="submitbtn-group">
                         <input type="submit" class="btn btn-primary pull-left" name="submit" value="submit">                      
                      </div> 
                    </div>
                     
                {!! Form::close() !!}

                {!! Form::open(['url'=>'user/my-account-details/bank-details','method'=>'POST', 'files'=>true,'class'=>'','id'=>'stripe_bank_account','name'=>'stripe_bank_account']) !!}
                    <div class="user-formpanel section_block" id="bank_div" style="display: none;">
                      <input type="hidden" class="form-control" name="account_type" value="">

                      <div class="total-form">

                        <div class="row">

                          <div class="col-sm-12 form-group">
                             <label>Enter Address</label> 
                             <input type="text" class="form-control" placeholder="Enter Address" name="autocomplete" id="autocomplete" value="<?php if(!empty($user_bank_data)) echo $user_bank_data['stripe_address'];?>">
                          </div>
                         
                        </div>

                        <div class="row form-group">

                          <div class="col-sm-6">
                             <label>City</label> 
                             <input type="text" class="form-control" placeholder="City" name="locality" id="locality" value="<?php if(!empty($user_bank_data)) echo $user_bank_data['stripe_city'];?>">
                          </div>

                          <div class="col-sm-6">
                             <label>State</label> 
                             <input type="text" class="form-control" placeholder="State" name="administrative_area_level_1" id="administrative_area_level_1" value="<?php if(!empty($user_bank_data)) echo $user_bank_data['stripe_state'];?>">
                          </div>

                        </div>  

                        <div class="row form-group">

                          <div class="col-sm-6">
                             <label>Country</label> 
                             <input type="text" class="form-control" placeholder="Country" name="country" id="country" value="<?php if(!empty($user_bank_data)) echo $user_bank_data['stripe_country'];?>">
                          </div>

                          <div class="col-sm-6">
                             <label>Postal Code</label> 
                             <input type="text" class="form-control" placeholder="State" name="postal_code" id="postal_code" value="<?php if(!empty($user_bank_data)) echo $user_bank_data['stripe_postal_code'];?>">
                          </div>

                        </div>

                        <div class="row form-group">

                          <div class="col-sm-6">
                             <label>Business Name</label> 
                             <input type="text" class="form-control" placeholder="Business Name" name="business_name" id="business_name" value="<?php if(!empty($user_bank_data)) echo $user_bank_data['stripe_business_name'];?>">
                          </div>

                          <div class="col-sm-6">
                             <label>Business Tax Id</label> 
                             <input type="text" class="form-control" placeholder="Business Tax Id" name="business_tax_id" id="business_tax_id" value="<?php if(!empty($user_bank_data)) echo $user_bank_data['stripe_business_tax_id'];?>">
                          </div>

                        </div>

                        <div class="row form-group">

                          <div class="col-sm-6">
                            <label>DOB</label>
                            <?php
                                if(!empty($user_bank_data)){
                                  $stripe_dob = date('d-m-Y',strtotime($user_bank_data['stripe_dob']));
                                }
                                else{
                                  $stripe_dob = "";
                                }
                              ?>
                            <input type="text" class="form-control" placeholder="dd-mm-yyyy" name="dob" id="dob" value="<?php echo $stripe_dob;?>">
                          </div>

                          <div class="col-sm-6">
                            <label>Document</label>
                            <input type="file" class="form-control" name="document" id="document">
                          </div>

                        </div>

                        <div class="row form-group">

                          <div class="col-sm-6">
                             <label>First Name</label> 
                             <input type="text" class="form-control" placeholder="First Name" name="first_name" id="first_name" value="<?php if(!empty($user_bank_data)){echo $user_bank_data['stripe_first_name'];}?>">
                          </div>

                          <div class="col-sm-6">
                             <label>Last Name</label> 
                             <input type="text" class="form-control" placeholder="Last Name" name="last_name" id="last_name" value="<?php if(!empty($user_bank_data)) echo $user_bank_data['stripe_last_name'];?>">
                          </div>

                        </div>

                        <div class="row form-group">

                          <div class="col-sm-6">
                             <label>Personal Id Number</label> 
                             <input type="text" class="form-control" placeholder="Personal Id Number" name="personal_id_number" id="personal_id_number" value="<?php if(!empty($user_bank_data)) echo $user_bank_data['stripe_personal_id_number'];?>">
                          </div>

                          <div class="col-sm-6">
                             <label>SSN Last 4</label> 
                             <input type="text" class="form-control" placeholder="SSN Last 4" name="ssn_last_4" id="ssn_last_4" value="<?php if(!empty($user_bank_data)) echo $user_bank_data['stripe_ssn_last_4'];?>">
                          </div>

                        </div>

                        <div class="row form-group">

                          <div class="col-sm-6">
                             <label>Account Holder Name</label> 
                             <input type="text" class="form-control" placeholder="Account Holder Name" name="account_holder_name" id="account_holder_name" value="<?php if(!empty($user_bank_data)) echo $user_bank_data['stripe_account_holder_name'];?>">
                          </div>

                          <div class="col-sm-6">
                             <label>Account Holder Type</label> 
                             <select name="account_holder_type" id="account_holder_type" class="form-control">
                              <?php
                                if(empty($user_bank_data)){
                                  $stripe_account_holder_type = '';
                                }
                                else{
                                  $stripe_account_holder_type = $user_bank_data['stripe_account_holder_type'];
                                }
                              ?>
                              <option val="">Account Holder Type</option>
                              <option val="individual" <?php echo ($stripe_account_holder_type=='Individual')?'selected="selected"':'' ?>>Individual</option>
                             </select>
                          </div>

                        </div>

                        <div class="row form-group">

                          <div class="col-sm-6">
                             <label>Routing Number</label> 
                             <input type="text" class="form-control" placeholder="Routing Number" name="routing_number" id="routing_number" value="<?php if(!empty($user_bank_data)) echo $user_bank_data['stripe_routing_number'];?>">
                          </div>

                          <div class="col-sm-6">
                             <label>Account Number</label> 
                             <input type="text" class="form-control" placeholder="Account Number" name="account_number" id="account_number" value="<?php if(!empty($user_bank_data)) echo $user_bank_data['stripe_account_number'];?>">
                          </div>

                        </div>

                      </div>
                      <div class="submitbtn-group">
                         <input type="submit" class="btn btn-primary pull-left" name="submit" value="submit">                      
                      </div> 
                    </div>
                     
                {!! Form::close() !!}

          </div>
      </div>
    </section>
    <!-- maincontent -->

    <script type="text/javascript">
      $(document).ready(function(){
        
        $('input[name=\'account_type_rad\']').on('click',function(){
         
         // hide all relative divs
         $('.section_block').hide();

          if($(this).val()=='stripe'){
            $('#stripe_div').slideDown();
            $('input[name=\'account_type\']').val(1);
          }
          else if($(this).val()=='bank_account'){
            $('#bank_div').slideDown();
            $('input[name=\'account_type\']').val(3);
          }
          else if( $(this).val()== 'paypal'){
             $('#paypal_div').slideDown();
             $('input[name=\'account_type\']').val(2);
          }


        })

        <?php 
          if(!empty($user_bank_data) && $user_bank_data['account_type']==1){
        ?>
            $('#stripe').trigger('click');
        <?php
          }
          if(!empty($user_bank_data) && $user_bank_data['account_type']==2){
         ?>
            $('#paypal').trigger('click');
        <?php
          }
          if(!empty($user_bank_data) && $user_bank_data['account_type']==3){
         ?>
            $('#bank_account').trigger('click');
        <?php
          }
        ?>

      });
    </script>

@stop