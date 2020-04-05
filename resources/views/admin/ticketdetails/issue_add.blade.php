@extends('admin/layout/admin_template')
@section('content')
<script>

	$(function() {
  
    // Setup form validation on the #register-form element
    $("#issueadd").validate({
        
        ignore: [],
        rules: {
            issue_type: "required",
        },
        
        // Specify the validation error messages
        messages: {
            issue_type: "Issue type is required"
          },
                       

        submitHandler: function(form) {
            form.submit();
        }
    });

  });
</script>
@if(Session::has('success_message'))
<p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('success_message') }}</p>
@endif
@if(Session::has('failure_message'))
<p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('failure_message') }}</p>
@endif
{!! Form::open(['url' => 'admin/ticket_issue/add-issue','method'=>'POST', 'files'=>true, 'id'=>'issueadd']) !!}
 	
 	<div class="form-group form-row">
        <label class="col-sm-2 col-form-label" for="basicinput">Issue <span class="required" style="color: red">*</span></label>
        <div class="col-sm-10">
            {!! Form::text('issue_type','',['class'=>'form-control','id'=>'issue_type']) !!}
        </div>
	</div>
	<div class="form-group form-row">
        <div class="col-sm-12 text-right">
            {!! Form::submit('Save', ['class' => 'btn btn-blue']) !!}
            <a href="{!! url('admin/ticket_issue_list')!!}" class="btn btn-ylw">Back</a>
        </div>
    </div>
{!! Form::close() !!}

@stop