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
			name: "required",
            email: {
				required: true,
				//email: true,
				"remote" :
				{
					url: "<?php echo url().'/admin/user/check' ?>",
					type: "post",
					data:
					{
						email: function()
						{
							return $('#email').val();
						},
						hid_user_id: function(){
							return $('#hid_user_id').val();
						}
					},
				},
			},
            contact:
            {
              required:true,
              number:true,
              minlength:8,
              maxlength:10
              
            },
        },
        messages: {
			email: {
					remote:"email already exist."
			}
		},
      submitHandler: function(form) {
            form.submit();
        }
    });

  }); 
  </script>

	{!! Form::open(['url' => 'admin/user/edit/'.$user_id,'method'=>'POST', 'files'=>true,'class'=>'form-horizontal row-fluid','id'=>'user_form']) !!}
		
		{!! Form::hidden('hid_user_id',$user_id,['class'=>'span8','id'=>'hid_user_id']) !!}

		<div class="control-group" style="display:block;">
			{!! Html::decode(Form::label('name','Name:',['class' => 'control-label'])) !!}
				
			<div class="controls">
				{{$user_details->firstname.' '.$user_details->lastname}}
			</div>
				
		</div>

		
		
		<div class="control-group" style="display:block;">
			{!! Html::decode(Form::label('name','Email:',['class' => 'control-label'])) !!}
				
			<div class="controls">
				{{$user_details->email}}
			</div>
				
		</div>
		
		<div class="control-group" style="display:block;">
			{!! Html::decode(Form::label('name','Phone Number:',['class' => 'control-label'])) !!}
				
			<div class="controls">
				{{$user_details->contact}}
			</div>
				
		</div>

		<div class="control-group" style="display:block;">
			{!! Html::decode(Form::label('name','Status :',['class' => 'control-label'])) !!}
				
			<div class="controls">
				<?php if($user_details->status==1){ echo "active" ; }else {  echo "Inactive"; }?>
			</div>
				
		</div>
		

		<div class="control-group" style="display:block;">
			{!! Html::decode(Form::label('name','Role :',['class' => 'control-label'])) !!}
				
			<div class="controls">
				<?php if($user_details->role==1){ echo "SuperAdmin" ; }else if($user_details->role==2) {  echo "Subadmin"; }?>
			</div>
				
		</div>
		

		<div class="control-group" style="display:block;">
			{!! Html::decode(Form::label('name','Created at:',['class' => 'control-label'])) !!}
				
			<div class="controls">
				{{$user_details->created_at}}
			</div>
				
		</div>

		<div class="control-group" style="display:block;">
			{!! Html::decode(Form::label('name','Updated at:',['class' => 'control-label'])) !!}
				
			<div class="controls">
				{{$user_details->updated_at}}
			</div>
				
		</div>

		
		
		<div class="control-group">
		
			<div class="controls">
				<a href="{!! url('admin/user/list')!!}" class="btn">Back</a>
			</div>
				
		</div>
	
	{!! Form::close() !!}
	{!! HTML::script(url().'/public/backend/scripts/user.js') !!}
@stop
    
    