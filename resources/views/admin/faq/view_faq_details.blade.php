{!! HTML::script('resources/assets/js/ckeditor/ckeditor.js') !!}
@extends('admin/layout/admin_template')

@section('content')





		<div class="form-group form-row">
			{!! Html::decode(Form::label('Category','Category:',['class' => 'col-sm-2 col-form-label'])) !!}
				
			<div class="col-sm-10">
				<?php echo strip_tags($faq_details->faqCategory->name);?>
			</div>
				
		</div>


		<div class="form-group form-row" >
			{!! Html::decode(Form::label('Question','Question:',['class' => 'col-sm-2 col-form-label'])) !!}
				
			<div class="col-sm-10">
				<?php echo strip_tags($faq_details->question);?>
			</div>
				
		</div>


		<div class="form-group form-row">
			{!! Html::decode(Form::label('Answer','Answer:',['class' => 'col-sm-2 col-form-label'])) !!}
				
			<div class="col-sm-10">
				<?php echo strip_tags(html_entity_decode($faq_details->answer));?>
			</div>
				
		</div>	

		<div class="form-group form-row" >
			{!! Html::decode(Form::label('name','Created at:',['class' => 'col-sm-2 col-form-label'])) !!}
				
			<div class="col-sm-10">
				{{$faq_details->created_at}}
			</div>
				
		</div>

		<div class="form-group form-row" >
			{!! Html::decode(Form::label('name','Updated at:',['class' => 'col-sm-2 col-form-label'])) !!}
				
			<div class="col-sm-10">
				{{$faq_details->updated_at}}
			</div>
				
		</div>

		
		
		<div class="form-group form-row">
		
			<div class="col-sm-10">
				<a href="{!! url('admin/faq')!!}" class="btn btn-ylw">Back</a>
			</div>
				
		</div>

	
	

@stop
    
    