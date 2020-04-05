{!! HTML::script('resources/assets/js/ckeditor/ckeditor.js') !!} 

@extends('admin/layout/admin_template')

@section('content')
<script>
$(function() {
       
   $("#stote_edit_frm").validate({
    ignore: [],
    rules: {
      store_name: {
        required: true
      }
    },
  // Specify the validation error messages
    messages: {
      vendor_image:{
               extension: 'Selected File should be an image',
      } ,
    },
    submitHandler: function(form) {
      form.submit();
    }
  });
});
</script>
	{!! Form::open(['url' => 'admin/stores/edit','method'=>'POST', 'files'=>true,'class'=>'form-horizontal row-fluid','id'=>'stote_edit_frm']) !!}
		
		{!! Form::hidden('hid_frm_submit_res',1,['class'=>'span8','id'=>$store_id]) !!}
		{!! Form::hidden('hid_validate_res',1,['class'=>'span8','id'=>$store_id]) !!}
		
		<div class="control-group" style="display:block;">
			<label class="control-label" for="basicinput">Store Name *</label>
				
			<div class="controls">
				{!! Form::text('store_name',$store_info->name,['class'=>'span8','id'=>'store_name']) !!}
				<div style="color:#F00;font-size:13px;clear:both;display:none;" id="category_name_msg"></div>
			</div>
				
		</div>

		<!-- <div class="control-group" style="display:block;">
			<label class="control-label" for="basicinput">Icon class *</label>
				
			<div class="controls">
				{!! Form::text('store_class',$store_info->icon,['class'=>'span8','id'=>'store_class']) !!}
				<div style="color:#F00;font-size:13px;clear:both;display:none;" id="category_name_msg"></div>
			</div>
				
		</div> -->

		<!-- <div class="control-group" style="display:block;">
			<label class="control-label" for="basicinput">Image *</label>
				
			<div class="controls">
				{!! Form::file('store_image','',['class'=>'span8','id'=>'store_image']) !!}
		
			</div>

				
		</div> -->	
		
		<div class="control-group">
		
			<div class="controls">
				{!! Form::submit('Save',array('class'=>'btn','name'=>'action','value'=>'save')) !!}
				<a href="{!! url('admin/stores/list')!!}" class="btn">Back</a>
			</div>
				
		</div>
	
	{!! Form::close() !!}
	{!! HTML::script(url().'/public/backend/scripts/category.js') !!}
@stop
    
    