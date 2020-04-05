@extends('../layout/frontend_template')
@section('content')
<style>
    .inner-sidebar.abc ul > li > ul > li::before{ display:none }
</style>
    <!-- maincontent -->
    <section class="maincontent">
        <div class="container">
            <!-- page-nameblock -->
            <div class="page-nameblock text-center">
                <ul class="breadcrumb-custom clearfix">                    
                    <li><a href="#">Home</a></li>
                    <li><a href="<?php echo url();?>/designer-list/">Designers</a></li>
                    <li class="active"><a href="#"><?php echo $brand_details['brand_name']; ?></a></li>
                </ul>
                <div class="common-headerblock text-center">
                    <h4 class="text-uppercase"><?php echo $brand_details['brand_name']; ?></h4>
                </div>
            </div>
            <!-- page-nameblock -->
            <!-- category-description -->
            <div class="category-description">
                <div class="row">
                    <div class="col-sm-6 col-sm-push-6 right-categoryimg">
                        <div class="table-prop"><div class="table-cell">
                        <?php
                            if($brand_details['featured_image'] != ''){
                        ?>
                        <img src="<?php echo url();?>/public/uploads/brand_image/thumb/<?php echo $brand_details['featured_image'] ;?>" class="img-responsive" alt="not available">
                        
                        <?php
                            }
                        ?>
                        </div></div>
                    </div>
                    <div class="col-sm-6 col-sm-pull-6 leftcategory-desc">
                        <div class="table-prop">
                            <div class="table-cell">
                                <?php echo $brand_details['description']; ?>
                            </div>
                        </div>
                    </div>                    
                </div>
            </div>
            <!--\\ category-description -->
            <div class="row">
              <div class="table-prop">
                <!-- sidebar -->

                <div class="col-sm-4 col-md-3 table-cellspecial" id="sidebar">
                  <button type="button" class="toggle-sidebar for-mob collapsed" data-toggle="collapse" data-target="#user-menu" aria-expanded="false">Toggle User Menu<span class="total-bars"><span class="bar"></span><span class="bar"></span><span class="bar"></span></span></button> 
                  <div class="inner-sidebar abc clearfix" id="left-sidebar">
                    <h5>Categories</h5>
                     <ul>
                    <?php 

                    if(!empty($cat_view_arr)){
                        foreach ($cat_view_arr as $key => $value) {
                    ?>
                    <li><a href="javascript:void(0);" data-target="#item<?php echo $key;?>" class="collapsed" data-toggle="collapse"><?php echo 'All '.$value[0]['main_cat'];?></a>
                        <ul class="collapse" id="item<?php echo $key;?>">  
                            <li>
                                <div class="custom-checkboxbrand">
                                   <input type="checkbox" id="category-<?php echo $key;?>" class="category_checkbox" name="category-all" value="<?php echo $key;?>">
                                    <label for="category-<?php echo $key;?>">{{$value[0]['main_cat']}} Test</label>
                                </div>
                            </li>
                    <?php
                    //echo '<pre>';print_r($value);
                        foreach ($value as $newkey => $newvalue) {
                                
                    ?>
                        <li><div class="custom-checkboxbrand">
                               <input type="checkbox" id="category-<?php echo $key.$newkey;?>" class="category_checkbox" name="category-all" value="<?php echo $newvalue['subcategory']['id'];?>">
                                <label for="category-<?php echo $key.$newkey;?>">{{$newvalue['subcategory']['name']}}</label>
                            </div>                            
                        </li>                        
                    <?php
                        }
                    ?>
                        </ul>  
                    </li>
                    <?php
                        }
                    }
                    ?>


                    <?php
                        if(!empty($individual_array)){
                            foreach ($individual_array as $key => $value) {
                    ?>
                        <li><div class="custom-checkboxbrand">
                               <input type="checkbox" id="category-<?php echo 'sep'.$key;?>" class="category_checkbox" name="category-all" value="<?php echo $value['category_id'];?>">
                                <label for="category-<?php echo 'sep'.$key;?>"> {{$value['name']}}</label>
                            </div> 
                        </li>
                    <?php
                            }
                        }
                    ?>                                    
                    </ul>
                    <div class="brand-listing">
                        <h5>Price</h5>
                        <div class="outer-slider"><div id="slider"></div></div>    <!-- Slider get from==>https://codepen.io/ignaty/pen/EruAe -->             
                    </div>
                  </div>  
                </div>
                <!--\\ sidebar -->
                <div class="col-sm-8 col-md-9 table-cellspecial">
                    <!-- toolbar -->
                    
                    <div class="toolbar clearfix">
                        <div class="sorter pull-left">
                          <p>Sort By Price <a href="javascript:void(0)" id="high" onclick="getHigh();">HIGH</a>|<a href="javascript:void(0)" id="low" onclick="getLow();">Low</a></p>
                        </div>
                        <div class="pagination-block pull-right">
                            <p class="pull-left">PAGE <span id="page"></span> OF <span id="total_page"></span></p>
                            <span id="prev"><a href="javascript:void(0)" onclick="prevProduct();">Prev</a></span><span id="pipe">|</span><span id="next"><a href="javascript:void(0)" onclick="nextProduct();">Next</a></span><!-- <a href="javascript:void(0)" onclick="viewAll();">View All</a> -->
                        </div>
                    </div>
                    <!--\\ toolbar -->
                    <!-- product-list -->

                    <div class="product-list" id="product-list">
              
                    </div>
                    <!-- toolbar -->
                    <div class="toolbar clearfix">
                        <div class="sorter pull-left">
                          <a href="javascript:void(0);" class="back-to-top">Back to Top <i class="fa fa-angle-up" aria-hidden="true"></i></a>
                        </div>
                        <div class="pagination-block pull-right">
                            <p class="pull-left">PAGE <span id="page1"></span> OF <span id="total_page1"></span></p>
                            <span id="prev1"><a href="javascript:void(0)" onclick="prevProduct();">Prev</a></span><span id="pipe1">|</span><span id="next1"><a href="javascript:void(0)" onclick="nextProduct();">Next</a></span><!-- <a href="javascript:void(0)" onclick="viewAll();">View All</a> -->
                        </div>
                    </div>
                    <!--\\ toolbar -->
                </div>
              </div>  
            </div>
        </div>
       <!--  <div class="loader"></div> -->
    </section>
    <!-- maincontent -->

    <!-- Price Slider -->
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/jquery-ui.min.js"></script>
    <!-- Price Slider -->
    <script>

        var offset = 0;
        var limit = 90;
        var total_no_of_products = 0;
        total_no_of_page = Math.ceil(total_no_of_products/limit);
        if(total_no_of_page == 0)
            var page = 0;
        else
            var page = 1;
        //var order = 'DESC';
        var cate_arr = [];
        var count_total_product = 0;
        var pid = '';
        var flag = 1;
        var flags = 1;
        var minprice = 0;
        var maxprice = '';
        var arr = '{{$id}}';

        $(document).ready(function(){
            getProduct(cate_arr,order='',arr,minprice,maxprice);        
        });

        function getProduct(cate_arr,order,brand_id,minprice,maxprice){

            $('.cs-loader').removeClass('hideloader');

            if(typeof order=='undefined'){
                order = '';
            }
            if(typeof cate_arr=='undefined'){
                cate_arr = '';
            }
            
            $.ajax({
              type    : "GET",
              url     : "<?php echo url() ?>/brand-product-list",
              data    : "brand_id="+brand_id+"&offset="+offset+"&limit="+limit+"&order="+order+"&category_id="+cate_arr+"&minprice="+minprice+"&maxprice="+maxprice,
              dataType: "HTML",
              async   : true,
              success : function(response){

                $('#product-list').html(response);
                
             	var count_total_product = $('#total_no_of_products').val();
                total_no_of_page = Math.ceil(count_total_product/limit);

                if($('#total_no_of_products').val()!=0 && page==0)
                    page =1;


                function collision($div1, $div2) {
                  var x1 = $div1.offset().left;
                  var w1 = 40;
                  var r1 = x1 + w1;
                  var x2 = $div2.offset().left;
                  var w2 = 40;
                  var r2 = x2 + w2;
                    
                  if (r1 < x2 || x1 > r2) return false;
                  return true;
                  
                }
                
                // // slider call

                $('#slider').slider({
                    
                    range: true,
                    min: 0,
                    max: $('#max_price_range').val(),
                    values: [ $('#minprice').val(), $('#fix_max_price').val() ],
                    slide: function(event, ui) {
                        
                        $('.ui-slider-handle:eq(0) .price-range-min').html('$' + ui.values[ 0 ]);
                        $('.ui-slider-handle:eq(1) .price-range-max').html('$' + ui.values[ 1 ]);
                        $('.price-range-both').html('<i>$' + ui.values[ 0 ] + ' - </i>$' + ui.values[ 1 ] );
                        
                        //
                        
                        if ( ui.values[0] == ui.values[1] ) {
                          $('.price-range-both i').css('display', 'none');
                        } else {
                          $('.price-range-both i').css('display', 'inline');
                        }
                        
                        //
                        
                        if (collision($('.price-range-min'), $('.price-range-max')) == true) {

                            $('.price-range-min, .price-range-max').css('opacity', '0');    
                            $('.price-range-both').css('display', 'block');     
                        } else {
                            
                            $('.price-range-min, .price-range-max').css('opacity', '1');    
                            $('.price-range-both').css('display', 'none');      
                        }
                        
                    },
                    change: function(event, ui) {
                        maxprice = ui.values[1];
                        minprice = ui.values[0];
                    },
                    stop: function(event, ui) {
                        getProduct(cate_arr,order,arr,ui.values[0] ,ui.values[1]);
                    }
                });

                $('.ui-slider-range').append('<span class="price-range-both value"><i>$' + $('#slider').slider('values', 0 ) + ' - </i>' + $('#slider').slider('values', 1 ) + '</span>');
                $('.ui-slider-handle:eq(0)').append('<span class="price-range-min value">$' + $('#slider').slider('values', 0 ) + '</span>');
                $('.ui-slider-handle:eq(1)').append('<span class="price-range-max value">$' + $('#slider').slider('values', 1 ) + '</span>');    			
    			
    			if(total_no_of_page <= 1){
    			 $('#prev').hide();
    			 $('#next').hide();
    			 $('#pipe').hide();
    			 $('#prev1').hide();
    			 $('#next1').hide();
    			 $('#pipe1').hide(); 
    			}
    			if(page == 1){
    			   $('#prev').hide(); 
    			   $('#prev1').hide();
    			}
               
                $('#page').html(page);
                $('#total_page').html(total_no_of_page);
                $('#page1').html(page);
                $('#total_page1').html(total_no_of_page);
                 $('.cs-loader').addClass('hideloader');
                
              }
            });
        }

        function nextProduct(){
            
            if(page<total_no_of_page){

                offset = offset+limit;
                page++;
                $('#prev').show();
                $('#pipe').show();
                $('#prev1').show();
                $('#pipe1').show();
            }
            if(page == total_no_of_page){
                $('#next').hide();
                $('#pipe').hide();
                $('#next1').hide();
                $('#pipe1').hide();

            }
           // alert(page);
            $('#prev').show();
            $('#prev1').show();
            flags++;
            getProduct(cate_arr,order,arr,minprice,maxprice); 
        }

        function prevProduct(){

            if(page<=total_no_of_page){

                offset = offset-limit;
                page--;
                $('#prev').show();
                $('#pipe').show();
                $('#prev1').show();
                $('#pipe1').show();
            }
            if(page == 1){
                offset = 0;
                page = 1;
                $('#prev').hide();
                $('#pipe').hide();
                 $('#prev1').hide();
                $('#pipe1').hide();
            }
            // alert(page);
            $('#next').show();
            $('#next1').show();
            flags++;
           getProduct(cate_arr,order,arr,minprice,maxprice); 

        }


      function getLow(){
        $("#high").removeClass("active");
        $("#low").addClass("active");
        order = 'ASC';
        offset = 0;
        page = 1;
        
        getProduct(cate_arr,order,arr,minprice,maxprice);
      }
      function getHigh(){
        $("#low").removeClass("active");
        $("#high").addClass("active");
        order = 'DESC';
        offset = 0;
        page = 1;

        getProduct(cate_arr,order,arr,minprice,maxprice);
      }

      //####### For catwegory Search ############## //
        $(document).ready(function(){
            
            $('.category_checkbox').on('click',function(){
                offset = 0;
                page = 1;
                cate_arr = [];
                //alert($('.checkbox:checked').length);alert($('.checkbox').length);
                if($('.category_checkbox:checked').length == $('.category_checkbox').length){
                    $('#category-all').prop('checked',true);
                    $("input:checkbox[name=category-all]:checked").each(function(){
                        cate_arr.push($(this).val());
                    });
                    getProduct(cate_arr,order,arr,minprice,maxprice);
                    
                }else{
                    $('#category-all').prop('checked',false);
                    $("input:checkbox[name=category-all]:checked").each(function(){
                        cate_arr.push($(this).val());
                    });
                    getProduct(cate_arr,order,arr,minprice,maxprice);
                    $('#next').show();
                    $('#next1').show();
                }
            });
        });
        //####### For catwegory Search ############## //
  </script>

  <script type="text/javascript">
        
    </script>
@stop