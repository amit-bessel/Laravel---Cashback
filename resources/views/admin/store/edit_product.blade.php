{!! HTML::script('resources/assets/js/ckeditor/ckeditor.js') !!} 

@extends('admin/layout/admin_template')

@section('content')

	{!! Form::open(['url' => 'admin/products/edit','method'=>'POST', 'files'=>true,'class'=>'form-horizontal row-fluid','id'=>'cms_form']) !!}
	
		{!! Form::hidden('hid_frm_submit_res',1,['class'=>'span8','id'=>'hid_frm_submit_res']) !!}
		{!! Form::hidden('hid_validate_res',1,['class'=>'span8','id'=>'hid_validate_res']) !!}
		
		<div class="control-group" style="display:block;">
			<label class="control-label" for="basicinput">Category Name</label>
			<input type="hidden" name="product_id" value="<?php echo $product_details['id']; ?>">	
			<div class="controls">
			<select name="category_id" id="category_id">
				<?php
					foreach($category as $cat){
						?>
						<option value="<?php echo $cat['id'];?>" <?php if($cat['id'] == $product_details['category_id']){ echo "selected = 'selected'"; } ?>><?php echo $cat['name'];?></option>
						<?php
					}
				?>
			</select>
			</div>
				
		</div>

		<div class="control-group" style="display:block;">
			<label class="control-label" for="basicinput">Name *</label>
				
			<div class="controls">
				<input type="text" class="span8 valid" name="name" id="name" value="<?php echo $product_details['name']; ?>">
			</div>
				
		</div>

		<div class="control-group" style="display:block;">
			<label class="control-label" for="basicinput">In Stock</label>
				
			<div class="controls">
				<select name="stock" id="stock">
					<option value="1" <?php if($product_details['in-stock'] == 1){ echo "selected = 'selected'";}?>>Yes</option>
					<option value="0" <?php if($product_details['in-stock'] == 0){ echo "selected = 'selected'";}?>>No</option>
				</select>
			</div>
				
		</div>

		<div class="control-group" style="display:block;">
			<label class="control-label" for="basicinput">Price *</label>
				
			<div class="controls">
				<input type="text" class="span8 valid" name="price" id="price" value="{{ $product_details['price'] }}">	
			</div>
				
		</div>

		<div class="control-group" style="display:block;">
			<label class="control-label" for="basicinput">Retail Price *</label>
				
			<div class="controls">
				<input type="text" class="span8 valid" name="retail_price" id="retail_price" value="{{ $product_details['retail_price'] }}">
			</div>
				
		</div>

		<div class="control-group" style="display:block;">
			<label class="control-label" for="basicinput">Brand Name</label>
				
			<div class="controls">
				<select name="brand" id="brand">
					<option value="0" <?php if($product_details['brand_id'] == 0){ echo "selected = 'selected'";}?>>Select</option>
					<?php
						foreach ($brand as $val) {
							?>
							<option value="<?php echo $val['id'];?>" <?php if($product_details['brand_id'] == $val['id']){ echo "selected = 'selected'";}?>><?php echo $val['brand_name'];?></option>
							<?php
						}
					?>
				</select>
			</div>
				
		</div>

		<div class="control-group" style="display:block;">
			<label class="control-label" for="basicinput">Description</label>
				
			<div class="controls">
				<textarea name="description" id="description" rows="5" class="span8 valid">{{ $product_details['description'] }}</textarea>
			</div>
				
		</div>

		<div class="control-group" style="display:block;">
			<label class="control-label" for="basicinput">Status</label>
				
			<div class="controls">
				<select name="status" id="status">
                    <option value="1" <?php echo $product_details['status'] == '1' ? "selected='selected'" : "" ?>>Active</option>
                    <option value="0" <?php echo $product_details['status'] == '0' ? "selected='selected'" : "" ?>>Inactive</option>
                </select>
			</div>
				
		</div>
		
		<div class="control-group">
		
			<div class="controls">
				{!! Form::submit('Save',array('class'=>'btn','name'=>'action','value'=>'save')) !!}
				<a href="{!! url('admin/products/list')!!}" class="btn">Back</a>
			</div>
				
		</div>
	
	{!! Form::close() !!}
	{!! HTML::script(url().'/public/backend/scripts/category.js') !!}
    @stop