@extends('admin/layout/admin_template')
@section('content')
<script type="text/javascript">
function goForSearch(){
    var search_key      = $('#search_key').val();
    var is_active       = $('#is_active').val();
    var searchtime=$("#searchtime").val();

    var url = '<?php echo url(); ?>/admin/siteuser/all-transaction?';
    url += 'search_key='+search_key+'&active='+is_active+"&searchtime="+searchtime;
    window.location.href = url;
}

</script>
@if(Session::has('success_message'))
<p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('success_message') }}</p>
@endif

@if(Session::has('failure_message'))
<p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('failure_message') }}</p>
@endif
<div class="filter-sec d-flex align-items-center">
    


        <div class="filter-left-sec d-flex align-items-center" >


            <div class="search-field-holder">
                <input type="text" value="<?php echo isset($search_key) ? $search_key : ''; ?>" name="search_key" id="search_key" placeholder="Search by Name, Email, Contact" class="field" onkeyup="getFromLocation('search_key');" />
            </div>


            <div class="custom-select-holder">
                <select name="is_active" id="is_active" class="field custom-select" onchange="changeStatus();">
                    <option value="">Status</option>
                    <option value="1" <?php echo $active=='1' ? "selected='selected'" : ""; ?>>Success</option>
                    <option value="0" <?php echo $active=='0' ? "selected='selected'" : ""; ?>>Pending</option>
                </select>
            </div>


            <div class="custom-select-holder">
                <select name="searchtime" id="searchtime" class="field custom-select">
                    
                    <option value="daily" <?php if($searchtime=="daily"){?>selected="selected"<?php }?>>Daily</option>
                    <option value="weekly" <?php if($searchtime=="weekly"){?>selected="selected"<?php }?>>Weekly</option>
                    <option value="monthly" <?php if($searchtime=="monthly"){?>selected="selected"<?php }?>>Monthly</option>
                    <option value="yearly" <?php if($searchtime=="yearly"){?>selected="selected"<?php }?>>Yearly</option>
                    <option value="alltime" <?php if($searchtime=="alltime"){?>selected="selected"<?php } else if($searchtime==""){ ?>selected="selected"<?php }?>>All-Time</option>
                </select>
            </div>


            <div>
                <input type="button" onclick="goForSearch();" class="btn btn-ylw" name="btn_search_hotel" id="btn_search_hotel" value="Search">
                <button type="button" class="input-reset-btn" onclick="window.location.href='<?php echo url(); ?>/admin/siteuser/all-transaction';" name="btn_search_hotel" id="btn_search_hotel" value=""></button>
            </div>


            </div>

        <div class="filter-right-sec d-flex align-items-center justify-content-between">
              <div class="d-flex align-items-center">
                   
                   <div class="custom-select-holder">
                    <select name="multiple_operation_status" id="multiple_operation_status" class="custom-select">
                        <option value="">Change Status</option>
                        <option value="1">Success</option>
                        <option value="0">Pending</option>
                    </select>
                </div>
                    
                    <div class="btn-save-holder">
                    <a href="javascript:void(0);" onclick="globalActiveInactiveMultipleRecords('walletdetails');" class="btn btn-blue">Save</a>
                    <span style="color:red;" id="selected_record_msg"></span>
                    </div>

                </div>

            <!-- <div class="btn-add-holder">
                <input type="button" onclick="window.location.href='<?php //echo url(); ?>/admin/siteuser/create';" class="btn btn-ylw btn-add" name="btn_search_hotel" id="btn_search_hotel" value="Add Site User" />
            </div> -->
    </div>


</div><!--end filter-sec-->
<div class="table-responsive">
<table cellpadding="0" cellspacing="0" border="0" class="table table-bordered table-striped  display user-datatable" width="100%">
    <thead>
        <tr style="color:#FFFFFF; background:#141b27;">
            <th widht="5%" style="align:center;">
                <input type="checkbox" name="check_all_records" id="check_all_records" onclick="selectAllRecords();" />
            </th>
            <th width="5%">Transaction Id</th>
            <th width="10%">Name</th>
            
            <th width="10%">Created At</th>
            <th width="10%">Purpose</th>
            <th width="10%">Amount</th>
            <th width="30%">Item</th>
            <th width="5%">Statement</th>
            <th width="10%"> Type</th>
            <th width="10%">Status</th>
        </tr>
    </thead>
</table>    
</div>
<script>
$(document).ready(function($) {
    var serch_key_value = $("#search_key").val();
    var status_value = $("#is_active").val();
    var searchtime=$("#searchtime").val();
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
                        url:'<?php echo url();?>/admin/allsiteuser-transaction/?search_key='+serch_key_value+'&active='+status_value+"&searchtime="+searchtime,
                        method:'POST'
                    }, 

                    "aaSorting": [[ 0, 'desc' ]],

                    columns: [
                        { data: 'checkbox_td', name: 'checkbox_td',"bSortable": false},
                        { data: 'id', name: 'id' },
                        { data: 'name', name: 'name',"bSortable": false },
                        
                        { data: 'created_at', name: 'created_at'},
                        { data: 'purpose_state', name: 'purpose_state'},
                        { data: 'amount', name: 'amount'},
                        { data: 'itemdetails', name: 'itemdetails' ,"bSortable": false},
                        { data: 'purpose', name: 'purpose',"bSortable": false,'sClass' : 'action-btns'},
                        { data: 'type', name: 'type'},
                        { data: 'walletstatus', name: 'walletstatus'},
                        
                    ],
                "fnDrawCallback": function(oSettings) {
                    if (oSettings._iDisplayLength > oSettings.fnRecordsDisplay()) {
                        $(oSettings.nTableWrapper).find('.dataTables_paginate').hide();
                        $("#DataTables_Table_0_length").hide();
                    }
                    // $("#check_all_records").prop("checked",false);
                }
                
            });
});

</script>
{!! HTML::script(url().'/public/backend/scripts/user.js') !!}
@stop