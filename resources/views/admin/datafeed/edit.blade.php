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
            contact:
            {
              number:true
            },
            status:{
            	required:true
            }
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

	{!! Form::open(['url' => 'admin/datafeeds/edit/'.$user_id,'method'=>'POST', 'files'=>true,'class'=>'form-horizontal row-fluid','id'=>'user_form']) !!}
		
		{!! Form::hidden('hid_user_id',$user_id,['class'=>'span8','id'=>'hid_user_id']) !!}
		<div class="control-group" style="display:block;">
			{!! Html::decode(Form::label('name','First Name: <span class="required" style="color: red">*</span>',['class' => 'control-label'])) !!}
			<div class="controls">
				{!! Form::text('name',$user_details->name,['class'=>'span8','id'=>'name']) !!}
			</div>
		</div>
		<div class="control-group" style="display:block;">
			{!! Html::decode(Form::label('name','Vendor Id: <span class="required" style="color: red">*</span>',['class' => 'control-label'])) !!}
			<div class="controls">
				<input type="text" name="vendor_id" id="vendor_id" class="span8" value="{{$user_details->vendor_id}}">
			</div>
		</div>
		<div class="control-group" style="display:block;">
			<label class="control-label" for="basicinput">Status <span class="required" style="color: red">*</span></label>
			<div class="controls">
				{!! Form::select('status', array('' =>'Status', '1' => 'Active', '0' => 'Inactive'),$user_details->status, array('class' => 'span3')) !!}
			</div>
		</div>
		<div class="control-group">		
			<div class="controls">
				{!! Form::submit('Save',array('class'=>'btn','name'=>'action','value'=>'save')) !!}
				<a href="{!! url('admin/datafeeds/list')!!}" class="btn">Back</a>
			</div>
				
		</div>
	
	{!! Form::close() !!}
	{!! HTML::script(url().'/public/backend/scripts/user.js') !!}
@stop
    
    