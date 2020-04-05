@extends('admin.layout.layout')

@section('content')
	<style>
	
	.error{
	color:#000;
	}
	label.error {
		display: inline-block;
		max-width: 100%;
		margin-bottom: 5px;
		font-weight: 700;
		color:red!important;
	}
	</style>
<!-- jQuery Form Validation code -->
  <script>
  
  // When the browser is ready...
 
$(document).ready(function(){
//alert('test');
 $("#reset_password").validate({
        // Specify the validation rules
        rules: {
                password: {
                            required: true,
                            minlength:8,
							pattern: /^$|^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-_]).{8,}$/
                        },
                con_password: {
                  equalTo: "#password"
                }
            },
            messages: {
                password: {
                        minlength:"Please enter minimum 8 character",
						pattern: "Password should contain atleast one number, one uppercase letter, one lower case letter and one special character.",
                },
               con_password: "Please enter the same value again"
                
                
      }
    });

 });
  
  </script>
 
  <div class="tab"><div class="tab_cell"><div class="container-fluid">
    <div class="row">
      <div class="col-sm-8 col-md-6 col-sm-offset-2 col-md-offset-3">
        <div class="panel panel-default">
          <div class="panel-heading"><i class="fa fa-lock"></i>Reset Password</div>
          <div class="panel-body">

@if(Session::has('success'))
        <div class="alert alert-success">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <strong>{!! Session::get('success') !!}</strong>
        </div>
 @endif

   @if(Session::has('error'))
        <div class="alert alert-danger">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <strong>{!! Session::get('error') !!}</strong>
        </div>
    @endif

<?php if(isset($admin_email)){$admin_email = $admin_email;}else{$admin_email='';} ?>
            <form class="form-horizontal" method="POST" id="reset_password" action="{!! url('admin/updatepassword/'.$admin_email) !!}" > <!--onsubmit="return false;"-->

              <input type="hidden" name="_token" value="{!! csrf_token() !!}">

              <div class="form-group">
                <label class="col-md-4 control-label">Reset Password</label>
                <div class="col-md-7">
                  <input type="password" class="form-control" name="password" id="password" value=""> <!-- {{ old('email') }} -->
                </div>
              </div>

              <div class="form-group">
                <label class="col-md-4 control-label">Confirm Password</label>
                <div class="col-md-7">
                  <input type="password" class="form-control" name="con_password" id="con_password" value=""> <!-- {{ old('email') }} -->
                </div>
              </div>


              <div class="form-group">
                <div class="col-md-6 col-md-offset-4">
                  <button type="submit" class="btn btn-primary">Submit</button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div></div></div>
@endsection

