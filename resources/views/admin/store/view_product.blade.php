{!! HTML::script('resources/assets/js/ckeditor/ckeditor.js') !!} 

@extends('admin/layout/admin_template')

@section('content')

	{!! Form::open(['url' => 'admin/category/add','method'=>'POST', 'files'=>true,'class'=>'form-horizontal row-fluid','id'=>'cms_form','onsubmit'=>'category.checkCategoryName("ADD")']) !!}
	
		{!! Form::hidden('hid_frm_submit_res',1,['class'=>'span8','id'=>'hid_frm_submit_res']) !!}
		{!! Form::hidden('hid_validate_res',1,['class'=>'span8','id'=>'hid_validate_res']) !!}
		
		<div class="control-group" style="display:block;">
			<label class="control-label" for="basicinput">Category Name *</label>
				
			<div class="controls">
				{{ $category_details['name'] }}
			</div>
				
		</div>

		<div class="control-group" style="display:block;">
			<label class="control-label" for="basicinput">Name *</label>
				
			<div class="controls">
				{{ $product_details['name'] }}
			</div>
				
		</div>

		<div class="control-group" style="display:block;">
			<label class="control-label" for="basicinput">In Stock *</label>
				
			<div class="controls">
				<?php if($product_details['in-stock'] == '1'){
						echo "Yes";
					}
					elseif($product_details['in-stock'] == '0'){
						echo "No";
					}?>
			</div>
				
		</div>

		<div class="control-group" style="display:block;">
			<label class="control-label" for="basicinput">Price *</label>
				
			<div class="controls">
				{{ $product_details['currency'] }} {{ $product_details['price'] }}
			</div>
				
		</div>

		<div class="control-group" style="display:block;">
			<label class="control-label" for="basicinput">Retail Price *</label>
				
			<div class="controls">
				{{ $product_details['currency'] }} {{ $product_details['retail_price'] }}
			</div>
				
		</div>

		<div class="control-group" style="display:block;">
			<label class="control-label" for="basicinput">Brand Name *</label>
				
			<div class="controls">
				{{ $brand_details['name'] }}
			</div>
				
		</div>

		<div class="control-group" style="display:block;">
			<label class="control-label" for="basicinput">Description *</label>
				
			<div class="controls">
				{{ $product_details['description'] }}
			</div>
				
		</div>

		<div class="control-group" style="display:block;">
			<label class="control-label" for="basicinput">Status *</label>
				
			<div class="controls">
				<?php if($product_details['status'] == '1'){
						echo "Active";
					}
					elseif($product_details['status'] == '0'){
						echo "Inactive";
					}?>
			</div>
				
		</div>

		<div class="control-group" style="display:block;">
			<label class="control-label" for="basicinput">Image *</label>
				
			<div class="controls">
				<img src="{{ $product_details['image_url'] }}" height="300px" width="150px" alt=""/>
			</div>
				
		</div>
		
		<div class="control-group">
		
			<div class="controls">
				<!-- {!! Form::submit('Save',array('class'=>'btn','name'=>'action','value'=>'save')) !!} -->
				<a href="{!! url('admin/products/list')!!}" class="btn">Back</a>
			</div>
				
		</div>
	
	{!! Form::close() !!}
	{!! HTML::script(url().'/public/backend/scripts/category.js') !!}
    @stop
    
    