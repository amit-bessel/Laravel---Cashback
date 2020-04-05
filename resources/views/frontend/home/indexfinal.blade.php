@extends('../layout/home_template')
@section('content')

<section class="banner-sec">

  

  <div class="banner-slider-sec">
      <!-- Swiper -->
  <div class="swiper-container">
    <div class="swiper-wrapper">

      <?php 
      if(!empty($banner))
      {
       
              foreach ($banner as $key => $value) 
              {
        
        
      ?>

      <div class="swiper-slide">
        <div class="banner-content-sec">
        <h2><?php if(!empty($value->banner_heading)){ echo $value->banner_heading; }?></h2>
        <p><?php if(!empty($value->banner_text)){ echo $value->banner_text; }?> </p>
        <div class="banner-content-btn">
          <a href="<?php if(!empty($data['sitesettings']['homebannerlearnmorelink'][0]->value)){ echo $data['sitesettings']['homebannerlearnmorelink'][0]->value; }?>" class="btn btn-blue">Learn More</a>
            <a href="<?php echo url();?>/cms/how-it-works" class="btn btn-ylw">How It Works</a>
        </div>
      </div><!--end banner-content-sec-->


        <div class="banner-img">
            <?php 
            $pathinfo = pathinfo($value->banner_image);
            if($pathinfo['extension'] == 'jpg' || $pathinfo['extension'] == 'jpeg' || $pathinfo['extension'] == 'png' || $pathinfo['extension'] == 'gif'){
            ?>
          <img src="<?php echo url(); ?>/public/uploads/banner/thumb/<?php echo $value->banner_image;?>">
          <?php }else{ ?>
          <!-- <div class="videoWrapper" style="position: relative;z-index: 8;">

          <iframe src="<?php echo url(); ?>/public/uploads/banner/thumb/<?php echo $value->banner_image;?>" allowtransparency="true" frameborder="0" scrolling="no" class="wistia_embed" name="wistia_embed" allowfullscreen mozallowfullscreen webkitallowfullscreen oallowfullscreen msallowfullscreen width="640" height="360"></iframe>

          <script charset="ISO-8859-1" src="//fast.wistia.com/assets/external/E-v1.js" async></script> -->
          <?php } ?>
        </div>
      </div>
      <?php 
               }                 

        } 

       ?>

    </div>    
    <!-- If we need navigation buttons -->
<!--     <div class="swiper-button-prev"></div>
    <div class="swiper-button-next"></div> -->

  </div>
  <div class="swiper-pagination"></div>
  </div><!--end banner-slider-sec-->
  
</section>

<section class="benefits-sec">
<div class="container">
  <div class="heading">
    <h2><?php if(!empty($data['sitesettings']['homebannerundersavemoneytitle'][0]->value)){ echo $data['sitesettings']['homebannerundersavemoneytitle'][0]->value; }?></h2>
    <p><?php if(!empty($data['sitesettings']['homebannerundersavemoneydescription'][0]->value)){ echo $data['sitesettings']['homebannerundersavemoneydescription'][0]->value; }?></p>
  </div>

  <div class="benefits-content-sec">

    <div class="row">

      <div class="col-md-4">
      <div class="benefits-content-block">
        <div class="icon-holder">
          <img src="<?php echo url(); ?>/public/frontend/images/icons/give-money.svg">
        </div>
        <h3><?php if(!empty($data['sitesettings']['homebannerundercashbacktitle'][0]->value)){ echo $data['sitesettings']['homebannerundercashbacktitle'][0]->value; }?></h3>
        <p><?php if(!empty($data['sitesettings']['homebannerundercashbackdescription'][0]->value)){ echo $data['sitesettings']['homebannerundercashbackdescription'][0]->value; }?></p>
      </div><!--end benefits-content-block-->
      </div><!--end col-->

    <div class="col-md-4">
      <div class="benefits-content-block">
        <div class="icon-holder">
          <img src="<?php echo url(); ?>/public/frontend/images/icons/gift.svg">
        </div>
        <h3><?php if(!empty($data['sitesettings']['homebannerundergiftcardstitle'][0]->value)){ echo $data['sitesettings']['homebannerundergiftcardstitle'][0]->value; }?></h3>
        <p><?php if(!empty($data['sitesettings']['homebannerundergiftcardsdescription'][0]->value)){ echo $data['sitesettings']['homebannerundergiftcardsdescription'][0]->value; } ?></p>
      </div><!--end benefits-content-block-->
      </div><!--end col-->

    <div class="col-md-4">
      <div class="benefits-content-block">
        <div class="icon-holder">
          <img src="<?php echo url(); ?>/public/frontend/images/icons/coupon.svg">
        </div>
        <h3><?php if(!empty($data['sitesettings']['homebannerunderprivatecouponstitle'][0]->value)){ echo $data['sitesettings']['homebannerunderprivatecouponstitle'][0]->value; }?></h3>
        <p><?php if(!empty($data['sitesettings']['homebannerunderprivatecouponsdescription'][0]->value)){ echo $data['sitesettings']['homebannerunderprivatecouponsdescription'][0]->value; } ?> </p>
      </div><!--end benefits-content-block-->
      </div><!--end col-->

    </div><!--end row-->

  </div><!--end benefits-content-sec-->
  </div><!--end container-->
</section>


