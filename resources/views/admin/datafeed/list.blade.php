@extends('admin/layout/admin_template')
 
@section('content')
<style>
.country input{margin-right:0px;}
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
}
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
    var url = '<?php echo url(); ?>/admin/datafeeds/list?';
    url += 'search_key='+search_key+'&active='+is_active;
    window.location.href = url;
}

</script>




<div class="country">
    <table cellpadding="0" cellspacing="0" border="0" width="100%"> 
        <tr class="searchtr">
            <td widht="25%">
				<input type="text" class="span4" value="<?php echo isset($search_key) ? $search_key : ''; ?>" name="search_key" id="search_key" placeholder="Search Name" class="field" onkeyup="getFromLocation('search_key');" />
			</td>
            <td width="50%">
                <select name="is_active" id="is_active" class="field">
                    <option value="">Status</option>
                    <option value="1" <?php echo $active=='1' ? "selected='selected'" : ""; ?>>Active</option>
                    <option value="0" <?php echo $active=='0' ? "selected='selected'" : ""; ?>>Inactive</option>
                </select>
            </td>
            <td width="50%" style="padding:0px 1px; text-align:right;">
                <input type="button" onclick="goForSearch();" class="btn" name="btn_search_hotel" id="btn_search_hotel" value="Search">
				<input type="button" onclick="window.location.href='<?php echo url(); ?>/admin/datafeeds/list';" class="btn" name="btn_search_hotel" id="btn_search_hotel" value="Reset" />
            </td>
            </tr>
		<tr>
             
            <td width="25%" style="float:right;margin-right:0px;text-align: right">
                <input type="button" onclick="window.location.href='<?php echo url(); ?>/admin/datafeeds/add';" class="btn" name="btn_search_hotel" id="btn_search_hotel" value="Add DataFeed" />
            </td>

        </tr>
	

    </table>
</div>

{!! Form::open(['url' => 'admin/datafeeds/list','method'=>'POST', 'files'=>true,'class'=>'form-horizontal row-fluid','id'=>'']) !!}
<div class="module">
<table cellpadding="0" cellspacing="0" border="0" class="table table-bordered table-striped  display user-datatable" width="100%">
    <thead>
        <tr style="color:#FFFFFF; background:#141b27;">
			<th widht="5%" style="align:center;">
                Sl No.<!-- <input type="checkbox" name="check_all_records" id="check_all_records" onclick="selectAllRecords();" /> -->
            </th>
			<th width="55%">Name</th>
            <th width="25%">Status</th>
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
    $("#"+serach_by).autocomplete({
        source: "<?php echo url() ?>/admin/datafeeds/search-datafeed?search_by="+serach_by,
        select: function( event, ui ) {
           $("#"+serach_by).val( ui.item.label );
           return false;
        },
        minLength: 2
    });
}

function change_datafeed_status(){

    $.ajax({
        url: base_url+'/admin/datafeeds/remove-datafeed',
        type: "get",
        data: { this_val : value,this_id : id,_token:$('meta[name="_token"]').attr('content')},
        success: function(data){
            if(data == '1'){
                $('.alert-success').html('');
                $('#success_status_span_'+id).html('Status updated.');
                $('#success_status_span_'+id).fadeIn('slow');
                $('#success_status_span_'+id).fadeOut('slow');
            }
        }
    });
}

function remove_datafeed(id){

    var response = confirm('Do you really want to remove this datafeed?');
    if(response==1){
        window.location.href = base_url+"/admin/datafeeds/remove/"+id;
    }
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
                    "aaSorting": [],
                    language : {
                      sLengthMenu: "Show _MENU_",
                      emptyTable: "No record found",
                      processing: ""
                    },
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url:'<?php echo url();?>/admin/datafeeds/ajax-datafeeds-list/?search_key='+serch_key_value+'&active='+status_value,
                        method:'POST'
                    }, 
                    columns: [
                        { data: 'slnbr', name: 'checkbox_td',"bSortable": false},
                        { data: 'name', name: 'name' },
                        { data: 'status', name: 'status'},
                        { data: 'action', name: 'action',"bSortable": false,'sClass' : 'action-btns'},
                    ],
                "fnDrawCallback": function(oSettings) {
                    if (oSettings._iDisplayLength > oSettings.fnRecordsDisplay()) {
                        $(oSettings.nTableWrapper).find('.dataTables_paginate').hide();
                        $("#DataTables_Table_0_length").hide();
                    }
                }
                
            });
     $("th").removeClass("action-btns");
});
</script>
{!! HTML::script(url().'/public/backend/scripts/user.js') !!}
@endsection
