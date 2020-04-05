<!doctype html>
<html lang="en">
<head>
    <?php
    $url=url();
    ?>
    <title>Checkout Saver</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">


    <meta name="title" content="<?php echo isset($meta_title)?$meta_title:'';?>">
    <meta name="description" content="<?php echo isset($meta_description_content)?$meta_description_content:'';?>">
     <meta name="keywords" content="<?php echo isset($keywords)?$keywords:'';?>">
    <meta name="author" content="Checkout Saver">
    <!-- 152168048896494 -->
    <meta property="fb:app_id" content="<?php echo $fbappid;?>" />
    <meta property="og:title" content="<?php echo (isset($title))?$title:'Checkout Saver';?>" /> 
    <meta property="og:type" content="Checkout Saver" /> 
    <meta property='og:site_name' content='Checkout Saver' />
    <meta property="og:description" content="<?php echo (isset($og_description))?$og_description:'Checkout Saver'; ?>" />
    <meta property="og:url" content="<?php echo (isset($og_url))?$og_url:$url; ?>" />
    <meta property="og:image" content="<img src='http://dev.uiplonline.com/cashback-justin/public/frontend/images/fbshare.jpeg'/>" />



    
    <link rel="shortcut icon" type="image/x-icon" href="<?php echo url(); ?>/public/frontend/images/favicon.ico">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="<?php echo url(); ?>/public/frontend/bootstrap/css/bootstrap.min.css">
    <!-- perfect-scrollbar CSS -->
    <link rel="stylesheet" href="<?php echo url(); ?>/public/frontend/css/perfect-scrollbar.css" type="text/css">
    <!-- swiper-slider CSS -->
    <link href="<?php echo url(); ?>/public/frontend/css/swiper.min.css" rel="stylesheet">

<link rel="stylesheet" href="<?php echo url('');?>/public/frontend/datepicker/datepicker.min.css" />
<link rel="stylesheet" href="<?php echo url('');?>/public/frontend/datepicker/datepicker3.min.css" />

    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?php echo url(); ?>/public/frontend/css/chat-style.css">
    <link rel="stylesheet" href="<?php echo url(); ?>/public/frontend/css/style.css">
    <link rel="stylesheet" href="<?php echo url(); ?>/public/frontend/css/responsive.css">
    <link rel="stylesheet" href="<?php echo url(); ?>/public/frontend/css/custom.css">

    <script src="<?php echo url(); ?>/public/frontend/js/jquery.min.js"></script>
    <script src="<?php echo url(); ?>/public/frontend/js/jquery-ui.js"></script>
    
    <script src="<?php echo url(); ?>/public/frontend/js/jquery.validate-1.9.1.min.js"></script>
    <script src="<?php echo url(); ?>/public/frontend/js/additional-methods-1.10.0.js"></script>
    <script src="<?php echo url(); ?>/public/frontend/scripts/validation.js"></script>

<script src="<?php echo url(); ?>/public/frontend/js/moment.min.js"></script>
<script src="<?php echo url(); ?>/public/frontend/js/Chart.js"></script>
<script src="<?php echo url(); ?>/public/frontend/js/utils.js"></script>

    <div id="fb-root"></div>
    <script>(function(d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) return;
    js = d.createElement(s); js.id = id;
    js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.3&appId=<?php echo $fbappid;?>";


    fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));</script>

</head>