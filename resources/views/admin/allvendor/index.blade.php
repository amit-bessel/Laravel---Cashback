@extends('admin/layout/admin_template')
 
@section('content')


@if(Session::has('success_message'))
<p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('success_message') }}</p>
@endif

@if(Session::has('failure_message'))
<p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('failure_message') }}</p>
@endif

<script type="text/javascript">
function goForSearch(){
    var search_key      = $('#search_key').val();
    var is_active       = $('#is_active').val();
    var url = '<?php echo url(); ?>/admin/vendor/list?';
 
   url += 'search_key='+search_key;
    window.location.href = url;
}

</script>


<?php 

 $role=Auth::user()->role;

?>

<div class="filter-sec d-flex align-items-center" >
    


        <div class="filter-left-sec d-flex align-items-center"  >


            <div class="search-field-holder" >
                <input type="text" value="<?php echo isset($search_key) ? $search_key : ''; ?>" name="search_key" id="search_key" placeholder="Vendor  name or category" class="field" onkeyup="getFromLocation('search_key');" />
            </div>


            <div class="custom-select-holder" style="display: none;">
                <select name="is_active" id="is_active" class="field custom-select" onchange="changeStatus();">
                    <option value="">Status</option>
                    <option value="1" <?php echo $active=='1' ? "selected='selected'" : ""; ?>>Active</option>
                    <option value="0" <?php echo $active=='0' ? "selected='selected'" : ""; ?>>Inactive</option>
                </select>
            </div>


            <div>
                <input type="button" onclick="goForSearch();" class="btn btn-ylw" name="btn_search_hotel" id="btn_search_hotel" value="Search">
                <button type="button" class="input-reset-btn" onclick="window.location.href='<?php echo url(); ?>/admin/vendor/list';" name="btn_search_hotel" id="btn_search_hotel" value=""></button>
            </div>


            </div>

        <div class="filter-right-sec d-flex align-items-center justify-content-between" style="display: none !important;">
              <div class="d-flex align-items-center">
                   
                   <div class="custom-select-holder">
                    <select name="multiple_operation_status" id="multiple_operation_status" class="custom-select">
                        <option value="">Change Status</option>
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                </div>
                    
                    <div class="btn-save-holder">
                    <a href="javascript:void(0);" onclick="globalActiveInactiveMultipleRecords('siteusers');" class="btn btn-blue">Save</a>
                    <span style="color:red;" id="selected_record_msg"></span>
                    </div>

                </div>

            <div class="btn-add-holder">
                <input type="button" onclick="window.location.href='<?php echo url(); ?>/admin/siteuser/create';" class="btn btn-ylw btn-add" name="btn_search_hotel" id="btn_search_hotel" value="Add Site User" />
            </div>
    </div>


</div><!--end filter-sec-->




{!! Form::open(['url' => 'admin/hotel-list','method'=>'POST', 'files'=>true,'class'=>'form-horizontal row-fluid','id'=>'']) !!}
<div class="table-responsive">
<table cellpadding="0" cellspacing="0" border="0" class="table table-bordered table-striped  display user-datatable" width="100%">
    <thead>
        <tr style="color:#FFFFFF; background:#141b27;">
            <th widht="5%" style="align:center;">
                <input type="checkbox" name="check_all_records" id="check_all_records" onclick="selectAllRecords();" />
            </th>
             <th width="25%">Vendor Name</th>
             <th width="10%">Vendor Id</th>
            <th width="25%">Category</th>
            
           
             <th width="10%"> Link</th>
             <th width="10%">Sale Commission</th>
             <th width="10%">Affiliate Network</th>
            <th width="8%">Popular Status</th>
            <th width="2%">Action</th>
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
                        url:'<?php echo url();?>/admin/vendor/ajax-vendors/?search_key='+serch_key_value,
                        method:'POST'
                    }, 

                    "aaSorting": [[ 0, 'desc' ]],

                    columns: [
                        { data: 'checkbox_td', name: 'checkbox_td',"bSortable": false},
                        { data: 'advertisername', name: 'advertisername' },
                        { data: 'advertiserid', name: 'advertiserid' ,"bSortable": false},
                        { data: 'catname', name: 'catname' ,"bSortable": false},
                        
                        
                        
                       { data: 'linkname', name: 'linkname',"bSortable": false},
                        { data: 'salecommission', name: 'salecommission'},
                         { data: 'api', name: 'api'},
                        { data: 'popularvendor', name: 'popularvendor'},
                        { data: 'action', name: 'action',"bSortable": false,'sClass':'delete-icon'},
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
