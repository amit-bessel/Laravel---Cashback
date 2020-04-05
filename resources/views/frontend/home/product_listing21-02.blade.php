@extends('../layout/frontend_template')
@section('content')

    <!-- maincontent -->
    <section class="maincontent">
        <div class="container">
            <!-- page-nameblock -->
            <input type="hidden" name="search_keyword" value="{{$search_keyword}}" id="search_keyword">
            <div class="page-nameblock text-center">
                <ul class="breadcrumb-custom clearfix">                    
                    <li><a href="<?php echo url(); ?>">Home</a></li>
                    <?php if(!empty($main_category_details)){
                        ?>
                        <li><a href="<?php echo url().'/products/'.base64_encode($main_category_details['id']).'/'.$main_category_details['slug']; ?>"><?php echo $main_category_details['name']; ?></a></li>
                        <?php
                    }
                    if($category_details['parent_id'] != 0){
                    ?>
                        <li class="active"><a href="<?php echo url().'/products/'.base64_encode($category_details['id']).'/'.$category_details['slug']; ?>"><?php if($category_details['name'] != ''){ echo $category_details['name']; }?></a></li>
                    <?php }?>
                </ul>
                <div class="common-headerblock text-center">
                    <h4 class="text-uppercase"><?php if($category_details['name'] != ''){ echo $category_details['name']; }?></h4>
                </div>
            </div>
            <!-- page-nameblock -->
            <div class="row">
              <div class="table-prop">
                <!-- sidebar -->
                <div class="col-sm-4 col-md-3 table-cellspecial" id="sidebar">
                  <button type="button" class="toggle-sidebar for-mob" data-toggle="collapse" data-target="#left-sidebar">Toggle Sidebar<span class="total-bars"><span class="bar"></span><span class="bar"></span><span class="bar"></span></span></button>  
                  <div class="inner-sidebar clearfix" id="left-sidebar">
                    <h5>Categories</h5>
                    <ul>

                        <li><a href="<?php echo url().'/products/'.base64_encode($main_category_details['id']).'/'.$main_category_details['slug']; ?>">ALL <?php echo strtoupper($main_category_details['name']);?></a> </li>

                        <?php 

                            foreach($categories_levels as $key1=>$category_info){

                                if(empty($category_info['children'])){ ?>

                                    <li><a href="<?php echo url().'/products/'.base64_encode($category_info['id']).'/'.$category_info['slug']; ?>"><?php echo strtoupper($category_info['name']); ?></a></li> 

                          <?php }else { ?>

                                    <li><a href="javascript:void(0);" data-target="#item<?php echo $key1;?>" class="<?php echo in_array($category_id,$category_id_array[$category_info['id']])?'':'collapsed'; ?>" data-toggle="collapse"><?php echo strtoupper('All '.$category_info['name']); ?></a>

                                    <ul class="<?php echo in_array($category_id,$category_id_array[$category_info['id']])?'collapse in':'collapse'; ?>" id="item<?php echo $key1;?>" unorderList="<?php echo ($category_info['id'].'=='.$category_info['children'][0]['parent_id']); ?>">

                                    <li ><a href="<?php echo url().'/products/'.base64_encode($category_info['id']).'/'.$category_info['slug']; ?>"><?php echo strtoupper($category_info['name']); ?></a></li>

                                <?php  

                                    foreach ($category_info['children'] as $key2 => $send_category) { 

                                        ?>
                                            <li class="<?php echo ($category_id==$send_category['id'])?'active':''; ?>"><a href="<?php echo url().'/products/'.base64_encode($send_category['id']).'/'.$send_category['slug']; ?>"><?php echo strtoupper($send_category['name']); ?></a></li>

                                  <?php 

                                    } ?>

                                    </ul>

                               <?php

                                }

                            }

                        ?>

                    </ul>


                    <?php
                        if(count($brand_name)>0){
                    ?>
                    <div class="brand-listing">
                        <h5>Brands</h5>
                        <?php
                            $brand_array = array();
                            foreach ($brand_name as $value) {
                                $brand_array[] =  $value['id'];
                            }
                            $brand_id_list = implode(',',$brand_array);                        
                        ?>    
                        <div class="custom-checkboxbrand">
                            <input type="checkbox" id="brand-all" class="" name="brand-all" value="<?php echo $brand_id_list; ?>" onclick="showProducts({{ $category_id }},0);">
                            <label for="brand-all">All</label>
                        </div>
                        <?php
                            foreach ($brand_name as $value) {
                        ?>
                                <div class="custom-checkboxbrand">
                                    <input type="checkbox" id="brand-<?php echo $value['id'];?>" class="checkbox" name="brand-all" value="<?php echo $value['id'];?>" onclick="showProducts({{ $category_id }},this.value);">
                                    <label for="brand-<?php echo $value['id'];?>"><?php echo $value['brand_name'];?></label>
                                </div>
                        <?php
                        }                        
                        ?>                   
                    </div>
                    <?php } ?>

                    <div class="brand-listing">
                        <h5>Price</h5>
                        <div class="outer-slider">
                            <div id="left-price" class="commonprice pull-left"></div>
                            <div id="right-price" class="commonprice pull-right"></div>
                            <div id="slider"></div>
                        </div>    <!-- Slider get from==>https://codepen.io/ignaty/pen/EruAe -->             
                    </div>

                  </div>  
                </div>
                <!--\\ sidebar -->
                <div class="col-sm-8 col-md-9 table-cellspecial">
                    <!-- toolbar -->
                    <div class="toolbar clearfix">
                        <div class="sorter pull-left">
                          <p>Sort By Price <a href="javascript:void(0)" id="high" onclick="getHigh();">HIGH</a>|<a href="javascript:void(0)" id="low" onclick="getLow();">Low</a>
                          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Total Product <span id="total_count_product"></span>
                          </p>
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
        <!-- <div class="loader"></div> -->
    </section>

    <!-- Price Slider -->
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/jquery-ui.min.js"></script>
    <!-- Price Slider -->


    <script>
    $(window).load(function() {
        //$(".loader").fadeOut("slow");
    });

    var offset = 0;
    var limit = 90;
    var total_no_of_products = '{{ $total_no_of_products }}';
    var total_no_of_page = Math.ceil(total_no_of_products/limit);

    if(total_no_of_page == 0)
        var page = 0;
    else
        var page = 1;
    //var order = 'DESC';
    var category_id = '';
    var count_total_product = 0;
    var pid = '';
    var flag = 1;
    var flags = 1;
    var arr = [];
    var minprice = 0;
    var maxprice = '';
    var max_slide_range = $('#max_price_range').val();

    $(document).ready(function(){

        getProduct({{ $category_id }},order='',brand_id='',minprice,maxprice);
        
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

    }); 

    function getProduct(cat_id,order,brand_id,minprice,maxprice){

        category_id = cat_id;
        $('.cs-loader').removeClass('hideloader');
        //$(".loader").fadeIn("slow");
        if(typeof brand_id == 'undefined'){
            brand_id = 0;
        }

        var search_key = $('#search_keyword').val();

        $.ajax({
          type    : "GET",
          url     : "<?php echo url() ?>/product-list",
          data    : "category_id="+category_id+"&offset="+offset+"&limit="+limit+"&order="+order+"&brand_id="+brand_id+"&search_key="+search_key+"&minprice="+minprice+"&maxprice="+maxprice+"&_token=<?php echo csrf_token(); ?>",
          dataType: "HTML",
          async   : true,
          success : function(response,tedst,xhr){
            console.log(response);
            
            $('#product-list').html(response);
            /*if(xhr.status==200){
                $('.cs-loader').addClass('hideloader');
            }*/
            $(window).scrollTop(0);
            var count_total_product = $('#total_no_of_products').val();
            $('#total_count_product').html(count_total_product);
            total_no_of_page = Math.ceil(count_total_product/limit);
            //alert(total_no_of_page);
            $('#page').html(page);
            $('#total_page').html(total_no_of_page);
            $('#page1').html(page);
            $('#total_page1').html(total_no_of_page);
            //$(".loader").fadeOut("slow");

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
                //step: 50,
                max: $('#max_price_range').val(),
                values: [ $('#minprice').val(), $('#fix_max_price').val() ],
                slide: function(event, ui) {
                    
                    //$('.ui-slider-handle:eq(0) .price-range-min').html('$' + ui.values[ 0 ]);
                    //$('.ui-slider-handle:eq(1) .price-range-max').html('$' + ui.values[ 1 ]);
                    /*$('.price-range-both').html('<i>$' + ui.values[ 0 ] + ' - </i>$' + ui.values[ 1 ] );*/
                    $('#left-price .price-range-min').html('$' + ui.values[ 0 ]);
                    $('#right-price .price-range-max').html('$' + ui.values[ 1 ]);
                    
                    //
                    
                    /*if ( ui.values[0] == ui.values[1] ) {
                      $('.price-range-both i').css('display', 'none');
                    } else {
                      $('.price-range-both i').css('display', 'inline');
                    }*/
                    
                    //
                    
                    /*if (collision($('.price-range-min'), $('.price-range-max')) == true) {

                        $('.price-range-min, .price-range-max').css('opacity', '0');    
                        $('.price-range-both').css('display', 'block');     
                    } else {
                        //console.log({{ $category_id }},order,arr,ui.values[0] ,ui.values[1]);
                        $('.price-range-min, .price-range-max').css('opacity', '1');    
                        $('.price-range-both').css('display', 'none');      
                    }*/
                    
                },
                chnage: function(event, ui) {
                    maxprice = ui.values[1];
                    minprice = ui.values[0];
                },
                stop: function(event, ui) {
                    getProduct ({{ $category_id }},order,arr,ui.values[0] ,ui.values[1]);
                    $( "#slider" ).slider( "destroy" ); 
                }
            });

            /*$('.ui-slider-range').append('<span class="price-range-both value"><i>$' + $('#slider').slider('values', 0 ) + ' - </i>' + $('#slider').slider('values', 1 ) + '</span>');
            $('.ui-slider-handle:eq(0)').append('<span class="price-range-min value">$' + $('#slider').slider('values', 0 ) + '</span>');
            $('.ui-slider-handle:eq(1)').append('<span class="price-range-max value">$' + $('#slider').slider('values', 1 ) + '</span>');*/
            if($('#right-price .price-range-max').length==0)
                $('#right-price').append('<span class="price-range-max">$' + $('#slider').slider('values', 1 ) + '</span>');
            if($('#left-price .price-range-min').length==0)
                $('#left-price').append('<span class="price-range-min">$' + $('#slider').slider('values', 0 ) + '</span>');

            //setTimeout(function(){
                $('.cs-loader').addClass('hideloader');
            //},10000);         
            
            
            if(total_no_of_page <= 1){
                 $('#prev').hide();
                 $('#next').hide();
                 $('#pipe').hide();
                 $('#prev1').hide();
                 $('#next1').hide();
                 $('#pipe1').hide(); 
            }else if(page == total_no_of_page){
                 $('#next').hide();
                 $('#next1').hide();
            }
            if(page == 1){
               $('#prev').hide(); 
               $('#prev1').hide();
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
            getProduct({{ $category_id }},order,arr,minprice,maxprice); 
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
            getProduct({{ $category_id }},order,arr,minprice,maxprice); 

        }

    function getLow(){
        $("#high").removeClass("active");
        $("#low").addClass("active");
        order = 'ASC';
        offset = 0;
        page = 1;
        if(count_total_product > 0)
        {
           //showProducts(pid); 
        }
        else
        getProduct({{ $category_id }},order,arr,minprice,maxprice);
    }
    function getHigh(){
        $("#low").removeClass("active");
        $("#high").addClass("active");
        order = 'DESC';
        offset = 0;
        page = 1;
        if(count_total_product > 0)
        {
           //showProducts(pid); 
        }
        else
        getProduct({{ $category_id }},order,arr,minprice,maxprice);
      }

        $(document).ready(function(){
            /*$( "#brand-all , .checkbox" ).one( "click", function() {
              offset = 0;
              page = 1;
            });*/
            $('#brand-all').on('click',function(){
                offset = 0;
                page = 1;
                if(this.checked){
                    $('.checkbox').each(function(){
                        this.checked = true;
                    });
                }else{
                    $('.checkbox').each(function(){
                        this.checked = false;
                    });
                    $('#next').show();
                    $('#next1').show();
                }
                arr = [];
                $("input:checkbox[name=brand-all]:checked").each(function(){
                    arr.push($(this).val());
                });
                    getProduct(category_id,order,arr,minprice,maxprice);
            });
            
            $('.checkbox').on('click',function(){
                offset = 0;
                page = 1;
                arr = [];
                //alert($('.checkbox:checked').length);alert($('.checkbox').length);
                if($('.checkbox:checked').length == $('.checkbox').length){
                    $('#brand-all').prop('checked',true);
                    $("input:checkbox[name=brand-all]:checked").each(function(){
                        arr.push($(this).val());
                    });
                    getProduct(category_id,order,arr,minprice,maxprice);
                    
                }else{
                    $('#brand-all').prop('checked',false);
                    $("input:checkbox[name=brand-all]:checked").each(function(){
                        arr.push($(this).val());
                    });
                    getProduct(category_id,order,arr,minprice,maxprice);
                    $('#next').show();
                    $('#next1').show();
                }
            });
        });

        function showProducts(category_id,id){
        }
  
    </script>

    <script type="text/javascript">
        
        
    </script>
 @stop