<section class="feature-sec">

  <div class="feature-blueBlock-sec">
     <div class="feature-blueBlock-content">
      <h3><?php if(!empty($data['sitesettings']['homemakemoneytitle'][0]->value)){ echo $data['sitesettings']['homemakemoneytitle'][0]->value; }?></h3>
      <p><?php if(!empty($data['sitesettings']['homemakemoneydescription'][0]->value)){ echo $data['sitesettings']['homemakemoneydescription'][0]->value; } ?></p>
     </div><!--end feature-blueBlock-content-->
  </div><!--end feature-blueBlock-sec-->


    <div class="feature-circel-block block_1">
      <div class="feature-circel-content d-flex align-items-center">
        <div class="content-holder">
        <div class="icon-holder">
          <img src="<?php echo url(); ?>/public/frontend/images/icons/repeat.svg">
        </div>
        <h3><?php if(!empty($data['sitesettings']['homelifetimecommissiontitle'][0]->value)){ echo $data['sitesettings']['homelifetimecommissiontitle'][0]->value; }?></h3>
        <p><?php if(!empty($data['sitesettings']['homelifetimecommissiondescription'][0]->value)){ echo $data['sitesettings']['homelifetimecommissiondescription'][0]->value; } ?></p>
       </div>
      </div><!--end feature-circel-content-->
     </div><!--end feature-circel-block-->

    <div class="feature-circel-block block_2">
      <div class="feature-circel-content d-flex align-items-center">
        <div class="content-holder">
        <div class="icon-holder">
          <img src="<?php echo url(); ?>/public/frontend/images/icons/id-card.svg">
        </div>
        <h3><?php if(!empty($data['sitesettings']['homegiftcardstitle'][0]->value)){ echo $data['sitesettings']['homegiftcardstitle'][0]->value; }?></h3>
        <p><?php if(!empty($data['sitesettings']['homegiftcardsdescription'][0]->value)){ echo $data['sitesettings']['homegiftcardsdescription'][0]->value; } ?></p>
       </div>
      </div><!--end feature-circel-content-->
     </div><!--end feature-circel-block-->

    <div class="feature-circel-block block_3">
      <div class="feature-circel-content d-flex align-items-center">
        <div class="content-holder">
        <div class="icon-holder">
          <img src="<?php echo url(); ?>/public/frontend/images/icons/magic-wand.svg">
        </div>
        <h3><?php if(!empty($data['sitesettings']['homeprivatecouponstitle'][0]->value)){ echo $data['sitesettings']['homeprivatecouponstitle'][0]->value; }?></h3>
        <p><?php if(!empty($data['sitesettings']['homeprivatecouponsdescription'][0]->value)){ echo $data['sitesettings']['homeprivatecouponsdescription'][0]->value; } ?></p>
       </div>
      </div><!--end feature-circel-content-->
     </div><!--end feature-circel-block-->

<div class="feature-blueSmBlock-sec-holder">
<div class="feature-blueBlock-sec feature-blueSmBlock-sec"></div>
</div>
  
</section><!--end feature-sec-->

<section class="stores-sec">

  
<div class="container">
<div class="heading text-center">
  <h2>Top Stores</h2>
</div>

<div class="stores-content-sec">

<div class="row">
<?php
//echo "<pre>";
//print_r($popularvendordetails);exit();
?>
<?php
if(!empty($popularvendordetails)){
foreach ($popularvendordetails as $key => $value) {
	

	?>
<div class="col-md-4">
  <div class="stores-content-block">
    <div class="brand-img-holder d-flex align-items-center justify-content-center">
     <?php // echo $value->linkcodehtml;?>
     <?php if(($value->logo!="") && (file_exists("public/uploads/brand/logo/".$value->logo)))
                    {
                    ?>
                        <img id="blah1" src="<?php echo url(); ?>/public/uploads/brand/logo/<?php echo $value->logo ?>">
                    <?php
                    }
                    else
                    {
                    ?>
                        <img id="blah1" src="<?php echo url(); ?>/public/uploads/no-image.png" >
                    <?php
                    }
                    ?>
    </div>
    <h4><?php echo $value->salecommission;?> discount on shopping</h4>
  </div><!--end stores-content-block-->
</div><!--end col-->

<?php } } ?>



</div><!--end row-->

</div><!--stores-content-sec-->

</div><!--end container-->
  
</section>


<section class="signup-sec">
  
  <div class="container">
    <div class="signup-content-sec">
      <h2><?php if(!empty($data['sitesettings']['homesignuptitle'][0]->value)){ echo $data['sitesettings']['homesignuptitle'][0]->value; }?></h2>
      <p><?php if(!empty($data['sitesettings']['homesignupdescription'][0]->value)){ echo $data['sitesettings']['homesignupdescription'][0]->value; } ?></p>

<div class="btn-holder">
  <a href="<?php echo url();?>/signup" class="btn btn-ylw btn-signup-lg">SIGN UP NOW</a>
</div>

    </div>

  </div>
</section><!--end signup-sec-->



<!-- newsletter Modal -->
<div class="modal fade" class="newsletter-modal" id="newsletter_modal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content newsletter-modal-content">
    
    <div class="newsletter-modal-header">
    <div class="close" data-dismiss="modal" aria-label="Close"><i class="la la-close"></i></div>
   <div class="logo-holder"><img src="<?php echo url(); ?>/public/frontend/images/logo.png"></div>
    <div class="logo-icon-holder"><img src="<?php echo url(); ?>/public/frontend/images/cashbag-img2.png"></div>
    </div>

    <div class="newsletter-modal-body">

    <div class="heading">
    <h2>Join Us Now</h2>
    <p>Ut vel magna vel risus egestas ullamcorper ut posuere diam. Duis sed tortor in neque feugiat molestie.</p>
    </div>

    <div class="newsletter-modal-form-sec">
    <div class="form-group">
    <input type="email" class="form-control" placeholder="Email Address">
    </div>
    <div class="btn-holder">
    <button type="submit" class="btn btn-ylw">Subscribe</button>
    </div>
    </div><!--end newsletter-modal-form-sec-->

    </div>
      
    </div>
  </div>
</div>


@stop