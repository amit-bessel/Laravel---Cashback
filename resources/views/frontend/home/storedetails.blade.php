@extends('../layout/home_template')
@section('content')
<div class="site-main-container">


<div class="site-main-content">

<div class="store-sec-1">

<div class="container">

<div class="store-dsc-sec">
<div class="heading-holder">
	<?php echo $store_details['advertisername']; ?>
	<?php //echo $store_details['linkcodehtml']; ?>

<!-- <img src="<?php echo url(); ?>/public/frontend/images/brand/southwest-logo.jpg"> -->
</div>

<div class="dsc-text">
<p><?php echo $store_details['description']; ?></p>
</div><!--end store-dsc-text-->
<div class="blnc-text"><?php echo $store_details['salecommission']; ?> Cashback</div>

<div class="btn-holder">
<a href="<?php echo $store_details['clickurl']; ?>" class="btn btn-ylw">Activate Cashback</a>
</div>

</div><!--end store-dsc-sec-->

<div class="store-feature-sec">

<div class="row">

<div class="col-lg-5 col-md-12">

<div class="store-feature-dsc-block">
<h3>Get paid easily</h3>
<h4><?php echo $vendor_details_left_content[0]['display_name']; ?></h4>
<p><?php echo $vendor_details_left_content[0]['value']; ?></p>
</div><!--end store-feature-dsc-block-->

</div><!--end col-->

<div class="col-lg-7 col-md-12">

<div class="store-feature-box-holder clearfix">

<div class="store-feature-box feature-box1 d-flex align-items-center ">

	<div class="icon-holder">
	</div>

	<div class="text-info">
	<h3>click</h3>
	<p>Proin ultrices sed mauris at ornare. </p>
	</div>

</div><!--end store-feature-box-->

<div class="store-feature-box feature-box2 d-flex align-items-center">

	<div class="icon-holder">
	</div>

	<div class="text-info">
	<h3>click</h3>
	<p>Proin ultrices sed mauris at ornare. </p>
	</div>

</div><!--end store-feature-box-->

<div class="store-feature-box feature-box3 d-flex align-items-center ">

	<div class="icon-holder">
	</div>

	<div class="text-info">
	<h3>click</h3>
	<p>Proin ultrices sed mauris at ornare. </p>
	</div>

</div><!--end store-feature-box-->

<div class="store-feature-box feature-box4 d-flex align-items-center ">

	<div class="icon-holder">
	</div>

	<div class="text-info">
	<h3>click</h3>
	<p>Proin ultrices sed mauris at ornare. </p>
	</div>

</div><!--end store-feature-box-->

</div><!--end store-feature-box-holder-->

</div><!--end col-->

</div><!--end row-->
</div><!--end store-feature-sec-->

</div><!--end container-->

</div><!--end <div class="store-sec-1">-->

<div class="store-sec-2">

<div class="container">

<div class="store-tc-sec">
<div class="heading"><h2>Other Terms & Conditions</h2></div>

<div class="store-tc-content">

<ul>
	<li>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut imperdiet magna ut leo ultrices, quis dapibus eros tristebitasse     
    platea di nec feugiat consequat. Nullam non nunc nisl. Nullam viverrctumst.</li>

  <li>Proin ultrices sed mauris at ornare. Praesent dictum at magna vel tincidunt. Donec eu augue vel magna loe porta pulvinar     
    dui. Morbi sit amet dolor mattis, commodo nulla viverra, sagitti</li>

<li>Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Fusllentesque tincidunt justo       
     aliquet at. Donec et ex vitae mi posuere blandit. Etiam nibh tellus, auctor vitae sollicitudin quisis sed tinct erosusce gula. Morbi sit amet dolor mattis, commodo nulla viverra, sagitti</li>
     	<li>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut imperdiet magna ut leo ultrices, quis dapibus eros tristebitasse     
    platea di nec feugiat consequat. Nullam non nunc nisl. Nullam viverrctumst.</li>

  <li>Proin ultrices sed mauris at ornare. Praesent dictum at magna vel tincidunt. Donec eu augue vel magna loe porta pulvinar     
    dui. Morbi sit amet dolor mattis, commodo nulla viverra, sagitti</li>

