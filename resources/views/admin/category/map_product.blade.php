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
    <table cellpadding="0" cellspacing="0" border="0" class="product-mapping"> 
        <tr>
            <td width="" colspan="1">
                <input type="text" value="<?php echo isset($search_key) ? $search_key : ''; ?>" name="search_key" id="search_key1" placeholder="Search Product By Keyword..." class="span5" style="margin-right: 10px;"/>
            </td>

             <td>

              <select name="brand_id" id="brand_id">
                        <option value="">---Select Brand---</option>
                        <?php
                            foreach($brand_array as $main_brand){?>
                                    <option value="{{$main_brand['id']}}" label="{{$main_brand['brand_name']}}" <?php echo ($srch_brand_id==$main_brand['id'])?'selected="selected"':'' ?>>{{$main_brand['brand_name']}}</option>
                                <?php
                            }
                        ?>
                </select>
            </td>

            <td>
              <select name="product_for" id="product_for">
                  <option value="">---Select Gender---</option>
                  <option value="1">Male</option>
                  <option value="2">Female</option>
                  <option value="3">Unisex</option>
              </select>
            </td>

            <td>
              <select name="record_per_page" id="record_per_page">
                  <option value="100">100</option>
                  <option value="500">500</option>
              </select>
            </td>

            <td width="25%" style="padding:0px;">
                <input style="margin-left: 10px; width: 60px;" type="button" onclick="getProductByCAtegory(0);" class="btn span1" name="btn_search_hotel" id="btn_search_hotel1" value="Submit">
            </td>
        </tr>
        <tr>
                  
        </tr>

    </table>
</div>

<div id="serverloader" class="dataTables_processing" style="display: none;"></div>

{!! Form::open(['url' => 'admin/category/product-category-add','method'=>'POST', 'files'=>true,'class'=>'form-horizontal row-fluid','id'=>'cms_form']) !!}
  <div class="module">
    <input type="hidden" name="category_id" value="<?php echo $category_id;?>">
    <table cellpadding="0" cellspacing="0" border="0" class="table table-bordered table-striped  display user-datatable" width="100%" style="display:none;" id="data_table">
          <thead>
              <tr style="color:#FFFFFF; background:#444;">
                  <th colspan="5">Assign products to category</th>
                  <th colspan="5" id="all_product_count"></th>
              </tr>
              <tr style="color:#FFFFFF; background:#444;">
                  <th width="5%">Sl No.</th>
                  <th width="10%"><input type="checkbox" name="all" id="select_all">Check All</th>
                  <th width="25%">Product Name</th>
                  <th width="20%">Vendor Name</th>
                  <th width="20%">Gender</th>
                  <th width="20%">Image</th>
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
    
    var form_data = $('#search_key1').val();
    var srch_brand_id = $("#brand_id option:selected").val();
    var record_per_page = $("#record_per_page option:selected").val();
    var product_for = $("#product_for option:selected").val();
    var cat_id = '<?php echo $category_id;?>';

    $.ajax({
      type    : "GET",
      url     : "<?php echo url() ?>/admin/category/product-list",
      data    : "search_key="+form_data+"&srch_brand_id="+srch_brand_id+"&offset="+offset+"&page="+page+"&product_for="+product_for+"&record_per_page="+record_per_page+"&cat_id="+cat_id,
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
        $("#serverloader").hide();
      }
    });
  }

  

  function loadMore(){

    var limit     = 20;
    offset       += parseInt(limit);
    page++;
    getProductByCAtegory(page);
    
  }

  $(document).on('keypress','#search_key1', function(e) {
  
    if (e.which == 13){
       e.preventDefault();
        e.stopPropagation();
       $('#btn_search_hotel1').trigger('click');
       return false;
     }
});

</script>
@endsection
