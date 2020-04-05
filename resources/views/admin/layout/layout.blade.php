<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>{{ config('blog.title') }} Admin</title>

  <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css"
        rel="stylesheet">
  <link type="text/css" href="<?php echo url();?>/public/backend/css/font-awesome.min.css" rel="stylesheet">         
  <link type="text/css" href="<?php echo url();?>/public/backend/css/login_page.css" rel="stylesheet">    
  @yield('styles')
<script src="<?php echo url();?>/public/backend/scripts/jquery-1.9.1.min.js" type="text/javascript"></script>
<script src="//ajax.aspnetcdn.com/ajax/jquery.validate/1.9/jquery.validate.min.js"></script>
<script
src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
	
	
	<script src="<?php echo url();?>/resources/assets/js/jquery.validate-1.9.1.min.js"></script>
	<script src="<?php echo url();?>/public/backend/scripts/jquery-ui-1.10.1.custom.min.js" type="text/javascript"></script>
	<script src="http://ajax.googleapis.com/ajax/libs/angularjs/1.4.4/angular.min.js"></script>
	<script type="text/javascript">
	// Change JQueryUI plugin names to fix name collision with Bootstrap.
	$.widget.bridge('uitooltip', $.ui.tooltip);
	$.widget.bridge('uibutton', $.ui.button);
	</script>
	<script src="<?php echo url();?>/public/backend/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
	<!-- <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css"> -->
	<link type="text/css" href="<?php echo url();?>/public/backend/css/jquery-ui.css" rel="stylesheet">
	
	<script src="<?php echo url();?>/resources/assets/js/additional-methods-1.10.0.js"></script>
	
	
<link href="<?php echo url();?>/public/backend/css/star-rating.css" media="all" rel="stylesheet" type="text/css" />
 
<!-- optionally if you need to use a theme, then include the theme file as mentioned below -->
<link href="<?php echo url();?>/public/backend/css/theme-rating.css" media="all" rel="stylesheet" type="text/css" />
<script src="<?php echo url();?>/public/backend/js/star-rating.js" type="text/javascript"></script>

@yield('scripts')
  <!--[if lt IE 9]>
    <script src="//oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="//oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
</head>
<body>

{{-- Navigation Bar --}}
<nav class="navbar navbar-default">
  <div class="container-fluid">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed"
              data-toggle="collapse" data-target="#navbar-menu">
        <span class="sr-only">Toggle Navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="#"><img src='<?php echo url()."/public/backend/images/admin-logo.png"; ?>' alt=""></a>
    </div>
    <div class="collapse navbar-collapse" id="navbar-menu">
      @include('admin.partials.navbar')
    </div>
  </div>
</nav>

@yield('content')



</body>
</html>