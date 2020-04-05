@extends('admin/layout/admin_template')
 
@section('content')
 
  
@if(Session::has('success'))
        <div class="alert alert-success">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <strong>{!! Session::get('success') !!}</strong>
        </div>
 @endif


 
 <div class="table-responsive">
  <!-- <div class="form-control"><select id="sts">
      <option value="">Select</option>
      <option value="1">Unread</option>
  </select></div> -->
<table cellpadding="0" cellspacing="0" border="0" class="table table-bordered table-striped  display user-datatable" width="100%">
    <thead>
        <tr style="color:#FFFFFF; background:#141b27;">
            <th widht="5%" style="align:center;">
                <input type="checkbox" name="check_all_records" id="check_all_records" onclick="selectAllRecords();" />
            </th>
            <th width="10%">Ticket Id</th>
            <th width="20%">User Name</th>
            <th width="20%">Emotional State</th>
            <th width="20%">Subject</th>
            <th width="20%">Isuues Type</th>
            <th width="10%">Status</th>
            <!-- <th width="10%">Action</th> -->
        </tr>
    </thead>
</table>    
</div>


  <div> </div>

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
                    "aaSorting": [],
                    language : {
                      sLengthMenu: "Show _MENU_",
                      emptyTable: "No record found",
                      processing: ""
                    },
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url:'<?php echo url();?>/admin/ajax_active_issue_list/?search_key='+serch_key_value+'&active='+status_value,
                        method:'POST'
                    }, 

                    "aaSorting": [[ 0, 'desc' ]],

                    columns: [
                        { data: 'checkbox_td', name: 'checkbox_td',"bSortable": false},
                        { data: 'id', name: 'id' },
                        { data: 'firstname', name: 'firstname' },
                        { data: 'emotional_state', name: 'emotional_state',"bSortable": false },
                        { data: 'subject', name: 'subject' },
                        
                        { data: 'issue', name: 'issue' },
                        // { data: 'phoneno', name: 'phoneno' },
                        // { data: 'refer', name: 'refer',"bSortable": false,'sClass' : 'action-btns'},
                        { data: 'status', name: 'status'},
                        // { data: 'action', name: 'action',"bSortable": false,'sClass' : 'action-btns'},
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
<script>
    function ChangeTicketStatus(val,id){
     // alert(val);
      if(val==0){
         var conf = confirm("Do you want to open this ticket?");
      }
      else{
         var conf = confirm("Do you want to close this ticket?");
      }
       
       
        if (conf == true) {
                $.ajax({url:'{{route("ChangeTicketStatus")}}',type:'get', data:{val:val,id:id}});
                location.reload(true);
        }else{
             location.reload(true);
        } 
        
    }
</script>
@endsection
