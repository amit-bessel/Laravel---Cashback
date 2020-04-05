{!! HTML::script('resources/assets/js/ckeditor/ckeditor.js') !!}
@extends('admin/layout/admin_template')

@section('content')




	

		
		<div class="form-horizontal row-fluid">


		<div class="control-group" style="display:block;">
			{!! Html::decode(Form::label('Category','Category:',['class' => 'control-label'])) !!}
				
			<div class="controls">
				<?php echo strip_tags($faq_details->faqCategory->name);?>
			</div>
				
		</div>


		<div class="control-group" style="display:block;">
			{!! Html::decode(Form::label('Question','Question:',['class' => 'control-label'])) !!}
				
			<div class="controls">
				<?php echo strip_tags($faq_details->question);?>
			</div>
				
		</div>


		<div class="control-group" style="display:block;">
			{!! Html::decode(Form::label('Answer','Answer:',['class' => 'control-label'])) !!}
				
			<div class="controls">
				<?php echo strip_tags(html_entity_decode($faq_details->answer));?>
			</div>
				
		</div>	

		<div class="control-group" style="display:block;">
			{!! Html::decode(Form::label('name','Created at:',['class' => 'control-label'])) !!}
				
			<div class="controls">
				{{$faq_details->created_at}}
			</div>
				
		</div>

		<div class="control-group" style="display:block;">
			{!! Html::decode(Form::label('name','Updated at:',['class' => 'control-label'])) !!}
				
			<div class="controls">
				{{$faq_details->updated_at}}
			</div>
				
		</div>

		
		
		<div class="control-group">
		
			<div class="controls">
				<a href="{!! url('admin/faq')!!}" class="btn">Back</a>
			</div>
				
		</div>

	</div>
	

@stop
    
    