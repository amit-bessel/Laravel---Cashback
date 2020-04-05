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
							return $('#hid_user_id').val();
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
        },
        messages: {
			email: {
					remote:"Email is already registered."
			},
			firstname: "Only alphabets and spaces are allowed",

            lastname: "Only alphabets and spaces are allowed",
			contact: " Phone number digits limit should 4-16 character "
		},
      submitHandler: function(form) {
            form.submit();
        }
    });

  }); 
  </script>

	{!! Form::open(['url' => 'admin/user/edit/'.$user_id,'method'=>'POST', 'files'=>true,'class'=>'form-horizontal row-fluid','id'=>'user_form']) !!}
		
		{!! Form::hidden('hid_user_id',$user_id,['class'=>'span8','id'=>'hid_user_id']) !!}
		<div class="form-group form-row">
			
			 {!! Html::decode(Form::label('title','Title: <span class="required" style="color: red">*</span>',['class' => 'col-sm-2 col-form-label'])) !!}	
			<div class="col-sm-10">
				<select name="title" id="title" class="custom-select">
					<option value="">Title</option>
					<option value="Mr" <?php echo ($user_details->title=='Mr')?'selected="selected"':''; ?>>Mr</option>
					<option value="Mrs" <?php echo ($user_details->title=='Mrs')?'selected="selected"':''; ?>>Mrs</option>
					<option value="Ms" <?php echo ($user_details->title=='Ms')?'selected="selected"':''; ?>>Ms</option>
				</select>
			</div>
				
		</div>


			


		<div class="form-group form-row">
			{!! Html::decode(Form::label('name','First Name: <span class="required" style="color: red">*</span>',['class' => 'col-sm-2 col-form-label'])) !!}
				
			<div class="col-sm-10">
				{!! Form::text('firstname',$user_details->firstname,['class'=>'span8','id'=>'firstname']) !!}
			</div>
				
		</div>

		<div class="form-group form-row" >
			
			 {!! Html::decode(Form::label('name','Last Name: <span class="required" style="color: red">*</span>',['class' => 'col-sm-2 col-form-label'])) !!}	
			<div class="col-sm-10">
				{!! Form::text('lastname',$user_details->lastname,['class'=>'span8','id'=>'lastname']) !!}
			</div>
				
		</div>
		
		<div class="form-group form-row">
			{!! Html::decode(Form::label('name','Email: <span class="required" style="color: red">*</span>',['class' => 'col-sm-2 col-form-label'])) !!}
				
			<div class="col-sm-10">
				{!! Form::email('email',$user_details->email,['class'=>'span8','id'=>'email']) !!}
				<div style="color:#F00;font-size:13px;clear:both;display:none;" id="user_email_msg"></div>
			</div>
				
		</div>
		
		<div class="form-group form-row" >
			{!! Html::decode(Form::label('name','Phone Number: <span class="required" style="color: red">*</span>',['class' => 'col-sm-2 col-form-label'])) !!}
				
			<div class="col-sm-10">
				<input type="text" name="contact" id="contact" class="span8" data-format=" (ddd) ddd-dddd" data-number="{{$user_details->contact}}" value="{{$user_details->contact}}">
			</div>
				
		</div>
		
		<div class="form-group form-row" >
			<label class="col-sm-2 col-form-label" for="basicinput">Password :</label>
				
			<div class="col-sm-10">
				<input type="password" name="password" id="password" class="span8" onblur="userJs.rulesWhileEditingUser();"  />
			</div>
				
		</div>
		
		<div class="form-group form-row" >
			<label class="col-sm-2 col-form-label" for="basicinput">Confirm Password :</label>
				
			<div class="col-sm-10">
				<input type="password" name="conf_password" id="conf_password" class="span8"  />
			</div>
				
		</div>
		<div class="form-group form-row">
            
        </div>
			
		<div class="form-group form-row">
		
			<div class="col-sm-12 text-right">
				{!! Form::submit('Save',array('class'=>'btn btn-blue','name'=>'action','value'=>'save')) !!}
				<a href="{!! url('admin/user/list')!!}" class="btn btn-ylw">Back</a>
			</div>
				
		</div>
	
	{!! Form::close() !!}
	{!! HTML::script(url().'/public/backend/scripts/user.js') !!}
@stop
    
    