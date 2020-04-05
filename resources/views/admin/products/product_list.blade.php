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
    var srch_category_id = $("#category_id option:selected").val();
    var srch_brand_id = $("#brand_id option:selected").val();
    var srch_vendor_id = $("#vendor_id option:selected").val();
    var srch_gender_id = $("#srch_gender_id").val();
    var url = '<?php echo url(); ?>/admin/products/list?';
    url += 'search_key='+search_key+'&active='+is_active+'&srch_category_id='+srch_category_id+'&srch_brand_id='+srch_brand_id+'&srch_vendor_id='+srch_vendor_id+'&srch_gender_id='+srch_gender_id;
    window.location.href = url;
}
</script>




<div class="country">

    <table width="100%" cellpadding="0" cellspacing="0" border="0"> 
        <tr>
            <td width="" colspan="2">
                <input type="text" value="{{$search_key}}" name="search_key" id="search_key" placeholder="Product name..." class="span5" width="100%" onkeyup="getFromLocation('search_key');" />
            </td>
            
              <td class="first-row">
                <select name="is_active" id="is_active" class="field">
                    <option value="">Status</option>
                    <option value="1" <?php echo $active=='1' ? "selected='selected'" : ""; ?>>Active</option>
                    <option value="0" <?php echo $active=='0' ? "selected='selected'" : ""; ?>>Inactive</option>
                </select>
            </td>
        </tr>
        <tr>
            <td class="country-btn">
               
             <select name="category_id" id="category_id" style="width:100%;" class="selectpicker" data-live-search="true">
                        <option value="">---Select Category---</option>
                        <?php
                            foreach($category_array as $main_category){
                                //if(count($main_category['children'])){
                                    ?>
                                    <option value="{{$main_category['id']}}" label="{{$main_category['name']}}" <?php echo ($srch_category_id==$main_category['id'])?'selected="selected"':'' ?>>{{$main_category['name']}}</option>
                                    <?php
                                //}
                                
                            }
                        ?>
                </select>

                <select name="brand_id" id="brand_id" style="width:100%;" class="selectpicker" data-live-search="true">
                        <option value="">---Select Brand---</option>
                        <?php
                            foreach($brand_array as $main_brand){?>
                                    <option value="{{$main_brand['id']}}" label="{{$main_brand['brand_name']}}" <?php echo ($srch_brand_id==$main_brand['id'])?'selected="selected"':'' ?>>{{$main_brand['brand_name']}}</option>
                                <?php
                            }
                        ?>
                </select>
            
                <select name="vendor_id" id="vendor_id" style="width:100%;" class="selectpicker" data-live-search="true">
                        <option value="">---Select Vendor---</option>
                        <?php
                            foreach($all_vendor as $main_vendor){
                                $venderid = isset($main_vendor['advertiser-id'])?$main_vendor['advertiser-id']:$main_vendor['ad-id'];
                                ?>
                                    <option value="{{$venderid}}" label="{{$main_vendor['advertiser-name']}}" <?php echo ($srch_vendor_id==$venderid)?'selected="selected"':'' ?>>{{$main_vendor['advertiser-name']}}</option>
                                <?php
                            }
                        ?>
                </select>
            
            </td>

            
          
        </tr>
        <tr>

            <td width="50%" style="padding:0px;">
                <select name="srch_gender_id" id="srch_gender_id" style="width:100%;">
                    <option value="">---Select Gender---</option>
                    <option value="1">Male</option>
                    <option value="2">Female</option>
                </select>
            </td>
            
        </tr>
        <tr>

            
          
            
            <td width="50%" style="padding:0px;">
                    <input type="button" onclick="goForSearch();" class="btn" name="btn_search_hotel" id="btn_search_hotel" value="Search">
                    <input type="button" onclick="window.location.href='<?php echo url(); ?>/admin/products/list';" class="btn" name="btn_search_hotel" id="btn_search_hotel" value="Reset" />
            </td>
            
        </tr>
		

    </table>
</div>

<div id="serverloader" class="dataTables_processing" style="display: none;"></div>

