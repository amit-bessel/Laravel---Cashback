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
    var search_key      = $('#search_key').val();
    var is_active       = $('#is_active').val();
    var searchtime=$("#searchtime").val();

    var url = '<?php echo url(); ?>/admin/siteuser/invite-friend-list-notregistered?';
    url += 'search_key='+search_key+'&active='+is_active+"&searchtime="+searchtime;
    window.location.href = url;
}

</script>


<?php 

 $role=Auth::user()->role;

?>

<div class="filter-sec d-flex align-items-center">
    


        <div class="filter-left-sec d-flex align-items-center" <?php if($role==2){ ?> style="display: none;"<?php }?>>


            <div class="search-field-holder">
                <input type="text" value="<?php echo isset($search_key) ? $search_key : ''; ?>" name="search_key" id="search_key" placeholder="Search by Email" class="field" onkeyup="getFromLocation('search_key');" />
            </div>


           <!--  <div class="custom-select-holder">
                <select name="is_active" id="is_active" class="field custom-select" onchange="changeStatus();">
                    <option value="">Status</option>
                    <option value="1" <?php //echo $active=='1' ? "selected='selected'" : ""; ?>>Active</option>
                    <option value="0" <?php //echo $active=='0' ? "selected='selected'" : ""; ?>>Inactive</option>
                </select>
            </div> -->


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
                <button type="button" class="input-reset-btn" onclick="window.location.href='<?php echo url(); ?>/admin/siteuser/invite-friend-list-notregistered';" name="btn_search_hotel" id="btn_search_hotel" value=""></button>
            </div>


            </div>

       


</div><!--end filter-sec-->




{!! Form::open(['url' => 'admin/hotel-list','method'=>'POST', 'files'=>true,'class'=>'form-horizontal row-fluid','id'=>'']) !!}
<div class="table-responsive">
<table cellpadding="0" cellspacing="0" border="0" class="table table-bordered table-striped  display user-datatable" width="100%">
    <thead>
        <tr style="color:#FFFFFF; background:#141b27;">
            <!-- <th widht="5%" style="align:center;">
                <input type="checkbox" name="check_all_records" id="check_all_records" onclick="selectAllRecords();" />
            </th> -->
            
             <th width="10%">Refer By Name</th>
            <th width="20%">Refer To Email</th>
            <th width="20%">Status</th>
            <th> Date</th>
            
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
                        url:'<?php echo url();?>/admin/siteuser/ajaxinvitefriendlistnotregistered/?search_key='+serch_key_value+'&active='+status_value+"&searchtime="+searchtime,
                        method:'POST'
                    }, 

                    "aaSorting": [[ 0, 'desc' ]],

                    columns: [
                        // { data: 'checkbox_td', name: 'checkbox_td',"bSortable": false},
                        { data: 'siteuserfirstname', name: 'siteuserfirstname' ,"bSortable": false},
                        { data: 'email', name: 'email' ,"bSortable": false},
                        { data: 'status', name: 'status' ,"bSortable": false},
                        { data: 'created_at', name: 'created_at' },
                       
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
