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
        	datafeed_name:"required",
            vendor_id:
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

	{!! Form::open(['url' => 'admin/datafeeds/add/','method'=>'POST', 'files'=>true,'class'=>'form-horizontal row-fluid','id'=>'user_form']) !!}
	
		<div class="control-group" style="display:block;">
			 {!! Html::decode(Form::label('datafeed_name','Datafeed Name: <span class="required" style="color: red">*</span>',['class' => 'control-label'])) !!}	
			<div class="controls">
				{!! Form::text('datafeed_name',null,['class'=>'span8','id'=>'datafeed_name','placeholder'=>'Datafeed Name']) !!}
			</div>
		</div>

		<div class="control-group" style="display:block;">
			 {!! Html::decode(Form::label('vendor_id','Vendor Id:',['class' => 'control-label'])) !!}	
			<div class="controls">
				{!! Form::text('vendor_id',null,['class'=>'span8','id'=>'vendor_id','placeholder'=>'Vendor Id']) !!}
			</div>
		</div>

		<div class="control-group" style="display:block;">
			<label class="control-label" for="basicinput">Status <span class="required" style="color: red">*</span></label>
			<div class="controls">
				{!! Form::select('status', array('' =>'Status', '1' => 'Active', '0' => 'Inactive'),null, array('class' => 'span3')) !!}
			</div>
		</div>
			
		<div class="control-group">
			<div class="controls">
				{!! Form::submit('Save',array('class'=>'btn','name'=>'action','value'=>'save')) !!}
				<a href="{!! url('admin/user/list')!!}" class="btn">Back</a>
			</div>
		</div>
	
	{!! Form::close() !!}
	{!! HTML::script(url().'/public/backend/scripts/user.js') !!}
@stop
    
    