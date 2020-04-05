@extends('admin/layout/admin_template')
 
@section('content')
<script type="text/javascript" src="<?php echo url('');?>/public/backend/loadingoverlay/loadingoverlay.min.js"></script>
<script type="text/javascript" src="<?php echo url('');?>/public/backend/loadingoverlay/loadingoverlay_progress.min.js"></script>
<link rel="stylesheet" href="<?php echo url('');?>/public/frontend/datepicker/datepicker.min.css" />
<link rel="stylesheet" href="<?php echo url('');?>/public/frontend/datepicker/datepicker3.min.css" />

<script src="<?php echo url('');?>/public/frontend/datepicker/bootstrap-datepicker.min.js"></script>
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
    var startdate=$("#startdate").val();
    var enddate=$("#enddate").val();
   // var is_active       = $('#is_active').val();
    var url = '<?php echo url(); ?>/admin/userwithdrawl/request?';
    url += 'search_key='+search_key+"&startdate="+startdate+"&enddate="+enddate;
    window.location.href = url;
}

</script>


<?php 

 $role=Auth::user()->role;

?>
<?php

if(!empty($_GET['startdate'])){

  $startdate=$_GET['startdate'];
}
else{
  $startdate='';
}
if(!empty($_GET['enddate'])){

  $enddate=$_GET['enddate'];
}
else{
  $enddate='';
}
?>

<div class="filter-sec d-flex align-items-center">
    


        <div class="filter-left-sec userwithdrawl-filter-left-sec d-flex align-items-center" <?php if($role==2){ ?> style="display: none;"<?php }?>>


            <div class="search-field-holder">
                <input type="text" value="<?php echo isset($search_key) ? $search_key : ''; ?>" name="search_key" id="search_key" placeholder="Search by Name, Email, Contact" class="field" onkeyup="getFromLocation('search_key');" />
            </div>
             <div class="date-field-holder">
                <div class="input-group input-append date" id="datePicker">

                <input type="text" name="startdate" value="<?php echo isset($startdate) ? $startdate : ''; ?>" id="startdate" readonly="readonly" placeholder="Startdate">
                <span class="input-group-addon add-on" style="/*padding-right: 20px;*/"><span class="glyphicon glyphicon-calendar"></span></span></div>

       
             </div>


             <div class="date-field-holder">
                

                <div class="input-group input-append date" id="datePicker1">
       
          <input type="text" name="enddate" value="<?php echo isset($enddate) ? $enddate : ''; ?>" id="enddate" readonly="readonly" placeholder="Enddate">
        <span class="input-group-addon add-on" style="/*padding-right: 20px;*/"><span class="glyphicon glyphicon-calendar"></span></span></div>
             </div>

          <!--   <div class="custom-select-holder">
                <select name="is_active" id="is_active" class="field custom-select" onchange="changeStatus();">
                    <option value="">Status</option>
                    <option value="1" <?php //echo $active=='1' ? "selected='selected'" : ""; ?>>Active</option>
                    <option value="0" <?php //echo $active=='0' ? "selected='selected'" : ""; ?>>Inactive</option>
                </select>
            </div> -->


            <div>
                <input type="button" onclick="goForSearch();" class="btn btn-ylw" name="btn_search_hotel" id="btn_search_hotel" value="Search">
                <button type="button" class="input-reset-btn" onclick="window.location.href='<?php echo url(); ?>/admin/userwithdrawl/request';" name="btn_search_hotel" id="btn_search_hotel" value=""></button>
            </div>


            </div>

        <div class="filter-right-sec userwithdrawl-filter-right-sec d-flex align-items-center justify-content-between">
              <div class="d-flex align-items-center" <?php if($role==2){ ?> style="display: none;"<?php }else {?> <?php }?>>
                   
                   <!-- <div class="custom-select-holder">
                    <select name="multiple_operation_status" id="multiple_operation_status" class="custom-select">
                        <option value="">Change Status</option>
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                </div> -->
                    
                    <!-- <div class="btn-save-holder">
                    <a href="javascript:void(0);" onclick="globalActiveInactiveMultipleRecords('siteusers');" class="btn btn-blue">Save</a>
                    <span style="color:red;" id="selected_record_msg"></span>
                    </div> -->

                    <div class="btn-save-holder">
                    <a href="javascript:void(0);"  class="btn btn-blue paycashall">Pay all</a>
                    <span style="color:red;" id="selected_record_msg"></span>
                    </div>

                    <span class="cntwdreq">Total Withdrawl Request : <?php echo $countwithdrawlrequest;?></span>

                </div>

           <!--  <div class="btn-add-holder">
                <input type="button" onclick="window.location.href='<?php //echo url(); ?>/admin/siteuser/create';" class="btn btn-ylw btn-add" name="btn_search_hotel" id="btn_search_hotel" value="Add Site User" />
            </div> -->
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
            <th width="25%">Name</th>
            <th width="25%">Email</th>
            <th width="25%">Phone</th>
            <th width="15%">Created</th>
            <th width="25%">Amount </th>
             
             <th width="15%">Status</th>
            
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
    //var status_value = $("#is_active").val();
    var startdate=$("#startdate").val();
    var enddate=$("#enddate").val();
     $(".user-datatable").dataTable({
                   "iDisplayLength": 100,
                   "displayStart": 0,
                   "searching": false,
                   "paging": true,
                    "bPaginate": true,
                    "sPaginationType": "full_numbers",
                    "bFilter": false,
                    "bInfo": false,
                    "pagingType": "full_numbers",
                    "aaSorting": [],
                    "lengthMenu": [[25, 50, 100,250, -1], [25, 50, 100,250, "All"]],
                    language : {
                      sLengthMenu: "Show _MENU_",
                      emptyTable: "No record found",
                      processing: ""
                    },
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url:'<?php echo url();?>/admin/userwithdrawl/ajax-withdrawl-request/?search_key='+serch_key_value+"&startdate="+startdate+"&enddate="+enddate,
                        method:'POST',


                    }, 

                    "aaSorting": [[ 0, 'desc' ]],

                    columns: [
                        { data: 'checkbox_td', name: 'checkbox_td',"bSortable": false},
                        { data: 'firstname', name: 'firstname' ,"bSortable": false},
                        { data: 'email', name: 'email' ,"bSortable": false },
                        { data: 'phoneno', name: 'phoneno' ,"bSortable": false},
                        { data: 'created_at', name: 'created_at' },
                        { data: 'amount', name: 'amount' },
                        
                        { data: 'status', name: 'status' ,"bSortable": false},
                        
                    ],
                "fnDrawCallback": function(oSettings) {
                    if (oSettings._iDisplayLength > oSettings.fnRecordsDisplay()) {
                        $(oSettings.nTableWrapper).find('.dataTables_paginate').hide();
                        $("#DataTables_Table_0_length").hide();
                    }
                    //alert("hh");
                    $("#check_all_records").prop("checked",false);
                }
                
            });
     $("th").removeClass("action-btns");
});
</script>

