{!! HTML::script('resources/assets/js/ckeditor/ckeditor.js') !!} 

@extends('admin/layout/admin_template')

@section('content')

	{!! Form::open(['url' => 'admin/edit-country-details/'.$country_details->id,'method'=>'POST', 'files'=>true,'class'=>'form-horizontal row-fluid','id'=>'cms_form']) !!}
		
		<input type="hidden" id="no_of_featured_country" value="{{ $count_featured_country }}" />
		<div class="control-group" style="display:block;">
			<label class="control-label" for="basicinput">Country Name</label>
			<div class="controls">
				<input type="text" name="country_name" id="country_name" value="<?php echo $country_details->name; ?>" class="span8" readonly>
			</div>
		</div>


		<div class="control-group" style="display:block;">
			<label class="control-label" for="basicinput">Iso Code</label>
			<div class="controls">
				<input type="text" name="iso_code" id="iso_code" value="<?php echo $country_details->iso_code_2; ?>" class="span8" readonly>
			</div>
		</div>

		<div class="control-group" style="display:block;">
			<label class="control-label" for="basicinput">Status</label>
			<div class="controls">
				<select name="country_status" id="country_status">
					<option value="1" <?php echo $country_details->status == '1' ? "selected='selected'" : ""; ?>>Active</option>
					<option value="0" <?php echo $country_details->status == '0' ? "selected='selected'" : ""; ?>>Inactive</option>
				</select>
			</div>
		</div>

		<div class="control-group" style="display:none;">
			<label class="control-label" for="basicinput">Is Featured</label>
			<input type="hidden" id="hid_featured" value="{{ $country_details->is_featured }}" />
			<div class="controls">
				<select name="is_featured" id="is_featured" onchange="check_country_status(this.value);">
					<option value="1" <?php echo $country_details->is_featured == '1' ? "selected='selected'" : ""; ?>>Yes</option>
					<option value="0" <?php echo $country_details->is_featured == '0' ? "selected='selected'" : ""; ?>>No</option>
				</select>
				<span class='alert-error' id="featured_span" style="display:none;"></span>
			</div>
		</div>


		<div class="control-group" style="display:none;">
			<label class="control-label" for="basicinput">Current Image</label>
			<div class="controls">
				<input type="hidden" id="country_image_hid" value="<?php echo $country_details->county_image; ?>" />
				<?php if($country_details->county_image != ''){ ?>
				<img src="<?php echo url(); ?>/uploads/country_logo/thumb/<?php echo $country_details->county_image; ?>" alt="No Image." />
				<?php }else{ ?> 
				No Image.
				<?php } ?>
			</div>
		</div>

			
		<div class="control-group" style="display:none;">
			<label class="control-label" for="basicinput">Upload Image *</label>

			<div class="controls">
				 <!--{!! Form::file('hotel_image',null,['class'=>'span8','id'=>'hotel_image']) !!}-->
				 <input type="file" name="country_image" id="country_image" class="span8">
			</div>
		</div>
			

	   <div class="control-group">
			<div class="controls">
				{!! Form::submit('Save',array('class'=>'btn','name'=>'action','value'=>'save')) !!}
				<a href="{!! url('admin/country-list')!!}" class="btn">Back</a>
			</div>
		</div>
			
	
	{!! Form::close() !!}
@stop
    
    