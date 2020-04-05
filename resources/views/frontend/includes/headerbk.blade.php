<h1>header</h1>
 <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
   

    <title><?php echo isset($title)?$title:'';?></title>
    <meta name="title" content="<?php echo isset($meta_title)?$meta_title:'';?>">
    <meta name="description" content="<?php echo isset($meta_description_content)?$meta_description_content:'';?>">
     <meta name="keywords" content="<?php echo isset($keywords)?$keywords:'';?>">
    <meta name="author" content="Cashback :: Cashback">

    <meta property="fb:app_id" content="152168048896494" />
    <meta property="og:title" content="<?php echo (isset($title))?$title:'title';?>" /> 
    <meta property="og:type" content="article" /> 
    <meta property='og:site_name' content='<?php echo url(); ?>' />
    <meta property="og:description" content="<?php echo (isset($og_description))?$og_description:'description'; ?>" />
    <meta property="og:url" content="<?php echo (isset($og_url))?$og_url:'cashback Saver'; ?>" />
    <meta property="og:image" content="<img src='http://dev.uiplonline.com/cashback-justin/public/frontend/images/fbshare.jpeg'/>" />
  

 
  
    <link href="<?php echo url(); ?>/public/frontend/css/font-awesome.min.css" rel="stylesheet">
   
    <link href="<?php echo url(); ?>/public/frontend/css/bootstrap.min.css" rel="stylesheet">
    
    <link href="<?php echo url(); ?>/public/frontend/css/swiper.min.css" rel="stylesheet">
    <link href="<?php echo url(); ?>/public/frontend/css/jquery-ui.css" rel="stylesheet">

    <link href="<?php echo url(); ?>/public/frontend/css/glasscase.min.css" rel="stylesheet">
    
    <link href="<?php echo url(); ?>/public/frontend/css/mainstyle.css?2" rel="stylesheet">
    <link href="<?php echo url(); ?>/public/frontend/css/responsive.css" rel="stylesheet">

    
    

	
    <script>var site_url = "<?php echo url(); ?>";</script>
    <script src="http://code.jquery.com/jquery-1.9.1.js"></script>
    <script src="http://code.jquery.com/ui/1.11.0/jquery-ui.js"></script>
    
    <script src="<?php echo url(); ?>/public/frontend/js/jquery.validate-1.9.1.min.js"></script>
    <script src="<?php echo url(); ?>/public/frontend/js/additional-methods-1.10.0.js"></script>
    <script src="<?php echo url(); ?>/public/frontend/scripts/validation.js"></script>
    



<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.3&appId=152168048896494";

  
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>

    
    
<link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.3/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zug+QiDoJOrZ5t4lssLdxGhVrurbmBWopoEl+M6BdEfwnCJZtKxi1KgxUyJq13dy" crossorigin="anonymous">

<a href="{{ url('/all-stores') }}" class="btn btn-primary">All stores</a> 
<link type="text/css" href="http://localhost/cashback-justin/public/backend/css/jquery-ui.css" rel="stylesheet">
<?php if(Session::has('user_id')){ ?>
 
<a href="{{ url('user/my-profile') }}" class="btn btn-primary">My profile</a> 
<a href="{{ url('user/logout') }}" class="btn btn-primary">Logout</a>
<?php }?>