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
            name: "required",
            
        },
        
        // Specify the validation error messages
        messages: {
            name: "Please enter faq category name.",
          
        },               

        submitHandler: function(form) {
            form.submit();
        }
    });

  });
  
  </script>

    
    {!! Form::model($faq,array('method' => 'PATCH','class'=>'form-horizontal row-fluid','id'=>'faq_form','route'=>array('admin.faqcategory.update',$faq->id))) !!}
   

   


    <div class="form-group form-row">
        <label class="col-sm-2 col-form-label" for="basicinput">Faq Category</label>

        <div class="col-sm-10">
             {!! Form::text('name',null,['class'=>'span8','id'=>'name']) !!}
        </div>
    </div>

    

    <div class="form-group form-row">
        <div class="col-sm-12 text-right">
            {!! Form::submit('Save', ['class' => 'btn btn-blue']) !!}
           
             <a href="{!! url('admin/faqcategory')!!}" class="btn btn-ylw">Back</a>
           
        </div>
    </div>
        
    {!! Form::close() !!}
@stop