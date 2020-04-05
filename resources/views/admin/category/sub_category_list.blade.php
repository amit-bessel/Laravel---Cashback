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

    var url = '<?php echo url(); ?>/admin/category/child-category-list/<?php echo base64_encode($category_id); ?>?';
    url += 'search_key='+search_key+'&active='+is_active;
    window.location.href = url;
}
</script>




<div class="country">
    <table cellpadding="0" cellspacing="0" border="0" width="100%"> 
        <tr>
            <td width="30%">
                <input type="text" value="<?php echo isset($search_key) ? $search_key : ''; ?>" name="search_key" id="search_key" placeholder="Sub category name..." class="span5" />
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
				<input type="button" onclick="window.location.href='<?php echo url(); ?>/admin/category/child-category-list/<?php echo base64_encode($category_id); ?>';" class="btn" name="btn_search_hotel" id="btn_search_hotel" value="Reset" />
            </td>
            <td></td>

        </tr>
		<tr>
            <td width="25%"></td>
			<td width="25%"></td>
			<td width="25%"></td>
			<td width="15%" style="float:right;margin-right:0px;text-align: right">
				<input type="button" onclick="window.location.href='<?php echo url(); ?>/admin/category/add-sub-category/<?php echo base64_encode($category_id) ?>';" class="btn" name="btn_search_hotel" id="btn_search_hotel" value="Add Sub Category" />
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
                <th width="60%">Sub Category Name</th>
                <th width="15%">Map Product</th>
                <th width="15%">View Product</th>
                <?php
                    if($count_layer<2){
                        ?>
                        <th width="15%">Sub Category</th>
                        <?php
                    }
                ?>
                <!-- <th width="15%">Status</th> -->  
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
                <?php 

                if($count_layer<2){
                    ?>
                    <td class="" style="text-align:center;">
                        <?php

                        if($v->id!=1){
                            ?>
                            <a href="<?php echo url(); ?>/admin/category/child-category-list/{{ base64_encode($v->id) }}" title="View Sub Categories"><i class="fa fa-list-alt" aria-hidden="true"></i></a>
                            <?php
                        }
                        ?>
                    </td>
                    <?php
                }
                ?>

                <!-- <td class="">
                    <select onchange="change_sub_category_status(<?php echo $indx; ?>)" style="width:100px;" name="category_active_<?php echo $indx; ?>" id="category_active_<?php echo $indx; ?>">
                        <option value="1" <?php echo $v->status == '1' ? "selected='selected'" : "" ?>>Active</option>
                        <option value="0" <?php echo $v->status == '0' ? "selected='selected'" : "" ?>>Inactive</option>
                    </select>
                    <br />
                    <span class='alert-success' id="success_status_span_<?php echo $indx; ?>" style="display:none;"></span>
                </td> -->
                <td class="action-btns">
					<input type="hidden" value="<?php echo $v->id ?>" name="record_id_<?php echo $indx; ?>" id="record_id_<?php echo $indx; ?>" />
					<!--<a href="<?php echo url(); ?>/admin/category/remove/{{ $v->id }}" class="btn btn-warning">Delete</a>-->

                        <?php
                        if(in_array($v->slug, $main_category_slugs)){
                            ?>
                            <a href="<?php echo url(); ?>/admin/category/edit-sub-category/{{base64_encode($category_id)}}/{{ base64_encode($v->id) }}"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                            <?php
                        }
                        else{
                            ?>
                            <a href="<?php echo url(); ?>/admin/category/edit-sub-category/{{base64_encode($category_id)}}/{{ base64_encode($v->id) }}"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>

                            <a href="javascript:void(0);" onclick="delete_sub_category('{{ base64_encode($category_id)}}','{{base64_encode($v->id)}}')"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
                            <?php
                        }
                        ?>
                        
                </td>
                
            </tr>
            <?php $indx++; ?>
            @endforeach
            <?php }else{ ?> 
            <tr><td colspan="8">No Records.</td></tr>
            <?php } ?>
        <tr>
            <td colspan="8">
                <input type="button" onclick="window.history.back();" class="btn" name="btn_search_hotel" id="btn_search_hotel" value="Back" />
            </td>
        </tr>      
        </tbody>
            
    </table>    

</div>
{!! $categories->appends(['search_key'=>$search_key,'active'=>$active])->render() !!}
{!! Form::close() !!}
<script>
function change_sub_category_status(id){
     $("#serverloader").show();
    var this_val = $('#category_active_'+id).val();
        var this_id = $('#record_id_'+id).val();
        $.ajax({
            url: base_url+'/admin/category/sub-category-status',
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
function delete_sub_category(category_id,sub_category_id){
    //alert("test");
    var confirm_del = confirm("If any prodcut has this category then that product category will be changed to General category after deleting this category.");
    if(confirm_del){
        window.location.href = site_url+"/admin/category/remove-sub-category/"+category_id+"/"+sub_category_id;
    }

}
</script>
@endsection
