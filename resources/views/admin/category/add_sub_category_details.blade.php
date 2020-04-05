{!! HTML::script('resources/assets/js/ckeditor/ckeditor.js') !!} 

@extends('admin/layout/admin_template')

@section('content')
<script>
$(function() {
	
	// Setup form validation on the #register-form element
	$("#sub_category").validate({
	
		ignore: [],
		// Specify the validation rules
		rules: {
			name: {
				required: true,
				"remote" :
		        {
		            url: "<?php echo url().'/admin/category/sub-category-availability/'.base64_encode($category_id); ?>",
		            type: "get",
		            data:
		            {
		                name: function()
		                {
		                    return $('#sub_category :input[name="name"]').val();
		                }
		            }
		        },
			},
			status: {
				required: true
			},
			
		
		},
	
	 // Specify the validation error messages
		messages: {

			name:{
				remote: "Sub category already exits.",
			}
			
		},               

		submitHandler: function(form) {
			form.submit();
		}
	});
});
</script>

@if(Session::has('failure_message'))
                  <p class="alert {{ Session::get('alert-class', 'alert-danger') }}">{{ Session::get('failure_message') }}</p>
                @endif
      
                @if(Session::has('success_message'))
                  <p class="alert {{ Session::get('alert-class', 'alert-success') }}">{{ Session::get('success_message') }}</p>
                @endif
	{!! Form::open(['url' => 'admin/category/add-sub-category/'.base64_encode($category_id),'method'=>'POST', 'files'=>true,'class'=>'form-horizontal row-fluid','id'=>'cms_form']) !!}
		
		<div class="control-group" style="display:block;">
			<label class="control-label" for="basicinput">Category Gender *</label>
				
			<div class="controls">
				Men<input type="checkbox" name="gender[]" value="1"><br/>
				Women<input type="checkbox" name="gender[]" value="2">

			</div>
				
		</div>


		<div class="control-group" style="display:block;">
			<label class="control-label" for="basicinput">Name *</label>
				
			<div class="controls">
				{!! Form::text('name',null,['class'=>'span8','id'=>'name']) !!}
				<div style="color:#F00;font-size:13px;clear:both;display:none;" id="category_name_msg"></div>
			</div>
				
		</div>

		<div class="control-group" style="display:block;">
			<label class="control-label" for="basicinput">Status *</label>
				
			<div class="controls">
				{!! Form::select('status', array('' =>'Status', '1' => 'Active', '0' => 'Inactive'),null, array('class' => 'span3')) !!}
			</div>
				
		</div>
		
		<div class="control-group">
		
			<div class="controls">
				{!! Form::submit('Save',array('class'=>'btn','name'=>'action','value'=>'save')) !!}
				<a href="{!! url('admin/category/child-category-list/'.base64_encode($category_id))!!}" class="btn">Back</a>
			</div>
				
		</div>
	
	{!! Form::close() !!}
	{!! HTML::script(url().'/public/backend/scripts/category.js') !!}
    @stop
    
    