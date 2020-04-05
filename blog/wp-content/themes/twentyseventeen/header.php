<!doctype html>
<html lang="en">
<head>
<?php
//$url=url();
//echo "<pre>";print_r($_SERVER);
if($_SERVER['HTTP_HOST']=='localhost')
  define('SITEURL','http://localhost/cashback-justin');
else if($_SERVER['HTTP_HOST']=='staging.uiplonline.com')
  define('SITEURL','http://staging.uiplonline.com/cashback-justin');
else if($_SERVER['HTTP_HOST']=='dev.uiplonline.com')
  define('SITEURL','http://dev.uiplonline.com/cashback-justin');


?>
<title>Checkout Saver</title>
<!-- Required meta tags -->
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">


<meta name="title" content="<?php echo isset($meta_title)?$meta_title:'';?>">
<meta name="description" content="<?php echo isset($meta_description_content)?$meta_description_content:'';?>">
 <meta name="keywords" content="<?php echo isset($keywords)?$keywords:'';?>">
<meta name="author" content="Checkout Saver">

<meta property="fb:app_id" content="152168048896494" />
<meta property="og:title" content="<?php echo (isset($title))?$title:'Checkout Saver';?>" /> 
<meta property="og:type" content="Checkout Saver" /> 
<meta property='og:site_name' content='Checkout Saver' />
<meta property="og:description" content="<?php echo (isset($og_description))?$og_description:'Checkout Saver'; ?>" />
<meta property="og:url" content="<?php echo (isset($og_url))?$og_url:$url; ?>" />
<meta property="og:image" content="<img src='<?php echo SITEURL; ?>/public/frontend/images/fbshare.jpeg'/>" />




<link rel="shortcut icon" type="image/x-icon" href="<?php echo get_home_url(); ?>/../public/frontend/images/favicon.ico">
<!-- Bootstrap CSS -->
<link rel="stylesheet" href="<?php echo get_home_url(); ?>/../public/frontend/bootstrap/css/bootstrap.min.css">
<!-- perfect-scrollbar CSS -->
<link rel="stylesheet" href="<?php echo get_home_url(); ?>/../public/frontend/css/perfect-scrollbar.css" type="text/css">
<!-- swiper-slider CSS -->
<link href="<?php echo get_home_url(); ?>/../public/frontend/css/swiper.min.css" rel="stylesheet">

<link rel="stylesheet" href="<?php echo get_home_url(); ?>/../public/frontend/datepicker/datepicker.min.css" />
<link rel="stylesheet" href="<?php echo get_home_url(); ?>/../public/frontend/datepicker/datepicker3.min.css" />

<!-- Custom CSS -->
<link rel="stylesheet" href="<?php echo get_home_url(); ?>/../public/frontend/css/chat-style.css">
<link rel="stylesheet" href="<?php echo get_home_url(); ?>/../public/frontend/css/style.css">
<link rel="stylesheet" href="<?php echo get_home_url(); ?>/../public/frontend/css/responsive.css">
<link rel="stylesheet" href="<?php echo get_home_url(); ?>/../public/frontend/css/custom.css">

<script src="<?php echo get_home_url(); ?>/../public/frontend/js/jquery.min.js"></script>
<script src="<?php echo get_home_url(); ?>/../public/frontend/js/jquery-ui.js"></script>

<script src="<?php echo get_home_url(); ?>/../public/frontend/js/jquery.validate-1.9.1.min.js"></script>
<script src="<?php echo get_home_url(); ?>/../public/frontend/js/additional-methods-1.10.0.js"></script>
<script src="<?php echo get_home_url(); ?>/../public/frontend/scripts/validation.js"></script>

<script src="<?php echo get_home_url(); ?>/../public/frontend/js/moment.min.js"></script>
<script src="<?php echo get_home_url(); ?>/../public/frontend/js/Chart.js"></script>
<script src="<?php echo get_home_url(); ?>/../public/frontend/js/utils.js"></script>

<div id="fb-root"></div>
<script>(function(d, s, id) {
var js, fjs = d.getElementsByTagName(s)[0];
if (d.getElementById(id)) return;
js = d.createElement(s); js.id = id;
js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.3&appId=152168048896494";


fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>

</head>

<header class="fixed-top main-header">
<nav class="navbar navbar-expand-lg main-navbar">
  <a class="navbar-brand" href="<?php echo SITEURL; ?>"><img src="<?php echo get_home_url(); ?>/../public/frontend/images/logo.png" alt=""></a>

    <div class="topSearch-sec">
      <input type="text" name="" placeholder="Search retailers">
      <button type="button"><img src="<?php echo get_home_url(); ?>/../public/frontend/images/icons/search.svg"></button>
    </div>

  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#main-navbarToggler" aria-controls="main-navbarToggler" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"><i class="la la-navicon"></i></span>
  </button>



  <div class="collapse navbar-collapse justify-content-md-end" id="main-navbarToggler">

    <ul class="navbar-nav right-navbar-nav align-items-md-center"">

      

       
      <li class="nav-item">
        <a class="nav-link" href="<?php echo get_home_url('../login'); ?>" >Login</a>
      </li><!--end nav-item-->
      <li class="nav-item">
        <a class="btn btn-sign" href="" data-hover="CHA-CHING" data-active="CHA-CHING"><span>Sign Up</span></a>
      </li><!--end nav-item-->
     
    </ul>

  </div>
</nav>

<div class="navbar navbar-expand-lg second-navbar">

<ul class="navbar-nav second-left-navbar-nav">


<li class="nav-item"><a  class="nav-link" href="<?php echo SITEURL; ?>/all-stores">All Stores</a></li>

<li class="nav-item"><a class="nav-link" href="<?php echo SITEURL; ?>/cms/how-it-works">How it Works</a></li><!--end nav-item-->

</ul>

<div class="second-navbar-right">
<div class="sarver-checkout-text">
  <span class="icon"><img src="<?php echo get_home_url(); ?>/../public/frontend/images/icons/crome-icon.png"></span>
  <span class="text">Get Checkout Saver</span>
</div>
</div>

</div>

</header>




<body <?php body_class(); ?>>
<div id="page" class="site">
	<a class="skip-link screen-reader-text" href="#content"><?php _e( 'Skip to content', 'twentyseventeen' ); ?></a>

	<div class="site-content-contain">
		<div id="content" class="site-content">
