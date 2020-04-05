{!! HTML::script('resources/assets/js/ckeditor/ckeditor.js') !!} 

@extends('admin/layout/admin_template')

@section('content')

<!-- jQuery Form Validation code -->
  <script>
  
  // When the browser is ready...
  $(function() {
    $("#cms_form").validate({
        ignore: [],
        // Specify the validation rules
        rules: {
			    name: {
            required:true,
          },
          arabic_name:{
            required: true,
          },
    			hid_frm_submit_res:{
    				equalTo:"#hid_validate_res",
    			},
        },
      submitHandler: function(form) {
            form.submit();
        }
    });

  }); 
  </script>

  @if(Session::has('failure_message'))
<p class="alert {{ Session::get('alert-class', 'alert-danger') }}">{{ Session::get('failure_message') }}</p>
@endif

        {!! Form::open(['url' => 'admin/city/add-city/','method'=>'POST', 'files'=>true,'class'=>'form-horizontal row-fluid','id'=>'cms_form']) !!}
            
            <div class="control-group" style="display:block;">
            {!! Html::decode(Form::label('name','City Name In English: <span class="required" style="color: red">*</span>',['class' => 'control-label'])) !!}
              
              <div class="controls">
               <?php echo Form::text('name', null, ['class' => 'span8', 'id'=>'name']); ?>
              </div>
            </div>

            <div class="control-group" style="display:block;">
            {!! Html::decode(Form::label('name','City Name In Arabic: <span class="required" style="color: red">*</span>',['class' => 'control-label'])) !!}
            
              <div class="controls">
                <?php echo Form::text('arabic_name', null, ['class' => 'span8', 'id'=>'arabic_name']); ?>
              </div>
            </div>

            <div class="control-group">
              <div class="controls">
                {!! Form::submit('Save',array('class'=>'btn','name'=>'action','value'=>'save')) !!}
                <a href="{!! url('admin/city/list/')!!}" class="btn">Back</a>
              </div>
            </div>
                
        
        {!! Form::close() !!}
    @stop
    
    