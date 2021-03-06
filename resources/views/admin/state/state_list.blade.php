@extends('admin/layout/admin_template')
 
@section('content')
<style>
.country input{margin-right:10px;}

.country select{margin-right:10px;}

.country .btn{
	    position: relative;
    top: -5px;
}
</style>
  
@if(Session::has('success_message'))
<p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('success_message') }}</p>
@endif

@if(Session::has('failure_message'))
<p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('failure_message') }}</p>
@endif
<script type="text/javascript">
function goForSearch(){
    var search_key = $('#search_key').val();
    var is_active = $('#is_active').val();

    var url = '<?php echo url(); ?>/admin/state-list/<?php echo $country_id ?>?';
    url += 'search_key='+search_key+'&active='+is_active;
    window.location.href = url;
}
</script>




<div class="country">
    <table cellpadding="0" cellspacing="0" border="0" width="100%"> 

        <tr>
            <td widht="25%"></td>
            <td width="25%">
                <input type="text" value="<?php echo isset($search_key) ? $search_key : ''; ?>" name="search_key" id="search_key" placeholder="State name..." class="field" />
            </td>

            <td width="25%">
                <select name="is_active" id="is_active" class="field">
                    <option value="">Is Active</option>
                    <option value="1" <?php echo $active=='1' ? "selected='selected'" : ""; ?>>Yes</option>
                    <option value="0" <?php echo $active=='0' ? "selected='selected'" : ""; ?>>No</option>
                </select>
            </td>

            <td width="25%" style="padding:0px;text-align:right;margin-right:0px;">
                <input type="button" onclick="goForSearch();" class="btn" name="btn_search_hotel" id="btn_search_hotel" value="Search">
				<input type="button" onclick="window.location.href='<?php echo url(); ?>/admin/state-list/<?php echo $country_id ?>';" class="btn" name="btn_search_hotel" id="btn_search_hotel" value="Reset">
            </td>


        </tr>
		<tr>
			<td widht="25%"></td>
			<td widht="25%"></td>
			<td widht="25%"></td>
			<td widht="25%" style="float:right;text-align:right;margin-right:0px;">
                <input type="button" class="btn" name="btn_search_hotel" id="btn_search_hotel" value="Add State" onclick="add_state();">
            </td>
		</tr>
    </table>
</div>
 

{!! Form::open(['url' => 'admin/hotel-list','method'=>'POST', 'files'=>true,'class'=>'form-horizontal row-fluid','id'=>'cms_form']) !!}
<div class="module">


    
    

    <table cellpadding="0" cellspacing="0" border="0" class="table table-bordered table-striped  display" width="100%">
        <thead>
            <tr style="color:#FFFFFF; background:#444;">
                <th widht="10%"><input type="checkbox" name="check_all_records" id="check_all_records" onclick="selectAllRecords();" /></th>
				<th>State Name</th>
                <th>Status</th>  
                <th>Action</th>
            </tr>
        </thead>
            
            
        <tbody>
            <?php $cnt = '1' ?>
            <?php if(count($states) > 0){ ?>
            @foreach ($states as $v)
            <tr class="odd gradeX">
				<td widht="10%"><input type="checkbox" recordType="multipleRecord" multipleRecord="{{ $v->id }}" /></td>
                <td class="">{{ $v->name }}</td>
                <td class="">
                    <select onchange="change_status(<?php echo $cnt; ?>)" style="width:100px;" name="cntry_active_<?php echo $cnt; ?>" id="cntry_active_<?php echo $cnt; ?>">
                        <option value="1" <?php echo $v->status == '1' ? "selected='selected'" : "" ?>>Active</option>
                        <option value="0" <?php echo $v->status == '0' ? "selected='selected'" : "" ?>>Inactive</option>
                    </select>
                    <br />
                    <span class='alert-success' id="success_status_span_<?php echo $cnt; ?>"></span>
                </td>
                <td>
                    <a href="<?php echo url(); ?>/admin/Edit-state/{{ $country_id }}/{{ $v->id }}" class="btn btn-warning">Edit</a>
                    <input type="hidden" value="<?php echo $v->id ?>" name="record_id_<?php echo $cnt; ?>" id="record_id_<?php echo $cnt; ?>" />
                </td>
            </tr>
            <?php $cnt++; ?>
            @endforeach
			<tr>
				<td colspan="8">
					<select name="multiple_operation_status" id="multiple_operation_status">
						<option value="">Status</option>
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
					<a href="javascript:void(0);" onclick="globalActiveInactiveMultipleRecords('zones');" class="btn">Go</a>
					<div style="color:red;" id="selected_record_msg"></div>
				</td>
			</tr>
            <?php }else{ ?> 
            <tr><td colspan="8">No Records.</td></tr>
            <?php } ?>
            <tr>
				<td colspan="8" align="center"> <a href="{!! url('admin/country-list/')!!}" class="btn">Back</a></td>
			</tr>
        </tbody>
            
    </table>    
    {!! Form::close() !!}

</div>
{!! $states->appends(['search_key' => $search_key,'active'=>$active])->render() !!}

<script type="text/javascript">
function change_status(id){
    var this_val = $('#cntry_active_'+id).val();
    var this_id = $('#record_id_'+id).val();
    var base_url = "<?php echo url(); ?>/";
    //alert(this_val);return false;
    $.ajax({
        url: base_url+'admin/state/change-status',
        type: "post",
        data: { this_val : this_val,this_id : this_id ,_token: '<?php echo csrf_token(); ?>'},
        success: function(data){
            if(data == '1'){
                $('.alert-success').html('');
                $('#success_status_span_'+id).html('Status updated.');
            }
        }
    });
}
</script>

<script type="text/javascript">
function change_feature(id){
    var this_val = $('#cntry_featured_'+id).val();
    var this_id = $('#record_id_'+id).val();
    var base_url = "<?php echo url(); ?>/";
    //alert(this_val);return false;
    $.ajax({
        url: base_url+'admin/country/change-featured',
        type: "post",
        data: { this_val : this_val,this_id : this_id ,_token: '<?php echo csrf_token(); ?>'},
        success: function(data){
            if(data == '1'){
                $('.alert-success').html('');
                $('#success_featured_span_'+id).html('Featured updated.');
            }
			else if(data=='Not Image Nor Active'){
				$('#cntry_featured_'+id).val(0);
				alert("Please active status and upload image.");
			}
			else if(data=='Not Active'){
				$('#cntry_featured_'+id).val(0);
				alert("Please active status.");
			}
			else if(data=='Not Image'){
				$('#cntry_featured_'+id).val(0);
				alert("Please upload image.");
			}
			else if(data==6){
				$('#cntry_featured_'+id).val(0);
				alert("Already 6 countries are set as featured.");
			}
        }
    });
}
</script>


<script type="text/javascript">
    function del_confirm(){
        var con = confirm("Do you really want to delete this record ?");
        if(con){
            return true;
        }else{
            return false;
        }
    }
	
	function add_state(){
		window.location.href="<?php echo url().'/admin/Add-state'?>/{{ $country_id }}";
	}
</script>

<script type="text/javascript">
    function del_multiple_confirm(){
        var ans = confirm("Do you really want to delete the selected records ?");
        if(ans){
            //alert('aaaa');
            $('#cms_form').submit();
        }else{
            return false;
        }
    }
</script>

<script type="text/javascript">
    $(document).ready(function(){
        $("#checkAll").change(function () {
            $("input:checkbox").prop('checked', $(this).prop("checked"));
        });
    });
</script>

@endsection
