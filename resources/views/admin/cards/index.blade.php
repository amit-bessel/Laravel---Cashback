@extends('admin/layout/admin_template')
 
@section('content')

  
@if(Session::has('success_message'))
        <div class="alert alert-success">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <strong>{!! Session::get('success_message') !!}</strong>
        </div>
 @endif
 
 	<div class="country">
 	<table cellpadding="0" cellspacing="0" border="0" width="100%" style="margin-bottom:10px;"> 
		<tr>
            <td width="25%"></td>
			<td width="25%"></td>
			<td width="25%"></td>
			<td width="15%" style="float:right;margin-right:45px;">
				<input type="button" onclick="window.location.href='<?php echo url(); ?>/admin/cards/admin-card-details';" class="btn" name="btn_search_hotel" id="btn_search_hotel" value="Add card" />
			</td>
		 </tr>

    </table>
   </div>
   <div class="module">
                               
			<table cellpadding="0" cellspacing="0" border="0" class="table table-bordered table-striped  display" width="100%">
				<thead>
					<tr style="color:#FFFFFF; background:#444;">
						<th width="10%">Sl No.</th>
						<th width="40%">Card No.</th>
						<th width="15%">Expiry MM/YY</th>
						<th width="15%">Primary</th>
						<th width="20%">Action	</th>
					   
					</tr>
				</thead>
					
					
				<tbody>
					<?php $i=1;
					if(count($admin_card_details)>0)?>
					@foreach ($admin_card_details as $each_card)
					<tr class="odd gradeX">
						<td class=" sorting_1">

							<?php echo $i; ?>
						</td>
						<td class=" ">
							XXXXXXXXXXXX{{ $each_card->last4 }}
						</td>

						<td class=" ">
							{{ $each_card->exp_month."/".$each_card->exp_year }}
						</td>

						<td class=" ">
							{!! ($each_card->primary_card==1)?'Yes':'No' !!}
						</td>

						<td>
							<?php
								if($each_card->primary_card!=1){
									?>
									<a href="javascript:void(0);" onclick="setPrimaryCard({{ $each_card->id }});" class="btn btn-warning">Primary</a>
									<a href="javascript:void(0);" onclick="removeCard({{ $each_card->id }});" class="btn btn-warning">Remove</a>
									<?php
								}
							?>
						</td>
					   
					</tr>
					<?php $i++; ?>
						@endforeach
					</tbody>
					
				</table>

	  
		</div>

  <div><?php echo $admin_card_details->render(); ?></div>
  <script>
  function setPrimaryCard(id){
  	if(confirm('Do you want to set this card to primary?')){
  		window.location.href = "<?php echo url(); ?>/admin/cards/update-primary-card?id="+id;
  	}
  	
  }
  
  function removeCard(id){
  	if(confirm('Do you want to remove this card?')){
  		window.location.href = "<?php echo url(); ?>/admin/cards/remove-card?id="+id;
  	}
  	
  }
  </script>
@endsection
