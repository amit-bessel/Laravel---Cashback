@extends('admin/layout/admin_template')

@section('content')
	{!! Form::open(['url' => 'admin/doctor/edit/'.$user_id,'method'=>'POST', 'files'=>true,'class'=>'form-horizontal row-fluid','id'=>'cms_form','onsubmit'=>'userJs.checkUserEmail("EDIT")']) !!}
		
		{!! Form::hidden('hid_frm_submit_res',1,['class'=>'span8','id'=>'hid_frm_submit_res']) !!}
		{!! Form::hidden('hid_validate_res',1,['class'=>'span8','id'=>'hid_validate_res']) !!}
		{!! Form::hidden('hid_user_id',$user_id,['class'=>'span8','id'=>'hid_user_id']) !!}
		{!! Form::hidden('form_type','EDIT',['class'=>'span8','id'=>'form_type']) !!}
		
		<div class="control-group" style="display:block;">
		<div class="control-group" style="display:block;">
			<label class="control-label" for="basicinput">Title *</label>
			<div class="controls">
				{!! Form::text('title',$user_details->title,['class'=>'span8','id'=>'title']) !!}
			</div>
		</div>
		
		<div class="control-group" style="display:block;">
			<label class="control-label" for="basicinput">First Name *</label>
			<div class="controls">
				{!! Form::text('name',$user_details->name,['class'=>'span8','id'=>'name']) !!}
			</div>
		</div>

		<div class="control-group" style="display:block;">
			<label class="control-label" for="basicinput">Last Name *</label>
				
			<div class="controls">
				{!! Form::text('last_name',$user_details->last_name,['class'=>'span8','id'=>'last_name']) !!}
			</div>
				
		</div>
		
		<div class="control-group" style="display:block;">
			<label class="control-label" for="basicinput">Email *</label>
				
			<div class="controls">
				{!! Form::email('email',$user_details->email,['class'=>'span8','id'=>'email']) !!}
				<div style="color:#F00;font-size:13px;clear:both;display:none;" id="user_email_msg"></div>
			</div>
				
		</div>
		
		<div class="control-group" style="display:block;">
			<label class="control-label" for="basicinput">Mobile Number *</label>
				
			<div class="controls">
				<input type="text" name="contact" id="contact" class="span8 input-medium bfh-phone" data-format=" (ddd) ddd-dddd" data-number="{{$user_details->contact}}" value="{{$user_details->contact}}">
			</div>
				
		</div>
		
		<div class="control-group" style="display:block;">
			<label class="control-label" for="basicinput">Password</label>
				
			<div class="controls">
				<input type="password" name="password" id="password" class="span8" onblur="userJs.rulesWhileEditingUser();"  />
			</div>
				
		</div>
		
		<div class="control-group" style="display:block;">
			<label class="control-label" for="basicinput">Confirm Password</label>
			<div class="controls">
				<input type="password" name="conf_password" id="conf_password" class="span8"  />
			</div>
		</div>

		<div class="control-group" style="display:block;">
			<label class="control-label" for="basicinput">Image</label>
				<div class="controls">
				<input type="file" id="profile_img" name="profile_img"/>
			</div>
			<p class="new_avatar" style="padding-left: 180px;padding-top: 10px;"><img src="<?php echo url();?>/uploads/profile_image/{{ $user_details->image }}" class="nav-avatar"></p>
		</div>

		<div class="control-group" style="display:block;">
			<label class="control-label" for="basicinput">Profile Title</label>
			<div class="controls">
				<textarea name="profile_title" class="span8">{{$user_details->profile_title}}</textarea>
			</div>
		</div>
		<div class="control-group" style="display:block;">
			<label class="control-label" for="basicinput">About You</label>
			<div class="controls">
				<textarea name="about_u" rows="5" class="span8">{{$user_details->about}}</textarea>
			</div>
		</div>

		<div class="control-group" style="display:block;">
			<label class="control-label" for="basicinput">Languages</label>
			<div class="controls">
				<!-- <textarea name="about_u" rows="5" class="span8">{{$user_details->about}}</textarea> -->
			</div>
		</div>

		<div class="control-group" style="display:block;">
			<label class="control-label" for="basicinput">Medical Procedures</label>
			<div class="controls" id="medical_proced_sec">
				<input type="text" name="medical_proced[]" id="medical_proced[]" class="span8"  />
				<a href="javascript:void(0);" class="btn" name="add_more" id="add_more" onclick="add_more_html('medical_proced[]','medical_proced_sec')">Add More</a>
				<!-- <textarea name="about_u" rows="5" class="span8">{{$user_details->about}}</textarea> -->
			</div>
		</div>

		<div class="control-group" style="display:block;">
			<label class="control-label" for="basicinput">Registered With</label>
			<div class="controls">
				<input type="text" name="registered" id="registered" class="span8" value="{{$user_details->registered_with}}"  />
			</div>
		</div>

		<div class="control-group" style="display:block;">
			<label class="control-label" for="basicinput">Practices</label>
			<div class="controls">
				<!-- <textarea name="about_u" rows="5" class="span8">{{$user_details->about}}</textarea> -->
			</div>
		</div>

		<div class="control-group" style="display:block;">
			<label class="control-label" for="basicinput">Education</label>
			<div class="controls">
				<!-- <textarea name="about_u" rows="5" class="span8">{{$user_details->about}}</textarea> -->
			</div>
		</div>

		<div class="control-group" style="display:block;">
			<label class="control-label" for="basicinput">Specialist</label>
			<div class="controls">
				<!-- <textarea name="about_u" rows="5" class="span8">{{$user_details->about}}</textarea> -->
			</div>
		</div>

		<div class="control-group" style="display:block;">
			<label class="control-label" for="basicinput">Research Interest</label>
			<div class="controls">
				<!-- <textarea name="about_u" rows="5" class="span8">{{$user_details->about}}</textarea> -->
			</div>
		</div>

		<div class="control-group">
            
        </div>
			
		<div class="control-group">
		
			<div class="controls">
				{!! Form::submit('Save',array('class'=>'btn','name'=>'action','value'=>'save')) !!}
				<a href="{!! url('admin/doctor/list')!!}" class="btn">Back</a>
			</div>
				
		</div>
	
	{!! Form::close() !!}
	{!! HTML::script(url().'/public/backend/scripts/user.js') !!}
	<script>
	function add_more_html(name,afterelement){
		var html = '<div class="controls" style="padding-top:5px;"><input type="text" name="'+name+'" id="'+name+'" class="span8"/><div class="action-btns"><a href="javascript:void(0);" onclick=""><i class="fa fa-trash-o" aria-hidden="true"></i></a></div></div>';
		$("#"+afterelement).after(html);
	}

	</script>
@stop