<script type="text/javascript">

//payall user   
$(document).on("click",".paycashall",function(){


var selected=[];

$(".payallchk:checked").each(function() {
       selected.push($(this).val());
  });
if(selected.length>0){

    var jsonString = JSON.stringify(selected);
    ajaxbatchpayment(jsonString);
  
}
else{
    $("#selected_record_msg").html("Please select a record");
}


});

//ajax function for paypal batch payment
function ajaxbatchpayment(jsonString){


     $.ajax({
        type: "POST",
        url: "<?php echo url()?>/payment",
        data: {data : jsonString}, 
        cache: false,
        beforeSend: function() {    
        
        $.LoadingOverlay("show");
        },
        success: function(data){
            $.LoadingOverlay("hide");
            if(data!='Error in payment'){
                alert('Payment success');
            }
            else{
                alert(data);
            }
            
           window.location.reload();
        }
    });
}

//paysingle user
function paysingle(val){

    var x=confirm("Are you sure to pay?");
    if(x==false){
    return false;
    }
    else{
    if(val!=''){
    var selected=[];
    selected.push(val);
    var jsonString = JSON.stringify(selected);
    ajaxbatchpayment(jsonString);   
    }
    }

    
}


</script>
<script type="text/javascript">
  
$(document).ready(function() {
    $('#datePicker').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true
        })
        

     $('#datePicker1').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true
        })
        
});

</script>
{!! HTML::script(url().'/public/backend/scripts/user.js') !!}
@endsection
