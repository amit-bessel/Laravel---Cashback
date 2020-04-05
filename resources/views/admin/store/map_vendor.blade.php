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
    var search_key = $('#search_key1').val();
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
        <tr>
            <td width="" colspan="3">
                <input type="text" value="<?php echo isset($search_key) ? $search_key : ''; ?>" name="search_key" id="search_key5" placeholder="Search vendor..." class="span5" style="margin-right: 10px;"/>
            </td>
        </tr>
        <tr>
            <td width="40%" style="padding:0px;">
                <input type="button" onclick="getProductByCAtegory(0);" class="btn" name="btn_search_hotel" id="btn_search_hotel5" value="Submit">
            </td>      
        </tr>

    </table>
</div>

<div id="serverloader" class="dataTables_processing" style="display: none;"></div>

{!! Form::open(['url' => 'admin/stores/store-vendor-add','method'=>'POST', 'files'=>true,'class'=>'form-horizontal row-fluid','id'=>'cms_form']) !!}
<div class="module">
<input type="hidden" name="store_id" value="<?php echo $store_id;?>">
<table cellpadding="0" cellspacing="0" border="0" class="table table-bordered table-striped  display user-datatable" width="100%" style="display:none;" id="data_table">
        <thead>
            <tr style="color:#FFFFFF; background:#444;">
                <th colspan="3">Assign vendors to store</th>
            </tr>
            <tr style="color:#FFFFFF; background:#444;">
                <th width="5%">Sl No.</th>
                <th width="10%"><input type="checkbox" checked="checked" name="all" id="select_all">Check All</th>
                <th width="40%">Product Name</th>
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
  function getProductByCAtegory(page1){

     $("#serverloader").show();
    
    var form_data = $('#search_key5').val();
    var store_id = '<?php echo $store_id;?>';

    $.ajax({
      type    : "GET",
      url     : "<?php echo url() ?>/admin/stores/vendors/<?php echo $store_id; ?>",
      data    : "search_key="+form_data+"&offset="+offset+"&page="+page+"&store_id="+store_id,
      dataType: "HTML",
      async   : true,
      success : function(response){
        
      // alert(page1);
       if(page1==0){
            $('#product_list').html(response);
            page = 0;
       }
        else
            $('#product_list').append(response);

        var count_total_product = $('#count_total_product').val();
        //alert(count_total_product);
        var count_list = $('#product_list tr[hasProduct="Yes"]').length;
       //alert(count_list);
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
        $("#serverloader").hide();
      }
    });
  }

  

  function loadMore(){

    var limit     = 100;
    offset       += parseInt(limit);
    page++;
    getProductByCAtegory(page);
    
  }

$(document).on('keypress','#search_key5', function(e) {
  
    if (e.which == 13){
       e.preventDefault();
       e.stopPropagation();
       $('#btn_search_hotel5').trigger('click');
       return false;
     }
});

/*function getFromLocation(serach_by)
{
  alert(serach_by);
    $("#"+serach_by).autocomplete({
        source: "<?php //echo url() ?>/admin/stores/search-vendor?search_by="+serach_by,
        focus: function( event, ui ) {
           $("#"+serach_by).val( ui.item.label );
           return false;
        },

        select: function( event, ui ) {
           $("#"+serach_by).val( ui.item.label );
           return false;
        },
        minLength: 2
    });
}*/

</script>
@endsection
