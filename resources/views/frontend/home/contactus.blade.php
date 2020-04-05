@extends('../layout/home_template')
@section('content')



<div class="site-main-container">

<div class="container">

<div class="site-main-heading">
<h2>Contact Us</h2>
</div>

   <div class="site-main-content contactus-content">

<!--    <section class="feature-sec contactus-sec">


   <div class="feature-blueBlock-sec">
     <div class="feature-blueBlock-content">

     <div class="contact-form-sec">
     <h3>Get In Touch</h3>

     <div class="comn-form-content contact-form">
		<div class="form-group">
		<label class="form-control-label">Name</label>
		<input type="text" class="form-control" placeholder="Enter Your Name">
		</div>

		<div class="form-group">
		<label class="form-control-label">Email</label>
		<input type="email" class="form-control">
		</div>

		<div class="form-group">
		<label class="form-control-label">Phone Number</label>
		<input type="text" class="form-control" placeholder="Phone Number">
		</div>

		<div class="form-group">
		<label class="form-control-label">Message</label>
		<textarea class="form-control"></textarea>
		</div>

     </div>

     </div>

     </div>
  </div>


    <div class="feature-circel-block block_1">
      <div class="feature-circel-content d-flex align-items-center">
        <div class="content-holder">
        <div class="icon-holder">
          <img src="<?php //echo url(); ?>/public/frontend/images/icons/repeat.svg">
        </div>
        <h3>Email Address</h3>
        <div class="contact-circel-info">
        <a href="mailto:info@checkoutsaver.com" class="contact-circel-info-link">info@checkoutsaver.com</a>
         <a href="mailto:info@company.com" class="contact-circel-info-link">info@company.com</a>
        </div>

       </div>
      </div>
     </div>

    <div class="feature-circel-block block_2">
      <div class="feature-circel-content d-flex align-items-center">
        <div class="content-holder">
        <div class="icon-holder">
          <img src="<?php //echo url(); ?>/public/frontend/images/icons/id-card.svg">
        </div>
         <div class="contact-circel-info">
         <a href="mailto:info@checkoutsaver.com" class="contact-circel-info-link">info@checkoutsaver.com</a>
         <a href="mailto:info@company.com" class="contact-circel-info-link">info@company.com</a>
        </div>
       </div>
      </div>
     </div>

    <div class="feature-circel-block block_3">
      <div class="feature-circel-content d-flex align-items-center">
        <div class="content-holder">
        <div class="icon-holder">
          <img src="<?php //echo url(); ?>/public/frontend/images/icons/magic-wand.svg">
        </div>
        <h3>Share with Us</h3>
        <div class="contact-circel-info social-info">
        <a href="" class="fb-link"><i class="fab fa-facebook-f"></i></a>
        <a href="" class="tw-link"><i class="fab fa-twitter"></i></a>
        <a href="" class="gp-link"><i class="fab fa-google-plus-g"></i></a>
        </div>
       </div>
      </div>
     </div>

</section> -->


<section class="contact-sec">

<div class="contact-card d-flex ">

<div class="contact-card-left">
	
@if(Session::has('success_message'))
<p class="alert {{ Session::get('alert-class', 'alert-success') }}">{{ Session::get('success_message') }}</p>
@endif

@if(Session::has('failure_message'))
<p class="alert {{ Session::get('alert-class', 'alert-danger') }}">{{ Session::get('failure_message') }}</p>
@endif

{!! Form::open(['url' => 'submit-contact-us','method'=>'POST', 'files'=>true,'id'=>'contact_us']) !!}
     <div class="contact-form-sec">
     <h3>Get In Touch</h3>

     <div class="comn-form-content contact-form">
		<div class="form-group">
			<label class="form-control-label">Name</label>
			<input type="text" class="form-control" placeholder="Enter Your Name" id="name" name="name">
		</div>

		<div class="form-group">
			<label class="form-control-label">Email</label>
			<input type="email" class="form-control" id="email" name="email">
		</div>

		<div class="form-group">
			<label class="form-control-label">Phone Number</label>
			<input type="text" class="form-control" placeholder="Phone Number" id="phone" name="phone">
		</div>

		<div class="form-group">
			<label class="form-control-label">Message</label>
			<textarea class="form-control" id="message" name="message"></textarea>
		</div>

		<div class="btn-holder text-center">
		<button type="submit" class="btn btn-solid-blue">Submit</button>
		</div>

     </div>

     </div>
     {!! Form::close() !!}

</div><!-- contact-card-left-->

<div class="contact-card-right d-flex align-items-center">

<div class="contact-info">

<div class="contact-dsc-info">
In auctor posuere sem, vitae molestie nisi condimentum non. Nunc sollicitudin, purus eget pretium pharetra.
</div>

	<div class="contact-info-block d-flex align-items-center">
	        <div class="icon-holder">
	          <img src="<?php echo url(); ?>/public/frontend/images/icons/envelope-wht.svg">
	        </div>
	         <div class="contact-text-info">
	         <div class="heading">Email Address</div>
	         <div class="content">
	         <a href="mailto:info@checkoutsaver.com" class="contact-circel-info-link">info@checkoutsaver.com</a>
	         </div>
	        </div>
	</div><!--end contact-info-block-->

		<div class="contact-info-block d-flex align-items-center">
	        <div class="icon-holder">
	          <img src="<?php echo url(); ?>/public/frontend/images/icons/mobile-wht.svg">
	        </div>
	         <div class="contact-text-info">
	         <div class="heading">Phone Number</div>
	         <div class="content">
	         <a href="tel:18001234347" class="contact-circel-info-link">1800 - 1234_347</a>
	         </div>
	        </div>
	</div><!--end contact-info-block-->

		<div class="contact-info-block d-flex align-items-center">
	        <div class="icon-holder">
	          <img src="<?php echo url(); ?>/public/frontend/images/icons/share-wht.svg">
	        </div>
	         <div class="contact-social-info">
	         <div class="heading">Share with Us</div>
	         <div class="content">
				<a href="" class="fb-link"><i class="fab fa-facebook-f"></i></a>
				<a href="" class="tw-link"><i class="fab fa-twitter"></i></a>
				<a href="" class="gp-link"><i class="fab fa-google-plus-g"></i></a>
				</div>
	        </div>
	</div><!--end contact-info-block-->

</div>

</div><!-- contact-card-right-->


</div>

</section>

    </div><!--end site-main-content-->

    </div><!--end container-->

</div>



@stop

