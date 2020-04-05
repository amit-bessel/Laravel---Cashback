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
</style>
  
@if(Session::has('success_message'))
<p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('success_message') }}</p>
@endif

@if(Session::has('failure_message'))
<p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('failure_message') }}</p>
@endif
 <!-- <a href="{!!url('admin/cms/create')!!}" class="btn btn-success pull-right">Create Content</a> -->
<!--<hr>-->

<script type="text/javascript">
function goForSearch(){
    var search_key = $('#search_key').val();
    var is_active = $('#is_active').val();

    var url = '<?php echo url(); ?>/admin/category/list?';
    url += 'search_key='+search_key+'&active='+is_active;
    window.location.href = url;
}
</script>




<div class="country">
    <table cellpadding="0" cellspacing="0" border="0" width="100%"> 
        <tr>
            <td width="30%">
                <input type="text" value="<?php echo isset($search_key) ? $search_key : ''; ?>" name="search_key" id="search_key" placeholder="Category name..." class="span5" />
            </td>
            <td width="30%">
                <select name="is_active" id="is_active" class="field">
                    <option value="">Status</option>
                    <option value="1" <?php echo $active=='1' ? "selected='selected'" : ""; ?>>Active</option>
                    <option value="0" <?php echo $active=='0' ? "selected='selected'" : ""; ?>>Inactive</option>
                </select>
            </td>

            <td width="40%" style="padding:0px;">
                <input type="button" onclick="goForSearch();" class="btn" name="btn_search_hotel" id="btn_search_hotel" value="Search">
				<input type="button" onclick="window.location.href='<?php echo url(); ?>/admin/category/list';" class="btn" name="btn_search_hotel" id="btn_search_hotel" value="Reset" />
            </td>
            <td></td>

        </tr>
		<tr>
            <td width="25%"></td>
			<td width="25%"></td>
			<td width="25%"></td>
			<td width="15%" style="float:right;margin-right:0px;text-align: right">
				<input type="button" onclick="window.location.href='<?php echo url(); ?>/admin/category/add';" class="btn" name="btn_search_hotel" id="btn_search_hotel" value="Add Category" />
			</td>
		 </tr>

    </table>
</div>
<div id="serverloader" class="dataTables_processing" style="display: none;"></div>

