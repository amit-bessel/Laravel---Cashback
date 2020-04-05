@extends('../layout/frontend_template')
@section('content')

<script type="text/javascript">
function goForSearch(){
    $('#pagination').hide();
    $('.cs-loader').removeClass('hideloader');
    var search_key = $('#search_key').val();
    //alert(search_key);
    $.ajax({
      type    : "GET",
      url     : "<?php echo url() ?>/products-search-list",
      data    : "search_key="+search_key,
      dataType: "HTML",
      async   : true,
      success : function(response){
      
        $('#wish-list').html(response);
        $(".loader").fadeOut("slow");
        $('.cs-loader').addClass('hideloader');
      }
    });
}
</script>
    <!-- maincontent -->
    <section class="maincontent">
        <div class="container">
            <hr class="special-divider">
            <!-- common-headerblock -->
            <div class="common-headerblock text-center">
                <h4 class="text-uppercase">My Wishlist</h4>
                <p>Nulla at velit eget nulla accumsan interdum</p>
            </div>
            <!--\\ common-headerblock -->
            <div class="row mt20">
            
              @include('frontend.includes.left')

              <div class="col-sm-8 col-md-9 right-userpanel">
                  <div class="user-formpanel">
                    <!-- wishlist-toolbar -->
                    <div class="wishlist-toolbar">
                        <div class="row">
                            <div class="col-sm-7 col-md-5 search-column">
                              <div class="wishlist-search">
                                <input type="text" class="form-control" placeholder="Search by product name" value="<?php echo isset($search_key) ? $search_key : ''; ?>" class="span5" width="100%"name="search_key" id="search_key"
                                onkeyup="getFromLocation('search_key');">
                                <button type="submit" class="search-wishlist" onclick="goForSearch();"><i class="fa fa-search" aria-hidden="true"></i></button>
                              </div>
                            </div>
                            <div class="col-sm-5 col-md-3 pull-right search-select">
                              <!-- <select class="form-control">
                                <option value="default">Sort products</option>
                                <option value="saab">Saab</option>
                                <option value="mercedes">Mercedes</option>
                                <option value="audi">Audi</option>
                              </select> -->
                              <span id="select_tag"></span>
                            </div>
                        </div>
                    </div>


                    
                    <!--\\ wishlist-toolbar -->
                    <div class="wishlist-section">
                        <div class="new-responsive">
                            <table class="table table-striped">
                                <tbody id="wish-list">
                                                                        
                                </tbody>
                              </table>
                              <div class="pagination-block pull-right">
                            <span id="pagination"> 
                            <p class="pull-left">PAGE <span id="page"></span> OF <span id="total_page"></span></p>
                            <span id="prev"><a href="javascript:void(0)" onclick="prevProduct();">Prev</a></span><span id="pipe">|</span><span id="next"><a href="javascript:void(0)" onclick="nextProduct();">Next</a></span></span>
                            
                        </div>
                        </div>
                    </div>                 
                  </div>
              </div>
            </div>
        </div>
       <!--  <div class="loader"></div> -->
    </section>
    <!-- maincontent -->

   <script>

    var offset = 0;
    var limit = 5;
    var flag = 1;
    var flags = 1;
    var brand_id = '';
    var total_no_of_products = $('#total_no_of_products').val();
    var total_no_of_page = Math.ceil(total_no_of_products/limit);
    if(total_no_of_page == 0)
      var page = 0;
    else
      var page = 1;


    $(document).ready(function(){

      $.ajax({
        type    : "GET",
        url     : "<?php echo url() ?>/brands-wish-list",
        data    : "offset="+offset+"&limit="+limit,
        dataType: "HTML",
        async   : true,
        success : function(response){
          var obj = jQuery.parseJSON(response);
          
          $('#wish-list').html(obj.wishlist);
          $('#select_tag').html(obj.select);

          var count_total_product = $('#total_no_of_products').val();
          total_no_of_page = Math.ceil(count_total_product/limit);
          
          $('#page').html(page);
          $('#total_page').html(total_no_of_page);
          $('.cs-loader').addClass('hideloader');

          if(total_no_of_page <= 1){
                 $('#prev').hide();
                 $('#next').hide();
                 $('#pipe').hide(); 
            }else if(page == total_no_of_page){
                 $('#next').hide();
            }
            if(page == 1){
               $('#prev').hide();
            }
        }
      });


        //getProduct(brand_id);
        if(total_no_of_page <= 1){
         $('#prev').hide();
         $('#next').hide();
         $('#pipe').hide();
        }
        if(page == 1){
           $('#prev').hide(); 
        }
    });
    
    function getProduct(brand_id){

      $('.cs-loader').removeClass('hideloader');
      
      $.ajax({
        type    : "GET",
        url     : "<?php echo url() ?>/get-my-wishlist",
        data    : "offset="+offset+"&limit="+limit+"&brand_id="+brand_id,
        dataType: "HTML",
        async   : true,
        success : function(response){
          
          $('#wish-list').html(response);
          
          var count_total_product = $('#total_no_of_products').val();
          total_no_of_page = Math.ceil(count_total_product/limit);

          $('#page').html(page);
          $('#total_page').html(total_no_of_page);
          $('.cs-loader').addClass('hideloader');

          if(total_no_of_page <= 1){
                 $('#prev').hide();
                 $('#next').hide();
                 $('#pipe').hide(); 
            }else if(page == total_no_of_page){
                 $('#next').hide();
            }
            if(page == 1){
               $('#prev').hide();
            }
        }
      });
  }

  function nextProduct(){

    if(page<total_no_of_page){

        offset = offset+limit;
        page++;
         $('#prev').show();
         $('#pipe').show();
    }
    if(page == total_no_of_page){
         $('#next').hide();
          $('#pipe').hide();
    }
   // alert(page);
    $('#prev').show();
    flags++;
    getProduct(brand_id);
  }
  function prevProduct(){
   
    //alert(total_no_of_page);
    if(page<=total_no_of_page){

        offset = offset-limit;
        page--;
        $('#prev').show();
        $('#pipe').show();
    }
    if(page == 1){
        offset = 0;
        page = 1;
        $('#prev').hide();
        $('#pipe').hide();
    }
    flags++;
    $('#next').show();
    getProduct(brand_id);
  }

  function getFromLocation(serach_by)
  {
    $("#"+serach_by).autocomplete({
        source: "<?php echo url() ?>/search-product?search_by="+serach_by,
      
        select: function( event, ui ) {
           $("#"+serach_by).val( ui.item.label );
           return false;
        },
        minLength: 1
    });
  }

  function get_brandChnage(brand){
    offset = 0;
    page = 1;
    brand_id = brand.value
    getProduct(brand_id);
  }
  </script>

 @stop