{!! Form::open(['url' => 'admin/hotel-list','method'=>'POST', 'files'=>true,'class'=>'form-horizontal row-fluid','id'=>'cms_form']) !!}
<div class="module">
<div class="table-responsive category-table">
    <table width="100%" cellpadding="0" cellspacing="0" border="0" class="table table-bordered table-striped  display user-datatable">
        <thead>
            <tr style="color:#FFFFFF; background:#444;">
                <!-- <th width="5">Sl No.</th>  -->
                <th width="130">Product Name</th>
                <th width="130">Vendor Name</th>
                <th width="130">Category</th>
                <th width="130">Image</th>
                <th width="100">Status</th>
                <th width="100">Popular Product</th>  
                <th width="100" align="right">Action</th>
            </tr>
        </thead>
            
            
        <tbody>
            <?php $indx = $sl_no ?>
            <?php if(count($products) > 0){ ?>
            @foreach ($products as $v)

            <tr class="odd gradeX">
                <!-- <td width="5" class="">{{ $indx }}</td> -->
                <td class="">{{ $v->name }}</td>
                <td  class="">{{ $v['advertiser-name'] }}</td>
                <td class="" id="category_td_{{ $v->id }}">
               
                        <select name="category_id" id="category_id" style="width:100%;"  onchange="changeProductCategory(this.value,{{$v->id}});" class="selectpicker" data-live-search="true">
                        <?php
                            foreach($category_array as $main_category){
                                //if(count($main_category['children'])){
                                    ?>
                                    <option value="{{$main_category['id']}}" label="{{$main_category['name']}}" {{ ($v->product_category->id==$main_category['id'])?'selected="selected"':'' }}>{{$main_category['name']}}</option>
                                    
                                    <?php
                                //}
                                
                                
                            }
                        ?>
                        </select>
                        <br />
                        
                <span class='alert-success' id="success_category_span_<?php echo $v->id; ?>" style="display:none;"></span>
                </td>
                <td class="" style="text-align:center;">
                    <img src="{{($v->image_url != '')?$v->image_url: url().'/public/backend/images/no-image.png'}}" width="100px" />
                </td>
                <td class="">
                    
                        <select onchange="change_status(<?php echo $indx; ?>)" style="width:100px;" name="category_active_<?php echo $indx; ?>" id="category_active_<?php echo $indx; ?>">
                            <option value="1" <?php echo $v->status == '1' ? "selected='selected'" : "" ?>>Active</option>
                            <option value="0" <?php echo $v->status == '0' ? "selected='selected'" : "" ?>>Inactive</option>
                        </select>
                        <br />
                        <span class='alert-success' id="success_status_span_<?php echo $indx; ?>" style="display:none;"></span>
                       
                </td>
                <td class="">
                    
                    <select onchange="makePopularProduct(<?php echo $v->id; ?>)" style="width:100px;" name="popular_product_<?php echo $v->id; ?>" id="popular_product_<?php echo $v->id; ?>">
                        <option value="1" <?php echo $v->popular_product == '1' ? "selected='selected'" : "" ?>>Yes</option>
                        <option value="0" <?php echo $v->popular_product == '0' ? "selected='selected'" : "" ?>>No</option>
                    </select>
                    <br />
                    <span class='alert-success' id="popular_success_span_<?php echo $v->id; ?>" style="display:none;"></span>
                       
                </td>
                <td class="action-btns">
                   
                        <a href="<?php echo url().'/admin/products/edit/'.base64_encode($v->id).'?search_key='.$search_key.'&active='.$active.'&srch_category_id='.$srch_category_id.'&srch_brand_id='.$srch_brand_id.'&srch_vendor_id='.$srch_vendor_id; ?>"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                        <a href="<?php echo url(); ?>/admin/products/view/{{ base64_encode($v->id) }}"><i class="fa fa-eye" aria-hidden="true"></i></a>
                        <a href="javascript:void(0);" onclick="delete_product({{ $v->id }})"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
                        
					<input type="hidden" value="<?php echo $v->id ?>" name="record_id_<?php echo $indx; ?>" id="record_id_<?php echo $indx; ?>" />
					
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
{!! $products->appends(['search_key'=>$search_key,'active'=>$active,'srch_category_id'=>$srch_category_id,'srch_brand_id'=>$srch_brand_id,'srch_vendor_id'=>$srch_vendor_id])->render() !!}
{!! Form::close() !!}
<script>
function change_status(id){

    $("#serverloader").show();

    var this_val = $('#category_active_'+id).val();
        var this_id = $('#record_id_'+id).val();
        $.ajax({
            url: base_url+'/admin/products/status',
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

function changeProductCategory(category_id,product_id){
    $("#serverloader").show();
    $.ajax({
        url: base_url+'/admin/products/change-category',
        type: "get",
        data: { category_id : category_id,product_id : product_id},
        success: function(data){
            $("#serverloader").hide();
            var response = data.split('@@');
            if(response[0] == '1'){
                /*$('#category_td_'+product_id).html(response[1]);*/
                $('#success_category_span_'+product_id).html('');
                $('#success_category_span_'+product_id).html('Category updated.');
                $('#success_category_span_'+product_id).fadeIn('slow');
                $('#success_category_span_'+product_id).fadeOut('slow');
            }
        }
    });
}

function delete_product(id){
    var confirm_del = confirm("Are you sure you want to delete this product?");
    if(confirm_del){
        window.location.href = site_url+"/admin/products/remove/"+id;
    }
}

function getFromLocation(serach_by)
{
    $("#"+serach_by).autocomplete({
        source: "<?php echo url() ?>/admin/products/search-product?search_by="+serach_by,
       /* focus: function( event, ui ) {
           $("#"+serach_by).val( ui.item.label );
           return false;
        },*/

        select: function( event, ui ) {
           $("#"+serach_by).val( ui.item.label );
           return false;
        },
        minLength: 2
    });
}

function makePopularProduct(product_id){
   
    var popular_product = $('#popular_product_'+product_id).val();
     //alert(popular_product);
    $("#serverloader").show();
    $.ajax({
        url: base_url+'/admin/products/popular-product',
        type: "get",
        data: { popular_product : popular_product,product_id:product_id},
        success: function(data){
            //alert(data);
            $("#serverloader").hide();
            if(data == '1'){
                //$('.alert-success').html('');
                $('#popular_success_span_'+product_id).html('updated.');
                $('#popular_success_span_'+product_id).fadeIn('slow');
                $('#popular_success_span_'+product_id).fadeOut('slow');
            }
            else if(data == '2'){

                alert('Already selected 10 popular products.');
                $('#popular_product_'+product_id).val(0);
            }
        }
    });
}
</script>
@endsection
