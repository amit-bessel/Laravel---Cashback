{!! HTML::script('resources/assets/js/ckeditor/ckeditor.js') !!} 

@extends('admin/layout/admin_template')

@section('content')

<!-- jQuery Form Validation code -->

  <script>
      $(function() {
        $( "#dob" ).datepicker({
            dateFormat : 'yy-mm-dd',
            changeMonth : true,
            changeYear : true,
            yearRange: '-100y:c+nn',
            maxDate: '-1d'
        });
    });
   
  // When the browser is ready...
  $(function() {
  
    // Setup form validation on the #register-form element
    $("#siteuser_form").validate({
        
        ignore: [],
        // Specify the validation rules
        rules: {

            title:"required",
            firstname:
            {
            required:true,


            pattern: /^[a-zA-Z ]*$/
            },

            lastname:
            {
            required:true,


            pattern: /^[a-zA-Z ]*$/
            },
            //address: "required",
            //country: "required",
            //city: "required",
            //state: "required",
            //zipcode: "required",
            phoneno: {
              required: true,
              digits: true,
              minlength:4,
              maxlength:16

            },
            // mobileno: {
            //   required: true,
            //   digits: true,
            //   minlength:8,
            //   maxlength:10

            // },

    email: {
        required: true,
        email: true,
        "remote" :
        {
          url: "<?php echo url().'/admin/siteuserpreviousemail/check' ?>",
          type: "post",
          data:
          {
            email: function()
            {
              return $('#email').val();
            },
            hid_user_id: function(){
              return '';
            },
            _token:'{{csrf_token()}}'
          },
        },
      },

            //dob: "required",
            
            password:
           {
            required:true,
            minlength : 8,
            //pattern: /^$|^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*[!@#$%^&*]).{8,}$/
            pattern: /^$|^(?=.*?[a-zA-Z])(?=.*?[0-9])(?=.*[!@#$%^&*_]).{8,}$/
           },
            conf_password:
           {
            required:true,
            minlength : 8,
            equalTo: "#password",
           },
            
        },
        
        // Specify the validation error messages
        messages: {
            firstname: "Only alphabets and spaces are allowed",

            lastname: "Only alphabets and spaces are allowed",
           // address: "Please enter address.",
           // country: "Please enter country.",
            //city: "Please enter city.",
            //state: "Please enter state.",
            //zipcode: "Please enter zipcode.",
            password:{
            minlength:"Please enter minimum 8 character.",
            pattern:"Password must contain one number, one letter , one special character.",
            //pattern:"Password must contain one number, one special character, one small letter and one capital letter",
            },
            phoneno: "Phone number digits limit should 4-16 character.",
           // mobileno: "Please enter digits mobileno.",
            //dob: "Please enter date of birth.",
            email: {
            remote:"Email is already registered."
           }
            
        },               

        submitHandler: function(form) {
            form.submit();
        }
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
/*      #locationField, #col-sm-10 {
        position: relative;
        width: 480px;
      }
      #autocomplete {
        position: absolute;
        top: 0px;
        left: 0px;
        width: 90%;
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
      #street_number{ margin-bottom: 10px; }*/
    </style>

        {!! Form::open(['url' => 'admin/siteuser','method'=>'POST', 'files'=>true,'class'=>'form-horizontal row-fluid','id'=>'siteuser_form']) !!}

        <div class="form-group form-row" >
      
       {!! Html::decode(Form::label('type','Title: <span class="required" style="color: red">*</span>',['class' => 'col-sm-2 col-form-label'])) !!} 
            <div class="col-sm-10">
              <select name="title" id="title" class="custom-select">
                <option value="">Title</option>
                <option value="Mr">Mr</option>
                <option value="Mrs">Mrs</option>
                <option value="Ms">Ms</option>
              </select>
            </div>
        
        </div>
          
          


        <div class="form-group form-row">
                <label class="col-sm-2 col-form-label" for="basicinput">First Name <span class="required" style="color: red">*</span></label>

                <div class="col-sm-10">
                     {!! Form::text('firstname',null,['class'=>'span8','id'=>'firstname']) !!}
                </div>
            </div>


        <div class="form-group form-row">
                <label class="col-sm-2 col-form-label" for="basicinput">Last Name <span class="required" style="color: red">*</span></label>

                <div class="col-sm-10">
                     {!! Form::text('lastname',null,['class'=>'span8','id'=>'lastname']) !!}
                </div>
            </div> 

            <!-- <div class="form-group form-row">
              <label class="col-sm-2 col-form-label" for="basicinput">Address<span class="required" style="color: red">*</span></label>

                <div class="col-sm-10" style="margin-left: 0px !important">
                    
           <div id="locationField" class="form-group form-row" style="margin: 0 auto; margin-top: 10px;">
            <input id="autocomplete" placeholder="Enter your address"
             onFocus="geolocate()" type="text" class="span8" style="margin: 0 auto;" name="address"></input>
          </div> 
                </div>
            </div> 


            <div class="form-group form-row" style="display: none;">
                <label class="col-sm-2 col-form-label" for="basicinput">Street address <span class="required" style="color: red">*</span></label>

                <div class="col-sm-10">
                     <input class="span8" id="street_number"
               ></input>
                </div>

                <div class="col-sm-10 wideField">
                   <input class="field" id="route"
              name="address1"></input>
                </div>
            </div>


            <div class="form-group form-row">
                <label class="col-sm-2 col-form-label" for="basicinput">City <span class="required" style="color: red">*</span></label>

                <div class="col-sm-10">
                <input class="" id="locality" type="text" 
               name="city"></input>
                </div>
            </div>
        
            <div class="form-group form-row">
                <label class="col-sm-2 col-form-label" for="basicinput">State <span class="required" style="color: red">*</span></label>

                <div class="col-sm-10">
                     <input class="" type="text" 
              id="administrative_area_level_1"  name="state"></input>
                </div>
            </div>

            <div class="form-group form-row">
                <label class="col-sm-2 col-form-label" for="basicinput">Zip code <span class="required" style="color: red">*</span></label>

                <div class="col-sm-10">
                     <input class="" id="postal_code" type="text" 
               name="zipcode"></input>
                </div>
            </div>

            <div class="form-group form-row">
                <label class="col-sm-2 col-form-label" for="basicinput">Country <span class="required" style="color: red">*</span></label>

                <div class="col-sm-10">
                     <input class="" type="text" 
              id="country"  name="country"></input>
                </div>
            </div> -->

          <div class="form-group form-row">
                <label class="col-sm-2 col-form-label" for="basicinput">Email <span class="required" style="color: red">*</span></label>

                <div class="col-sm-10">
                     {!! Form::text('email',null,['class'=>'span8','id'=>'email']) !!}
                </div>
            </div>
           

            <div class="form-group form-row">
                <label class="col-sm-2 col-form-label" for="basicinput">Phone Number <span class="required" style="color: red">*</span></label>

                <div class="col-sm-10">
                     {!! Form::text('phoneno',null,['class'=>'span8','id'=>'phoneno']) !!}
                </div>
            </div>

          <div class="form-group form-row" >
            {!! Html::decode(Form::label('name','Password: <span class="required" style="color: red">*</span>',['class' => 'col-sm-2 col-form-label'])) !!}   
            <div class="col-sm-10">
              <input type="password" name="password" id="password" class="span8" onblur="userJs.rulesWhileEditingUser();"  />
            </div>
              
          </div>

          <div class="form-group form-row" >
             {!! Html::decode(Form::label('name','Confirm Password: <span class="required" style="color: red">*</span>',['class' => 'col-sm-2 col-form-label'])) !!}  
            <div class="col-sm-10">
              <input type="password" name="conf_password" id="conf_password" class="span8"  />
            </div>
              
          </div>

            <!-- <div class="form-group form-row">
                <label class="col-sm-2 col-form-label" for="basicinput">Mobileno <span class="required" style="color: red">*</span></label>

                <div class="col-sm-10">
                     {!! Form::text('mobileno',null,['class'=>'span8','id'=>'mobileno']) !!}
                </div>
            </div> -->

            


            


            <!-- <div class="form-group form-row">
                <label class="col-sm-2 col-form-label" for="basicinput">Dob <span class="required" style="color: red">*</span></label>

                <div class="col-sm-10">
                     {!! Form::text('dob',null,['class'=>'span8','id'=>'dob', 'readonly' => 'true']) !!}
                </div>
            </div> -->


            <!-- <div class="form-group form-row">
                <label class="col-sm-2 col-form-label" for="basicinput">Profile Image </label>

                <div class="col-sm-10">
                  {!! Form::file('profileimage');!!}
                   
                </div>
            </div> -->
            
            

            <div class="form-group form-row">
                <div class="col-sm-12 text-right">
                    {!! Form::submit('Invite', ['class' => 'btn btn-blue']) !!}
                   
                     <a href="{!! url('admin/siteuser/list')!!}" class="btn btn-ylw">Back</a>
                   
                </div>
            </div>



        
        {!! Form::close() !!}

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
        async defer>
          

    
        </script>

    @stop
