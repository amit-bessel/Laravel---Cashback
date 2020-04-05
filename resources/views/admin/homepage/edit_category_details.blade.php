{!! HTML::script('resources/assets/js/ckeditor/ckeditor.js') !!} 

@extends('admin/layout/admin_template')

@section('content')
	{!! Form::open(['url' => 'admin/category/edit/'.$category_id,'method'=>'POST', 'files'=>true,'class'=>'form-horizontal row-fluid','id'=>'cms_form','onsubmit'=>'category.checkCategoryName("EDIT")']) !!}
		
		{!! Form::hidden('hid_frm_submit_res',1,['class'=>'span8','id'=>'hid_frm_submit_res']) !!}
		{!! Form::hidden('hid_validate_res',1,['class'=>'span8','id'=>'hid_validate_res']) !!}
		{!! Form::hidden('hid_category_id',$category_id,['class'=>'span8','id'=>'hid_category_id']) !!}
		
		<div class="control-group" style="display:block;">
			<label class="control-label" for="basicinput">Category Name *</label>
				
			<div class="controls">
				{!! Form::text('name',$category_details->name,['class'=>'span8','id'=>'name']) !!}
				<div style="color:#F00;font-size:13px;clear:both;display:none;" id="category_name_msg"></div>
			</div>
				
		</div>
		
		<div class="control-group" style="display:block;">
			<label class="control-label" for="basicinput">Status *</label>
				
			<div class="controls">
				{!! Form::select('status', array('' =>'Status', '1' => 'Active', '0' => 'Inactive'),$category_details->status, array('class' => 'span3')) !!}
			</div>
				
		</div>
		
		
		<div class="control-group">
		
			<div class="controls">
				{!! Form::submit('Save',array('class'=>'btn','name'=>'action','value'=>'save')) !!}
				<a href="{!! url('admin/category/list')!!}" class="btn">Back</a>
			</div>
				
		</div>
	
	{!! Form::close() !!}
	{!! HTML::script(url().'/public/backend/scripts/category.js') !!}
@stop
    
    