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
<p class="alert {{ Session::get('alert-class', 'alert-success') }}">{{ Session::get('success_message') }}</p>
@endif

@if(Session::has('failure_message'))
<p class="alert {{ Session::get('alert-class', 'alert-danger') }}">{{ Session::get('failure_message') }}</p>
@endif


<script type="text/javascript">
function goForSearch(){
    var search_key = $('#search_key').val();
    var is_active = $('#is_active').val();

    var url = '<?php echo url(); ?>/admin/brands/list?';
    url += 'search_key='+search_key+'&active='+is_active;
    window.location.href = url;
}
</script>

<div class="country">
    <table cellpadding="0" cellspacing="0" border="0" width="100%"> 

       <tr>
            <td width="30%">
                <input type="text" value="<?php echo isset($search_key) ? $search_key : ''; ?>" name="search_key" id="search_key" placeholder="Brand name..." class="span5" />
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
                <input type="button" onclick="window.location.href='<?php echo url(); ?>/admin/brands/list';" class="btn" name="btn_search_hotel" id="btn_search_hotel" value="Reset" />
            </td>
            <td></td>

        </tr>
     
		<tr>
            <td width="25%"></td>
			<td width="25%"></td>
			<td width="25%"></td>
			<td width="15%" style="float:right;margin-right:0px;text-align: right">
				<input type="button" onclick="window.location.href='<?php echo url(); ?>/admin/brands/add';" class="btn" name="btn_search_hotel" id="btn_search_hotel" value="Add Brand" />
			</td>
		 </tr>

    </table>
</div>

<div id="serverloader" class="dataTables_processing" style="display: none;"></div>

<div class="module">

    <table cellpadding="0" cellspacing="0" border="0" class="table table-bordered table-striped  display" width="100%">
        <thead>
            <tr style="color:#FFFFFF; background:#444;">
                <th width="10%">Sl No.</th>
				<th width="50%">Brand Name</th>
                <th width="15%">Map Product</th>
                <th width="15%">View Product</th>
				<th width="15%">Status</th>
				<th width="25%" align="right">Action</th>
            </tr>
        </thead>
       <tbody>
            <?php $sl_no = '1' ?>
            <?php if(count($brand_arr) > 0){ ?>
            @foreach ($brand_arr as $v)
            <tr class="odd gradeX">
				<td class="">{{ $sl_no }}</td>
				<td class="">{{ $v->brand_name }}</td>

                <td class="" style="text-align:center;">
                    <a href="<?php echo url(); ?>/admin/brands/search-brand/{{ $v->id }}" title="Map Product"><i class="fa fa-list-alt" aria-hidden="true"></i>
                    </a>
                </td>

                <td class="" style="text-align:center;">
                    <a href="<?php echo url(); ?>/admin/brands/brand-product-list/{{ $v->id }}" title="View Product"><i class="fa fa-list-alt" aria-hidden="true"></i>
                    </a>
                </td>

				<td class="">
                    <select onchange="change_status('<?php echo $v->id; ?>','<?php echo $v->status;?>')" style="width:100px;" name="city_active" id="city_active_<?php echo $v->id; ?>">
                        <option value="1" <?php echo $v->status == '1' ? "selected='selected'" : "" ?>>Active</option>
                        <option value="0" <?php echo $v->status == '0' ? "selected='selected'" : "" ?>>Inactive</option>
                    </select>
                    <br />
                    <span class='alert-success' id="success_status_span_<?php echo $v->id; ?>"></span>
                </td>

				<td>
					<a href="<?php echo url(); ?>/admin/brands/edit/{{ $v->id }}"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
					<a href="javascript:void(0);" onclick="delete_banner(<?php echo $v->id; ?>);"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
                </td>
            </tr>
            <?php $sl_no++; ?>
            @endforeach
			
            <?php }else{ ?> 
            <tr><td colspan="8">No Records.</td></tr>
            <?php } ?>
               
        </tbody>
            
    </table>
		

</div>
<script>

 function change_status(id,status){

    $("#serverloader").show();

    if (status == 0 ) {
      status = 1;
    }
    else{
      status = 0;
    }
    
    $.ajax({
        url: base_url+'/admin/brands/brand-change-status',
        type: "post",
        data: { 'id': id,'status': status},
        success: function(data){
            $("#serverloader").hide();
            if(data == '1'){
                $('.alert-success').hide();
                $('#success_status_span_'+id).show();
                $('#success_status_span_'+id).html('Status updated.');
                setTimeout(function() {
                    $('#success_status_span_'+id).fadeOut('fast');
                }, 400);
            }
        }
    });
}

function delete_banner(id){
	var confirm_banner = confirm("Are You sure you want to delete this brand?");
	if(confirm_banner){
		window.location.href = site_url+"/admin/brands/delete/"+id;
	}

}
</script>

@endsection
