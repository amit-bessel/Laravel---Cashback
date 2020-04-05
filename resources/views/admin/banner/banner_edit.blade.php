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

     
     <!--    <div class="box-header with-border">
          <h3 class="box-title">Edit Banner</h3>

          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            
          </div>
        </div> -->
        
      
					
		  {!! Form::open(['url' => 'admin/banner/edit-banner','method'=>'POST', 'files'=>true,'id'=>'banneredit']) !!}
		 {!! Form::hidden('banner_id',$banner_details->id,['class'=>'form-control','id'=>'banner_id']) !!}
         
              <!--<div class="form-group">
                <label>Page Name *</label>
              
									<select name="page_name" id="page_name" class="form-control">
									<option value="">Select Page Name</option>
									<?php
									if($cms_page_arr->count()>0)
									{
										foreach($cms_page_arr as $cms_page_list)
										{
											?>
											<option value="<?php echo $cms_page_list->slug ?>" <?php if($banner_details->page_name == $cms_page_list->slug){ echo "selected='selected'"; } ?>><?php echo ucfirst($cms_page_list->slug) ?></option>
											<?php
										}
									}
									?>
								 </select>
							</div>-->
				<div class="form-group form-row">
    					<label class="col-sm-2 col-form-label" for="basicinput">Banner Image (Min {{ $min_width }}X{{ $min_height }}) <span class="required" style="color: red">*</span></label>
		                 <div class="col-sm-10">
		              				 <span id="fileInput"><input type='file' id="banner_image" class="span8" name="banner_image"  /></span>
									 <span id="img_msg" style="color:red"></span>
						</div>
			 	</div>
							
				<div class="form-group form-row">
                <label class="col-sm-2 col-form-label" for="basicinput"></label>
                <div class="col-sm-10">
		               <?php if(($banner_details->banner_image!="") && (file_exists("public/uploads/banner/thumb/".$banner_details->banner_image)))
										{
		                ?>
												<img id="blah1" src="<?php echo url(); ?>/public/uploads/banner/thumb/<?php echo $banner_details->banner_image ?>" width="203" height="184"/>
		                <?php
		                }
		                else
		                {
		                ?>
												<img id="blah1" src="<?php echo url(); ?>/public/no-image.jpg" width="203" height="184">
		                <?php
		                }
		                ?>
            	</div>
            </div>
				
						<div class="form-group form-row">
                <label></label>
               <img id="blah" width="203" height="184" style="display:none"/>
              </div>
													
							
			<div class="form-group form-row">
                <label class="col-sm-2 col-form-label" for="basicinput">Banner Heading <span class="required" style="color: red">*</span></label>
                <div class="col-sm-10">
               {!! Form::text('banner_heading',$banner_details->banner_heading,['class'=>'form-control','id'=>'banner_heading']) !!}
               </div>
			</div>
			<?php if($banner_details->page_name == 'home'){?>	
            <div class="form-group form-row">
                <label class="col-sm-2 col-form-label" for="basicinput">Banner Sub-Heading <span class="required" style="color: red">*</span></label>
                <div class="col-sm-10">
               			{!! Form::text('banner_subheading',$banner_details->banner_subheading,['class'=>'form-control','id'=>'banner_subheading']) !!}
           		</div>
			</div>
			<?php } ?>	
			<div class="form-group form-row">
                <label class="col-sm-2 col-form-label" for="basicinput">Banner Text <span class="required" style="color: red">*</span></label>
                <div class="col-sm-10">
               		{!! Form::textarea('banner_text',$banner_details->banner_text,['class'=>'form-control','id'=>'banner_text']) !!}
          		 </div>
			</div>
			<?php //if($banner_details->page_name == 'home'){?>	
           <!--  <div class="form-group">
                <label>Banner Link <span class="required" style="color: red">*</span></label> -->
               <!-- {!! Form::text('banner_link',$banner_details->banner_link,['class'=>'form-control','id'=>'banner_link']) !!}
			</div> -->
			<?php //} ?>	

			<div class="form-group form-row">
                <div class="col-sm-12 text-right">
                    {!! Form::submit('Save', ['class' => 'btn btn-blue']) !!}
                   
                     <a href="{!! url('admin/banner/banner-list')!!}" class="btn btn-ylw">Back</a>
                   
                </div>
            </div>
						
							
						
              
            
            {!! Form::close() !!}
			{!! HTML::script(url().'/public/backend/scripts/user.js') !!}
         
    
        
     



    
 
 
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
        img = new Image();
        img.onload = function() {
				
				$('#new_width').val(this.width);
				$('#new_height').val(this.height);
						if ((this.width<{{ $min_width }}) || (this.height<{{ $min_height }})) {
               
								$('#img_msg').html("Image size should be min {{ $min_width }}X{{ $min_height }}");
								$('#blah').hide();
								$('#banner_image').val('');
								$('#blah1').show();
            }
						else
						{
							$('#img_msg').html("");
							$('#blah').show();
							$('#blah1').hide();
						}
            //alert(this.width + " " + this.height);
        };
       
        img.src = _URL.createObjectURL(file);


			}
			
		readURL(this);
		});
		

	</script>
@stop