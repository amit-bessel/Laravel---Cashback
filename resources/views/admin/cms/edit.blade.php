{!! HTML::script('resources/assets/js/ckeditor/ckeditor.js') !!} 
@extends('admin/layout/admin_template')

@section('content')

<!-- jQuery Form Validation code -->
  <script>
  
  // When the browser is ready...
  $(function() {
  
    // Setup form validation on the #register-form element
    $("#cms_form").validate({
        
        ignore: [],
        // Specify the validation rules
        rules: {
            title: "required",
            description: {
                        required: function() 
                        {
                        CKEDITOR.instances.description.updateElement();
                        }
                    }
            
        },
        
        // Specify the validation error messages
        messages: {
            title: "Please enter title.",
            description: "Please enter description."
        },               

        submitHandler: function(form) {
            form.submit();
        }
    });

  });
  
  </script>

    
    {!! Form::model($cms,array('method' => 'PATCH','class'=>'form-horizontal row-fluid','route'=>array('admin.cms.update',$cms->id))) !!}

    <div class="form-group form-row">
        <label class="col-sm-2 col-form-label" for="basicinput">Page Name</label>
        <div class="col-sm-10">
             {!! Form::text('page_name',null,['class'=>'span8','id'=>'page_name']) !!}
        </div>
    </div>

    <div class="form-group form-row">
        <label class="col-sm-2 col-form-label" for="basicinput">Page Title</label>
        <div class="col-sm-10">
             {!! Form::text('page_title',null,['class'=>'span8','id'=>'page_title']) !!}
        </div>
    </div>

    <div class="form-group form-row">
        <label class="col-sm-2 col-form-label" for="basicinput">Description</label>

        <div class="col-sm-10">
             {!! Form::textarea('description_eng',null,['class'=>'span8 ckeditor','id'=>'description']) !!}
        </div>
    </div>

     <div class="form-group form-row" style="display:none;">
        <label class="col-sm-2 col-form-label" for="basicinput">Description in Arabic</label>

        <div class="col-sm-10">
             {!! Form::textarea('description_arabic',null,['class'=>'span8 ckeditor','id'=>'description']) !!}
        </div>
     </div>

   <div class="form-group form-row">
        <label class="col-sm-2 col-form-label" for="basicinput">Meta Description</label>

        <div class="col-sm-10">
             {!! Form::textarea('meta_description',null,['class'=>'span8','id'=>'meta_description']) !!}
        </div>
    </div>
            
    <div class="form-group form-row">
        <label class="col-sm-2 col-form-label" for="basicinput">Meta Keyword</label>

        <div class="col-sm-10">
             {!! Form::text('meta_keyword',null,['class'=>'span8','id'=>'meta_keyword']) !!}
        </div>
    </div> 
            
    <div class="form-group form-row">
        <label class="col-sm-2 col-form-label" for="basicinput">Meta Title</label>

        <div class="col-sm-10">
             {!! Form::text('meta_title',null,['class'=>'span8','id'=>'meta_keyword']) !!}
        </div>
    </div> 
    <div class="form-group form-row">
        <div class="col-sm-12 text-right">
            {!! Form::submit('Save', ['class' => 'btn btn-blue']) !!}
           
             <a href="{!! url('admin/cms')!!}" class="btn btn-ylw">Back</a>
           
        </div>
    </div>
        
    {!! Form::close() !!}
@stop