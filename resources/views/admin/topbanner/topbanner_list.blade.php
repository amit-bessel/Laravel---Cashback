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


<div class="country">
    <table cellpadding="0" cellspacing="0" border="0" width="100%"> 
     
		<tr>
            <td width="25%"></td>
			<td width="25%"></td>
			<td width="25%"></td>
			<td width="15%" style="float:right;margin-right:0px;text-align: right">
				<input type="button" onclick="window.location.href='<?php echo url(); ?>/admin/add-topbanner';" class="btn" name="btn_search_hotel" id="btn_search_hotel" value="Add Banner" />
			</td>
		 </tr>

    </table>
</div>

{!! Form::open(['url' => 'admin/hotel-list','method'=>'POST', 'files'=>true,'class'=>'form-horizontal row-fluid','id'=>'cms_form']) !!}



<div class="module">

    <table cellpadding="0" cellspacing="0" border="0" class="table table-bordered table-striped  display" width="100%">
        <thead>
            <tr style="color:#FFFFFF; background:#444;">
                <th width="5%">Sl No.</th>
				<th width="35%">Banner image for men</th>
                <th width="35%">Banner image for women</th>
				<th width="10%">Status</th>
				<th width="15%" align="right">Action</th>
            </tr>
        </thead>
       <tbody>
            <?php $sl_no = '1' ?>
            <?php if(count($banner_arr) > 0){ ?>
            @foreach ($banner_arr as $v)
            <tr class="odd gradeX">
				<td class="">{{ $sl_no }}</td>
				<td class="">
				<?php
				$banner_image = $v->banner_image;
				if(($banner_image!= "") && (file_exists("uploads/banner_image/big/".$banner_image)))
				{
				?>
					<img src="<?php echo url(); ?>/uploads/banner_image/big/{{ $v->banner_image }}">
				<?php
				}
				else
				{
					?>
					<img src="<?php echo url(); ?>/public/backend/images/noimage.jpeg">
					<?php
				}
				?>
                </td>
                <td class="">
                <?php
                $banner_image_women = $v->banner_image_women;
                if(($banner_image_women!= "") && (file_exists("uploads/banner_image/big/".$banner_image_women)))
                {
                ?>
                    <img src="<?php echo url(); ?>/uploads/banner_image/big/{{ $v->banner_image_women }}">
                <?php
                }
                else
                {
                    ?>
                    <img src="<?php echo url(); ?>/public/backend/images/noimage.jpeg">
                    <?php
                }
                ?>
                </td>
				<td class="">
                    <select onchange="change_status('<?php echo $v->id; ?>','<?php echo $v->is_active;?>')" style="width:100px;" name="city_active" id="city_active_<?php echo $v->id; ?>">
                        <option value="1" <?php echo $v->is_active == '1' ? "selected='selected'" : "" ?>>Active</option>
                        <option value="0" <?php echo $v->is_active == '0' ? "selected='selected'" : "" ?>>Inactive</option>
                    </select>
                    <br />
                    <span class='alert-success' id="success_status_span_<?php echo $v->id; ?>"></span>
                </td>
				<td>
					<a href="<?php echo url(); ?>/admin/edit-topbanner/{{ $v->id }}"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
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
    var status = $('#city_active_'+id).val();
    
    $.ajax({
        url: base_url+'/admin/topbanner-change-status',
        type: "post",
        data: { 'id': id,'status': status},
        success: function(data){
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
	var confirm_banner = confirm("Are You sure you want to delete this banner?");
	if(confirm_banner){
		window.location.href = site_url+"/admin/delete-topbanner/"+id;
	}

}
</script>
{!! Form::close() !!}
@endsection
