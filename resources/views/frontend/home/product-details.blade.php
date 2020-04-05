@extends('../layout/frontend_template')
@section('content')

    <!-- maincontent -->
    <section class="maincontent">
        <div class="container">            
            <div class="row mt60">
                <!-- product-showcase -->
                <div class="col-sm-5">
                    <div class="product-showcase">
                      <a href="JavaScript:void(0);" class="" data-toggle="modal" data-target="#myModalZoompopUp">
                          <img src="<?php echo (($product_details['image_url']!='')?$product_details['image_url']:url().'/public/uploads/no-image.png')?>" class="img-responsive" alt="">
                      </a>
                    </div>
                </div>
                <!-- Modal -->
                  <div class="modal fade details-popup" id="myModalZoompopUp" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                    <div class="modal-dialog" role="document">
                      <div class="modal-content">
                        <div class="modal-header">
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        </div>
                        <div class="modal-body text-center">
                          <ul id="glasscase" class="gc-start">
                            <li>
                              <img src="<?php echo (($product_details['image_url']!='')?$product_details['image_url']:url().'/public/uploads/no-image.png')?>" class="img-responsive" alt=""></li>
                          </ul>
                          

                        </div>
                        <!-- <div class="modal-footer">
                          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div> -->
                      </div>
                    </div>
                  </div>
                <!--\\ product-showcase -->
                <!-- product-details -->
                
                <div class="col-sm-7">
                    <div class="product-details">

                        <h3 class="company-name text-uppercase"><?php echo $product_details['name'];?></h3>
                        <!-- brand-logo -->
                        <?php
                        //print_r($product_details);exit;
                        if($product_details['image']!='' && file_exists('uploads/vendor_image/'.$product_details['image'])){
                          ?>
                          <div class="brand-logo pull-right mt5">
                            <img src="<?php echo url(); ?>/uploads/vendor_image/<?php echo $product_details['image']; ?>" alt="">
                          </div>
                          <?php
                        }
                        ?>
                        
                        <!-- <h6 class="product-name">Black double breasted cotton Piqué Blazer</h6> -->
                        <div class="price-block pull-left mt5">&nbsp;$<?php echo number_format($product_details['price'],2);?>
                        <cite class="green-text">
                        <?php if(isset($product_details['percentage']) && $product_details['percentage']!=''){?>
                        &nbsp;Cashback :<span>
                          <?php 
                            $cashback = ($product_details['price']*$product_details['percentage'])/100;
                            echo "$".number_format($cashback,2);
                          ?></span>
                        <?php }?></cite>
                        </div>
                        <?php if($product_details['percentage']!=''){ ?>
                          <div class="productcashback"><span class="text-center"><?php echo $product_details['percentage']; ?>%</span> Cashback</div>
                        <?php } ?>
                        <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">

                            <div class="panel panel-default">
                                <div class="panel-heading" role="tab" id="headingOne">
                                    <h4 class="panel-title">
                                        <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                            <i class="more-less glyphicon glyphicon-minus"></i>
                                            Product Description
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
                                    <div class="panel-body">
                                          <?php 
                                           if($product_details['api'] == 'LS'){
                                           $details =unserialize($product_details['pd_description']);

                                            if(!empty($details['long'])){
                                              //$dtl = implode(",",$details['long']);
                                                $dtl = $details['long'];
                                            }else{
                                                $dtl = $details['short'];
                                            }
                                          }else{
                                              $dtl = $product_details['pd_description'];
                                          }
                                            echo $dtl;
                                          ?>
                                    </div>
                                </div>
                            </div>

                            <!-- <div class="panel panel-default">
                                <div class="panel-heading" role="tab" id="headingTwo">
                                    <h4 class="panel-title">
                                        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapsethere" aria-expanded="false" aria-controls="collapsethere">
                                            <i class="more-less glyphicon glyphicon-plus"></i>
                                            Product Details
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapsethere" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
                                    <div class="panel-body">
                                        <p>Manufacturer Name : {{($product_details['manufacturer_name']!='')?$product_details['manufacturer_name']:'Unknown'}}</p>

                                        <p>In-Stock :{{($product_details['in-stock']==1)?'Yes':'Out of stock'}}</p>

                                        <p>Brand :{{isset($product_details['product_brand']['brand_name'])?$product_details['product_brand']['brand_name']:'Unknown'}}</p>
                                        
                                      <?php
                                        if(isset($product_details['product_type'])){
                                          echo '<p> Product Type :'.$product_details['product_type'].'</p>';
                                        }
                                        if(isset($product_details['size'])){
                                          echo '<p> Product Size :'.$product_details['size'].'</p>';
                                        }
                                        if(isset($product_details['color'])){
                                          echo '<p> Product Color :'.$product_details['color'].'</p>';
                                        }
                                        if(isset($product_details['age'])){
                                          echo '<p> Age :'.$product_details['age'].'</p>';
                                        }
                                      ?>
                                    </div>
                                </div>
                            </div>  -->

                            <div class="panel panel-default">
                                <div class="panel-heading" role="tab" id="headingTwo">
                                    <h4 class="panel-title">
                                        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                            <i class="more-less glyphicon glyphicon-plus"></i>
                                            Store
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
                                    <div class="panel-body">
                                        Name : {{$product_details['advertiser-name']}}
                                    </div>
                                </div>
                            </div>                    

                        </div><!-- panel-group <?php //echo $product_details['buy_url'];?> -->
                        <div class="details-btns">                          

                          <?php if(Session::has('user_id')){ ?>
                          <a href="{{url()}}/product/click/{{$product_details['pd_id']}}" class="btn btn-primary btn-block">Buy Now</a>
                          <?php }else{ ?>
                            <a href="javascript:void(0);" onclick="loginFunction({{$product_details['pd_id']}});" class="btn btn-primary btn-block">Buy Now</a>
                          <?php } ?>

                          <a href="<?php echo url();?>/add-to-wishlist/<?php echo base64_encode($product_details['pd_id']); ?>" class="btn btn-link btn-block wishlist-link">add to wishlist</a>
                        <?php

                          $title = ucfirst(addslashes($product_details['name']));
                          $url = url()."/cashback/product_details/".base64_encode($product_details['id']);

                        ?>
                        </div>

                        <div class="social-iconblock text-center">
                           <a href="javascript:void(0);" class="fb-icon" onclick="fb_share('<?php echo $title;?>','<?php echo 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>');"><i class="fa fa-facebook"></i></a>
                        
                           <a class="twitter popup twitter-icon" href="http://twitter.com/share?text=<?php echo ucfirst($product_details['name']); ?>"><i class="fa fa-twitter"></i></a>
                        </div>

                    </div>
                </div>
                <!--\\ product-details -->
            </div>

            <!-- related-products -->
            <div class="related-products">
                <h3 class="main-heading text-center text-uppercase nomargin">Related products</h3>
                <div class="popular-products">
                    <div class="swiper-container">
                      <div class="swiper-wrapper">
                      <?php 
                        foreach($related_products as $product){

                           // Create Slug
                            $new_created_slug = preg_replace('/[^\da-z]/i', '-', strtolower($product->name));
                            $new_created_slug=trim(preg_replace('/-+/', '-', $new_created_slug), '-');
                            ?>
                           <div class="swiper-slide"><a href='<?php echo url();?>/product_details/<?php echo base64_encode($product->id); ?>/<?php echo $new_created_slug; ?>'>
                             <?php if(isset($product->percentage) && $product->percentage!='') {?>
                              <div class="main-discount">{{($product->percentage)}}%</div>
                             <?php } ?>
                            <div class="img-product">
                                <img src="<?php echo $product->image_url;?>" alt="">
                            </div>
                            <div class="prod-info">
                                <h6><?php echo $product->name;?></h6>
                                <p class="price-block">$<?php echo number_format($product->price,2);?></p>
                            </div>
                            </a>                        
                        </div>
                        <?php
                        }
                      ?>       
                      </div>
                     
                      <div class="swiper-button-next"></div>
                      <div class="swiper-button-prev"></div>
                    </div>
                </div>
            </div>
            <!--\\ related-products -->
        </div>
    </section>
    <!-- maincontent -->
    <!-- Modal -->
    <div id="confirm-modal" class="modal email-signupstyle text-modal fade" role="dialog">
      <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">          
          <div class="modal-body clearfix">
            <!--<button type="button" class="close" data-dismiss="modal">&times;</button>-->        
            <div class="modal-full">
              <div class="moadal-head text-center"><h4>On the way to <span class="product-name">Confident Living</span></h4></div>
              <div class="pad-20">
                <p>Sign up or login to get the <span class="green-text"><?php echo $product_details['percentage']; ?>%</span> bonus on your purchase</p>
                <!-- <input type="text" class="form-control" placeholder="EMAIL ADDRESS"> -->
                <div class="button-region text-center">
                  <button type="button"  onclick="buy_now();" class="btn btn-primary dark-btn text-uppercase">activate your bonus now</button>
                </div>                
              </div>
            </div>
          </div>          
        </div>

      </div>
    </div>

