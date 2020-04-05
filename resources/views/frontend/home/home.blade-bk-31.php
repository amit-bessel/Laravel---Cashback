@extends('../layout/frontend_template')
@section('content')

    
    <!-- cashback-showcase -->
    <div class="container">
    <?php
    if(count($home_page_sliders)>0){

      $slider_image_name = 'banner_image';
      $slider_image_url  = 'banner_url_men';
      if($sessionvalue==2){
        $slider_image_name = 'banner_image_women';
        $slider_image_url  = 'banner_url_women';
      }
      ?>
      <section class="cashback-showcase">
        <div class="swiper-container">
            <div class="swiper-wrapper">
                <?php
                foreach($home_page_sliders as $each_sliders){

                  ?>
                  <div class="swiper-slide" style="cursor:pointer;" onclick="window.open('<?php echo $each_sliders[$slider_image_url];?>', '_blank')"><img src="<?php echo url(); ?>/uploads/banner_image/big/<?php echo $each_sliders[$slider_image_name]; ?>" class="img-responsive" alt=""></div>
                  <?php
                }
                ?>
                                
            </div>
            <!-- Add Pagination -->
            <div class="swiper-pagination"></div>
        </div> 
      </section>
      <?php
    }
    ?>
    <!-- Swiper -->
    
    </div>
    <!--\\ cashback-showcase -->
    <!-- home-implink -->
    <!-- <section class="home-implink">
        <div class="container">            
            <div class="col-sm-4 icon-block icon-1">
                <div class="table-prop"><div class="table-cell"><a href="<?php echo url().'/stores/'; ?>"><img src="<?php echo url(); ?>/public/frontend/images/icon-1.png" alt="">Visit Our Vendors</a></div></div>
            </div>
            <div class="col-sm-4 icon-block icon-2">
                <div class="table-prop"><div class="table-cell"><a href="<?php echo url().'/stores/'; ?>"><img src="<?php echo url(); ?>/public/frontend/images/icon-2.png" alt="">Visit Our Vendors</a></div></div>
            </div>
            <div class="col-sm-4 icon-block icon-3">
                <div class="table-prop"><div class="table-cell"><a href="<?php echo url().'/stores/'; ?>"><img src="<?php echo url(); ?>/public/frontend/images/icon-3.png" alt="">Visit Our Vendors</a></div></div>
            </div>            
        </div>
    </section> -->
    <!-- home-implink -->

    <!-- newstepssection -->
    <section class="newstepssection">
      <div class="container">
        <div class="row">
          <div class="col-sm-4 step-part">
              <div class="step-icon step-1">

              </div>
              <div class="step-info">
                  <div class="table-prop"><div class="table-cell"><h4>Sign up and shop <span>through FrontMode at your favorite stores</span>.</h4></div></div>
              </div>
          </div>
          <div class="col-sm-4 step-part">
              <div class="step-icon step-2">

              </div>
              <div class="step-info">
                  <div class="table-prop"><div class="table-cell"><h4>Earn cash back <span>on your purchases</span></h4></div></div>
              </div>
          </div>
          <div class="col-sm-4 step-part">
              <div class="step-icon step-3">

              </div>
              <div class="step-info">
                  <div class="table-prop"><div class="table-cell"><h4><span>Get paid to your</span> Paypal <span>or</span> Stripe account.</h4></div></div>
              </div>
          </div>
        </div>  
      </div>  
    </section>
    <!--\\ newstepssection -->

    <!-- bigger-showcase -->
    <section class="bigger-showcase">
        <div class="container">
          <div class="row">
              <div class="col-sm-6">
                <div class="product-block">
                    <div class="img-section">
                        <span class="for-hover"></span>
                        <img src="<?php echo url(); ?>/uploads/home_page_images/<?php echo $home_page_banner_details[0]['image']; ?>" alt="">
                    </div>
                    <div class="text-block text-center">
                        <h3 class="text-uppercase"><?php echo $home_page_banner_details[0]['description']; ?></h3>
                        <div class="discountpercent text-uppercase">
                          <?php echo $home_page_banner_details[0]['cashback']; ?>
                        </div>
                        <a href="<?php echo $home_page_banner_details[0]['link']; ?>" target="_blank" class="btn-link">Shop now</a>
                    </div>
                </div>
              </div>
              <div class="col-sm-6">
                <div class="product-block">
                    <div class="img-section">
                        <span class="for-hover"></span>
                        <img src="<?php echo url(); ?>/uploads/home_page_images/<?php echo $home_page_banner_details[1]['image']; ?>" alt="">
                    </div>
                    <div class="text-block text-center">
                        <h3 class="text-uppercase"><?php echo $home_page_banner_details[1]['description']; ?></h3>
                        <div class="discountpercent text-uppercase">
                          <?php echo $home_page_banner_details[1]['cashback']; ?>
                        </div>
                        <a href="<?php echo $home_page_banner_details[1]['link']; ?>"  target="_blank" class="btn-link">Shop now</a>
                    </div>
                </div>
              </div>
          </div>
        </div>
    </section>
    <!--\\ bigger-showcase -->

    <!-- smallewr-showcase -->
    <section class="smallewr-showcase">
        <div class="container">
            <div class="row">
                <div class="col-sm-4">
                    <div class="product-block">
                        <div class="img-section">
                            <span class="for-hover"></span>
                            <img src="<?php echo url(); ?>/uploads/home_page_images/<?php echo $home_page_banner_details[2]['image']; ?>" alt="">
                        </div>
                        <div class="text-block text-center">
                            <h3 class="text-uppercase"><?php echo $home_page_banner_details[2]['description']; ?></h3>
                            <div class="discountpercent text-uppercase">
                              <?php echo $home_page_banner_details[2]['cashback']; ?>
                            </div>
                            <a href="<?php echo $home_page_banner_details[2]['link']; ?>" target="_blank" class="btn-link">Shop now</a>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="product-block">
                        <div class="img-section">
                            <span class="for-hover"></span>
                            <img src="<?php echo url(); ?>/uploads/home_page_images/<?php echo $home_page_banner_details[3]['image']; ?>" alt="">
                        </div>
                        <div class="text-block text-center">
                            <h3 class="text-uppercase"><?php echo $home_page_banner_details[3]['description']; ?></h3>
                            <div class="discountpercent text-uppercase">
                              <?php echo $home_page_banner_details[3]['cashback']; ?>
                            </div>
                            <a href="<?php echo $home_page_banner_details[3]['link']; ?>" target="_blank" class="btn-link">Shop now</a>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="product-block">
                        <div class="img-section">
                            <span class="for-hover"></span>
                            <img src="<?php echo url(); ?>/uploads/home_page_images/<?php echo $home_page_banner_details[4]['image']; ?>" alt="">
                        </div>
                        <div class="text-block text-center">
                            <h3 class="text-uppercase"><?php echo $home_page_banner_details[4]['description']; ?></h3>
                            <div class="discountpercent text-uppercase">
                              <?php echo $home_page_banner_details[4]['cashback']; ?>
                            </div>
                            <a href="<?php echo $home_page_banner_details[4]['link']; ?>" target="_blank" class="btn-link">Shop now</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--\\ smallewr-showcase -->

    
    <div class="container">
        <?php 
        if(count($popular_products)>0){
          ?>
          <h3 class="main-heading text-center text-uppercase">popular products</h3>
          <div class="popular-products">
            <div class="swiper-container">
              <div class="swiper-wrapper">
              <?php
              foreach ($popular_products as $key => $each_product) {
                # code...
                $new_created_slug = preg_replace('/[^\da-z]/i', '-', strtolower($each_product->name));
                $new_created_slug=trim(preg_replace('/-+/', '-', $new_created_slug), '-');
                $details_url = url()."/product_details/".base64_encode($each_product->id)."/".$new_created_slug;
                ?>
                
                <div class="swiper-slide" style="cursor:pointer;" onclick="window.location.href='<?php echo $details_url; ?>'" >
                  <?php
                  if($each_product->percentage!=''){
                    ?>
                    <div class="main-discount">{{$each_product->percentage}}%</div>
                    <?php
                  }
                  ?>
                    
                    <div class="img-sectioncarousel" style="background: url(<?php echo (($each_product->image_url !='')?$each_product->image_url: url().'/public/uploads/no-image.png'); ?>) no-repeat center center/contain;"></div>
                    <h6 title="{{$each_product->name}}">{{$each_product->name}}</h6>
                    <p class="price-block">${{number_format($each_product->price,2)}}</p>
                </div>
                <?php
              }
              ?> 
              </div>
             
              <div class="swiper-button-next"></div>
              <div class="swiper-button-prev"></div>
            </div>
          </div>
          <?php
        }
        ?>
        

      <div class="client-section">
        <?php
        if(count($our_clients)>0){
          ?>
            <h3 class="main-heading text-center text-uppercase nomargin">stores</h3>
            <h5 class="sub-heading text-center">Go to any of our partner via us and earn you cash back.</h5> 
            <div class="swiper-container">
            <div class="swiper-wrapper">
              <?php
              foreach($our_clients as $partner){
                ?>
                  <div class="swiper-slide" style="cursor:pointer;" onclick="window.open('<?php echo $partner['vendor_url']; ?>', '_blank')">
                    <div class="forwrap">
                    <?php
                      if($partner['percentage']!=0){
                        ?>
                          <div class="main-discount">{{$partner['percentage']}}%</div>
                        <?php
                      }
                      if(file_exists("uploads/vendor_image/".$partner['image']) && $partner['image']!=''){
                        $partner_image = url()."/uploads/vendor_image/".$partner['image'];
                      }
                      else{
                        $partner_image = url()."/public/frontend/images/no_vendor.jpg";
                      }
                      ?>
                      
                      <div class="client-img" style="background: url(<?php echo $partner_image ?>) no-repeat center center/contain;"></div>
                    </div>
                  </div>
                <?php
              }
              ?>
              </div>
             
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
            </div>
          <?php
        }
        ?>
        

      </div>     

    </div>

    @stop
