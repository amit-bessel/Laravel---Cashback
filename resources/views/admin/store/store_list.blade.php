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

<link rel="stylesheet" href="/public/sweetalert/dist/sweetalert.css">
<script src="/public/sweetalert/dist/sweetalert.min.js"></script>
<script type="text/javascript">
function setPercentageVendor(percentage,oldpercentage,venderid,api){

    if(percentage != oldpercentage && percentage<100){
        $.ajax({
            url: base_url+'/admin/vendors/chnage-percentage',
            type: "get",
            data: { percentage : percentage,venderid : venderid ,api : api},
            success: function(data){
                if(data == '1'){
                    $('.alert-success').html('');
                    $('#success_status_span_'+venderid).html('Percentage updated.');
                    $('#success_status_span_'+venderid).fadeIn('slow');
                    $('#success_status_span_'+venderid).fadeOut('slow');
                }
            }
        });
    }else{
        $('.alert-error').html('');
        $('#error_status_span_'+venderid).html('Eroor input.');
        $('#error_status_span_'+venderid).fadeIn('slow');
        $('#error_status_span_'+venderid).fadeOut('slow');
    }
}

function goForSearch(){
    var search_key = $('#search_key').val();
    var is_active = $('#is_active').val();

    var url = '<?php echo url(); ?>/admin/vendors/list?';
    url += 'search_key='+search_key+'&active='+is_active;
    window.location.href = url;
}
</script>


<link href="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.4.6/bootstrap-editable/css/bootstrap-editable.css" rel="stylesheet"/>
<script src="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.4.6/bootstrap-editable/js/bootstrap-editable.min.js"></script>



<div class="country">
    <!-- <table cellpadding="0" cellspacing="0" border="0" width="100%"> 
        <tr>
            <td width="" colspan="3">
                <input type="text" value="<?php //echo isset($search_key) ? $search_key : ''; ?>" name="search_key" id="search_key" placeholder="Vendor name..." class="span5" width="100%" onkeyup="getFromLocation('search_key');" />
            </td>
             <td width="30%">
                
            </td>
        </tr>
        <tr>
            <td width="30%" style="padding:0px;">
                    <input type="button" onclick="goForSearch();" class="btn" name="btn_search_hotel" id="btn_search_hotel" value="Search">
                    <input type="button" onclick="window.location.href='<?php //echo url(); ?>/admin/vendors/list';" class="btn" name="btn_search_hotel" id="btn_search_hotel" value="Reset" />
            </td>
            
        </tr>
    </table> -->
</div>

