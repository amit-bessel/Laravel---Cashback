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
    <table cellpadding="0" cellspacing="0" border="0" width="100%"> 
        <tr>
            <td width="" colspan="3">
                <input type="text" value="<?php echo isset($search_key) ? $search_key : ''; ?>" name="search_key" id="search_key" placeholder="Vendor name..." class="span5" width="100%" onkeyup="getFromLocation('search_key');" />
            </td>
             <td width="30%">
                <select name="is_active" id="is_active" class="field">
                    <option value="">Api Name</option>
                    <option value="LS" <?php echo $active=='LS' ? "selected='selected'" : ""; ?>>Link Share</option>
                    <option value="CJ" <?php echo $active=='CJ' ? "selected='selected'" : ""; ?>>CJ</option>
                </select>
            </td>
        </tr>
        <tr>
            <td width="30%" style="padding:0px;">
                    <input type="button" onclick="goForSearch();" class="btn" name="btn_search_hotel" id="btn_search_hotel" value="Search">
                    <input type="button" onclick="window.location.href='<?php echo url(); ?>/admin/vendors/list';" class="btn" name="btn_search_hotel" id="btn_search_hotel" value="Reset" />
            </td>
            
        </tr>
    </table>
</div>

{!! Form::open(['url' => 'admin/hotel-list','method'=>'POST', 'files'=>true,'class'=>'form-horizontal row-fluid','id'=>'cms_form']) !!}
<div class="module">
 <div class="table-responsive">
    <table cellpadding="0" cellspacing="0" border="0" class="table table-bordered table-striped  display user-datatable" width="100%">
        <thead>
            <tr style="color:#FFFFFF; background:#444;">
                <th width="3%">Sl No.</th> 
                <th width="25%">Vendor Name</th>
                <th width="7%">View Product</th>
                <th width="5%">Discount</th>
                <!-- <th width="5%" align="center">From</th> -->
                <th width="40%" align="center">Website</th>
                <th width="15%">Status</th>
                <th width="5%" align="center">Action</th>
            </tr>
        </thead>

        <tbody>
            <?php $indx = $sl_no ?>
            <?php if(count($all_vendors) > 0){ ?>
            @foreach ($all_vendors as $v)
            <?php $venderid = ($v['api']=='CJ')?$v['advertiser-id']:$v['advertiser-id'];?>
                <tr class="odd gradeX">
                <td class="">{{ $indx }}</td>
                <td class="">{{ $v['advertiser-name'] }}</td>

                <td class="" style="text-align:center;">
                    <a href="<?php echo url(); ?>/admin/products/list?search_key=&active=&srch_category_id=&srch_brand_id=&srch_vendor_id={{ $venderid }}" title="Veiw Product"><i class="fa fa-list-alt" aria-hidden="true"></i>
                    </a>
                </td>

               <td class="" style="text-align:center;">
               <!-- <input type="text" name="vendor_percentage" onkeyup="setPercentageVendor(this.value,{{$v['percentage']}},{{$venderid}},'{{$v['api']}}')" id="vendor_percentage" value="{{ $v['percentage'] }}"> -->
                
                <a href="#" id="username" class="pUpdate" data-type="text" data-api="{{$v['api']}}" data-pk="{{$venderid}}={{$v['api']}}" data-title="Enter percentage">{{ $v['percentage'] }}</a>
                <br />
                        <span class='alert-success' id="success_status_span_<?php echo $venderid; ?>" style="display:none;"></span>
                        <span class='alert-error' id="error_status_span_<?php echo $venderid; ?>" style="display:none;"></span>
               </td> 
                
                <!-- <td class="" style="text-align:center;">
                    <span>{{($v['api']=='LS'?'Link Share':'CJ')}}</span>
                </td> -->

                <td class="" style="text-align:center;width: 100px;word-break: break-all;">
                     <a href="#" id="vendorurl" class="vUpdate" data-type="text" data-api="{{$v['api']}}" data-pk="{{$venderid}}={{$v['api']}}" data-title="Enter Url">{{ $v['vendor_url'] }}</a>
                    <br />
                    <span class='alert-success' id="success_status_span_<?php echo $venderid; ?>" style="display:none;"></span>
                    <span class='alert-error' id="error_status_span_<?php echo $venderid; ?>" style="display:none;"></span>
               </td>

                <td class="">
                    <?php
                        if($v['status'] == 1){
                            $status_html = '<select class="custom-select table-custom-select" name="status" id="user_active_'.$v['id'].'" onchange="userJs.remove_vendor(this.value,'.$v['id'].')"><option value="1" selected>Active</option><option value="0">Inactive</option></select><br /><span class="alert-success" id="success_status_span_'.$v['id'].'" style="display:none;"></span>';
                        }else{
                            $status_html = '<select class="custom-select table-custom-select" name="status" id="user_active_'.$v['id'].'" onchange="userJs.remove_vendor(this.value,'.$v['id'].')"><option value="1">Active</option><option value="0" selected>Inactive</option></select><span class="alert-success" id="success_status_span_'.$v['id'].'" style="display:none;"></span>';
                        }
                        echo $status_html;
                    ?>
                  
                </td>

               <td class="" style="text-align:center;">
                    <a href="<?php echo url(); ?>/admin/vendors/edit/{{ base64_encode($v['id']) }}" class="edit-icon"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
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
</div>
{!! $all_vendors->appends(['search_key'=>$search_key,'active'=>$active])->render() !!}
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
</script>
{!! HTML::script(url().'/public/backend/scripts/user.js') !!}
@endsection
