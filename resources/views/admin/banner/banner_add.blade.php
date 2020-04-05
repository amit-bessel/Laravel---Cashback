@extends('admin/layout/admin_template')

@section('content')


    

   
		
		@if (count($errors) > 0)
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

     
        
        
          
					{!! Form::open(['url' => 'admin/banner/add-banner','method'=>'POST', 'files'=>true, 'id'=>'banneradd']) !!}
					
            
              <!-- <div class="form-group">
                <label>Page Name <span class="required" style="color: red">*</span></label>
              
									<select name="page_name" id="page_name" class="form-control" type="text">
									<option value="">Select Page Name</option>
									<?php
									//if($cms_page_arr->count()>0)
									{
										//foreach($cms_page_arr as $cms_page_list)
										{
											?>
											<option value="<?php //echo $cms_page_list->slug ?>"><?php //echo ucfirst($cms_page_list->slug) ?></option>
											<?php
										}
									}
									?>
								 </select>
							</div> -->
				<div class="form-group form-row">
                <label class="col-sm-2 col-form-label" for="basicinput">Banner Image (Min {{ $min_width }}X{{ $min_height }}) <span class="required" style="color: red">*</span></label>
                 			<div class="col-sm-10">
               							<span id="fileInput"><input type='file' id="banner_image" class="span8" name="banner_image" /></span>
									 	<span id="img_msg" style="color:red"></span>
							</div>
				 </div>
							
				<div class="form-group form-row">
    					<label class="col-sm-2 col-form-label" for="basicinput"></label>
    					<div class="col-sm-10">
   						<img id="blah" width="203" height="184"/>
   					</div>
				</div>
							
							
				<div class="form-group form-row">
        			<label class="col-sm-2 col-form-label" for="basicinput">Banner Heading <span class="required" style="color: red">*</span></label>
        			<div class="col-sm-10">
       				{!! Form::text('banner_heading',null,['class'=>'form-control','id'=>'banner_heading']) !!}
   					</div>
				</div>


				<div class="form-group form-row">
        <label class="col-sm-2 col-form-label" for="basicinput">Banner Text <span class="required" style="color: red">*</span></label>
        					<div class="col-sm-10">
       						{!! Form::textarea('banner_text',null,['class'=>'form-control','id'=>'banner_text']) !!}
  					 		</div>
				</div>	
            <!-- <div class="form-group">
                <label>Banner Link </label>
               {!! Form::text('banner_link',null,['class'=>'form-control','id'=>'banner_link']) !!}
						</div> -->
				
			<div class="form-group form-row">
                <div class="col-sm-12 text-right">
                    {!! Form::submit('Save', ['class' => 'btn btn-blue']) !!}
                   
                     <a href="{!! url('admin/banner/banner-list')!!}" class="btn btn-ylw">Back</a>
                   
                </div>
            </div>
							
						
              
            
            {!! Form::close() !!}
			{!! HTML::script(url().'/public/backend/scripts/user.js') !!}
          
         
        
      
   
  <!-- /.content-wrapper -->
    <script>
		function readURL(input) {

			if (input.files && input.files[0]) {
				var reader = new FileReader();
		
				reader.onload = function (e) {
				
					$('#blah').attr('src', e.target.result);
				}
		
				reader.readAsDataURL(input.files[0]);
			}
		}

		$("#banner_image").change(function(e)
		{
		
			var file, img;
			var _URL = window.URL || window.webkitURL;
			
			if ((file = this.files[0]))
			{
			
				var file = this.files[0];
				var fileType = file["type"];
				var ValidImageTypes = ["image/gif", "image/jpeg", "image/png"];
				if ($.inArray(fileType, ValidImageTypes) < 0) {
						$('#img_msg').html("Upload image only.");
						$('#banner_image').val('');
				}
			
        img = new Image();
        img.onload = function() {
				
				$('#new_width').val(this.width);
				$('#new_height').val(this.height);
						if ((this.width<{{ $min_width }}) || (this.height<{{ $min_height }})) {
               //alert(this.width);
								$('#img_msg').html("Image size should be min {{ $min_width }}X{{ $min_height }}");
								$('#blah').hide();
								$('#banner_image').val('');
            }
						else
						{
							$('#img_msg').html("");
							$('#blah').show();
							
						}
            //alert(this.width + " " + this.height);
        };
       
        img.src = _URL.createObjectURL(file);
			}
			
		readURL(this);
		});
		

	</script>
@stop