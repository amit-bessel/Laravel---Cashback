{!! HTML::script('resources/assets/js/ckeditor/ckeditor.js') !!} 

@extends('admin/layout/admin_template')

@section('content')
<script>
$(function() {
       

  $("#cms_form").validate({
    ignore: [],
    // Specify the validation rules
    rules: {
      /*"image[1]": {
        //required: true
        extension: "png|jpeg|jpg"
      },
      
      "link[1]":{
        required: true,
        url:true,
      },*/

      "image[2]": {
        //required: true
        extension: "png|jpeg|jpg"
      },
      "description[2]": {
        required: true
      },
      "link[2]":{
        required: true,
        url:true,
      },

      "image[3]": {
        //required: true
        extension: "png|jpeg|jpg"
      },
      "description[3]": {
        required: true
      },
      "link[3]":{
        required: true,
        url:true,
      },

      "image[4]": {
        //required: true
        extension: "png|jpeg|jpg"
      },
      "description[4]": {
        required: true
      },
      "link[4]":{
        required: true,
        url:true,
      },

      "image[5]": {
        //required: true
        extension: "png|jpeg|jpg"
      },
      "description[5]": {
        required: true
      },
      "link[5]":{
        required: true,
        url:true,
      },

      "image[6]": {
        //required: true
        extension: "png|jpeg|jpg"
      },
      "description[6]": {
        required: true
      },
      "link[6]":{
        required: true,
        url:true,
      },

      /*"image[7]": {
        //required: true
        extension: "png|jpeg|jpg"
      },
      "link[7]":{
        required: true,
        url:true,
      },*/

      "image[8]": {
        //required: true
        extension: "png|jpeg|jpg"
      },
      "description[8]": {
        required: true
      },
      "link[8]":{
        required: true,
        url:true,
      },

     "image[9]": {
        //required: true
        extension: "png|jpeg|jpg"
      },
      "description[9]": {
        required: true
      },
      "link[9]":{
        required: true,
        url:true,
      },

      "image[10]": {
        //required: true
        extension: "png|jpeg|jpg"
      },
      "description[10]": {
        required: true
      },
      "link[10]":{
        required: true,
        url:true,
      },

      "image[11]": {
        //required: true
        extension: "png|jpeg|jpg"
      },
      "description[11]": {
        required: true
      },
      "link[11]":{
        required: true,
        url:true,
      },

      "image[12]": {
        //required: true
        extension: "png|jpeg|jpg"
      },
      "description[12]": {
        required: true
      },
      "link[12]":{
        required: true,
        url:true,
      },

    },
  // Specify the validation error messages
    messages: {
      business_logo:{
               accept: 'Selected File should be an image',
      } ,
      password:{
        minlength:"Please enter minimum 8 character",
        pattern:"Password must contain one number, one special character, one small letter and one capital letter",
      },
    },
    submitHandler: function(form) {
      form.submit();
    }
  });
});


function chnageImage(files,id){

  var _URL = window.URL || window.webkitURL;
  var file, img, imgRealHeight, imgRealWidth;
  //var this  = $('#image_2');
  if ((file = files[0])) {
      img = new Image();
      img.onload = function () {
          img_width = this.width;
          img_height = this.height;
      };
      img.src = _URL.createObjectURL(file);
  }


  if(id=='image_2' || id=='image_3' || id=='image_8' || id=='image_9'){
    imgRealWidth = 555;
    imgRealHeight = 360;
  }
  else{
    imgRealWidth = 360;
    imgRealHeight = 245;
  }

  $("#"+id).rules('add', {
        accept: "image/*",
        minImageWidth: imgRealWidth,
        minImageHeight: imgRealWidth,
  });
}

</script>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/0.8.1/cropper.css" />
   <script src="<?php echo url(); ?>/public/backend/scripts/cropper.js"></script>
   <script src="<?php echo url(); ?>/public/backend/scripts/main.js"></script>

<!-- @if(Session::has('success_message')) -->
<p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('success_message') }}</p>
<!-- @endif -->

