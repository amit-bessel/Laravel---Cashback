@extends('../layout/frontend_template')
@section('content')


<?php
if($count>0){?>

<!-- jQuery Form Validation code -->
<div class="comn-main-wrap">
 <div class="form-horizontal row-fluid" style="width: 65%;
    font-size: 20px;
    margin: 0 auto;
    border: 2px solid #ccc;
    padding: 24px;">

		<div class="control-group" style="display:block;">
			Name
				
			<div class="controls">
				{{$user_details->firstname.' '.$user_details->lastname}}
			</div>
				
		</div>
		<?php
		if(!empty($user_details->profileimage)){?>
		<div class="control-group" style="display:block;">
			Profile Image
				<img src="<?php echo URL::to('/');?>/public/backend/profileimage/<?php echo $user_details->profileimage;?>" style="height: 110px; width: 110px; margin: 10px;border-radius: 5px;">
			<div class="controls">
			
			</div>
				
		</div>
		<?php }?>
		<div class="control-group" style="display:block;">
			Email
				
			<div class="controls">
				{{$user_details->email}}
			</div>
				
		</div>
		
		<div class="control-group" style="display:block;">
			Phone
				
			<div class="controls">
				{{$user_details->phoneno}}
			</div>
				
		</div>

		<div class="control-group" style="display:block;">
			Mobile
				
			<div class="controls">
				{{$user_details->mobileno}}
			</div>
				
		</div>


		<div class="control-group" style="display:block;">
			Date of birth
				
			<div class="controls">
				{{$user_details->dob}}
			</div>
				
		</div>

		
		<div class="control-group" style="display:block;">
			Address
			<div class="controls">
				{{$user_details->address}}
			</div>
				
		</div>

		<div class="control-group" style="display:block;">
			Country
				
			<div class="controls">
				{{$user_details->country}}
			</div>
				
		</div>

		<div class="control-group" style="display:block;">
			State
				
			<div class="controls">
				{{$user_details->state}}
			</div>
				
		</div>

		<div class="control-group" style="display:block;">
			City
				
			<div class="controls">
				{{$user_details->city}}
			</div>
				
		</div>

		<div class="control-group" style="display:block;">
			Zipcode
				
			<div class="controls">
				{{$user_details->zipcode}}
			</div>
				
		</div>
		

		

		
		
	</div>
	<?php }else {?>No record found<?php }?>
</div>
@stop
    
    