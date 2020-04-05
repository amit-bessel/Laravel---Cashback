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
            question: "required",
            answer: {
                        required: function() 
                        {
                        CKEDITOR.instances.answer.updateElement();
                        }
                    }
        },
        
        // Specify the validation error messages
        messages: {
            question: "Please enter question.",
            answer: "Please enter answer."
        },               

        submitHandler: function(form) {
            form.submit();
        }
    });

  });
  
  </script>

    
    {!! Form::model($faq,array('method' => 'PATCH','class'=>'form-horizontal row-fluid','route'=>array('admin.faq.update',$faq->id))) !!}
   

    <div class="form-group form-row">
                <label class="col-sm-2 col-form-label" for="basicinput">Faq Category *</label>

                <div class="col-sm-10">
                    
                            <select name="faqcatid">
                           
                                <?php
                    if(!empty($FaqCategory)){

                        foreach ($FaqCategory as $key => $value) {
                            ?>
                                <option value="<?php echo $value->id;?>" <?php if($faq->faqcategories_id==$value->id){?> selected="selected"<?php }?>>
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
        <label class="col-sm-2 col-form-label" for="basicinput">Question</label>

        <div class="col-sm-10">
             {!! Form::text('question',null,['class'=>'span8','id'=>'question']) !!}
        </div>
    </div>

    <div class="form-group form-row">
        <label class="col-sm-2 col-form-label" for="basicinput">Description</label>

        <div class="col-sm-10">
             {!! Form::textarea('answer',null,['class'=>'span8 ckeditor','id'=>'answer']) !!}
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