@extends('admin/layout/admin_template')
@section('content')
	{!! Form::open(['url' => 'admin/category/add','method'=>'POST', 'files'=>true,'class'=>'form-horizontal row-fluid','id'=>'cms_form','onsubmit'=>'category.checkCategoryName("ADD")']) !!}
	
		{!! Form::hidden('hid_frm_submit_res',1,['class'=>'span8','id'=>'hid_frm_submit_res']) !!}
		{!! Form::hidden('hid_validate_res',1,['class'=>'span8','id'=>'hid_validate_res']) !!}	
		<div class="control-group" style="display:block;">
			<label class="control-label" for="basicinput">Product Name *</label>
			<div class="controls">
				{{$tranctions_details['product_name'] }}
			</div>
		</div>

		<div class="control-group" style="display:block;">
			<label class="control-label" for="basicinput">Sku number *</label>
			<div class="controls">
				{{ $tranctions_details['sku_number'] }}
			</div>
		</div>

		<div class="control-group" style="display:block;">
			<label class="control-label" for="basicinput">Image *</label>
			<div class="controls">
				<img src="{{($tranctions_details['image_url']!='')?$tranctions_details['image_url']: url()."/public/uploads/no-image.png"}}" height="300px" width="150px" alt=""/>
			</div>
		</div>

		<div class="control-group" style="display:block;">
			<label class="control-label" for="basicinput">Transaction Id *</label>
			<div class="controls">
				{{ $tranctions_details['etransaction_id'] }}
			</div>
		</div>

		<div class="control-group" style="display:block;">
			<label class="control-label" for="basicinput">Transaction Date & Time *</label>
			<div class="controls">
				{{ $tranctions_details['transaction_date'] }}
			</div>
		</div>

		<div class="control-group" style="display:block;">
			<label class="control-label" for="basicinput">Order Id*</label>
			<div class="controls">
				{{$tranctions_details['order_id']}}
			</div>
		</div>

		<div class="control-group" style="display:block;">
			<label class="control-label" for="basicinput">Offer Id*</label>
			<div class="controls">
				{{$tranctions_details['offer_id']}}
			</div>
		</div>

		<div class="control-group" style="display:block;">
			<label class="control-label" for="basicinput">Vendor Name *</label>
			<div class="controls">
				{{ $tranctions_details['advertiser-name'] }}
			</div>
		</div>

		<div class="control-group" style="display:block;">
			<label class="control-label" for="basicinput">Vendor Id *</label>
			<div class="controls">
				{{ $tranctions_details['advertiser_id'] }}
			</div>
		</div>

		<div class="control-group" style="display:block;">
			<label class="control-label" for="basicinput">Quantity *</label>
			<div class="controls">
				{{ $tranctions_details['quantity'] }}
			</div>
		</div>

		<div class="control-group" style="display:block;">
			<label class="control-label" for="basicinput">Price *</label>
			<div class="controls">
				{{ $tranctions_details['sale_amount'].' '.$tranctions_details['currency'] }}
			</div>
		</div>

		<div class="control-group" style="display:block;">
			<label class="control-label" for="basicinput">Eran Commissions*</label>
			<div class="controls">
				{{$tranctions_details['commissions'].' '.$tranctions_details['currency']}}
			</div>
		</div>

		<div class="control-group" style="display:block;">
			<label class="control-label" for="basicinput">User Cashback Amount *</label>
			<div class="controls">
				{{ $tranctions_details['cashback_amount'].' '.$tranctions_details['currency'] }}
			</div>
		</div>

		<div class="control-group" style="display:block;">
			<label class="control-label" for="basicinput">User name *</label>
			<div class="controls">
				{{ $tranctions_details['name'] }} {{ $tranctions_details['last_name'] }}
			</div>
		</div>
		
		<div class="control-group">
			<div class="controls">
				<a href="{{url()}}/admin/user/transactions" class="btn">Back</a>
			</div>
				
		</div>
		{!! Form::close() !!}
	{!! HTML::script(url().'/public/backend/scripts/category.js') !!}
    @stop
    
    