{!! Form::open(['url' => 'admin/hotel-list','method'=>'POST', 'files'=>true,'class'=>'form-horizontal row-fluid','id'=>'cms_form']) !!}
<div class="module">

    <table cellpadding="0" cellspacing="0" border="0" class="table table-bordered table-striped  display user-datatable" width="100%">
        <thead>
            <tr style="color:#FFFFFF; background:#444;">
                <th width="5%">Sl No.</th> 
                <th width="25%">Store Name</th>
                <th width="10%">Map Vendor</th>
                <th width="10%" align="center">Action</th>
            </tr>
        </thead>

        <tbody>
            <?php $indx = $sl_no ?>
            <?php if(count($stores_arr) > 0){ ?>
            @foreach ($stores_arr as $v)
                <tr class="odd gradeX">
                <td class="">{{ $indx }}</td>
                <td class="">{{ $v->name }}</td>

                <td class="" style="text-align:center;">
                    <a href="<?php echo url(); ?>/admin/stores/vendor-list/{{base64_encode($v->id)}}" title="Map Vendor"><i class="fa fa-list-alt" aria-hidden="true"></i>
                    </a>
                </td>
               <td class="" style="text-align:center;">

                    <a href="<?php echo url(); ?>/admin/stores/edit/{{base64_encode($v->id)}}"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
<!--          Changed start at 5th oct 2017                             -->
                    <?php if($v->status == 1){ ?>
                        <a href="javascript:void(0);" onclick="storeVisibility({{$v->id}},0);"><i class="fa fa-check" aria-hidden="true"  title="Active"></i></a>
                    <?php } ?>
                    <?php if($v->status == 0){ ?>
                        <a href="javascript:void(0);" onclick="storeVisibility({{$v->id}},1);"><i class="fa fa-times" aria-hidden="true"  title="Inactive"></i></a>
                    <?php } ?>
<!--          Changed end at 5th oct 2017                             -->
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
{!! $stores_arr->appends(['search_key'=>$search_key,'active'=>$active])->render() !!}
{!! Form::close() !!}
<script>

$(function(){
        //edit form style - popup or inline
    $.fn.editable.defaults.mode = 'inline';
   
    $('.pUpdate').editable({

        validate: function(value) {
            if($.trim(value) == '') 
                return 'Value is required.';
            var regexp = new RegExp("[+]?([0-9]*[.])?[0-9]+");
            if (!regexp.test(value)) {
                return 'This input not valid';
            }
            var regexp = new RegExp("(0*(?:[1-9][0-9]?|100))");
            if (!regexp.test(value)) {
                return 'value more than 100';
            }
    },

    type: 'text',
    url: base_url+'/admin/vendors/chnage-percentage',  
    title: 'Edit Status',
    placement: 'top', 
    send:'always',
    ajaxOptions: {
        dataType: 'json'
    }
 });

    $('.vUpdate').editable({

        validate: function(value) {
            if($.trim(value) == '') 
                return 'Value is required.';

            var pattern = /^(http|https):\/\/[\w\-_]+(\.[\w\-_]+)+([\w\-\.,@?^=%&amp;:/~\+#]*[\w\-\@?^=%&amp;/~\+#])+(.[a-z])?/;
            var regex = new RegExp(pattern);
            if (!value.match(regex)) {
                return 'This not valid url';
            }
    },

    type: 'text',
    url: base_url+'/admin/vendors/chnage-vendor-url',  
    title: 'Edit Status',
    placement: 'top', 
    send:'always',
    ajaxOptions: {
        dataType: 'json'
    }
 });
});
// jQuery ".Class" SELECTOR.
$(document).ready(function() {

    $('#vendor_percentage').keypress(function (event) {
            return isNumber(event, this)
        });
    });

    // THE SCRIPT THAT CHECKS IF THE KEY PRESSED IS A NUMERIC OR DECIMAL VALUE.
function isNumber(evt, element) {

    var charCode = (evt.which) ? evt.which : event.keyCode

    if (
        (charCode != 45 || $(element).val().indexOf('-') != -1) &&      // “-” CHECK MINUS, AND ONLY ONE.
        (charCode != 46 || $(element).val().indexOf('.') != -1) &&      // “.” CHECK DOT, AND ONLY ONE.
        (charCode < 48 || charCode > 57))
        return false;

    return true;
}

function getFromLocation(serach_by)
{
    $("#"+serach_by).autocomplete({
        source: "<?php echo url() ?>/admin/vendors/search-vendor?search_by="+serach_by,
       
        select: function( event, ui ) {
           $("#"+serach_by).val( ui.item.label );
           return false;
        },
        minLength: 2
    });
}

function storeVisibility(store_id,stat){
   /* console.log(store_id);
    console.log(stat);*/
   /* swal ( "Oops" ,  "Something went wrong!" ,  "error" );*/
   $(this).blur();
   swal({

                title: "Change visibility of this store ?",
                text: "Once you change the visibility. The effect will be automatically reflect to frontend.",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn-danger",
                confirmButtonText: "Yes, Change!",
                closeOnConfirm: false,
                showLoaderOnConfirm: true,

                },
        function(){
            $.ajax({
                    type: "POST",
                url: site_url+"/admin/stores/change-visibility/"+store_id,
                data: {status:stat},
                success: function(data){
                    if(data.status==1){
                       /* swal("Success!", " Successfully updated", "success",{ showConfirmButton: false });*/
                         swal({
                                title: "Success!",
                                text: "Successfully updated",
                                type: "success",
                                showCancelButton: false,
                                showConfirmButton: false
                            });
                        location.reload();
                    }
                }
            });
        });


}

</script>
@endsection
