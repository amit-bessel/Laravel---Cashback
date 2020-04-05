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
            firstname:
            {
            required:true,


            pattern: /^[a-zA-Z ]*$/
            },

            title: "required",
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
              return $('#hid_user_id').val();
            },
            _token:'{{csrf_token()}}'
          },
        },
      },
            dob: "required",
            
            
            
        },
        
        // Specify the validation error messages
        messages: {
            firstname: "Only alphabets and spaces are allowed",

            lastname: "Only alphabets and spaces are allowed",
            //address: "Please enter address.",
            //country: "Please enter country.",
            //city: "Please enter city.",
           // state: "Please enter state.",
            //zipcode: "Please enter zipcode.",
            phoneno: "Phone number digits limit should 4-16 character.",
            
            //mobileno: "Please enter digits mobileno.",
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



          {!! Form::model($siteuser,array('method' => 'PATCH', 'files'=>true , 'class'=>'form-horizontal row-fluid','id'=>'siteuser_form','route'=>array('admin.siteuser.update',$siteuser->id))) !!}


          {!! Form::hidden('hid_user_id',$siteuser->id,['class'=>'span8','id'=>'hid_user_id']) !!}
          
    <div class="form-group form-row">
      
       {!! Html::decode(Form::label('title','Title: <span class="required" style="color: red">*</span>',['class' => 'col-sm-2 col-form-label'])) !!}  
      <div class="col-sm-10">
        <select name="title" id="title" class="custom-select">
          <option value="">Title</option>
          <option value="Mr" <?php echo ($siteuser->title=='Mr')?'selected="selected"':''; ?>>Mr</option>
          <option value="Mrs" <?php echo ($siteuser->title=='Mrs')?'selected="selected"':''; ?>>Mrs</option>
          <option value="Ms" <?php echo ($siteuser->title=='Ms')?'selected="selected"':''; ?>>Ms</option>
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


           <!--  <div class="form-group form-row">
                <label class="col-sm-2 col-form-label" for="basicinput">Mobileno <span class="required" style="color: red">*</span></label>

                <div class="col-sm-10">
                     {!! Form::text('mobileno',null,['class'=>'span8','id'=>'mobileno']) !!}
                </div>
            </div> -->

            


            


           <!--  <div class="form-group form-row">
                <label class="col-sm-2 col-form-label" for="basicinput">Dob <span class="required" style="color: red">*</span></label>

                <div class="col-sm-10">
                     {!! Form::text('dob',null,['class'=>'span8','id'=>'dob', 'readonly' => 'true']) !!}
                </div>
            </div> -->

             <div class="form-group form-row">
                <label class="col-sm-2 col-form-label" for="basicinput">Paypal id </label>

                <div class="col-sm-10">

                     <?php if($siteuser->paypalid!='') { echo $siteuser->paypalid; } else { echo "None" ; } ?>
                </div>
            </div>

            <!-- <div class="form-group form-row">
                <label class="col-sm-2 col-form-label" for="basicinput">Profile Image </label>

                <div class="col-sm-10">
                  {!! Form::file('profileimage');!!}
                   
                </div>
            </div> -->
            
            

            <div class="form-group form-row">
                <div class="col-sm-12 text-right">
                    {!! Form::submit('Save', ['class' => 'btn btn-blue']) !!}
                   
                     <a href="{!! url('admin/siteuser/list')!!}" class="btn btn-ylw">Back</a>
                   
                </div>
            </div>



        
        {!! Form::close() !!}

         

    @stop