{!! Form::open(['url' => 'admin/hotel-list','method'=>'POST', 'files'=>true,'class'=>'form-horizontal row-fluid','id'=>'cms_form']) !!}
<div class="module">

    <table cellpadding="0" cellspacing="0" border="0" class="table table-bordered table-striped  display user-datatable" width="100%">
        <thead>
            <tr style="color:#FFFFFF; background:#444;">
                <th width="10%">Sl No.</th> 
                <th width="45%">Category Name</th>
                <th width="15%">Map Product</th>
                <th width="15%">View Product</th>
                <th width="15%">Sub Category</th>
                <!-- <th width="15%">Status</th>   -->
                <th width="15%" align="right">Action</th>
            </tr>
        </thead>
            
            
        <tbody>
            <?php $indx = $sl_no ?>
            <?php if(count($categories) > 0){ ?>
            @foreach ($categories as $v)
            <tr class="odd gradeX">
                <td class="">{{ $indx }}</td>
                <td class="">{{ $v->name }}</td>

                <td class="" style="text-align:center;">
                    <a href="<?php echo url(); ?>/admin/category/search-category-product/{{ $v->id }}" title="Map Product"><i class="fa fa-list-alt" aria-hidden="true"></i>
                    </a>
                </td>

                <td class="" style="text-align:center;">
                    <a href="<?php echo url(); ?>/admin/category/category-product-list/{{ $v->id }}" title="Veiw Product"><i class="fa fa-list-alt" aria-hidden="true"></i>
                    </a>
                </td>

                <td class="" style="text-align:center;">
                    <?php
                    if($v->id!=1){
                        ?>
                        <a href="<?php echo url(); ?>/admin/category/child-category-list/{{ base64_encode($v->id) }}" title="View Sub Categories"><i class="fa fa-list-alt" aria-hidden="true"></i></a>
                        <?php
                    }
                    ?>
                </td>
                <!-- <td class="">
                    <?php
                    if($v->id!=1){
                        ?>
                        <select onchange="change_status(<?php echo $indx; ?>)" style="width:100px;" name="category_active_<?php echo $indx; ?>" id="category_active_<?php echo $indx; ?>">
                            <option value="1" <?php echo $v->status == '1' ? "selected='selected'" : "" ?>>Active</option>
                            <option value="0" <?php echo $v->status == '0' ? "selected='selected'" : "" ?>>Inactive</option>
                        </select>
                        <br />
                        <span class='alert-success' id="success_status_span_<?php echo $indx; ?>" style="display:none;"></span>
                        <?php
                    }
                    ?>
                </td> -->
                <td class="action-btns">
                    <?php
                    if($v->id!=1){
                        ?>
                        <a href="<?php echo url(); ?>/admin/category/edit/{{ $v->id }}"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                        <!--<a href="javascript:void(0);" onclick="delete_category({{ $v->id }})"><i class="fa fa-trash-o" aria-hidden="true"></i></a>-->
                        <?php
                    }
                    ?>
					<input type="hidden" value="<?php echo $v->id ?>" name="record_id_<?php echo $indx; ?>" id="record_id_<?php echo $indx; ?>" />
					<!--<a href="<?php echo url(); ?>/admin/category/remove/{{ $v->id }}" class="btn btn-warning">Delete</a>-->
                </td>
                
            </tr>
            <?php $indx++; ?>
            @endforeach
            <?php }else{ ?> 
            <tr><td colspan="8">No Records.</td></tr>
            <?php } ?>
               
        </tbody>
            
    </table>    

</div>
{!! $categories->appends(['search_key'=>$search_key,'active'=>$active])->render() !!}
{!! Form::close() !!}
<script>
function change_status(id){
    $("#serverloader").show();
    var this_val = $('#category_active_'+id).val();
        var this_id = $('#record_id_'+id).val();
        $.ajax({
            url: base_url+'/admin/category/status',
            type: "get",
            data: { this_val : this_val,this_id : this_id},
            success: function(data){
                $("#serverloader").hide();
                if(data == '1'){
                    $('.alert-success').html('');
                    $('#success_status_span_'+id).html('Status updated.');
                    $('#success_status_span_'+id).fadeIn('slow');
                    $('#success_status_span_'+id).fadeOut('slow');
                }
            }
        });
}
function delete_category(id){
    
    var confirm_del = confirm("If any prodcut has this category then that product category will be changed to General category after deleting this category.");
    if(confirm_del){
        window.location.href = site_url+"/admin/category/remove/"+id;
    }

}
$(document).ready(function(){
    <?php if(count($categories) > 0){ ?>

     $(".user-datatable").dataTable({
            "iDisplayLength": 10,
            "searching": false,
            "paging": true,
            "bPaginate": true,
            "sPaginationType": "full_numbers",
            "bLengthChange": true,
            "bFilter": false,
            "bInfo": false,
            "bAutoWidth": false,
            "pagingType": "full_numbers",
             "aaSorting": [],
            "aoColumns": [{"bSortable": true}, {"bSortable": true},{"bSortable": false},{"bSortable": false}],
             language : {
              sLengthMenu: "Show _MENU_"
            },
           "fnDrawCallback": function(oSettings) {
                if (oSettings._iDisplayLength > oSettings.fnRecordsDisplay()) {
                    $(oSettings.nTableWrapper).find('.dataTables_paginate').hide();
                    $("#DataTables_Table_0_length").hide();
                }
            }
        });
    <?php } ?>

});
</script>
@endsection
