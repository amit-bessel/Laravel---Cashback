@extends('admin/layout/admin_template')
@section('content')
  
<script src="<?php echo url(); ?>/public/tag-it/js/tag-it.js"></script>
<link href="<?php echo url(); ?>/public/tag-it/css/jquery.tagit.css" rel="stylesheet" type="text/css">
<link href="<?php echo url(); ?>/public/tag-it/css/tagit.ui-zendesk.css" rel="stylesheet" type="text/css">

<script>
$(document).ready(function() {
        $("#meta_keyword").tagit();
    });

</script>
  
<div class="content-wrapper"> <!-- Content Wrapper. Contains page content -->
  <section class="content-header"><!-- Content Header (Page header) -->
    <h1>
      <?php echo $module_head; ?>
    </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo url(); ?>/admin/home/dashboard"><i class="fa fa-dashboard"></i>Dashboard</a></li>
        <li class="active">FAQ</li>
      </ol>
  </section>

  <section class="content"><!-- Main content -->
    <div class="box-body">
    @if(Session::has('success_message'))
    <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('success_message') }}</p>
    @endif
    @if(Session::has('failure_message'))
    <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('failure_message') }}</p>
    @endif
      <div class="row">
      {!! Form::open(['url' => 'admin/content/faq-list','method'=>'POST', 'files'=>true,'id'=>'cms']) !!}
        <div class="col-sm-12 col-md-6">
          <div class="form-group">
            <label>Page Heading</label>
              {!! Form::text('title',$meta_content_arr->title,['class'=>'form-control ','id'=>'title','required'=>true]) !!}
           </div>
        
           <div class="form-group">
              <label>Page Content</label>
              {!! Form::textarea('description',$meta_content_arr->description,['class'=>'form-control ','id'=>'description','required'=>true]) !!}
           </div>
      
           <div class="form-group">
              <label>Meta Name</label>
              {!! Form::text('meta_name',$meta_content_arr->meta_name,['class'=>'form-control ','id'=>'meta_name','required'=>true]) !!}
           </div>
      
           <div class="form-group">
              <label>Meta Description</label>
              {!! Form::text('meta_description',$meta_content_arr->meta_description,['class'=>'form-control ','id'=>'meta_description','required'=>true]) !!}
           </div>
      
           <div class="form-group">
              <label>Meta Keywords</label>
              {!! Form::text('meta_keyword',$meta_content_arr->meta_keyword,['class'=>'form-control ','id'=>'meta_keyword','required'=>true]) !!}
           </div>
        
           <div class="form-group">
              <label></label>
              <div class="col-md-2 mt-o">
               {!! Form::submit('Save',array('class'=>'btn btn-block btn-success','name'=>'action_plan','id'=>'add_plan','value'=>'save')) !!}
              </div>
            </div>             
        </div>
        {!! Form::close() !!}
        {!! HTML::script(url().'/public/backend/scripts/user.js') !!}
      </div><!-- /.row -->
    </div>
  
    <div class="row">
      <div class="col-xs-12">
        <div class="box"><!-- /.box-header -->
            <div class="box-body">
              <div class="text-right" style="margin-bottom:10px">
                <a href="<?php echo url(); ?>/admin/content/add-faq-category" class="btn btn-primary">Add FAQ Category</a>
              </div>
              <!--<table id="employee-grid"  cellpadding="0" cellspacing="0" border="0" class="display" width="100%">-->
               
               <table id="employee-grid" class="table table-bordered table-hover">
                  <thead>
                    <tr>
                      <th>FAQ Category Name</th>
                      <th>Total Questions</th>
                      <th>Action</th>
                    </tr>
                  </thead>
              </table>
                
            </div>
             <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
  
  
        
<script>


$(document).ready(function() {
        var dataTable = $('#employee-grid').DataTable( {
        "paging": true,
        "ordering": true,
        "processing": true,
        "serverSide": true,
        "ajax":{
            url :"<?php echo url(); ?>/admin/content/ajax-list", // json datasource
            type: "post",  // method  , by default get
            "data": {"_token": "{{ csrf_token() }}"},
            error: function(){  // error handling
              $(".employee-grid-error").html("");
              $("#employee-grid").append('<tbody class="employee-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
              $("#employee-grid_processing").css("display","none");
              
            }
          },
        "aoColumns": [
           
            { "mData": "name", "sTitle": "FAQ Category Name" , "sWidth": "30%"},
            { "mData": "total_question", "sTitle": "Total Question" , "sWidth": "30%"},

           
            
            {
                "mData": null,
                "bSortable": false,
                "sWidth": "20%",
               "mRender": function (o) { return '<a href="<?php echo url(); ?>/admin/content/edit-faq-category/'+o.id+'" class="btn" ><i class="fa fa-pencil-square-o" aria-hidden="true" title="Edit"></i></a><a href="javascript:void(0);" class="btn" onclick="delete_cat('+o.id+');" ><i class="fa fa-trash-o" aria-hidden="true" title="Delete"></i></a>'; }
            }
        ]
         
        });
        
        
        
      } );
     
     
        function delete_cat(id)
        {
            swal({
            title: "Are you sure you want to delete?",
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: "btn-danger",
            confirmButtonText: "Yes, delete it!",
            closeOnConfirm: false
          },
          function(){
            window.location = "<?php echo url(); ?>/admin/content/delete-faq-category/"+id;
          });
        }
    

</script><!-- /.content-wrapper -->
@endsection