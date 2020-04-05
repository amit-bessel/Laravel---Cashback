@extends('admin/layout/admin_template')
@section('content')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">

  <!-- Main content -->
  <section class="content">
    <div class="row">
      <div class="col-xs-12">
        <div class="box">
        @if(Session::has('success_message'))
          <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('success_message') }}</p>
        @endif

        @if(Session::has('failure_message'))
            <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('failure_message') }}</p>
        @endif

        <!-- /.box-header -->
        <div class="box-body">
          <div class="text-right" style="margin-bottom:10px">
            <a href="<?php echo url(); ?>/admin/content/add-faq-question/" class="btn btn-primary">Add Question</a>
          </div>
              

          <table id="package-grid" class="table table-bordered table-hover">
            <thead>
              <tr>
                <th>Question</th>
								<th>Answer</th>
                <th>Action</th>
              </tr>
            </thead>
          </table>
                
          </div> <!-- /.box-body -->
        </div> <!-- /.box --> 
      </div> <!-- /.col -->
    </div> <!-- /.row -->  
  </section> <!-- /.content -->    
</div>

<script>
$(document).ready(function() {

  var dataTable = $('#package-grid').DataTable( {
    "paging": true,
    "ordering": true,
		"processing": true,
		"serverSide": true,
    "searching" : false,
		"ajax":{
			url :"<?php echo url(); ?>/admin/content/question-list", // json datasource
			type: "post",  // method  , by default get
			"data": {"_token": "{{ csrf_token() }}"
       },
			error: function(){  // error handling
				$(".package-grid-error").html("");
				$("#package-grid").append('<tbody class="package-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
				$("#package-grid_processing").css("display","none");				
			}
		},
    "aoColumns": [
                  { "mData": "question", "sTitle": "Question" , "sWidth": "40%"},
									{ "mData": "answer", "sTitle": "Answer" , "sWidth": "50%"},
                  {
                    "mData": null,
                    "bSortable": false,
                    "sWidth": "10%",
                    "mRender": function (o) { return '<a href="<?php echo url(); ?>/admin/content/question-edit/'+o.id+'" class="btn" ><i class="fa fa-pencil-square-o" aria-hidden="true" title="Edit Package"></i></a>&nbsp;&nbsp;<a href="javascript:void(0);" class="btn" onclick="return getdelete('+o.id+')" ><i class="fa fa-trash-o" aria-hidden="true" title="Delete Package"></i></a>'; }
                  }
                ]
				}); 
});  
function getdelete(id){

  var confirm_banner = confirm("Are you sure you want to delete this question?");
  if(confirm_banner){
    window.location.href = "<?php echo url(); ?>/admin/content/question-remove/"+id;
  }
}	
</script>
<!-- /.content-wrapper -->
@endsection
