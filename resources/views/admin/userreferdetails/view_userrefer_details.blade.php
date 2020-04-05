{!! HTML::script('resources/assets/js/ckeditor/ckeditor.js') !!}
@extends('admin/layout/admin_template')

@section('content')
<div class="table-responsive">   
	<table class="table table-striped">
		<thead>
      <tr>
      	<th>Name</th>
        <th>Refer Code</th>
        <th>Email</th>
        <th>Cashback earned</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
	<?php
	if(!empty($refer)){
		//echo "<pre>";
		//print_r($refer);exit();
		foreach ($refer["firstname"] as $key => $value) {
			# code...
		$name=$refer['firstname'][$key]." ".$refer['lastname'][$key];
		$email=$refer['email'][$key];
		$refercode=$refer['refercode'][$key];
		$phoneno=$refer['phoneno'][$key];
		$userreferid=$refer['userreferid'][$key];
		$created_at=$refer['created_at'][$key];
		$updated_at=$refer['updated_at'][$key];
		$affiliate_status = $refer['affiliated'][$key];
		$cashback_earn = $refer['cashback_amount'][$key];
	?>
		



    
    
      <tr>
      	<td><?php if($affiliate_status == 1) echo '<span style="font-weight:bold;color:#31c3e4;">'; ?>{{$name}}<?php if($affiliate_status == 1) echo '</span>'; ?></td>
        <td>{{$refercode}}</td>
        <td>{{$email}}</td>
        <td>{{$cashback_earn}}</td>
     
        <td class="action-btns"><a href="<?php echo url('');?>/admin/siteuser/view/<?php echo $userreferid;?>"><i class="fa fa-eye" aria-hidden="true"></i></a>
        	<?php if($affiliate_status != 1){ ?>
        	<a href="javascript:void(0);" onclick="userJs.generateSuperaffiliateCode('<?php echo $userreferid;?>')" class="btn btn-primary-sm" id="refer_buttion_'.$userreferid.'" >Super Afiiliate</a><br /><div class="alert-success" id="success_referstatus_span_'.$userreferid.'" style="display:none; font-size:12px;"></div><?php } ?></td>
      </tr>
      
   
  



		<!---<div class="control-group" style="display:block;">
			{!! Html::decode(Form::label('name','Refer Code:',['class' => 'control-label'])) !!}
				
			<div class="controls">
				{{$refercode}}
			</div>
				
		</div>	

		<div class="control-group" style="display:block;">
			{!! Html::decode(Form::label('name','Name:',['class' => 'control-label'])) !!}
				
			<div class="controls">
				{{$name}}
			</div>
				
		</div>

		
		<div class="control-group" style="display:block;">
			{!! Html::decode(Form::label('name','Email:',['class' => 'control-label'])) !!}
				
			<div class="controls">
				{{$email}}
			</div>
				
		</div>

		<div class="control-group" style="display:block;">
			{!! Html::decode(Form::label('name','Phoneno:',['class' => 'control-label'])) !!}
				
			<div class="controls">
				{{$phoneno}}
			</div>
				
		</div>



		<div class="control-group" style="display:block;">
			{!! Html::decode(Form::label('name','Created At:',['class' => 'control-label'])) !!}
				
			<div class="controls">
				{{$created_at}}
			</div>
				
		</div>

		<div class="control-group" style="display:block;">
			{!! Html::decode(Form::label('name','Updated At:',['class' => 'control-label'])) !!}
				
			<div class="controls">
				{{$updated_at}}
			</div>
				
		</div>


		<div class="control-group" style="display:block;">
			{!! Html::decode(Form::label('name','Details:',['class' => 'control-label'])) !!}
				
			<div class="controls">
				<a href="<?php echo url('');?>/admin/userreferdetails/view/<?php echo $userreferid;?>" class="btn btn-primary">View User Details</a>
			</div>
				
		</div>-->
		
		
		
		

		


		
		
		
	

	<?php } } else {?> No record found <?php  }?>
	 </tbody>
	</table>
 </div>
	<div class="form-group form-row" style="margin-top:10px; ">
		
			<div class="col-sm-10">
				<a href="{!! url('admin/userreferdetails/list')!!}" class="btn btn-ylw">Back</a>
			</div>
				
		</div>

	{!! Form::close() !!}
	{!! HTML::script(url().'/public/backend/scripts/user.js') !!}
@stop
    
    