<script>
    function buy_now(){
      $('#confirm-modal').modal('hide');
      window.location.href="<?php echo url().'/buy-now/'.base64_encode($product_details['buy_url']);  ?>";
    }

    function loginFunction(product_id){
      var token = '{{csrf_token()}}';
      $.ajax({
        type  : 'POST',
        url : '<?php echo url(); ?>/product/session',
        data  : {product_id: product_id,_token:token},
        async : false,
        success : function(response){
            if (response==1) {
              window.location.href = '{{url('login')}}';
            }
          }
        });
    }

    $(document).ready(function(){
      $('#confirm-modal').modal({backdrop: 'static', keyboard: false,show:false});
    });
      
  function fb_share(team_name,url) {
  //alert(team_name+'-'+url);
    FB.ui(
    {
      method: 'share',
      name: team_name,
      href: url
    },
    
    function(response) {
      //if (response && !response.error_code) {
      //  alert('Posting completed.');
      //} else {
      //  alert('Error while posting.');
      //}
    }
  );
}
</script>

<script>  

$(document).ready(function(){
    $('a[data-text]').each(function(){
      $(this).attr('data-text', "");
    });
    $.getScript('http://platform.twitter.com/widgets.js');
    /*$('#confirm-modal').modal('show');
    $('#confirm-modal').on('bs.modal.shown',function(){

    });*/
});
</script>


<script>
  $('.popup').click(function(event) {
  
    var width  = 575,
        height = 400,
        left   = ($(window).width()  - width)  / 2,
        top    = ($(window).height() - height) / 2,
        url    = this.href,
        opts   = 'status=1' +
                 ',width='  + width  +
                 ',height=' + height +
                 ',top='    + top    +
                 ',left='   + left;

    window.open(url, 'twitter', opts);
 
    return false;
  });

 $('#myModalZoompopUp').on('shown.bs.modal', function(){
  $('#glasscase').glassCase({ 'autoInnerZoom': true, 'zoomPosition': 'inner'});
 });

</script>
 @stop