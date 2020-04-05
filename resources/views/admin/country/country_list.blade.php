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
    var is_featured = $('#is_featured').val();
    var is_active = $('#is_active').val();

    var url = '<?php echo url(); ?>/admin/country-list?';
    url += 'search_key='+search_key+'&featured='+is_featured+'&active='+is_active;
    window.location.href = url;
}
</script>




<div class="country">
    <table cellpadding="0" cellspacing="0" border="0" width="100%"> 

        <tr>
            <td widht="50%"></td>
            <td width="25%">
                <input type="text" value="<?php echo isset($search_key) ? $search_key : ''; ?>" name="search_key" id="search_key" placeholder="Country name..." class="field" />
            </td>
            <td width="25%">
                <select name="is_active" id="is_active" class="field">
                    <option value="">Is Active</option>
                    <option value="1" <?php echo $active=='1' ? "selected='selected'" : ""; ?>>Yes</option>
                    <option value="0" <?php echo $active=='0' ? "selected='selected'" : ""; ?>>No</option>
                </select>
            </td>

            <td width="15%" style="padding:0px; text-align:right;">
                <input type="button" onclick="goForSearch();" class="btn" name="btn_search_hotel" id="btn_search_hotel" value="Search">
				<input type="button" onclick="window.location.href='<?php echo url(); ?>/admin/country-list';" class="btn" name="btn_search_hotel" id="btn_search_hotel" value="Reset" />
            </td>

        </tr>
		<tr>
            <td width="25%"></td>
			<td width="25%"></td>
			<td width="25%"></td>
			<td width="15%" style="float:right;margin-right:0px;text-align: right">
				<input type="button" onclick="window.location.href='<?php echo url(); ?>/admin/add-country-details';" class="btn" name="btn_search_hotel" id="btn_search_hotel" value="Add Country" />
			</td>
		 </tr>

    </table>
</div>

{!! Form::open(['url' => 'admin/hotel-list','method'=>'POST', 'files'=>true,'class'=>'form-horizontal row-fluid','id'=>'cms_form']) !!}
<div class="module">

    <table cellpadding="0" cellspacing="0" border="0" class="table table-bordered table-striped  display" width="100%">
        <thead>
            <tr style="color:#FFFFFF; background:#444;">
				<th widht="10%" style="align:center;"><input type="checkbox" name="check_all_records" id="check_all_records" onclick="selectAllRecords();" /></th>
                <th width="30%">Country Name</th>
                <th width="10%">Iso Code</th>
                <th width="20%">Status</th>  
                <th width="30%" align="right">Action</th>
            </tr>
        </thead>
            
            
        <tbody>
            <?php $cnt = '1' ?>
            <?php if(count($countries) > 0){ ?>
            @foreach ($countries as $v)
            <tr class="odd gradeX">
				<td widht="10%"><input type="checkbox" recordType="multipleRecord" multipleRecord="{{ $v->id }}" /></td>
                <td class="">{{ $v->name }}</td>
                <td class="">{{ $v->iso_code_2 }}</td>
                <td class="">
                    <select onchange="change_status(<?php echo $cnt; ?>)" style="width:100px;" name="cntry_active_<?php echo $cnt; ?>" id="cntry_active_<?php echo $cnt; ?>">
                        <option value="1" <?php echo $v->status == '1' ? "selected='selected'" : "" ?>>Active</option>
                        <option value="0" <?php echo $v->status == '0' ? "selected='selected'" : "" ?>>Inactive</option>
                    </select>
                    <br />
                    <span class='alert-success' id="success_status_span_<?php echo $cnt; ?>"></span>
                </td>
                <td>
					<input type="hidden" value="<?php echo $v->id ?>" name="record_id_<?php echo $cnt; ?>" id="record_id_<?php echo $cnt; ?>" />
					
					<a href="<?php echo url(); ?>/admin/state-list/{{ $v->id }}" class="btn">State List</a>
					<a href="<?php echo url(); ?>/admin/city-list/{{ $v->id }}" class="btn">City List</a>
					<a href="<?php echo url(); ?>/admin/edit-country-details/{{ $v->id }}" class="btn btn-warning">Edit</a>
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
					<a href="javascript:void(0);" onclick="globalActiveInactiveMultipleRecords('countries');" class="btn">Go</a>
					<div style="color:red;" id="selected_record_msg"></div>
				</td>
			</tr>
            <?php }else{ ?> 
            <tr><td colspan="8">No Records.</td></tr>
            <?php } ?>
               
        </tbody>
            
    </table>    
    <?php /*<table border="0" cellpadding="0" cellspacing="0">
        <tr>
            <td><a href="javascript:void();" onclick="return del_multiple_confirm()">Delete Multiple Record.</a></td>
            <input type="hidden" value="<?php echo $cnt; ?>" name="hid_tot_record" id="hid_tot_record" />
        </tr>
    </table>*/ ?>
    {!! Form::close() !!}

</div>
{!! Form::close() !!}
<?php  //echo $countries->render() ?>
{!! $countries->appends(['search_key' => $search_key,'featured'=>$featured,'active'=>$active])->render() !!}


<script type="text/javascript">
function change_status(id){
    var this_val = $('#cntry_active_'+id).val();
    var this_id = $('#record_id_'+id).val();
    var base_url = "<?php echo url(); ?>/";
    //alert(this_val);return false;
    $.ajax({
        url: base_url+'admin/country/change-status',
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

<script type="text/javascript">
    function set_departure_country(country_id){
		var base_url = "<?php echo url(); ?>/";
		//alert(country_id);
		var departure_country_value = $('#departure_country_'+country_id).val();
		//alert(departure_country_value);
		$.ajax({
			url: base_url+'admin/country/set-departure-country',
			type: "get",
			data: { country_id : country_id,departure_country_value : departure_country_value},
			success: function(data){
				//alert(data);
				if(data == '1'){
					$('.alert-success').html('');
					$('#success_departure_span_'+country_id).html('Successfully updated.');
				}
			}
		});
	}
</script>

@endsection
