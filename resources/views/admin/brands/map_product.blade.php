@extends('admin/layout/admin_template')
 
@section('content')
<style>
.country input{margin-right:0px;}
#search_key2{
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


$("#search_key").bind('keyup', function(event){ 
  if(event.keyCode == 13){ 
    event.preventDefault();
    //$("#buttonSrch").click(); 
    //search(this.value);
  }
});

function goForSearch(){
    var search_key = $('#search_key2').val();
    if(search_key != ""){

        var url = '<?php echo url(); ?>/admin/brands/product-list?';
        url += 'search_key='+search_key;
        window.location.href = url;
    }
    else{

        var url = '<?php echo url(); ?>/admin/brands/search-brand';
        window.location.href = url;   
    }
    
}
</script>




<div class="country">
    <table cellpadding="0" cellspacing="0" border="0" width="100%"> 
      <!-- <form id="searchForm" method="post" onSubmit="return false;" onkeypress="return event.keyCode != 13"> -->
        <tr>
            <td width="" colspan="3">

                <input type="text" value="<?php echo isset($search_key) ? $search_key : ''; ?>" name="search_key" id="search_key2" placeholder="Search Product By Keyword..." class="span5" width="100%"/>

            </td>
        </tr>
        
        <tr>
            <td width="40%" style="padding:0px;">

                <input type="button" onclick="getProduct(0);"  class="btn" name="btn_search_hotel" id="btn_search_hotel" value="Submit">

            </td>      
        </tr>
      <!-- </form> -->
      <div id="ajax_loader_div" style="display:none;"><img src="{{url()}}/public/backend/images/loader.gif"></div>
    </table>
</div>

<div id="serverloader" class="dataTables_processing" style="display: none;"></div>

{!! Form::open(['url' => 'admin/brands/brand-add','method'=>'POST', 'files'=>true,'class'=>'form-horizontal row-fluid','id'=>'cms_form']) !!}
<div class="module">
<input type="hidden" name="brand" value="<?php echo $brand_id;?>">
<table cellpadding="0" cellspacing="0" border="0" class="table table-bordered table-striped  display user-datatable" width="100%" style="display:none;" id="data_table">
        <thead>
            <tr style="color:#FFFFFF; background:#444;">
                <th colspan="3">Assign products to brand</th>
                <th colspan="3" id="all_product_count"></th>
            </tr>
            <tr style="color:#FFFFFF; background:#444;">
                <th width="5%">Sl No.</th>
                <th width="10%"><input type="checkbox" checked="checked" name="all" id="select_all">Check All</th>
                <th width="40%">Product Name</th>
                <th width="40%">Vendor Name</th>
            </tr>
        </thead>
            
            
        <tbody class="row" id="product_list">   
               
        </tbody>
            
    </table> 
    

</div>
<div class="load-more" id="load_more" style="display:none;">
    <a href="javascript:void(0);" onclick="loadMore();">Load More...</a>
</div>
<div class="btn btn-primary" id="load_submit" style="display:none;float:right;">
    <input type="submit" name="submit" id="submit" class="btn" value="Map Product">
</div>
{!! Form::close() !!}
<script>

function change_status(id){
    var this_val = $('#category_active_'+id).val();
        var this_id = $('#record_id_'+id).val();
        $.ajax({
            url: base_url+'/admin/products/status',
            type: "get",
            data: { this_val : this_val,this_id : this_id},
            success: function(data){
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
    //alert(category_id+" "+product_id);
    $.ajax({
        url: base_url+'/admin/products/change-category',
        type: "get",
        data: { category_id : category_id,product_id : product_id},
        success: function(data){
            var response = data.split('@@');
            if(response[0] == '1'){
                $('#category_td_'+product_id).html(response[1]);
                /*$('#success_category_span_'+product_id).html('');
                $('#success_category_span_'+product_id).html('Category updated.');
                $('#success_category_span_'+product_id).fadeIn('slow');
                $('#success_category_span_'+product_id).fadeOut('slow');*/
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
    //alert(serach_by);
    //var avalable_tags =<?php //echo $tags; ?>;
    //$search_val = $( "#srch_res_name" ).val();
    $("#"+serach_by).autocomplete({
        source: "<?php echo url() ?>/admin/brands/search?search_by="+serach_by,
       /* focus: function( event, ui ) {
           $("#"+serach_by).val( ui.item.label );
           return false;
        },*/
        select: function( event, ui ) {
           $("#"+serach_by).val( ui.item.label );
           var id = ui.item.key;
           $("#brand").val(id);
           return false;
        },
        minLength: 1
    });
    
}


$(document).ready(function(){

    //select all checkboxes
$("#select_all").change(function(){  //"select all" change 
    var status = this.checked; // "select all" checked status
    $('.checkbox').each(function(){ //iterate all listed checkbox items
        this.checked = status; //change ".checkbox" checked status
    });
});

$('.checkbox').change(function(){ //".checkbox" change 
    //uncheck "select all", if one of the listed checkbox item is unchecked
    if(this.checked == false){ //if this item is unchecked
        $("#select_all")[0].checked = false; //change "select all" checked status to false
    }
    
    //check "select all" if all checkbox items are checked
    if ($('.checkbox:checked').length == $('.checkbox').length ){ 
        $("#select_all")[0].checked = true; //change "select all" checked status to true
    }
});

});
</script>
<script>

  var offset = 0; 
  var page = 0;
  function getProduct(page1,e){
    
    $("#serverloader").show();

    var form_data = $('#search_key2').val();
    var brand_id = '<?php echo $brand_id;?>';

    $.ajax({
      type    : "GET",
      url     : "<?php echo url() ?>/admin/brands/product-list",
      data    : "search_key="+form_data+"&offset="+offset+"&page="+page+"&brand_id="+brand_id,
      dataType: "HTML",
      async   : true,
      success : function(response){

      $("#serverloader").hide();

       if(page1==0){
            $('#product_list').html(response);
            page = 0;
       }
        else
            $('#product_list').append(response);

        var count_total_product = $('#count_total_product').val();
        $('#all_product_count').html(count_total_product);
        //alert(count_total_product);
        var count_list = $('#product_list tr[hasProduct="Yes"]').length;
       // alert(count_list);
        if(count_list==0){
          $('#load_more').hide();
          $('#data_table').show();
        }
        else if(count_list>=(count_total_product)){
          $('#load_more').hide();
            $('#data_table').show();
            $('#load_submit').show();
        }
        else{
          $('#load_more').show();
          $('#data_table').show();
          $('#load_submit').show();
        }
        return false;

      }
    });
  }

  

  function loadMore(){

    var limit     = 20;
    offset       += parseInt(limit);
    page++;
    getProduct(page);

    //getProduct(page,event);
    
  }


$(document).on('keypress','#search_key2', function(e) {
  
    if (e.which == 13){
       e.preventDefault();
       e.stopPropagation();
       $('#btn_search_hotel').trigger('click');
       return false;
     }
});

</script>
@endsection
