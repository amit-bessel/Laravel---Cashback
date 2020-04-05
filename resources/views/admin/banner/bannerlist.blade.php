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

<script src="<?php echo url(); ?>/public/frontend/js/sweetalert.min.js"></script>

@if(Session::has('success_message'))
<p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('success_message') }}</p>
@endif

@if(Session::has('failure_message'))
<p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('failure_message') }}</p>
@endif


<div class="filter-sec">
			<div class="btn-add-holder">
			                <input type="button" onclick="window.location.href='<?php echo url(); ?>/admin/banner/add-banner';" class="btn btn-ylw btn-add" name="btn_search_hotel" id="btn_search_hotel" value="Add Banner" />
			</div>

</div>



{!! Form::open(['url' => 'admin/hotel-list','method'=>'POST', 'files'=>true,'class'=>'form-horizontal row-fluid','id'=>'']) !!}
<div class="table-responsive">
<table cellpadding="0" cellspacing="0" border="0" class="table table-bordered table-striped  display user-datatable" width="100%" id="employee-grid"> 
    <thead>
        <tr style="color:#FFFFFF; background:#141b27;">
            <th widht="5%" style="align:center;">
                <input type="checkbox" name="check_all_records" id="check_all_records" onclick="selectAllRecords();" />
            </th>
            <th width="25%">Page Name</th>
            <th width="25%">Banner Image</th>
            <th width="25%">Banner Heading</th>
            <!--  <th width="15%">Banner Text </th> -->
            
            
        </tr>
    </thead>
</table>    
</div>
</div>
{!! Form::close() !!}


  <!-- Content Wrapper. Contains page content -->
  
  
  <?php
				  if($banner_arr->count()>0)
				  {
				  	foreach($banner_arr as $banner_list)
					{
				  ?>
				  
				  <div id="myModal<?php echo $banner_list->id; ?>" class="modal fade" role="dialog">
					  <div class="modal-dialog">
					
						<!-- Modal content-->
						<div class="modal-content">
						  <div class="modal-header">
							<button type="button" class="close" data-dismiss="modal">&times;</button>
							<h4 class="modal-title"><?php echo ucfirst($banner_list->page_name); ?> - Banner Text</h4>
						  </div>
						  <div class="modal-body">
							<p><?php echo $banner_list->banner_text; ?></p>
						  </div>
						  <div class="modal-footer">
							<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
						  </div>
						</div>
					
					  </div>
				</div>
					<?php
					}
				}
					?>
				
      <script>

			
$(document).ready(function() {
				var dataTable = $('#employee-grid').DataTable( {
					"iDisplayLength": 10,
					"searching": true,
					"displayStart": 0,
					"paging": true,
					"bPaginate": true,
					"sPaginationType": "full_numbers",
					"bFilter": false,
					"bInfo": false,
					"pagingType": "full_numbers",
					"ordering": true,
					"processing": true,
					"serverSide": true,
					"ajax":{
						url :"<?php echo url(); ?>/admin/banner/ajax-banner-list", // json datasource
						type: "post",  // method  , by default get
						"data": {"_token": "{{ csrf_token() }}"},
						error: function(){  // error handling
							$(".employee-grid-error").html("");
							$("#employee-grid").append('<tbody class="employee-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
							$("#employee-grid_processing").css("display","none");
							
						}
					},
		"aaSorting": [[ 0, 'desc' ]],
        "aoColumns": [
            
            { "mData": "page_name", "sTitle": "Page Name" , "sWidth": "20%"},
            { "mData": "banner_thumb_image", "sTitle": "Banner Image" , "sWidth": "20%","bSortable": false},
			{ "mData": "banner_heading", "sTitle": "Banner Heading" , "sWidth": "20%"},
			// {
			// 	"sWidth": "20%",
   //              "mData": null,
   //              "sTitle": "Banner Text" ,
   //              "bSortable": false,
   //              "mRender": function (o) { return '<a href="javascript:void(0);" data-toggle="modal" data-target="#myModal'+o.id+'"><i class="fa fa-eye" aria-hidden="true" title="View Description"></i></a>'; }
   //          },
   			// { "mData": "banner_text", "sTitle": "Banner Text" , "sWidth": "20%","bSortable": false},
			{ "mData": "action", "sTitle": "Action" , "sWidth": "20%","bSortable": false},
        ]
         
				});
        
     });
		 
		 function delete_banner(banner_id)
        {

          swal({
          title: "Are you sure?",
          text: "Once deleted, you will not be able to recover your banner image!",
          icon: "warning",
          buttons: true,
          dangerMode: true,
      		})
      		.then((willDelete) => {

      		if (willDelete) {
        //     swal("Poof! Your imaginary file has been deleted!", {
        //     icon: "success",
        // });
        //     location.reload();
        		window.location.href="<?php echo url();?>/admin/banner/delete-banner/"+banner_id;

      		} else {
            swal("Your Banner image is safe!");
      		}
      	 });

      	}
          
		</script>
  <!-- /.content-wrapper -->
  @endsection