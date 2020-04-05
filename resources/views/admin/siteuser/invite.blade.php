{!! HTML::script('resources/assets/js/ckeditor/ckeditor.js') !!} 

@extends('admin/layout/admin_template')

@section('content')

<!-- jQuery Form Validation code -->
  <script>
  
  // When the browser is ready...
  $(function() {
  
    // Setup form validation on the #register-form element
    $("#inviteform").validate({
        
        ignore: [],
        // Specify the validation rules
        rules: {
            refercode: "required",
            name: "required",
            email: "required",
            
            
        },
        
        // Specify the validation error messages
        messages: {
            refercode: "Please enter refer code.",
            name: "Please enter name.",
            email: "Please enter email."
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
                <p id="login_error_msg"></p>
                @if(Session::has('success_message'))
                  <p class="alert {{ Session::get('alert-class', 'alert-success') }}">{{ Session::get('success_message') }}</p>
                @endif
        {!! Form::open(['url' => 'admin/siteuser/inviteinsert','method'=>'POST', 'files'=>true,'class'=>'form-horizontal row-fluid','id'=>'inviteform']) !!}
        <?php
        $remembertoken=rand().time();
        ?>
        
        <input type="hidden" name="remembertoken" value="<?php echo $remembertoken;?>">
          
         <div class="form-group form-row">
                <label class="col-sm-2 col-form-label" for="basicinput">Super affiliate Code *</label>

                <div class="col-sm-10">
                     <input type="text" name="refercode" value="<?php echo $code;?>" class="span8" readonly="readonly">
                </div>
            </div>

        <div class="form-group form-row">
                <label class="col-sm-2 col-form-label" for="basicinput">Name *</label>

                <div class="col-sm-10">
                     {!! Form::text('name',null,['class'=>'span8','id'=>'name']) !!}
                </div>
            </div>

            <div class="form-group form-row">
                <label class="col-sm-2 col-form-label" for="basicinput">Email *</label>

                <div class="col-sm-10">
                     {!! Form::text('email',null,['class'=>'span8','id'=>'email']) !!}
                </div>
            </div>

            <div class="form-group form-row">

      <div class="col-sm-6 text-left">
            <?php
            $twitmsg="Your super affiliate refer code is ".$code;
            ?>
           <!--  <a class="twitter-share-button"
            href="https://twitter.com/intent/tweet?text=<?php //echo $twitmsg;?>"
            data-size="large"> <i class="fa fa-twitter" aria-hidden="true"></i> Tweet</a> -->

            </div>

                <div class="col-sm-6 text-right">
                    {!! Form::submit('Save', ['class' => 'btn btn-blue']) !!}
                   
                     <!-- <a href="{!! url('admin/faq')!!}" class="btn btn-ylw">Back</a> -->
                   
                </div>
            


            </div>

        {!! Form::close() !!}

    @stop