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
            arabic_name: "required",
            name: "required",
        },
       submitHandler: function(form) {
            form.submit();
        }
    });

  });
 
  </script>
        {!! Form::open(['url' => 'admin/city/add-city/'.$city_details->id,'method'=>'POST', 'files'=>true,'class'=>'form-horizontal row-fluid','id'=>'cms_form']) !!}
            
            <div class="control-group" style="display:block;">
              {!! Html::decode(Form::label('name','City Name In English: <span class="required" style="color: red">*</span>',['class' => 'control-label'])) !!}
              <div class="controls">
               <?php echo Form::text('name', $city_details->city_eng, ['class' => 'span8', 'id'=>'name']); ?>
              </div>
            </div>

            <div class="control-group" style="display:block;">
              {!! Html::decode(Form::label('name','City Name In Arabic: <span class="required" style="color: red">*</span>',['class' => 'control-label'])) !!}
              <div class="controls">
                <?php echo Form::text('arabic_name', $city_details->city_arabic, ['class' => 'span8', 'id'=>'arabic_name']); ?>
              </div>
            </div>

           <div class="control-group">
                <div class="controls">
                    {!! Form::submit('Update',array('class'=>'btn','name'=>'action','value'=>'edit')) !!}
                    <a href="{!! url('admin/city/list/')!!}" class="btn">Back</a>
                </div>
            </div>
                
        
        {!! Form::close() !!}
    @stop
    
    