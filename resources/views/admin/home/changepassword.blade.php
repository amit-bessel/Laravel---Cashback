@extends('admin/layout/admin_template')

@section('content')
<!-- jQuery Form Validation code -->
<script>
  
  // When the browser is ready...
  $( document ).ready(function(){

    $.validator.addMethod("notequalto", function(value, element, param) {
     return this.optional(element) || value != $(param).val();
    }, "Password and Old Password should not match...");


    $("#change_form").validate({

       rules: {
                old_password: "required",
                password: {
                            required: true,
                            minlength:8,
                            notequalto:"#old_password",
							pattern: /^$|^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-_]).{8,}$/
                        },
                conf_pass: {
                  equalTo: "#password"
                }
            },
            messages: {
                old_password: "Please enter old password",
                password: {
                        required:"Please enter current password",
                        minlength:"Please enter minimum 8 character",
						pattern: "Password should contain atleast one number, one uppercase letter, one lower case letter and one special character.",
                },
               conf_pass: "Please enter the same value again"
                
                
      }

        
    });

  });
  

</script>

    @if(Session::has('error'))
        <div class="alert alert-danger">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <strong>{!! Session::get('error') !!}</strong>
        </div>
    @endif
    @if(Session::has('success'))
        <div class="alert alert-success">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <strong>{!! Session::get('success') !!}</strong>
        </div>
    @endif

    {!! Form::open(array('url' => 'admin/change-password','method'=>'POST','id' =>'change_form')) !!}
   
        <!-- <form class="form-horizontal row-fluid"> -->
        <div class="form-group form-row">
        <label class="col-sm-2 col-form-label" for="basicinput">Old Password</label>

        <div class="col-sm-10">
             {!! Form::password('old_password',array('class'=>'span8','id'=>'old_password')) !!}
        </div>
        </div>

        <div class="form-group form-row">
            <label class="col-sm-2 col-form-label" for="basicinput">New Password</label>

            <div class="col-sm-10">
                {!! Form::password('password',array('class'=>'span8','id'=>'password')) !!}
            </div>
        </div>
        <div class="form-group form-row">
            <label class="col-sm-2 col-form-label" for="basicinput">Confirm Password</label>

            <div class="col-sm-10">
                {!! Form::password('conf_pass',array('class'=>'span8')) !!}
            </div>
            
        </div>


        <div class="form-group form-row">
            <div class="col-sm-12 text-right">
                <!-- <button type="submit" class="btn">Submit Form</button> -->
                {!! Form::submit('Save', ['class' => 'btn btn-blue']) !!}
            </div>
        </div>
        {!! Form::close() !!}
                            

            

                

@endsection

