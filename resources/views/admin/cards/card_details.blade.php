{!! HTML::script('resources/assets/js/ckeditor/ckeditor.js') !!} 
{!! HTML::script('https://js.stripe.com/v1/') !!} 
@extends('admin/layout/admin_template')

@section('content')
    
    <!-- jQuery Form Validation code -->
  <script>
   //When the browser is ready...
   $(function() {
     // Setup form validation on the #register-form element
    $("#adminCardDtlsFrm").validate({
    
        ignore: [],
        // Specify the validation rules
        rules: {
           
            card_no: {
                required: true,
                number: true,
                minlength: 15,
                maxlength: 19
            },
            exp_month: {
                required: true
            },
            exp_year: {
                required: true
            },
            exp_cvc: {
                required: true,
                number: true,
                minlength: 3,
                maxlength: 3
            },
            name_on_card: {
                required: true
            },
        
        },
        messages: {
                card_no: {
                    minlength: "Credit card must be 15-19 digits/numbers",
                    maxlength: "Credit card must be 15-19 digits/numbers",
                }
        },

        submitHandler: function(form) {
            form.submit();
        }
    });

   });
  </script>
    {!! Form::open(['url' => 'admin/cards/admin-card-details','method'=>'POST', 'files'=>true,'class'=>'form-horizontal row-fluid','id'=>'adminCardDtlsFrm']) !!}
   
    <div class="control-group">
        <label class="control-label" for="basicinput">Card Number</label>

        <div class="controls">
             {!! Form::text('card_no',null,['class'=>'span8','id'=>'card_no','required'=>true]) !!}
        </div>
    </div>


    <div class="control-group">
        <label class="control-label" for="basicinput">Exp Month</label>

        <div class="controls">
            <select name="exp_month" id="exp_month" class="span8" id="exp_month" required>
                <option value="">Month</option>
                <?php
                    for($month=1;$month<=12;$month++){
                        $month  = ($month<10)?'0'.$month:$month;
                        $date   = "2000-".$month."-01";
                        ?>
                        <option value="<?php echo $month; ?>"><?php echo date('F',strtotime($date))?></option>
                        <?php
                    }
                ?>
            </select>
        </div>
    </div>

    <div class="control-group">
        <label class="control-label" for="basicinput">Exp Year</label>

        <div class="controls">
            <select name="exp_year" id="exp_year" class="span8" id="exp_year" required>
                <option value="">Year</option>
                <?php
                $year = date('Y');
                $year_upto = $year+40;
                    for($year=date('Y');$year<=$year_upto;$year++){
                        ?>
                        <option value="<?php echo $year; ?>"><?php echo $year ?></option>
                        <?php
                    }
                ?>
            </select>
        </div>
    </div>

    <div class="control-group">
        <label class="control-label" for="basicinput">CVC</label>

        <div class="controls">
            {!! Form::text('exp_cvc',null,['class'=>'span8','id'=>'exp_cvc','required'=>true]) !!}
        </div>
    </div>

    <div class="control-group">
        <label class="control-label" for="basicinput">Name on card</label>

        <div class="controls">
            {!! Form::text('name_on_card',null,['class'=>'span8','id'=>'name_on_card','required'=>true]) !!}
        </div>
    </div>

    <div class="control-group">
        <div class="controls">
            {!! Form::submit('Save', ['class' => 'btn']) !!}
           
             <a href="{!! url('admin/cards/list')!!}" class="btn">Back</a>
           
        </div>
    </div>
        
    {!! Form::close() !!}
    
@stop