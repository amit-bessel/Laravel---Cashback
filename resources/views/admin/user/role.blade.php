{!! HTML::script('resources/assets/js/ckeditor/ckeditor.js') !!}
@extends('admin/layout/admin_template')

@section('content')




	{!! Form::open(['url' => 'admin/user/editrole/'.$user_id,'method'=>'POST', 'files'=>true,'class'=>'form-horizontal row-fluid','id'=>'user_form']) !!}
		
		{!! Form::hidden('hid_user_id',$user_id,['class'=>'span8','id'=>'hid_user_id']) !!}
		


		<?php
		$modulear=array();
		if($Usersmodules->count()>0){

			foreach ($Usersmodules as $key => $usermodulevalue) {
				array_push($modulear, $usermodulevalue->modules->slug);
			}
		}
		//echo "<pre>";
		//print_r($modulear);exit();
		?>	

		 @if(Session::has('success'))

        <div class="alert alert-success">

            <button type="button" class="close" data-dismiss="alert">×</button>

            <strong>{!! Session::get('success') !!}</strong>

        </div>

   		 @endif

		

		<div class="form-group form-row" >
			
			 {!! Html::decode(Form::label('name','Name: ',['class' => 'col-sm-2 col-form-label'])) !!}	
			<div class="col-sm-10" style="margin-top: 7px;">
				<?php echo ucfirst($user_details->firstname);?> <?php echo $user_details->lastname;?>
			</div>
				
		</div>
		
		<div class="form-group form-row">
			{!! Html::decode(Form::label('name','Email:',['class' => 'col-sm-2 col-form-label'])) !!}
				
			<div class="col-sm-10" style="margin-top: 7px;">
				<?php echo $user_details->email;?>
			</div>
				
		</div>


		<div class="form-group form-row">
			{!! Html::decode(Form::label('roll','Roles:',['class' => 'col-sm-2 col-form-label'])) !!}
				
			<div class="col-sm-10 inline-custom-checkbox" style="margin-top: 7px;">
				  
				<?php
				if($module->count()>0){

					foreach ($module as $key => $modulevalue) {

						if($modulevalue->slug!='Rolemanagement' && $modulevalue->slug!='AdminUserList'){
					?>
					<div class="custom-checkbox">
					<input type="checkbox" name="module[]" value="<?php echo $modulevalue->id;?>" <?php if (in_array($modulevalue->slug,$modulear)){?> checked="checked" <?php }?> id="<?php echo $modulevalue->slug;?>">
						<label for="<?php echo $modulevalue->slug;?>">
                        <?php echo $modulevalue->name;?><br/>
                        </label>
					</div>
					<?php
						}
					}
				}
				?>
				
			</div>
				
		</div>
		
		
	
			
		<div class="form-group form-row">
		
			<div class="col-sm-12 text-right">
				{!! Form::submit('Save',array('class'=>'btn btn-blue','name'=>'action','value'=>'save')) !!}
				<a href="{!! url('admin/user/list')!!}" class="btn btn-ylw">Back</a>
			</div>
				
		</div>
	
	{!! Form::close() !!}
	{!! HTML::script(url().'/public/backend/scripts/user.js') !!}
@stop
    
    