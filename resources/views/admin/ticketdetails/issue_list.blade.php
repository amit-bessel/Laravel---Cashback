@extends('admin/layout/admin_template')
 
@section('content')
@if(Session::has('success_message'))
<p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('success_message') }}</p>
@endif
@if(Session::has('failure_message'))
<p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('failure_message') }}</p>
@endif

<div class="filter-sec">
		<div class="btn-add-holder">
			<input type="button" onclick="window.location.href='<?php echo url(); ?>/admin/ticket_issue/add-issue';" class="btn btn-ylw btn-add" name="btn_search_hotel" id="btn_search_hotel" value="Add Issue" />
		</div>

</div>

<div class="table-responsive">
<table cellpadding="0" cellspacing="0" border="0" class="table table-bordered table-striped  display user-datatable" width="100%">
    <thead>
        <tr style="color:#FFFFFF; background:#141b27;">
            <th width="50%">Issue</th>
            <th width="50%">Action</th>
        </tr>
    </thead>
    <tbody>
    	<?php
    	if(count($ticket_issue) > 0){ 
    	foreach ($ticket_issue as $key => $value) {
    		?>
    		<tr>
    			<td><?php echo $value->issue_type; ?></td>
    			<td><a class="edit-icon" href="<?php echo url().'/admin/ticket_issue_edit/'.base64_encode($value->id);?>" class="btn" ><i class="fa fa-pencil-square-o" aria-hidden="true" title="Edit"></i></a><a class="delete-icon" href="javascript:void(0)" onclick="delete_issue('<?php echo $value->id; ?>');"><i class="fa fa-trash-o" aria-hidden="true"></i></a></td>
    		</tr>

    	<?php }

    	} ?>
    </tbody>
</table>    
</div>
<script type="text/javascript">
	
 function delete_issue(id){
	$.ajax({
			type 	: 'post',
			data 	: {issueid: id},
			url 	: base_url+'/admin/ticket_issue_delete',
			async	: false,
			success	: function(response){
				
				if (response==1){
					location.reload();
				}
				
			}
		});
}



</script>

@endsection