@if(Session::has('failure_message'))
<p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('failure_message') }}</p>
@endif


    {!! Form::open(['url' => 'admin/homepage/save','method'=>'POST', 'files'=>true,'class'=>'form-horizontal row-fluid','id'=>'cms_form']) !!}
 
        <h4 class="text-center" style="margin-bottom: 2px;background-color:#45a1e1;color:#fff;height:25px;">Men</h4>
        <?php
        $men=1;
        foreach($home_page_details_for_men as $home_page_each_details_men){
           $image_array = explode( ' x ',$home_page_each_details_men['image_size']);
           $image_w = $image_array[0];
           $image_h = str_replace(' px', '', $image_array[1]) ;
           $aspect_rasio = $image_w/$image_h;
            ?>
            <hr>
            <h4 class="text-center" style="margin-bottom: 2px;">Banner <?php echo $men;?></h4>
            <hr>
            <div class="control-group" style="display:block;">
                <label class="control-label" for="basicinput">Image</label>
                <div class="controls">
                    <input type="file" name="image[<?php echo $home_page_each_details_men['id'];?>]" id="image_<?php echo $home_page_each_details_men['id'];?>" onchange="initcroper(this.files,this.id,'image_preview_div_men_{{$home_page_each_details_men['id']}}','upload_banner_image_{{$home_page_each_details_men['id']}}','banner_img_error_men_{{$home_page_each_details_men['id']}}','{{$aspect_rasio }}');">
                    <div style="clear:both;"></div>
                    @if($men != 4)
                    <span style="color:#45a1e1;clear:both;">Please upload <?php echo $home_page_each_details_men['image_size']; ?> image for best view.</span>
                    @endif
                    <span id="image_preview_div_men_{{$home_page_each_details_men['id']}}" class="coomonimgclass">
                    <img class="img-responsive" src="<?php echo url(); ?>/uploads/home_page_images/{{$home_page_each_details_men['image']}}" />
                    </span>

                    <input type="hidden" id="upload_banner_image_{{$home_page_each_details_men['id']}}" name="upload_banner_image_{{$home_page_each_details_men['id']}}"/>
                    <span id="banner_img_error_men_{{$home_page_each_details_men['id']}}" style="color:red"></span>

                </div>
            </div>

            <div class="control-group" style="<?php echo ($home_page_each_details_men['id']!=1)?'display:block;':'display:none;' ?>">
                <label class="control-label" for="basicinput">Decription</label>
                <div class="controls">
                    <input type="text" class="span8" name="decription[<?php echo $home_page_each_details_men['id'];?>]" id="decription_<?php echo $home_page_each_details_men['id'];?>" value="<?php echo $home_page_each_details_men['description'];?>">                
                </div>
            </div>
            <div class="control-group" style="<?php echo ($home_page_each_details_men['id']!=1)?'display:block;':'display:none;' ?>">
                <label class="control-label" for="basicinput">Cashback</label>
                <div class="controls">
                    <input type="text" class="span8" name="cashback[<?php echo $home_page_each_details_men['id'];?>]" id="decription_<?php echo $home_page_each_details_men['id'];?>" value="<?php echo $home_page_each_details_men['cashback'];?>">                
                </div>
            </div>
            <div class="control-group" style="display:block;">
                <label class="control-label" for="basicinput">Link</label>
                <div class="controls">
                    <input type="text" class="span8" name="link[<?php echo $home_page_each_details_men['id'];?>]" id="link_<?php echo $home_page_each_details_men['id'];?>" value="<?php echo $home_page_each_details_men['link'];?>">                
                </div>
            </div>
            <?php
            $men++;
        } 
        ?>
        
        <div class="control-group" style="display:block;">
        <h4 class="text-center" style="margin-bottom: 2px;background-color:#45a1e1;color:#fff;height:25px;">Women</h4>
        <?php
        $women=1;
        foreach($home_page_details_for_women as $home_page_each_details_women){
           $image_array = explode( ' x ',$home_page_each_details_women['image_size']);
           $image_w = $image_array[0];
           $image_h = str_replace(' px', '', $image_array[1]) ;
           $aspect_rasio = $image_w/$image_h;
            ?>
            <hr>
            <h4 class="text-center" style="margin-bottom: 2px;">Banner <?php echo $women;?></h4>
            <hr>
            <div class="control-group" style="display:block;">
                <label class="control-label" for="basicinput">Image</label>
                <div class="controls">
                    <input type="file" name="image[<?php echo $home_page_each_details_women['id'];?>]" id="image_<?php echo $home_page_each_details_women['id'];?>" onchange="initcroper(this.files,this.id,'image_preview_div_women_{{$home_page_each_details_women['id']}}','upload_banner_image_{{$home_page_each_details_women['id'],$aspect_rasio}}','banner_img_error_women_{{$home_page_each_details_women['id']}}',{{$aspect_rasio }});">
                    <div style="clear:both;"></div>
                    <span style="color:#45a1e1;">Please upload <?php echo $home_page_each_details_women['image_size']; ?> image for best view.</span>
                    

                    <span id="image_preview_div_women_{{$home_page_each_details_women['id']}}" class="coomonimgclass">
                    <img class="img-responsive" src="<?php echo url(); ?>/uploads/home_page_images/{{$home_page_each_details_women['image']}}" />
                    </span>

                    <input type="hidden" id="upload_banner_image_{{$home_page_each_details_women['id']}}" name="upload_banner_image_{{$home_page_each_details_women['id']}}"/>
                    <span id="banner_img_error_women_{{$home_page_each_details_women['id']}}" style="color:red"></span>
                </div>
            </div>
            <div class="control-group" style="<?php echo ($home_page_each_details_women['id']!=7)?'display:block;':'display:none;' ?>">
                <label class="control-label" for="basicinput">Decription</label>
                <div class="controls">
                    <input type="text" class="span8" name="decription[<?php echo $home_page_each_details_women['id'];?>]" id="decription_<?php echo $home_page_each_details_women['id'];?>" value="<?php echo $home_page_each_details_women['description'];?>">                
                </div>
            </div>
            <div class="control-group" style="<?php echo ($home_page_each_details_women['id']!=7)?'display:block;':'display:none;' ?>">
                <label class="control-label" for="basicinput">Cashback</label>
                <div class="controls">
                    <input type="text" class="span8" name="cashback[<?php echo $home_page_each_details_women['id'];?>]" id="decription_<?php echo $home_page_each_details_women['id'];?>" value="<?php echo $home_page_each_details_women['cashback'];?>">                
                </div>
            </div>
            <div class="control-group" style="display:block;">
                <label class="control-label" for="basicinput">Link</label>
                <div class="controls">
                    <input type="text" class="span8" name="link[<?php echo $home_page_each_details_women['id'];?>]" id="link_<?php echo $home_page_each_details_women['id'];?>" value="<?php echo $home_page_each_details_women['link'];?>">                
                </div>
            </div>
            <?php
            $women++;
        } 
        ?>
       
        <div class="control-group">
        
            <div class="controls">
                {!! Form::submit('Save',array('class'=>'btn','name'=>'action','value'=>'save')) !!}
                
                <!-- <a href="{!! url('admin/products/list')!!}" class="btn">Back</a> -->
            </div>
                
        </div>
    </div>



          <!-- Popup for croper -->

              <!-- Modal -->
                  <div id="test_crop_preview_modal" class="modal fade modal-lg" role="dialog">
                      <div class="modal-dialog">

                        <!-- Modal content-->
                        <div class="modal-content">
                          <div class="modal-header">
                            <button type="button" id="modal_header_close" class="close" onclick="closeCropModal();">&times;</button>
                            <h4 class="modal-title">Crop</h4>
                          </div>

                          <div class="modal-body no-pad">
                              <div class="row">

                                 <div class="col-md-12 md-img for-scrollimage">
                                    <div class="img-container" id="img-container">
                                       <img id="image" alt="Loading...">
                                    </div>
                                 </div>

                                  <div class="col-md-12 no-pad">
                                    <!-- <h3>Preview:</h3> -->
                                    <div class="docs-preview clearfix">
                                       <div class="img-preview preview-lg"></div>
                                       <div class="img-preview preview-md"></div>
                                       <div class="img-preview preview-sm"></div>
                                       <div class="img-preview preview-xs"></div> 
                                    </div>

                                      <input type="hidden" readonly class="form-control" id="image_preview_div" placeholder="x">
                                      <input type="hidden" readonly class="form-control" id="upload_banner_name" placeholder="x">

                                      <input type="hidden" readonly class="form-control" id="dataX" placeholder="x">
                                      <input type="hidden" readonly class="form-control" id="dataY" placeholder="y">
                                      <input type="hidden" readonly class="form-control" id="dataWidth" placeholder="width">
                                      <input type="hidden" readonly class="form-control" id="dataHeight" placeholder="height">
                                      <input type="hidden" readonly class="form-control" id="dataRotate" placeholder="rotate" value="0">
                                      <input type="hidden" readonly class="form-control" id="dataScaleX" placeholder="scaleX">
                                      <input type="hidden" readonly class="form-control" id="dataScaleY" placeholder="scaleY">
                                    
                                  </div>
                                </div>

                              <div class="" id="actions">
                                 <div class="docs-buttons">
                                    <!-- <h3>Toolbar:</h3> -->
                                    <!-- <div class="btn-group move-section">
                                       <button type="button" class="btn btn-primary" data-method="setDragMode" data-option="move" title="Move">
                                          <span class="docs-tooltip" data-toggle="tooltip" title="Move">
                                             <span class="fa fa-arrows">&nbsp;Move</span>
                                          </span>
                                       </button>
                                       <button type="button" class="btn btn-primary" data-method="setDragMode" data-option="crop" title="Crop">
                                          <span class="docs-tooltip" data-toggle="tooltip" title="cropper.setDragMode(&quot;crop&quot;)">
                                             <span class="fa fa-crop"></span>
                                          </span>
                                       </button>
                                    </div> -->

                                    

                                    <!-- <div class="btn-group move-section">
                                       <button type="button" class="btn btn-primary" data-method="move" data-option="-10" data-second-option="0" title="Move Left">
                                          <span class="docs-tooltip" data-toggle="tooltip" title="Move Left">
                                             <span class="fa fa-arrow-left">&nbsp;Move Left</span>
                                          </span>
                                       </button>

                                       <button type="button" class="btn btn-primary" data-method="move" data-option="10" data-second-option="0" title="Move Right">
                                          <span class="docs-tooltip" data-toggle="tooltip" title="Move Right">
                                             <span class="fa fa-arrow-right">&nbsp;Move Right</span>
                                          </span>
                                       </button>

                                       <button type="button" class="btn btn-primary" data-method="move" data-option="0" data-second-option="-10" title="Move Up">
                                          <span class="docs-tooltip" data-toggle="tooltip" title="Move Up">
                                             <span class="fa fa-arrow-up">&nbsp;Move Up</span>
                                          </span>
                                       </button>

                                       <button type="button" class="btn btn-primary" data-method="move" data-option="0" data-second-option="10" title="Move Down">
                                          <span class="docs-tooltip" data-toggle="tooltip" title="Move Down">
                                             <span class="fa fa-arrow-down">&nbsp;Move Down</span>
                                          </span>
                                       </button>
                                    </div> -->

                                   <!--  <div class="btn-group">

                                       <button type="button" class="btn btn-primary" data-method="rotate" data-option="-45" >
                                          <span class="docs-tooltip" data-toggle="tooltip" >
                                             <span class="fa fa-rotate-left">&nbsp;{{ Lang::get('messages.image_crop.rotate_left') }}</span>
                                          </span>
                                       </button>

                                       <button type="button" class="btn btn-primary" data-method="rotate" data-option="45" >
                                          <span class="docs-tooltip" data-toggle="tooltip" >
                                             <span class="fa fa-rotate-right">&nbsp;{{ Lang::get('messages.image_crop.rotate_right') }}</span>
                                          </span>
                                       </button>
                                    </div> -->

                                    <!-- <div class="btn-group">
                                       <button type="button" class="btn btn-primary" data-method="scaleX" data-option="-1" title="Flip Horizontal">
                                          <span class="docs-tooltip" data-toggle="tooltip" title="cropper.scaleX(-1)">
                                             <span class="fa fa-arrows-h"></span>
                                          </span>
                                       </button>
                                       <button type="button" class="btn btn-primary" data-method="scaleY" data-option="-1" title="Flip Vertical">
                                          <span class="docs-tooltip" data-toggle="tooltip" title="cropper.scaleY(-1)">
                                             <span class="fa fa-arrows-v"></span>
                                          </span>
                                       </button>
                                    </div> -->

                                    <!--<div class="btn-group">
                                        <button type="button" class="btn btn-primary" data-method="crop" title="Crop">
                                          <span class="docs-tooltip" data-toggle="tooltip" title="Crop">
                                             <span class="fa fa-check">&nbsp;{{ Lang::get('messages.image_crop.enable') }}</span>
                                          </span>
                                       </button> -->
                                       
                                       <!-- <button type="button" class="btn btn-primary" data-method="disable" title="Disable">
                                          <span class="docs-tooltip" data-toggle="tooltip" title="Disable">
                                             <span class="fa fa-lock">&nbsp;Disable</span>
                                          </span>
                                       </button>

                                       <button type="button" class="btn btn-primary" data-method="enable" title="Enable">
                                          <span class="docs-tooltip" data-toggle="tooltip" title="Enable">
                                             <span class="fa fa-unlock">&nbsp;Enable</span>
                                          </span>
                                       </button> 
                                    </div>-->
                                    

                                    <div class="btn-group">
                                       <button type="button" class="btn btn-primary" data-method="reset" style="display:none;" >
                                          <span class="docs-tooltip" data-toggle="tooltip" >
                                             <span class="fa fa-refresh">&nbsp;Reset</span>
                                          </span>
                                       </button>

                                       <label class="btn btn-primary btn-upload" style="display:none;"  for="inputImage" title="Upload image file">
                                          <input type="file" class="sr-only" id="inputImage" name="file" onchange="initcroper(this.files,this.id);"  accept=".jpg,.jpeg,.png,.gif,.bmp,.tiff">
                                          <span class="docs-tooltip" data-toggle="tooltip" title="Import image with Blob URLs">
                                             <span class="fa fa-upload"></span>
                                           </span>
                                       </label>

                                       <button type="button" class="btn btn-primary" style="display:none;" title="Destroy" onclick="destroyCropper();">
                                          <span class="docs-tooltip" data-toggle="tooltip" title="Destroy">
                                             <span class="fa fa-power-off">&nbsp;Destroy</span>
                                          </span>
                                       </button>

                                       <!-- <button type="button" id="destroy_cropper" class="btn btn-primary" title="{{ Lang::get('messages.image_crop.destroy') }}" onclick="destroyCropper();">
                                          <span class="docs-tooltip" data-toggle="tooltip" title="{{ Lang::get('messages.image_crop.destroy') }}">
                                             <span class="fa fa-power-off">&nbsp;{{ Lang::get('messages.image_crop.destroy') }}</span>
                                          </span>
                                       </button> -->
                                    </div>

                                    <div class="btn-group btn-group-crop" style="display:none;">
                                       <button type="button" class="btn btn-primary" data-method="getCroppedCanvas">
                                          <span class="docs-tooltip" data-toggle="tooltip" title="cropper.getCroppedCanvas()">
                                          Get Cropped Canvas
                                          </span>
                                       </button>
                                       <button type="button" class="btn btn-primary" data-method="getCroppedCanvas" data-option="{ &quot;width&quot;: 160, &quot;height&quot;: 90 }">
                                          <span class="docs-tooltip" data-toggle="tooltip" title="cropper.getCroppedCanvas({ width: 160, height: 90 })">
                                          160&times;90
                                          </span>
                                       </button>
                                       <button type="button" class="btn btn-primary" data-method="getCroppedCanvas" data-option="{ &quot;width&quot;: 320, &quot;height&quot;: 180 }">
                                          <span class="docs-tooltip" data-toggle="tooltip" title="cropper.getCroppedCanvas({ width: 320, height: 180 })">
                                          320&times;180
                                          </span>
                                       </button>
                                    </div>

                                    <!-- Show the cropped image in modal -->
                                    <div class="modal fade docs-cropped" style="display:none;" id="getCroppedCanvasModal" role="dialog" aria-hidden="true" aria-labelledby="getCroppedCanvasTitle" tabindex="-1">
                                       <div class="modal-dialog">
                                          <div class="modal-content">
                                             <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                <h4 class="modal-title" id="getCroppedCanvasTitle">Cropped</h4>
                                             </div>
                                             <div class="modal-body"></div>
                                             <div class="clearfix"></div>
                                             <div class="modal-footer">
                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                <a class="btn btn-primary" id="download" href="javascript:void(0);" download="cropped.jpg">Download</a>
                                             </div>
                                          </div>
                                       </div>
                                    </div><!-- /.modal -->

                               <div class="row custommbbottom">
                                  <div class="btn-group zoom-section docs-buttons">
                                     <button type="button" class="btn btn-primary" data-method="zoom" data-option="0.1" title="zoom">
                                        <span class="docs-tooltip" data-toggle="tooltip" title="zoom">
                                           <span class="fa fa-search-plus"></span>&nbsp;zoom
                                        </span>
                                     </button>

                                     <button type="button" class="btn btn-primary" data-method="zoom" data-option="-0.1" title="Zoom out">
                                        <span class="docs-tooltip" data-toggle="tooltip" title="Zoom out">
                                           <span class="fa fa-search-minus"></span>&nbsp;Zoom out
                                        </span>
                                     </button>
                                     <button type="button" class="btn btn-primary" data-method="clear">
                                          <span class="docs-tooltip" data-toggle="tooltip">
                                             <span class="fa fa-remove"></span>&nbsp;Clear
                                          </span>
                                     </button>
                                     <button type="button" class="btn btn-default" id="modal_footer_close" onclick="closeModal();" data-dismiss="modal">Save</button>
                                  </div>
                               </div>
                                    <!-- <button type="button" class="btn btn-primary"  data-method="getData" data-option data-target="#putData">
                                       <span class="docs-tooltip" data-toggle="tooltip" title="cropper.getData()">
                                       Get Data
                                       </span>
                                    </button>
                                    <button type="button" class="btn btn-primary" data-method="setData" data-target="#putData">
                                       <span class="docs-tooltip" data-toggle="tooltip" title="cropper.setData(data)">
                                       Set Data
                                       </span>
                                    </button>
                                    <button type="button" class="btn btn-primary" data-method="getContainerData" data-option data-target="#putData">
                                       <span class="docs-tooltip" data-toggle="tooltip" title="cropper.getContainerData()">
                                       Get Container Data
                                       </span>
                                    </button>
                                    <button type="button" class="btn btn-primary" data-method="getImageData" data-option data-target="#putData">
                                       <span class="docs-tooltip" data-toggle="tooltip" title="cropper.getImageData()">
                                       Get Image Data
                                       </span>
                                    </button>
                                    <button type="button" class="btn btn-primary" data-method="getCanvasData" data-option data-target="#putData">
                                       <span class="docs-tooltip" data-toggle="tooltip" title="cropper.getCanvasData()">
                                       Get Canvas Data
                                       </span>
                                    </button>
                                    <button type="button" class="btn btn-primary" data-method="setCanvasData" data-target="#putData">
                                       <span class="docs-tooltip" data-toggle="tooltip" title="cropper.setCanvasData(data)">
                                       Set Canvas Data
                                       </span>
                                    </button>
                                    <button type="button" class="btn btn-primary" data-method="getCropBoxData" data-option data-target="#putData">
                                       <span class="docs-tooltip" data-toggle="tooltip" title="cropper.getCropBoxData()">
                                       Get Crop Box Data
                                       </span>
                                    </button>
                                    <button type="button" class="btn btn-primary" data-method="setCropBoxData" data-target="#putData">
                                       <span class="docs-tooltip" data-toggle="tooltip" title="cropper.setCropBoxData(data)">
                                       Set Crop Box Data
                                       </span>
                                    </button>
                                    <button type="button" class="btn btn-primary" data-method="moveTo" data-option="0">
                                       <span class="docs-tooltip" data-toggle="tooltip" title="cropper.moveTo(0)">
                                       0,0
                                       </span>
                                    </button>
                                    <button type="button" class="btn btn-primary" data-method="zoomTo" data-option="1">
                                       <span class="docs-tooltip" data-toggle="tooltip" title="cropper.zoomTo(1)">
                                       100%
                                       </span>
                                    </button>
                                    <button type="button" class="btn btn-primary" data-method="rotateTo" data-option="180">
                                       <span class="docs-tooltip" data-toggle="tooltip" title="cropper.rotateTo(180)">
                                       180°
                                       </span>
                                    </button>
                                    <input type="text" class="form-control" id="putData" placeholder="Get data to here or set data with this value"> -->

                                 </div><!-- /.docs-buttons -->

                                 <div class="docs-toggles show-radio">
                                 <!-- <h3>Toggles:</h3> -->
                                    <div class="btn-group docs-aspect-ratios" data-toggle="buttons">
                                      <label class="btn btn-primary active" id="activeAspectRatio1Default">
                                          <input type="radio" class="sr-only" id="aspectRatiodefault" name="aspectRatio" value="4">
                                          <span class="docs-tooltip" data-toggle="tooltip" title="4X1">
                                             4X1
                                          </span>
                                       </label>
                                       <label class="btn btn-primary active" id="activeAspectRatio1">
                                          <input type="radio" class="sr-only" id="aspectRatio1" name="aspectRatio" value="1.7777777777777777" >
                                          <span class="docs-tooltip" data-toggle="tooltip" title="16X9">
                                             16X9
                                          </span>
                                       </label>
                                       <label class="btn btn-primary" id="activeAspectRatio2">
                                          <input type="radio" class="sr-only" id="aspectRatio2" name="aspectRatio" value="1.3333333333333333">
                                          <span class="docs-tooltip" data-toggle="tooltip" title=" 4X3">
                                            4X3
                                          </span>
                                       </label>
                                       <label class="btn btn-primary" id="activeAspectRatio3">
                                          <input type="radio" class="sr-only" id="aspectRatio3" name="aspectRatio" value="1">
                                          <span class="docs-tooltip" data-toggle="tooltip" title="1X1">
                                            1X1
                                          </span>
                                       </label>
                                       <label class="btn btn-primary" id="activeAspectRatio4">
                                          <input type="radio" class="sr-only" id="aspectRatio4" name="aspectRatio" value="0.6666666666666666">
                                          <span class="docs-tooltip" data-toggle="tooltip" title="2X3">
                                             2X3
                                          </span>
                                       </label>
                                       <label class="btn btn-primary" style="display:none;" id="activeAspectRatio5">
                                          <input type="radio" class="sr-only" id="aspectRatio5" name="aspectRatio" value="NaN">
                                          <span class="fa fa-crop" data-toggle="tooltip" title="NaN">
                                          NaN
                                          </span>
                                       </label>
                                    </div>

                                    <div class="btn-group docs-view-modes" style="display:none;" data-toggle="buttons">
                                       <label class="btn btn-primary active">
                                          <input type="radio" class="sr-only" id="viewMode0" name="viewMode" value="0" checked>
                                          <span class="docs-tooltip" data-toggle="tooltip" title="View Mode 0">
                                             VM0
                                          </span>
                                       </label>
                                       <label class="btn btn-primary">
                                          <input type="radio" class="sr-only" id="viewMode1" name="viewMode" value="1">
                                          <span class="docs-tooltip" data-toggle="tooltip" title="View Mode 1">
                                             VM1
                                          </span>
                                       </label>
                                       <label class="btn btn-primary">
                                          <input type="radio" class="sr-only" id="viewMode2" name="viewMode" value="2">
                                          <span class="docs-tooltip" data-toggle="tooltip" title="View Mode 2">
                                             VM2
                                          </span>
                                       </label>
                                       <label class="btn btn-primary">
                                          <input type="radio" class="sr-only" id="viewMode3" name="viewMode" value="3">
                                          <span class="docs-tooltip" data-toggle="tooltip" title="View Mode 3">
                                          VM3
                                          </span>
                                       </label>
                                    </div>

                                    <div class="dropdown dropup docs-options" style="display:none;">
                                       <button type="button" class="btn btn-primary btn-block dropdown-toggle" id="toggleOptions" data-toggle="dropdown" aria-expanded="true">
                                          Toggle Options
                                          <span class="caret"></span>
                                       </button>

                                       <ul class="dropdown-menu" role="menu" aria-labelledby="toggleOptions">
                                          <li role="presentation">
                                             <label class="checkbox-inline">
                                                <input type="checkbox" name="responsive" checked>
                                                responsive
                                             </label>
                                          </li>
                                          <li role="presentation">
                                             <label class="checkbox-inline">
                                                <input type="checkbox" name="restore" checked>
                                                restore
                                             </label>
                                          </li>
                                          <li role="presentation">
                                             <label class="checkbox-inline">
                                                <input type="checkbox" name="checkCrossOrigin" checked>
                                                checkCrossOrigin
                                             </label>
                                          </li>
                                          <li role="presentation">
                                             <label class="checkbox-inline">
                                                <input type="checkbox" name="checkOrientation" checked>
                                                checkOrientation
                                             </label>
                                          </li>

                                          <li role="presentation">
                                             <label class="checkbox-inline">
                                                <input type="checkbox" name="modal" checked>
                                                modal
                                             </label>
                                          </li>
                                          <li role="presentation">
                                             <label class="checkbox-inline">
                                                <input type="checkbox" name="guides" checked>
                                                guides
                                             </label>
                                          </li>
                                          <li role="presentation">
                                             <label class="checkbox-inline">
                                                <input type="checkbox" name="center" checked>
                                                center
                                             </label>
                                          </li>
                                          <li role="presentation">
                                             <label class="checkbox-inline">
                                                <input type="checkbox" name="highlight" checked>
                                                highlight
                                             </label>
                                          </li>
                                          <li role="presentation">
                                             <label class="checkbox-inline">
                                                <input type="checkbox" name="background" checked>
                                                background
                                             </label>
                                          </li>

                                          <li role="presentation">
                                             <label class="checkbox-inline">
                                                <input type="checkbox" name="autoCrop" checked>
                                                autoCrop
                                             </label>
                                          </li>
                                          <li role="presentation">
                                             <label class="checkbox-inline">
                                                <input type="checkbox" name="movable" checked>
                                                movable
                                             </label>
                                          </li>
                                          <li role="presentation">
                                             <label class="checkbox-inline">
                                                <input type="checkbox" name="rotatable" checked>
                                                rotatable
                                             </label>
                                          </li>
                                          <li role="presentation">
                                             <label class="checkbox-inline">
                                                <input type="checkbox" name="scalable" checked>
                                                scalable
                                             </label>
                                          </li>
                                          <li role="presentation">
                                             <label class="checkbox-inline">
                                                <input type="checkbox" name="zoomable" checked>
                                                zoomable
                                             </label>
                                          </li>
                                          <li role="presentation">
                                             <label class="checkbox-inline">
                                                <input type="checkbox" name="zoomOnTouch" checked>
                                                zoomOnTouch
                                             </label>
                                          </li>
                                          <li role="presentation">
                                             <label class="checkbox-inline">
                                                <input type="checkbox" name="zoomOnWheel" checked>
                                                zoomOnWheel
                                             </label>
                                          </li>
                                          <li role="presentation">
                                             <label class="checkbox-inline">
                                                <input type="checkbox" name="cropBoxMovable" checked>
                                                cropBoxMovable
                                             </label>
                                          </li>
                                          <li role="presentation">
                                             <label class="checkbox-inline">
                                                <input type="checkbox" name="cropBoxResizable" checked>
                                                cropBoxResizable
                                             </label>
                                          </li>
                                          <li role="presentation">
                                             <label class="checkbox-inline">
                                                <input type="checkbox" name="toggleDragModeOnDblclick" checked>
                                                toggleDragModeOnDblclick
                                             </label>
                                          </li>
                                       </ul>
                                    </div><!-- /.dropdown -->
                                    
                                 </div>
                              </div>
                         </div>                         
                        </div>
                      </div>
                  </div>
                  <div class="" id="loader-image-sell-watch" style="display:none;width:100%;height:100%;position: fixed; bottom: 0; left: 0; z-index: 99999; ">
                     <div id="loader2"><!-- <img src="<?php echo url(); ?>/public/frontend/images/loder.png" alt="wait"> --></div>
                     <div class="loader-out"></div>
                  </div>

               <!-- Popup for cropper -->
              <div id="popupdiv"></div>
              <div id="wholebodyloader" class="dataTables_processing" style="display: none;"></div>

    {!! Form::close() !!}
    
    <script type="text/javascript">
    
    var image_info = new FormData();
    var globalfile = '';
    var image_li_counter = 1;
    var count_image = 0;

    function initcroper(files,id,image_preview_div,upload_banner_image,banner_img_error,aspectratio){

      //document.getElementById("image").value = '';
      var get_id = id.split("_");
      var selected_id = get_id[1];
      var file = files[0];
      var reader  = new FileReader();
      var image   = new Image();

      globalfile = file;
      
      image_info.append('file',file);

      reader.readAsDataURL(file);  
      $("#loader-image-sell-watch").show();

      //alert(aspectratio);
      jQuery('#aspectRatiodefault').val(aspectratio);

      reader.onload = function(_file) {
          var show_pop_up = 1;
          image.src    = _file.target.result;              // url.createObjectURL(file);
          image.onload = function() {
          var w = this.width,
          h = this.height,
          t = file.type,                           // ext only: // file.type.split('/')[1],
          n = file.name,
          s = ~~(file.size/1024);
           
          var image_type_arr = t.split("/");
          //cropper.destroy();
          document.getElementById("image").src = '';
          //alert(count_image);

              if(image_type_arr.length<2)
              {
                $('#'+banner_img_error).css('color','red');
                $('#'+banner_img_error).html("Upload Image Only");
                

                //alert("Not a valid image type.Please use jpg/jpeg/png/gif type image.");    
                /*sweetAlert("Oops...", "Not a valid image type.Please use jpg/jpeg/png/gif type image.", "error");*/
                //$('#title_preview').val('');
                $("#loader-image-sell-watch").hide();
                show_pop_up = 0;
                return;
              }
              else{
                $('#'+banner_img_error).text('');
              }

              var image_type = image_type_arr[1].toLowerCase();
              //alert(image_type);
           
              if(!(image_type ==='jpg' ||  image_type ==='jpeg' ||  image_type ==='png' ||  image_type ==='gif'))
              {
                $('#'+banner_img_error).css('color','red');
                //$('#'+banner_img_error).text('Not a valid image type.Please use jpg/jpeg/png/gif type image.');
              
                  $('#'+banner_img_error).html("Upload Image Only");
                //alert("Not a valid image type.Please use jpg/jpeg/png/gif type image.");
                /*sweetAlert("Oops...", "Not a valid image type.Please use jpg/jpeg/png/gif type image.", "error");*/
                //$('#title_preview').val('');
                $("#loader-image-sell-watch").hide();
                show_pop_up = 0;
                return;
              }
              else{
                $('#'+banner_img_error).text('');
              }
              
              if(!t.match("image.*")) {
                return;
              }
                //alert(f.size);
                
              /*if(w<500 && (id != "image_5"))
              {
                $("#loader-image-sell-watch").hide();
                $('#'+banner_img_error).css('color','red');

                $('#'+banner_img_error).html("Image width should be minimum "+w+" px");
                show_pop_up = 0;

                return;
              }
              else{
                $('#'+banner_img_error).text('');
              }

              if(h<550 && (id != "image_5"))
              {
                $("#loader-image-sell-watch").hide();
                $('#'+banner_img_error).css('color','red');

                $('#'+banner_img_error).html("Image height should be minimum "+h+" px");

                show_pop_up = 0;
                return;
              }
              else{
                $('#'+banner_img_error).text('');
              }*/
                
            if(show_pop_up ==1){

                document.getElementById("image").src = image.src;
                $("#test_crop_preview_modal").modal("show");

                $('#image_preview_div').val(image_preview_div);
                $('#upload_banner_name').val(upload_banner_image);
            }
            $("#loader-image-sell-watch").hide();
            $('#'+id).val('');                   
          } // image onload end

          /*alert($('#aspectRatiodefault').val());
          jQuery('#aspectRatiodefault').trigger('click');alert();

          setTimeout(function(){  }, 1000);*/

      } // render onload end /**/  
                               
        $("#dataX").val('');
        $("#dataY").val('');
        $("#dataWidth").val('');
        $("#dataHeight").val('');
        $("#dataRotate").val(0);
        $("#dataScaleX").val('');
        $("#dataScaleY").val('');        
         
      /*$('#activeAspectRatio1').attr("class","btn btn-primary active");
      $('#activeAspectRatio2').attr("class","btn btn-primary");
      $('#activeAspectRatio3').attr("class","btn btn-primary");
      $('#activeAspectRatio4').attr("class","btn btn-primary");
      $('#activeAspectRatio5').attr("class","btn btn-primary");*/
    }

    $('#test_crop_preview_modal').on('shown.bs.modal',function(){
      cropper = new Cropper(image, options);

        jQuery('#aspectRatiodefault').prop('checked','checked').trigger('change');
    });
    
  
    var pre_image_id;
    var curr_image_id;
    var rotate_angle = 0;

    function closeModal(image_id,image_name){
      
      image_info = new FormData();
      image_info.append('file',globalfile);
      //alert(image_id+" "+image_name);
      //dataX,dataY,dataWidth,dataHeight,dataRotate,dataScaleX,dataScaleY
      var dataX       = $("#dataX").val();
      var dataY       = $("#dataY").val();
      var dataWidth   = $("#dataWidth").val();
      var dataHeight  = $("#dataHeight").val();
      var dataRotate  = $("#dataRotate").val();
      var dataScaleX  = $("#dataScaleX").val();
      var dataScaleY  = $("#dataScaleY").val();
      image_info.append('dataX',dataX);
      image_info.append('dataY',dataY);
      image_info.append('dataWidth',dataWidth);
      image_info.append('dataHeight',dataHeight);
      image_info.append('dataRotate',dataRotate);
      image_info.append('dataScaleX',dataScaleX);
      image_info.append('dataScaleY',dataScaleY);

      image_info.append('_token','{!! csrf_token() !!}');
      $("#loader-image-sell-watch").show();


      $.ajax({
        url:'<?php echo url(); ?>/admin/upload-image-crop',
        type:'POST',
        data :image_info,
        processData: false,
        contentType: false,
        success:function(dataname){
          //alert(dataname);
          $("#loader-image-sell-watch").hide();

          $('#'+$('#image_preview_div').val()).html('<img class="img-responsive" src="<?php echo url(); ?>/uploads/home_page_images/' + dataname + '" />');
          $('#'+$('#upload_banner_name').val()).val(dataname);

          if($('#image_preview_div').val()=='image_preview_div_women')
          $('label[for=upload_banner_image_women]').remove();
        if($('#image_preview_div').val()=='image_preview_div_men')
          $('label[for=upload_banner_image]').remove();

        //console.log($('#image_preview_div').val());

          $("#image").attr("src","");
            
          document.getElementById('image').value = null;
          
          $("#dataX").val('');
          $("#dataY").val('');
          $("#dataWidth").val('');
          $("#dataHeight").val('');
          $("#dataRotate").val('');
          $("#dataScaleX").val('');
          $("#dataScaleY").val('');

          cropper.destroy();
          //$('<div id=""></div>').append('')
          
        }
      });

    }

    function closeCropModal(){
      $('#files').val('');
      $("#loader-image-sell-watch").hide();
      $("#test_crop_preview_modal").modal("hide");
      document.getElementById('image').value = null;
      destroyCropper();
      
    }
    function destroyCropper(){
      //$("").cropper("destroy");
      cropper.destroy();
      /*$('#activeAspectRatio1').attr("class","btn btn-primary active");
      $('#activeAspectRatio2').attr("class","btn btn-primary");
      $('#activeAspectRatio3').attr("class","btn btn-primary");
      $('#activeAspectRatio4').attr("class","btn btn-primary");
      $('#activeAspectRatio5').attr("class","btn btn-primary");*/
      $("#dataX").val('');
      $("#dataY").val('');
      $("#dataWidth").val('');
      $("#dataHeight").val('');
      $("#dataRotate").val('');
      $("#dataScaleX").val('');
      $("#dataScaleY").val('');   
      //cropper = new Cropper(image, options);
      
    }   


  </script>
  <style>
    #actions .docs-toggles {
      width: 0;
      height: 0;
      overflow: hidden;
    }
    #actions .docs-toggles.show-radio {
        width: auto;
        height: auto;
    }
  </style>
    @stop

    