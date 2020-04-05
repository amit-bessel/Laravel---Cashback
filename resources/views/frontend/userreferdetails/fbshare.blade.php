<!doctype html>
<html lang="en">
<head>
    
    <title>Checkout Saver</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">


   <!--  <meta name="title" content="<?php //echo isset($meta_title)?$meta_title:'';?>">
    <meta name="description" content="<?php //echo isset($meta_description_content)?$meta_description_content:'';?>">
     <meta name="keywords" content="<?php //echo isset($keywords)?$keywords:'';?>">
    <meta name="author" content="Checkout Saver"> -->

    <meta property="fb:app_id" content="152168048896494" />
    <meta property="og:title" content="<?php echo 'Checkout Saver';?>" /> 
    <meta property="og:type" content="Checkout Saver" /> 
    <meta property='og:site_name' content='Checkout Saver' />
    <meta property="og:description" content="<?php echo 'Register And Get Earn Money.'; ?>" />
    <meta property="og:url" content="<?php echo $link; ?>" />

    <meta property="og:image" content="<img src='http://staging.uiplonline.com/cashback-justin/public/frontend/images/fbshare.jpeg'/>" />

    <script src="<?php echo url(); ?>/public/frontend/js/jquery.min.js"></script>

    
    

    <div id="fb-root"></div>
    <script>(function(d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) return;
    js = d.createElement(s); js.id = id;
    js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.3&appId=152168048896494";


    fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));

    
        

      

</script>

</head>

<?php
if (strpos($_SERVER["HTTP_USER_AGENT"], "facebookexternalhit/") != false || strpos($_SERVER["HTTP_USER_AGENT"], "Facebot") != false) {
    
    
}
else {
    echo "<script>window.location.href='http://staging.uiplonline.com/cashback-justin/r/".$code."';</script>";
    // that is not Facebook
}

?>