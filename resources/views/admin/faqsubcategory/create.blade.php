{!! HTML::script('resources/assets/js/ckeditor/ckeditor.js') !!} 

@extends('admin/layout/admin_template')

@section('content')

<!-- jQuery Form Validation code -->
  <script>
  
  // When the browser is ready...
  $(function() {
  
    // Setup form validation on the #register-form element
    $("#faq_form").validate({
        
        ignore: [],
        // Specify the validation rules
        rules: {
            subcategory: "required",
            faqcatid: "required",
            
            
        },
        
        // Specify the validation error messages
        messages: {
            subcategory: "Please enter subcate.",
            faqcatid: "Please select faq category."
        },               

        submitHandler: function(form) {
            form.submit();
        }
    });

  });
  
  </script>
        {!! Form::open(['url' => 'admin/faqsubcategory','method'=>'POST', 'files'=>true,'class'=>'form-horizontal row-fluid','id'=>'faq_form']) !!}

        

        <div class="form-group form-row">
                <label class="col-sm-2 col-form-label" for="basicinput">Faq Category *</label>

                <div class="col-sm-10">
                    
                            <select class="custom-select" name="faqcatid">
                            <option value="">Select Category</option>
                                <?php
                    if(!empty($FaqCategory)){

                        foreach ($FaqCategory as $key => $value) {
                            ?>
                                <option value="<?php echo $value->id;?>">
                                    <?php echo $value->name;?>
                                </option>
                                <?php
                        }
                    }
                    ?>

                            </select>
                            
                </div>
            </div>    


        <div class="form-group form-row">
                <label class="col-sm-2 col-form-label" for="basicinput">Sub Category *</label>

                <div class="col-sm-10">
                     {!! Form::text('subcategory',null,['class'=>'span8','id'=>'subcategory']) !!}
                </div>
            </div>


            <div class="form-group form-row">
                <div class="col-sm-12  text-right">
                    {!! Form::submit('Save', ['class' => 'btn btn-blue']) !!}
                   
                     <a href="{!! url('admin/faq')!!}" class="btn btn-ylw">Back</a>
                   
                </div>
            </div>
        
        {!! Form::close() !!}

    @stop