<li>Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Fusllentesque tincidunt justo       
     aliquet at. Donec et ex vitae mi posuere blandit. Etiam nibh tellus, auctor vitae sollicitudin quisis sed tinct erosusce gula. Morbi sit amet dolor mattis, commodo nulla viverra, sagitti</li>
     	<li>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut imperdiet magna ut leo ultrices, quis dapibus eros tristebitasse     
    platea di nec feugiat consequat. Nullam non nunc nisl. Nullam viverrctumst.</li>

  <li>Proin ultrices sed mauris at ornare. Praesent dictum at magna vel tincidunt. Donec eu augue vel magna loe porta pulvinar     
    dui. Morbi sit amet dolor mattis, commodo nulla viverra, sagitti</li>


</ul>

</div>

<div class="more-info-text">For more details: <a href="">www.checkout_saver.com</a></div>

</div><!--end store-tc-sec-->

<?php if(count($similar_vendors) > 0){ ?>
<div class="similar-store-slider-sec">
<div class="heading"><h2>Similar Stores</h2></div>

<div class="similar-store-slider">

  <div class="swiper-container">
    <div class="swiper-wrapper">
    	<?php foreach ($similar_vendors as $key => $value) { ?>
    		
    	
        <div class="swiper-slide">
			<div class="stores-content-block">
				<div class="brand-img-holder d-flex align-items-center justify-content-center">
					<?php echo $value['linkcodehtml']; ?>
					<!-- <a href="http://www.kqzyfj.com/click-8457053-12587039-1500397974000">
						<img src="http://www.lduhtrp.net/image-8457053-12587039-1500397974000" width="300" height="250" alt="" border="0">
					</a>  -->   
				</div>
				<h4><?php echo $value['salecommission']; ?> discount on shopping</h4>
			</div>
      	</div><!--end swiper-slide-->
      	<?php } ?>
            <!-- <div class="swiper-slide">
		<div class="stores-content-block">
		<div class="brand-img-holder d-flex align-items-center justify-content-center">
		<a href="http://www.kqzyfj.com/click-8457053-12587039-1500397974000">
		<img src="http://www.lduhtrp.net/image-8457053-12587039-1500397974000" width="300" height="250" alt="" border="0"></a>    </div>
		<h4>USD 75.00 discount on shopping</h4>
		</div>
      </div> --><!--end swiper-slide-->

            <!-- <div class="swiper-slide">
		<div class="stores-content-block">
		<div class="brand-img-holder d-flex align-items-center justify-content-center">
		<a href="http://www.kqzyfj.com/click-8457053-12587039-1500397974000">
		<img src="http://www.lduhtrp.net/image-8457053-12587039-1500397974000" width="300" height="250" alt="" border="0"></a>    </div>
		<h4>USD 75.00 discount on shopping</h4>
		</div>
      </div> --><!--end swiper-slide-->

            <!-- <div class="swiper-slide">
		<div class="stores-content-block">
		<div class="brand-img-holder d-flex align-items-center justify-content-center">
		<a href="http://www.kqzyfj.com/click-8457053-12587039-1500397974000">
		<img src="http://www.lduhtrp.net/image-8457053-12587039-1500397974000" width="300" height="250" alt="" border="0"></a>    </div>
		<h4>USD 75.00 discount on shopping</h4>
		</div>
      </div> --><!--end swiper-slide-->

            <!-- <div class="swiper-slide">
		<div class="stores-content-block">
		<div class="brand-img-holder d-flex align-items-center justify-content-center">
		<a href="http://www.kqzyfj.com/click-8457053-12587039-1500397974000">
		<img src="http://www.lduhtrp.net/image-8457053-12587039-1500397974000" width="300" height="250" alt="" border="0"></a>    </div>
		<h4>USD 75.00 discount on shopping</h4>
		</div>
      </div> --><!--end swiper-slide-->

            <!-- <div class="swiper-slide">
		<div class="stores-content-block">
		<div class="brand-img-holder d-flex align-items-center justify-content-center">
		<a href="http://www.kqzyfj.com/click-8457053-12587039-1500397974000">
		<img src="http://www.lduhtrp.net/image-8457053-12587039-1500397974000" width="300" height="250" alt="" border="0"></a>    </div>
		<h4>USD 75.00 discount on shopping</h4>
		</div>
      </div> --><!--end swiper-slide-->

    </div>
  </div><!--end swiper-container-->

      <!-- Add Arrows -->
    <div class="swiper-button-next"></div>
    <div class="swiper-button-prev"></div>

</div>

</div><!--end similar-store-slider-sec-->

<?php } ?>

</div><!--end container-->

</div><!--end <div class="store-sec-2">-->

</div>


</div>
@stop