@extends('admin/layout/admin_template')
 
@section('content')
<style>
/*.country input{margin-right:0px;}
#search_key{
	margin-right:10px;
}
.country select{margin-right:10px;}

.country .btn{
	    position: relative;
    top: -5px;
}

.country tr{
	float:right;
	width:100%;

}

.country select {
    margin-right: 10px;
    width: 190px;
}


.country button, .country input[type="button"], .country input[type="submit"] {
    -webkit-appearance: button;
    cursor: pointer;
    width: 90px;
}
.tractive td{
    background-color: #ffe7dc !important;
}
.searchtr{
    margin-bottom: 17px;border-bottom: 1px solid #ccc;padding-bottom:9px;
}*/
</style>
@if(Session::has('success_message'))
<p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('success_message') }}</p>
@endif

@if(Session::has('failure_message'))
<p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('failure_message') }}</p>
@endif

<script type="text/javascript">
function goForSearch(){
    var search_key 		= $('#search_key').val();
    var is_active  		= $('#is_active').val();
    var url = '<?php echo url(); ?>/admin/user/list?';
    url += 'search_key='+search_key+'&active='+is_active;
    window.location.href = url;
}

</script>


<?php 

 $role=Auth::user()->role;

?>

<div class="filter-sec d-flex align-items-center">

 

        <div class="filter-left-sec d-flex align-items-center"  <?php if($role==2){ ?> style="display: none;"<?php }?>>

            <div class="search-field-holder">
				<input type="text" class="span4" value="<?php echo isset($search_key) ? $search_key : ''; ?>" name="search_key" id="search_key" placeholder="Search by Name, Email, Contact" class="field" onkeyup="getFromLocation('search_key');" />
			</div>

            <div class="custom-select-holder">
                <select name="is_active" id="is_active" class="custom-select">
                    <option value="">Status</option>
                    <option value="1" <?php echo $active=='1' ? "selected='selected'" : ""; ?>>Active</option>
                    <option value="0" <?php echo $active=='0' ? "selected='selected'" : ""; ?>>Inactive</option>
                </select>
            </div>

            <div>
                <input type="button" onclick="goForSearch();" class="btn btn-ylw" name="btn_search_hotel" id="btn_search_hotel" value="Search">

				<button type="button" onclick="window.location.href='<?php echo url(); ?>/admin/user/list';" class="input-reset-btn" name="btn_search_hotel" id="btn_search_hotel" value="Reset"></button>
            </div>


        </div>

        

	<div class="filter-right-sec d-flex align-items-center justify-content-between">

        <div class="d-flex align-items-center" <?php if($role==2){ ?> style="display: none;"<?php }else {?> style="float:left;margin-right:0px;" <?php }?>>

             <div class="custom-select-holder">
                    <select name="multiple_operation_status" id="multiple_operation_status" class="custom-select">
                        <option value="">Change Status</option>
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
             </div>  

                    <div class="btn-save-holder">
                    <div href="javascript:void(0);" onclick="globalActiveInactiveMultipleRecords('users');" class="btn btn-blue">Save</div>
                    <span style="color:red;" id="selected_record_msg"></span>
                    </div>
        </div>

                <div class="btn-add-holder">

                <input type="button" onclick="window.location.href='<?php echo url(); ?>/admin/user/add';" class="btn btn-ylw btn-add" name="btn_search_hotel" id="btn_search_hotel" value="Add User" />

               </div>
        

        </div>

</div>

{!! Form::open(['url' => 'admin/hotel-list','method'=>'POST', 'files'=>true,'class'=>'form-horizontal row-fluid','id'=>'']) !!}
<div class="table-responsive">
<table cellpadding="0" cellspacing="0" border="0" class="table table-bordered table-striped  display user-datatable" width="100%">
    <thead>
        <tr style="color:#FFFFFF; background:#141b27;">
			<th widht="5%" style="align:center;">
                <input type="checkbox" name="check_all_records" id="check_all_records" onclick="selectAllRecords();" />
            </th>
            <th>#</th>
			<th width="25%"> Name</th>
          
            <th width="25%">Email</th>
            <th width="25%">Contact</th>
            
             <th width="15%">Status</th>
            <th width="10%">Action</th>
        </tr>
    </thead>
</table>    
</div>
</div>
{!! Form::close() !!}
<script>

function getFromLocation(serach_by)
{
    //alert(serach_by);
    //var avalable_tags =<?php //echo $tags; ?>;
    //$search_val = $( "#srch_res_name" ).val();
    $("#"+serach_by).autocomplete({
        source: "<?php echo url() ?>/admin/user/search-user?search_by="+serach_by,
       /* focus: function( event, ui ) {
           $("#"+serach_by).val( ui.item.label );
           return false;
        },*/
        select: function( event, ui ) {
           $("#"+serach_by).val( ui.item.label );
           return false;
        },
        minLength: 2
    });
}

$(document).ready(function($) {
    var serch_key_value = $("#search_key").val();
    var status_value = $("#is_active").val();
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
                    "aaSorting": [[ 0, 'asc' ]],
                    language : {
                      sLengthMenu: "Show _MENU_",
                      emptyTable: "No record found",
                      processing: ""
                    },
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url:'<?php echo url();?>/admin/user/ajax-patients/?search_key='+serch_key_value+'&active='+status_value,
                        method:'POST'
                    },
                    "aaSorting": [[ 0, 'desc' ]], 
                    columns: [
                        { data: 'checkbox_td', name: 'checkbox_td',"bSortable": false},
                        { data: 'uniqueid', name: 'uniqueid' ,"bSortable": false},
                        { data: 'name', name: 'firstname' },                   
                        { data: 'email', name: 'email' },
                        { data: 'contact', name: 'contact' },                        
                        { data: 'status', name: 'status'},
                        { data: 'action', name: 'action',"bSortable": false,'sClass' : 'action-btns'},
                    ],
                "fnDrawCallback": function(oSettings) {
                    if (oSettings._iDisplayLength > oSettings.fnRecordsDisplay()) {
                        $(oSettings.nTableWrapper).find('.dataTables_paginate').hide();
                        $("#DataTables_Table_0_length").hide();
                    }
                      $("#check_all_records").prop("checked",false);
                }
                
            });
     $("th").removeClass("action-btns");
});
</script>
{!! HTML::script(url().'/public/backend/scripts/user.js') !!}
@endsection
