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
            <td width="15%" style="float:right;margin-right:0px;text-align: right"><a href="javascript:void(0);" onclick="deleteAll();">
                    <i class="fa fa-trash-o" style='font-size:20px;'></i>
                           &nbsp;&nbsp;Delete all
             </a></td>
            <td width="15%" style="float:right;margin-right:0px;text-align: right">
                <input type="button" onclick="window.location.href='<?php echo url(); ?>/admin/city/add-city';" class="btn" name="btn_search_hotel" id="btn_search_hotel" value="Add City" />
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
                <th width="20%">Id</th>
                <th width="20%">City Name</th>
                <th width="20%">Arabic City Name</th>
                <th width="20%">Status</th>
                <th width="20%" align="right">Action</th>
            </tr>
        </thead>
       <tbody>
            <?php $cnt = '1' ?>
            <?php if(count($city_arr) > 0){ ?>
            @foreach ($city_arr as $v)
            <tr class="odd gradeX">
                <td ><input type="checkbox" id="ad_Checkbox" class="ads_Checkbox" value="{{ $v->id }}" recordType="multipleRecord" multipleRecord="{{ $v->id }}" /></td>
                <td class=""> {{ $v->id }} </td>
                <td class="">{{ $v->city_eng}}</td>
                <td class="">{{ $v->city_arabic}}</td>
                <td class="">
                    <select onchange="change_status('<?php echo $v->id; ?>','<?php echo $v->is_active;?>')" style="width:100px;" name="city_active" id="city_active_<?php echo $v->id; ?>">
                        <option value="1" <?php echo $v->is_active == '1' ? "selected='selected'" : "" ?>>Active</option>
                        <option value="0" <?php echo $v->is_active == '0' ? "selected='selected'" : "" ?>>Inactive</option>
                    </select>
                    <br />
                    <span class='alert-success' id="success_status_span_<?php echo $v->id; ?>"></span>
                </td>
                <td>
                    <a href="<?php echo url(); ?>/admin/city/edit-city/{{ $v->id }}" class="btn btn-warning">Edit</a>
                    <a href="javascript:void(0);" onclick="delete_city(<?php echo $v->id; ?>);" class="btn btn-warning">Delete</a>
                </td>
            </tr>
            <?php $cnt++; ?>
            @endforeach
            
            <?php }else{ ?> 
            <tr><td colspan="8">No Records.</td></tr>
            <?php } ?>
               
        </tbody>
            
    </table>
        

</div>

<script type="text/javascript">
function change_status(id,status){
    if (status == 0 ) {
      status = 1;
    }
    else{
      status = 0;
    }
    
    $.ajax({
        url: base_url+'/admin/city/change-status',
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

function delete_city(id){
    var confirm_banner = confirm("Are You sure you want to delete this city?");
    if(confirm_banner){
        window.location.href = site_url+"/admin/city/delete-city/"+id;
    }


}


 function deleteAll()
    {  
        var val = [];
        $(':checkbox:checked:checked.ads_Checkbox').each(function(i){
          val[i] = $(this).val();
        });
        var  ids = val.join();;
        var numberOfChecked = $('input:checkbox:checked.ads_Checkbox').length;
        if(numberOfChecked >0)
        {
            if (confirm("Are you sure you want to delete?")) {
                $.ajax({
                    type: "GET",
                    url: base_url+'/admin/city/delete-all',
                    data:{ids:ids},
                    success: function(msg){
                         window.location = base_url+'/admin/city/list';
                     }
                });
            }
         }
         else
         {

           alert("Please select One");
         }
    }
</script>


{!! Form::close() !!}
@endsection
