{!! HTML::script('resources/assets/js/ckeditor/ckeditor.js') !!}
@extends('admin/layout/admin_template')

@section('content')

<!-- jQuery Form Validation code -->
  <script>
  
  // When the browser is ready...
  $(function() {
    $("#user_form").validate({
        ignore: [],
        // Specify the validation rules
        rules: {
        	title:"required",
			
			name:
			{
			        required:true,
			        
			        
			        pattern: /^[a-zA-Z ]*$/
			},
			

			last_name:
			{
			        required:true,
			        
			        
			        pattern: /^[a-zA-Z ]*$/
			},

            email: {
				required: true,
				email: true,
				"remote" :
				{
					url: "<?php echo url().'/admin/adminuserpreviousemail/check' ?>",
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
            
            contact:
            {
              required:true,
              number:true,
              minlength:4,
              maxlength:16
            },
           password:
           {
           	required:true,
           	minlength : 8,
            //pattern: /^$|^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*[!@#$%^&*]).{8,}$/
            pattern: /^$|^(?=.*?[a-zA-Z])(?=.*?[0-9])(?=.*[!@#$%^&*_]).{8,}$/
           },
            usertype:
           {
           	required:true,
           },
           conf_password:
           {
           	required:true,
           	minlength : 8,
           	equalTo: "#password",
           },
	
        },
        messages: {
			email: {
					remote:"Email is already registered."
			},
			name: "Only alphabets and spaces are allowed",

            last_name: "Only alphabets and spaces are allowed",

			password:{
            minlength:"Please enter minimum 8 character.",
            pattern:"Password must contain one number, one letter , one special character.",
            //pattern:"Password must contain one number, one special character, one small letter and one capital letter",
            },
            conf_password: " Enter Confirm Password Same as Password",
            contact: " Phone number digits limit should 4-16 character "
		},
      submitHandler: function(form) {
            form.submit();
        }
    });

  }); 
  </script>




	{!! Form::open(['url' => 'admin/user/add/','method'=>'POST', 'files'=>true,'class'=>'form-horizontal row-fluid','id'=>'user_form']) !!}
	
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


		<div class="form-group form-row" >
			
			 {!! Html::decode(Form::label('name','Type: <span class="required" style="color: red">*</span>',['class' => 'col-sm-2 col-form-label'])) !!}	
			<div class="col-sm-10">
				<select name="usertype" id="usertype" class="custom-select">
					<option value="">Select Type</option>
					<option value="2">SubAdmin</option>
					
				</select>
			</div>
				
		</div>

		<div class="form-group form-row" >
			
			 {!! Html::decode(Form::label('name','First Name: <span class="required" style="color: red">*</span>',['class' => 'col-sm-2 col-form-label'])) !!}	
			<div class="col-sm-10">
				{!! Form::text('name',null,['class'=>'span8','id'=>'name']) !!}
			</div>
				
		</div>

		<div class="form-group form-row" >
			
			 {!! Html::decode(Form::label('last_name','Last Name: <span class="required" style="color: red">*</span>',['class' => 'col-sm-2 col-form-label'])) !!}	
			<div class="col-sm-10">
				{!! Form::text('last_name',null,['class'=>'span8','id'=>'last_name']) !!}
			</div>
				
		</div>
		
		<div class="form-group form-row" >
			
			{!! Html::decode(Form::label('name','Email: <span class="required" style="color: red">*</span>',['class' => 'col-sm-2 col-form-label'])) !!}		
			<div class="col-sm-10">
				{!! Form::email('email',null,['class'=>'span8','id'=>'email']) !!}
				<div style="color:#F00;font-size:13px;clear:both;display:none;" id="user_email_msg"></div>
			</div>
				
		</div>
		
		<div class="form-group form-row" >
			
			{!! Html::decode(Form::label('name','Phone Number: <span class="required" style="color: red">*</span>',['class' => 'col-sm-2 col-form-label'])) !!}		
			<div class="col-sm-10">
				<input type="text" name="contact" id="contact" class="span8" data-format=" (ddd) ddd-dddd"  value="">
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
		
			
		<div class="form-group form-row">
		
			<div class="col-sm-12 text-right">
				{!! Form::submit('Invite',array('class'=>'btn btn-blue','name'=>'action','value'=>'save')) !!}
				<a href="{!! url('admin/user/list')!!}" class="btn btn-ylw">Back</a>
			</div>
				
		</div>
	
	{!! Form::close() !!}
	{!! HTML::script(url().'/public/backend/scripts/user.js') !!}
@stop
    
    