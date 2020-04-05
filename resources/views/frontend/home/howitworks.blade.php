@extends('../layout/home_template')
@section('content')


<div class="site-main-container">
  <div class="container">

<div class="site-main-heading">
<h2>How It Works</h2>
<p>Cras sodales lacus turpis. Maecenas rutrum pellentesque neque, eu interdum arcu vehicula non. Etiam quam quis aliquet volutpat metus sit amet egestas augue.</p>
</div>

<div class="site-main-content howItwork-content">

<div class="howItwork-video-sec">
<div class="video-holder videoWrapper">
<iframe height="460" src="https://www.youtube.com/embed/fNdNUdP1bBQ" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
</div>
</div>

<div class="howItwork-step-sec">

<div class="heading">
<h2>Duis et tincidunt ipsum. Curabitur ut augue nisl.</h2>
<p>Cras sodales lacus turpis. Maecenas rutrum pellentesque neque, eu interdum arcu vehicula non. Etiam quam quis aliquet volutpat metus sit amet egestas augue.</p>
</div>

<div class="howItwork-step">
<span class="blue-line"></span>
<div class="howItwork-step-block howItwork-step-block-1 active d-flex justify-content-center align-items-center" id="howItwork_step_1">

<div class="howItwork-step-icon-holder">
<div class="howItwork-step-icon step-1-icon">
</div>
</div>

<div class="howItwork-step-dsc">
<div class="step-numb">
1
</div>
<h3>click</h3>
<p>Aliquam quis euismod orci. Curabitur eu sapien nec elit sagittis mollis eu non mi. Vivamus vel nisi commodo, eleifend sapien nec, facilisis ligula.
</p>

</div>

</div><!--end howItwork-step-block-->


<div class="howItwork-step-block howItwork-step-block-2 even-block d-flex justify-content-center align-items-center" id="howItwork_step_2">

<div class="howItwork-step-icon-holder order-sm-2">
<div class="howItwork-step-icon step-2-icon">
</div>
</div>

<div class="howItwork-step-dsc">
<div class="step-numb">
2
</div>
<h3>shop</h3>
<p>Aliquam quis euismod orci. Curabitur eu sapien nec elit sagittis mollis eu non mi. Vivamus vel nisi commodo, eleifend sapien nec, facilisis ligula.
</p>

</div>

</div><!--end howItwork-step-block-->

<div class="howItwork-step-block howItwork-step-block-3 d-flex justify-content-center align-items-center" id="howItwork_step_3">

<div class="howItwork-step-icon-holder">
<div class="howItwork-step-icon step-3-icon">
</div>
</div>

<div class="howItwork-step-dsc">
<div class="step-numb">
3
</div>
<h3>earn</h3>
<p>Aliquam quis euismod orci. Curabitur eu sapien nec elit sagittis mollis eu non mi. Vivamus vel nisi commodo, eleifend sapien nec, facilisis ligula.
</p>

</div>

</div><!--end howItwork-step-block-->

<div class="howItwork-step-block howItwork-step-block-4 even-block d-flex justify-content-center align-items-center" id="howItwork_step_4">

<div class="howItwork-step-icon-holder order-sm-2">
<div class="howItwork-step-icon step-4-icon">
</div>
</div>

<div class="howItwork-step-dsc">
<div class="step-numb">
4
</div>
<h3>invite friends for commission</h3>
<p>Aliquam quis euismod orci. Curabitur eu sapien nec elit sagittis mollis eu non mi. Vivamus vel nisi commodo, eleifend sapien nec, facilisis ligula.
</p>

</div>

</div><!--end howItwork-step-block-->

<div class="howItwork-step-block howItwork-step-block-5 d-flex justify-content-center align-items-center" id="howItwork_step_5">

<div class="howItwork-step-icon-holder">
<div class="howItwork-step-icon step-5-icon">
</div>
</div>

<div class="howItwork-step-dsc">
<div class="step-numb">
5
</div>
<h3>payout</h3>
<p>Aliquam quis euismod orci. Curabitur eu sapien nec elit sagittis mollis eu non mi. Vivamus vel nisi commodo, eleifend sapien nec, facilisis ligula.
</p>

</div>

</div><!--end howItwork-step-block-->

</div><!--end howItwork-step-->

<div class="heading">
<h2>Duis et tincidunt ipsum. Curabitur ut augue nisl.</h2>
<p>Cras sodales lacus turpis. Maecenas rutrum pellentesque neque, eu interdum arcu vehicula non. Etiam quam quis aliquet volutpat metus sit amet egestas augue.</p>
</div>

<div class="btn-holder">
<a href="#" class="btn howItwork-sign-btn border-btn">Sign Up</a>
</div>


</div><!--end howItwork-step-sec-->



		</div>


</div><!--end container-->


<div class="coming-soon-sec">
<div class="container">

<div class="heading">
<h2>Coming Soon</h2>
<p>Quisque lectus orci, finibus sit amet sollicitudin vitae</p>
</div>

<div class="coming-soon-content d-sm-flex justify-content-sm-center">

<div class="coming-soon-content-left d-md-flex justify-content-md-end">
<div class="img-holder order-2">
<img src="<?php echo url(); ?>/public/frontend/images/icons/gift-card-wht.svg">
</div>
<div class="dsc-content">
<h3>Gift card</h3>
<p>Aliquam quis euismod orci. Curabitur eu sapien nec elit sagittis mollis eu non mi. Vivamus vel.</p>
</div>

</div><!--end coming-soon-content-left-->

<div class="coming-soon-content-right d-md-flex justify-content-md-start">
<div class="img-holder">
<img src="<?php echo url(); ?>/public/frontend/images/icons/coupon2-wht.svg">
</div>
<div class="dsc-content">
<h3>Coupon</h3>
<p>Aliquam quis euismod orci. Curabitur eu sapien nec elit sagittis mollis eu non mi. Vivamus vel.</p>
</div>
</div>

</div><!--end coming-soon-content-->

</div>
</div><!--end site-main-container-->	

@stop