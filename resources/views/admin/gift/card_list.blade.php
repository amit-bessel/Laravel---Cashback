@extends('admin/layout/admin_template')
 
@section('content')
@if(Session::has('success_message'))
<p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('success_message') }}</p>
@endif

@if(Session::has('failure_message'))
<p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('failure_message') }}</p>
@endif

<?php 

 $role=Auth::user()->role;

?>

<script type="text/javascript">
function goForSearch(){
    var search_key      = $('#search_key').val();
    var url = '<?php echo url(); ?>/admin/gift-card/list?';
    url += 'search_key='+search_key;
    window.location.href = url;
}

</script>

<div class="filter-sec d-flex align-items-center">
    
<div class="filter-left-sec d-flex align-items-center">
</div>

        <div class="filter-right-sec d-flex align-items-center justify-content-end" <?php if($role==2){ ?> style="display: none;"<?php }?>>


            <div class="search-field-holder">
                <input type="text" value="<?php echo isset($search_key) ? $search_key : ''; ?>" name="search_key" id="search_key" placeholder="Search by Name" class="field" onkeyup="goForSearch();" />
            </div>
        </div>
</div>


        
<div class="table-responsive">
<table cellpadding="0" cellspacing="0" border="0" class="table table-bordered table-striped  display user-datatable" width="100%">
    <thead>
        <tr style="color:#FFFFFF; background:#141b27;">
            <!-- <th widht="5%" style="align:center;">
                <input type="checkbox" name="check_all_records" id="check_all_records" onclick="selectAllRecords();" />
            </th> -->
            <th width="33%">Name</th>
            <th width="33%">Image</th>
            <th width="34%">Status</th>
        </tr>
    </thead>
</table>    
</div>

<script>
$(document).ready(function($) {
			getData();
	});
	
	function getData(){

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
                        url:'<?php echo url();?>/admin/gift-card-ajax?search_key='+serch_key_value,
                        method:'POST'
                    }, 

                    "aaSorting": [[ 0, 'desc' ]],

                    columns: [
                        { data: 'brandname', name: 'name' },
                        { data: 'image', name: 'image',"bSortable": false,'sClass' : 'action-btns'},
                        { data: 'status', name: 'status',"bSortable": false,'sClass' : 'action-btns'},
                        
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

}


</script>
{!! HTML::script(url().'/public/backend/scripts/user.js') !!}

@endsection