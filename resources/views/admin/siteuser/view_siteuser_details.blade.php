{!! HTML::script('resources/assets/js/ckeditor/ckeditor.js') !!}
@extends('admin/layout/admin_template')

@section('content')


<!-- jQuery Form Validation code -->
 <div class="form-horizontal row-fluid">

		<div class="form-group form-row">
			{!! Html::decode(Form::label('name','Name:',['class' => 'col-sm-2 col-form-label'])) !!}
				
			<div class="col-sm-10">
				{{$user_details->firstname.' '.$user_details->lastname}}
			</div>
				
		</div>
		<?php
		if(!empty($user_details->profileimage)){?>
		<div class="form-group form-row">
			{!! Html::decode(Form::label('name','Profile Image:',['class' => 'col-sm-2 col-form-label'])) !!}
				<img src="<?php echo URL::to('/');?>/public/backend/profileimage/<?php echo $user_details->profileimage;?>" style="height: 110px; width: 110px; margin: 10px;border-radius: 5px;">
			<div class="col-sm-10">
			
			</div>
				
		</div>
		<?php }?>
		<div class="form-group form-row">
			{!! Html::decode(Form::label('name','Email:',['class' => 'col-sm-2 col-form-label'])) !!}
				
			<div class="col-sm-10">
				{{$user_details->email}}
			</div>
				
		</div>
		
		<!-- <div class="form-group form-row">
			{!! Html::decode(Form::label('name','Phone Number:',['class' => 'col-sm-2 col-form-label'])) !!}
				
			<div class="col-sm-10">
				{{$user_details->phoneno}}
			</div>
				
		</div> -->

		<!-- <div class="form-group form-row">
			{!! Html::decode(Form::label('name','Mobile Number:',['class' => 'col-sm-2 col-form-label'])) !!}
				
			<div class="col-sm-10">
				{{$user_details->mobileno}}
			</div>
				
		</div> -->


		<div class="form-group form-row">
			{!! Html::decode(Form::label('name','Date of birth:',['class' => 'col-sm-2 col-form-label'])) !!}
				
			<div class="col-sm-10">
				{{$user_details->dob}}
			</div>
				
		</div>

		
		<div class="form-group form-row">
			{!! Html::decode(Form::label('name','Address :',['class' => 'col-sm-2 col-form-label'])) !!}
				
			<div class="col-sm-10">
				{{$user_details->address}}
			</div>
				
		</div>

		<div class="form-group form-row">
			{!! Html::decode(Form::label('name','Country :',['class' => 'col-sm-2 col-form-label'])) !!}
				
			<div class="col-sm-10">
				{{$user_details->country}}
			</div>
				
		</div>

		<div class="form-group form-row">
			{!! Html::decode(Form::label('name','State :',['class' => 'col-sm-2 col-form-label'])) !!}
				
			<div class="col-sm-10">
				{{$user_details->state}}
			</div>
				
		</div>

		<div class="form-group form-row">
			{!! Html::decode(Form::label('name','City :',['class' => 'col-sm-2 col-form-label'])) !!}
				
			<div class="col-sm-10">
				{{$user_details->city}}
			</div>
				
		</div>

		<div class="form-group form-row">
			{!! Html::decode(Form::label('name','Zipcode :',['class' => 'col-sm-2 col-form-label'])) !!}
				
			<div class="col-sm-10">
				{{$user_details->zipcode}}
			</div>
				
		</div>
		

		<div class="form-group form-row">
			{!! Html::decode(Form::label('name','Status :',['class' => 'col-sm-2 col-form-label'])) !!}
				
			<div class="col-sm-10">
				<?php if($user_details->status==1){ echo "active" ; }else {  echo "Inactive"; }?>
			</div>
				
		</div>

		<div class="form-group form-row">
			{!! Html::decode(Form::label('name','Created at:',['class' => 'col-sm-2 col-form-label'])) !!}
				
			<div class="col-sm-10">
				{{$user_details->created_at}}
			</div>
				
		</div>

		<div class="form-group form-row">
			{!! Html::decode(Form::label('name','Updated at:',['class' => 'col-sm-2 col-form-label'])) !!}
				
			<div class="col-sm-10">
				{{$user_details->updated_at}}
			</div>
				
		</div>

		
		
		<div class="form-group form-row">
		
			<div class="col-sm-12">
				<a href="{!! url('admin/siteuser/list')!!}" class="btn btn-ylw">Back</a>
				<a href="<?php echo url().'/admin/siteuser/transaction/'.base64_encode($user_details->id); ?>" class="btn btn-ylw">Transaction History</a>
			</div>
				
		</div>
	</div>
	
@stop
    
    