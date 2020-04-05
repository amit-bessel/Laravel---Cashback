@extends('admin/layout/admin_template')
@section('content')

  
<div class="content-wrapper"> <!-- Content Wrapper. Contains page content -->

  <section class="content"><!-- Main content -->
    <div class="box-body">
    @if(Session::has('success_message'))
    <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('success_message') }}</p>
    @endif
    @if(Session::has('failure_message'))
    <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('failure_message') }}</p>
    @endif
      <div class="row">
      {!! Form::open(['url' => 'admin/content/question-edit','method'=>'POST', 'files'=>true,'id'=>'cms']) !!}
        <div class="col-sm-12 col-md-6">
          <div class="form-group">
            <label>Question </label>
              {!! Form::text('question',$faq_arr->question,['class'=>'form-control ','id'=>'question','required'=>true]) !!}
           </div>

           <div class="form-group">
            <label>Question </label>
              {!! Form::textarea('answer',$faq_arr->answer,['class'=>'form-control ','id'=>'answer','required'=>true]) !!}
           </div>
        <input type="hidden" name="id" value="{{ $faq_arr->id }}">
           <div class="form-group">
              <label></label>
              <div class="col-md-2 mt-o">
               {!! Form::submit('Save',array('class'=>'btn btn-block btn-success','name'=>'action_plan','id'=>'add_plan','value'=>'save')) !!}
              </div>
            </div>             
        </div>
        {!! Form::close() !!}
        {!! HTML::script(url().'/public/backend/scripts/user.js') !!}
      </div><!-- /.row -->
    </div>
</section> <!-- /.content -->
</div><!-- /.content-wrapper -->
    @endsection