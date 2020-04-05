{!! HTML::script('resources/assets/js/ckeditor/ckeditor.js') !!} 

@extends('admin/layout/admin_template')

@section('content')
<!-- jQuery Form Validation code -->
<script>
	// When the browser is ready...
	$(function() {
	
		$("#cms_form").validate();
	
	});
	
	function validate_form()
	{
		//alert('test');
		var valdate_res	 = 1; 
		var from_page	 = 'ADD';
		var country_name = $('#country_name').val();
		var iso_code	 = $('#iso_code').val();
		if (country_name!='' && iso_code!='') {
			//alert(true);
			$.ajax({
				type	: "GET",
				url		: "<?php echo url(); ?>/admin/check-country-and-isocode",
				data 	: {country_name: country_name,iso_code:iso_code,from_page: from_page},
				async	: false,
				success	: function(response){
					//alert(response);
					if(response=='BOTH ARE EXISTS')
					{
						$('#country_name_msg').css('display','block');
						$('#iso_code_msg').css('display','block');
						valdate_res = 0;
					}
					else if(response=='COUNTRY NAME EXISTS')
					{
						$('#country_name_msg').css('display','block');
						$('#iso_code_msg').css('display','none');
						valdate_res = 0;
					}
					else if(response=='ISO CODE EXISTS')
					{
						$('#country_name_msg').css('display','none');
						$('#iso_code_msg').css('display','block');
						valdate_res = 0;
					}
					else
					{
						valdate_res = 1;
					}
					
				}
			});
			//alert(valdate_res);
			if (valdate_res==0)
			{
				return false;
			}
		}
		else{
			return false;
		}
		
	}
</script> 
	{!! Form::open(['url' => 'admin/add-country-details/','method'=>'POST', 'files'=>true,'class'=>'form-horizontal row-fluid','id'=>'cms_form','onsubmit'=>'return validate_form()']) !!}
		
		<div class="control-group" style="display:block;">
			<label class="control-label" for="basicinput">Country Name</label>
			<div class="controls">
				<input type="text" name="country_name" id="country_name" value="" class="span8" required>
				<div style="color:#F00;font-size:13px;clear:both;display:none;" id="country_name_msg">
					Country name alredy exists.
				</div>
			</div>
		</div>


		<div class="control-group" style="display:block;">
			<label class="control-label" for="basicinput">Iso Code</label>
			<div class="controls">
				<input type="text" name="iso_code" id="iso_code" value="" class="span8" required>
				<div style="color:#F00;font-size:13px;clear:both;display:none;" id="iso_code_msg">
					ISO code alredy exists.
				</div>
			</div>
		</div>

		<div class="control-group" style="display:block;">
			<label class="control-label" for="basicinput">Status</label>
			<div class="controls">
				<select name="country_status" id="country_status" required>
					<option value="1">Active</option>
					<option value="0">Inactive</option>
				</select>
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
    
    