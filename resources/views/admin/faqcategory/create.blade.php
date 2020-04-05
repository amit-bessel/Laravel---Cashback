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
            faqcat: "required",
            
            
            
        },
        
        // Specify the validation error messages
        messages: {
            faqcat: "Please enter faq category.",
                
        },               

        submitHandler: function(form) {
            form.submit();
        }
    });

  });
  
  </script>
        {!! Form::open(['url' => 'admin/faqcategory','method'=>'POST', 'files'=>true,'class'=>'form-horizontal row-fluid','id'=>'faq_form']) !!}

        

         


            <div class="form-group form-row">
                <label class="col-sm-2 col-form-label" for="basicinput">Category Name *</label>

                <div class="col-sm-10">
                     {!! Form::text('faqcat',null,['class'=>'span8','id'=>'faqcat']) !!}
                </div>
            </div>

            

            <div class="form-group form-row">
                <div class="col-sm-12  text-right">
                    {!! Form::submit('Save', ['class' => 'btn btn-blue']) !!}
                   
                     <a href="{!! url('admin/faqcategory')!!}" class="btn btn-ylw">Back</a>
                   
                </div>
            </div>
        
        {!! Form::close() !!}

    @stop