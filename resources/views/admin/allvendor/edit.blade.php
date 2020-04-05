{!! HTML::script('resources/assets/js/ckeditor/ckeditor.js') !!} 
@extends('admin/layout/admin_template')

@section('content')

<!-- jQuery Form Validation code -->
  <script>
  
  // When the browser is ready...
  $(function() {
  
    // Setup form validation on the #register-form element
    $("#vendor_form").validate({
        
        ignore: [],
        // Specify the validation rules
        rules: {
            salecommission: "required",
            
        },
        
        // Specify the validation error messages
        messages: {
            name: "Please enter vendor sale commission.",
          
        },               

        submitHandler: function(form) {
            form.submit();
        }
    });

  });
  
  </script>

    
    {!! Form::model($vendor,array('method' => 'POST','class'=>'form-horizontal row-fluid','files'=>true,'id'=>'vendor_form','url' => 'admin/vendor/edit/'.$vendor_id)) !!}

    

    <div class="form-group form-row">
        <label class="col-sm-2 col-form-label" for="basicinput">Vendor Sale Commission</label>

        <div class="col-sm-10">
             {!! Form::text('salecommission',null,['class'=>'span8','id'=>'name']) !!}
        </div>
    </div>

     

    <div class="form-group form-row">
              <label class="col-sm-2 col-form-label" for="basicinput">Vendor Logo <span class="required" style="color: red">*</span></label>
                     <div class="col-sm-10">
                           <span id="fileInput"><input type='file' id="logo_image" class="span8" name="logo_image"  /></span>
                   <!-- <span id="img_msg" style="color:red"></span> -->
            </div>
        </div>

        <div class="form-group form-row">
                <label class="col-sm-2 col-form-label" for="basicinput"></label>
                <div class="col-sm-10">
                   <?php if(($vendor->logo!="") && (file_exists("public/uploads/brand/logo/".$vendor->logo)))
                    {
                    ?>
                        <img id="blah1" src="<?php echo url(); ?>/public/uploads/brand/logo/<?php echo $vendor->logo ?>" width="203" height="184"/>
                    <?php
                    }
                    else
                    {
                    ?>
                        <img id="blah1" src="<?php echo url(); ?>/public/uploads/no-image.png" width="203" height="184">
                    <?php
                    }
                    ?>
              </div>
            </div>
        <div class="form-group form-row">
            <label class="col-sm-2 col-form-label" for="basicinput"></label>
            <div class="col-sm-10">
              <img id="blah" width="203" height="184" style="display:none"/>
            </div>
        </div>
    

    <div class="form-group form-row">
        <div class="col-sm-12 text-right">
            {!! Form::submit('Save', ['class' => 'btn btn-blue']) !!}
           
             <a href="{!! url('admin/vendor/list')!!}" class="btn btn-ylw">Back</a>
           
        </div>
    </div>
        
    {!! Form::close() !!}

    <script type="text/javascript">

      function readURL(input) {

      if (input.files && input.files[0]) {
        var reader = new FileReader();
    
        reader.onload = function (e) {
        
          $('#blah').attr('src', e.target.result);
        }
    
        reader.readAsDataURL(input.files[0]);
      }
    }
      
      $("#logo_image").change(function(e)
    {
    
      var file, img;
      var _URL = window.URL || window.webkitURL;
      
      if ((file = this.files[0]))
      {
        img = new Image();
        img.onload = function() {
        
        $('#new_width').val(this.width);
        $('#new_height').val(this.height);
            
              $('#img_msg').html("");
              $('#blah').show();
              $('#blah1').hide();
            // }
            //alert(this.width + " " + this.height);
        };
       
        img.src = _URL.createObjectURL(file);


      }
      
    readURL(this);
    });

    </script>
@stop