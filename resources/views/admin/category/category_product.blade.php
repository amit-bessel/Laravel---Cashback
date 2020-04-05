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
img{
  width: 200px !important;
}
</style>



@if(Session::has('success_message'))
<p class="alert {{ Session::get('alert-class', 'alert-success') }}">{{ Session::get('success_message') }}</p>
@endif

@if(Session::has('failure_message'))
<p class="alert {{ Session::get('alert-class', 'alert-danger') }}">{{ Session::get('failure_message') }}</p>
@endif

<div id="serverloader" class="dataTables_processing" style="display: none;"></div>

<div class="module">
    
    <div class="catPdt-list-filter">
      <input type="text" name="product_name" id="product_name">
      <button class="btn" onclick="serachProduct()" >Search</button>
      <button class="btn" onclick="clearSerachProduct()" >Clear</button>

      <a href="" style="float:right;"><button class="btn" onclick="window.history.back();" >Back</button></a>
      <span style="float:right;margin-right:20px;font-weight:bold" id="all_product_count"></span>

    </div>
    <?php
      if($no_of_product!=0){
        ?>
        <div style="margin: 0px 5px 5px 0px;">
            <input type="submit" name="submit" class="btn" onclick="return remove_brand();" value="Remove All">
        </div>
        <div class="clearfix"></div>
        <?php
      }
      ?>
        
    
    <form action="<?php echo url(); ?>/admin/category/remove-all-product" method="post" id="removebrandpro">
      <input type="hidden" name="_token"  value="{{ csrf_token() }}">
      <input type="hidden" name="category_id" id="category_id" value="{{ $category_id }}">
      
        <table cellpadding="0" cellspacing="0" border="0" class="table table-bordered table-striped  display" width="100%">
            <thead>
                <tr style="color:#FFFFFF; background:#444;">
                    <th width="10%"><input type="checkbox" checked="checked" name="all" id="select_all"><div>Check All</div></th>
                    <th width="10%">Sl No.</th>
                    <th width="30%">Product Name</th>
                    <th width="20%">Vendor Name</th>
                    <th width="25%">Image</th>
                    <th width="5%" align="right">Action</th>
                </tr>
            </thead>
           <tbody id="product_list">
                      
            </tbody>
                
        </table>
    </form>
    
        
    <div class="load-more" id="load_more" style="display:none;">
        <a href="javascript:void(0);" onclick="loadMore();">Load More...</a>
    </div>
</div>
<script>

  var offset = 0; 
  var page = 0;

  $(document).ready(function(){
      getProduct(0);

    
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

  function serachProduct(){
    getProduct(page,$('#product_name').val());
  }
  function clearSerachProduct(){
    $('#product_name').val('');
    getProduct(page,$('#product_name').val());
  }

  

 

  function getProduct(page1,product_name){
    
    $("#serverloader").show();

    if(typeof product_name =='undefined')
      product_name = '';

    var form_data = '<?php echo $category_id; ?>';

    $.ajax({
      type    : "GET",
      url     : "<?php echo url() ?>/admin/category/category-product-list-ajax/"+form_data+"/",
      data    : "offset="+offset+"&page="+page+"&product_name="+product_name,
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
        var count_check = (count_total_product)?count_total_product:0;
        $('#all_product_count').html('Total Products: '+count_check);
        
       // alert(count_total_product);
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
      }
    });
  }


  function loadMore(){

    var limit     = 20;
    offset       += parseInt(limit);
    page++;
    getProduct(page,$('#product_name').val());
    
  }


function delete_product(id){
    var confirm_banner = confirm("Are You sure you want to remove category?");
    if(confirm_banner){
        window.location.href = site_url+"/admin/category/product-delete/"+id;
    }

}

function remove_brand(){

    var confirm_banner = confirm("Are You sure you want to remove category?");
    if(confirm_banner){
        $('#removebrandpro').submit();
    }
    else{
        return false;
    }
}
</script>

@endsection
