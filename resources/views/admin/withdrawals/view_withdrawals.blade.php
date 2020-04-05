@extends('admin/layout/admin_template')
@section('content')
	{!! Form::open(['url' => 'admin/category/add','method'=>'POST', 'files'=>true,'class'=>'form-horizontal row-fluid','id'=>'cms_form','onsubmit'=>'category.checkCategoryName("ADD")']) !!}
	
		{!! Form::hidden('hid_frm_submit_res',1,['class'=>'span8','id'=>'hid_frm_submit_res']) !!}
		{!! Form::hidden('hid_validate_res',1,['class'=>'span8','id'=>'hid_validate_res']) !!}	
		<div class="control-group" style="display:block;">
			<label class="control-label" for="basicinput">User Name *</label>
			<div class="controls">
				{{$tranctions_details['name'].' '.$tranctions_details['last_name'] }}
			</div>
		</div>

		<div class="control-group" style="display:block;">
			<label class="control-label" for="basicinput">Amount *</label>
			<div class="controls">
				{{ $tranctions_details['amount'] }}
			</div>
		</div>

		<div class="control-group" style="display:block;">
			<label class="control-label" for="basicinput">Transfer Id *</label>
			<div class="controls">
				{{ $tranctions_details['transfer_id'] }}
			</div>
		</div>

		<div class="control-group" style="display:block;">
			<label class="control-label" for="basicinput">Transaction Id *</label>
			<div class="controls">
				{{ $tranctions_details['txn_id'] }}
			</div>
		</div>

		<div class="control-group" style="display:block;">
			<label class="control-label" for="basicinput">Transaction Date & Time *</label>
			<div class="controls">
				{{ $tranctions_details['created_at'] }}
			</div>
		</div>

		<div class="control-group" style="display:block;">
			<label class="control-label" for="basicinput">Payment Id*</label>
			<div class="controls">
				{{$tranctions_details['payment_id']}}
			</div>
		</div>

		<div class="control-group" style="display:block;">
			<label class="control-label" for="basicinput">Description *</label>
			<div class="controls">
				{{ $tranctions_details['description'] }}
			</div>
		</div>

		<div class="control-group" style="display:block;">
			<label class="control-label" for="basicinput">Send To *</label>
			<div class="controls">
				{{ $tranctions_details['send_to']==1?'Stripe Pay':$tranctions_details['send_to']==2?'Paypal Pay':$tranctions_details['send_to']==3?'Bank Pay':'Unknown' }}
			</div>
		</div>
		
		<div class="control-group">
			<div class="controls">
				<a href="{{url()}}/admin/user/withdrawal/" class="btn">Back</a>
			</div>
				
		</div>
		{!! Form::close() !!}
	{!! HTML::script(url().'/public/backend/scripts/category.js') !!}
    @stop
    
    