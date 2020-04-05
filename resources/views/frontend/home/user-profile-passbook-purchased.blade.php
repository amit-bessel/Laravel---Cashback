@extends('../layout/frontend_template')
@section('content')

    <!-- maincontent -->
    <section class="maincontent">
        <div class="container">
            <hr class="special-divider">
            <!-- common-headerblock -->
            <div class="common-headerblock text-center">
                <h4 class="text-uppercase">passbook</h4>
                <p>Nulla at velit eget nulla accumsan interdum</p>
            </div>
            <!--\\ common-headerblock -->
            <div class="row mt20">
             @include('frontend.includes.left')

              <div class="col-sm-8 col-md-9 right-userpanel">

                  <div class="user-formpanel">
                      <h3 class="user-heading">transactions <span class="total-cashback pull-right">Total Cashback Earned - <strong>${{$cashback_amount}}</strong></span></h3>
                      <div class="transaction-tabs">

                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs" role="tablist">
                          <li role="presentation" class="active"><a href="#home" aria-controls="home" role="tab" data-toggle="tab">purchased</a></li>
                          <li role="presentation"><a href="#profile" aria-controls="profile" role="tab" data-toggle="tab">withdrawn</a></li>                          
                        </ul>

                        <!-- Tab panes -->
                        <div class="tab-content">
                          <div role="tabpanel" class="tab-pane active" id="home">
                            <div class="table-responsive">

                              <!-- order-list -->
                              <span class="order-list" id="order-list"></span> 
                              <!-- order-list -->
                              
                              <!-- toolbar -->
                              <div class="toolbar clearfix">                                  
                                  <div class="pagination-block pull-right">
                                      <p class="pull-left">PAGE <span id="page"></span> OF <span id="total_page"></span></p>
                                      <span id="prev"><a href="javascript:void(0)" onclick="prevOrder();">Prev</a></span><span id="pipe">|</span><span id="next"><a href="javascript:void(0)" onclick="nextOrder();">Next</a></span>
                                  </div>
                              </div>
                              <!--\\ toolbar -->
                            </div>  
                          </div>
                          <div role="tabpanel" class="tab-pane" id="profile">
                            <div class="table-responsive">
                              <!-- <table class="table table-striped">
                                <thead>
                                  <tr>
                                    <th>withdraw details</th>
                                    <th>date of withdraw</th>
                                    <th>cash withdrawn</th>
                                    <th>withdraw status</th>
                                  </tr>
                                </thead>
                                <tbody>
                                  <tr>
                                    <td class="cart-description">
                                      <div class="cart-img wallet-img pull-left" style="background:url(<?php echo url(); ?>/public/frontend/images/small-wallet.png) no-repeat center center/contain;"></div>
                                      <h4>Blue Kiruna Slim-Fit Piqué Blazer</h4>
                                    </td>
                                    <td>21-02-2017</td>
                                    <td>$730</td>
                                    <td>success</td>
                                  </tr>
                                  <tr>
                                    <td class="cart-description">
                                      <div class="cart-img wallet-img pull-left" style="background:url(<?php echo url(); ?>/public/frontend/images/small-wallet.png) no-repeat center center/contain;"></div>
                                      <h4>Pierce medium leather shoulder bag</h4>
                                    </td>
                                    <td>11-12-2016</td>
                                    <td>$1,788</td>
                                    <td>success</td>
                                  </tr>
                                  <tr>
                                    <td class="cart-description">
                                      <div class="cart-img wallet-img pull-left" style="background:url(<?php echo url(); ?>/public/frontend/images/small-wallet.png) no-repeat center center/contain;"></div>
                                      <h4>Pebble-Grain Leather Derby Shoes</h4>
                                    </td>
                                    <td>20-10-2016</td>
                                    <td>$577</td>
                                    <td>success</td>
                                  </tr>
                                  <tr>
                                    <td class="cart-description">
                                      <div class="cart-img wallet-img pull-left" style="background:url(<?php echo url(); ?>/public/frontend/images/small-wallet.png) no-repeat center center/contain;"></div>
                                      <h4>Black Kiruna Slim-Fit Piqué Blazer</h4>
                                    </td>
                                    <td>03-09-2016</td>
                                    <td>$550</td>
                                    <td>success</td>
                                  </tr>
                                  <tr>
                                    <td class="cart-description">
                                      <div class="cart-img wallet-img pull-left" style="background:url(<?php echo url(); ?>/public/frontend/images/small-wallet.png) no-repeat center center/contain;"></div>
                                      <h4>Black Kiruna Slim-Fit Piqué Blazer</h4>
                                    </td>
                                    <td>03-09-2016</td>
                                    <td>$550</td>
                                    <td>success</td>
                                  </tr>                                
                                </tbody>
                              </table> -->
                              <span id="withdrawls-list"></span>
                              <!-- toolbar -->
                              <div class="toolbar clearfix">                                  
                                  <div class="pagination-block pull-right">
                                      <p class="pull-left">PAGE <span id="page1"></span> OF <span id="total_page1"></span></p>
                                      <span id="prev1"><a href="javascript:void(0)" onclick="prevOrder1();">Prev</a></span><span id="pipe1">|</span><span id="next1"><a href="javascript:void(0)" onclick="nextOrder1();">Next</a></span>
                                  </div>
                              </div>
                              <!--\\ toolbar -->
                            </div>
                          </div>                          
                        </div>

                      </div>                      
                  </div>
              </div>
            </div>
        </div>
    </section>
    <!-- maincontent -->
   <script>
    var offset = 0;
    var limit = 10;
    var total_no_of_orders = '{{ $total_no_of_orders }}';
    var total_no_of_page = Math.ceil(total_no_of_orders/limit);

    if(total_no_of_page == 0)
        var page = 0;
    else
        var page = 1;;
    var count_total_product = 0;
    var pid = '';
    var flag = 1;
    var flags = 1;

    $(document).ready(function(){
        getOrder();
        
        if(total_no_of_page <= 1){
         $('#prev').hide();
         $('#next').hide();
         $('#pipe').hide();
        }
        if(page == 1){
           $('#prev').hide();
        }

    }); 

    function getOrder(){

        $('.cs-loader').removeClass('hideloader');
        $.ajax({
          type    : "GET",
          url     : "<?php echo url() ?>/order-list",
          data    : "offset="+offset+"&limit="+limit+"&_token=<?php echo csrf_token(); ?>",
          dataType: "HTML",
          async   : true,
          success : function(response,tedst,xhr){

            $('#order-list').html(response);
            if(xhr.status==200){
                $('.cs-loader').addClass('hideloader');
            }
            //$('.cs-loader').addClass('hideloader');
            $(window).scrollTop(0);
            var count_total_order = $('#total_no_of_orders').val();

            total_no_of_page = Math.ceil(count_total_order/limit);
            //alert(total_no_of_page);
            $('#page').html(page);
            $('#total_page').html(total_no_of_page);
            
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

        function nextOrder(){

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
            getOrder(); 
        }

        function prevOrder(){

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
            // alert(page);
            $('#next').show();
            flags++;
            getOrder(); 

        }
    </script>




    <script>
    var offset1 = 0;
    var limit1 = 10;
    var total_no_of_orders1 = '{{ $total_no_of_withdraw }}';
    var total_no_of_page1 = Math.ceil(total_no_of_orders1/limit1);

    if(total_no_of_page1 == 0)
        var page1 = 0;
    else
        var page1 = 1;;
    var count_total_product1 = 0;
    var pid1 = '';
    var flag1 = 1;
    var flags1 = 1;

    $(document).ready(function(){
        getOrder1();
        
        if(total_no_of_page1 <= 1){

         $('#prev1').hide();
         $('#next1').hide();
         $('#pipe1').hide(); 
        }
        if(page1 == 1){
           $('#prev1').hide();
        }

    }); 

    function getOrder1(){

        $('.cs-loader').removeClass('hideloader');
        $.ajax({
          type    : "GET",
          url     : "<?php echo url() ?>/withdrawls-list",
          data    : "offset="+offset1+"&limit="+limit1+"&_token=<?php echo csrf_token(); ?>",
          dataType: "HTML",
          async   : true,
          success : function(response,tedst,xhr){

            $('#withdrawls-list').html(response);
            if(xhr.status==200){
                $('.cs-loader').addClass('hideloader');
            }
            //$('.cs-loader').addClass('hideloader');
            $(window).scrollTop(0);
            var count_total_order1 = $('#total_no_of_orders1').val();

            total_no_of_page1 = Math.ceil(count_total_order1/limit1);
            //alert(total_no_of_page1);
            $('#page1').html(page1);
            $('#total_page1').html(total_no_of_page1);
            
            if(total_no_of_page1 <= 1){
                 $('#prev1').hide();
                 $('#next1').hide();
                 $('#pipe1').hide(); 
            }else if(page1 == total_no_of_page1){
                 $('#next1').hide();
            }
            if(page1 == 1){
               $('#prev1').hide();
            }
          }
        });
      }

        function nextOrder1(){

            if(page1<total_no_of_page1){

                offset1 = offset1+limit1;
                page1++;
                $('#prev1').show();
                $('#pipe1').show();
            }
            if(page1 == total_no_of_page1){
                $('#next1').hide();
                $('#pipe1').hide();

            }
           // alert(page1);
            $('#prev1').show();
            flags1++;
            getOrder1(); 
        }

        function prevOrder1(){

            if(page1<=total_no_of_page1){

                offset1 = offset1-limit1;
                page1--;
                $('#prev1').show();
                $('#pipe1').show();
            }
            if(page1 == 1){
                offset1 = 0;
                page1 = 1;
                 $('#prev1').hide();
                $('#pipe1').hide();
            }
            // alert(page1);
            $('#next1').show();
            flags1++;
            getOrder1(); 

        }
    </script>
  
    @stop