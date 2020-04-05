@extends('admin/layout/admin_template')
@section('content')
<div class="table-responsive">
<table cellpadding="0" cellspacing="0" border="0" class="table table-bordered table-striped  display user-datatable" width="100%">
    <thead>
        <tr style="color:#FFFFFF; background:#141b27;">
            <th width="5%">Transaction Id</th>
            <th width="20%">Created At</th>
            <th width="10%">Purpose</th>
            <th width="10%">Amount</th>
            <th width="35%">Statement</th>
            <th width="10%">Transaction Type</th>
            <th width="10%">Status</th>
        </tr>
    </thead>
</table>    
</div>
<script>
$(document).ready(function($) {
	$(".user-datatable").dataTable({
                   	"iDisplayLength": 10,
                   	"displayStart": 0,
                   	"searching": false,
                   	"paging": true,
                    "bPaginate": true,
                    "sPaginationType": "full_numbers",
                    "bFilter": false,
                    "bInfo": false,
                    "pagingType": "full_numbers",
                    "aaSorting": [],
                    language : {
                      sLengthMenu: "Show _MENU_",
                      emptyTable: "No record found",
                      processing: ""
                    },
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url:'<?php echo url();?>/admin/super-affiliate-payout-transaction/',
                        method:'POST'
                    }, 

                    "aaSorting": [[ 0, 'desc' ]],

                    columns: [
                        { data: 'id', name: 'id' },
                        { data: 'created_at', name: 'created_at'},
                        { data: 'purpose_state', name: 'purpose_state'},
                        { data: 'amount', name: 'amount'},
                        { data: 'purpose', name: 'purpose',"bSortable": false,'sClass' : 'action-btns'},
                        { data: 'type', name: 'type'},
                        { data: 'walletstatus', name: 'walletstatus'}
                        
                    ],
                "fnDrawCallback": function(oSettings) {
                    if (oSettings._iDisplayLength > oSettings.fnRecordsDisplay()) {
                        $(oSettings.nTableWrapper).find('.dataTables_paginate').hide();
                        $("#DataTables_Table_0_length").hide();
                    }
                    // $("#check_all_records").prop("checked",false);
                }
                
            });
	$("th").removeClass("action-btns");
});

</script>
@stop