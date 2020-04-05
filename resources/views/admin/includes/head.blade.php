<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{{ isset($title) ? $title : 'Checkout Saver' }}</title>
<!-- <link rel="shortcut icon" href="<?php echo url();?>/public/backend/images/favicon.ico" type="image/x-icon" /> -->
<meta name="_token" content="{!! csrf_token() !!}"/>
<link type="text/css" href="<?php echo url();?>/public/backend/bootstrap/css/bootstrap.min.css" rel="stylesheet">
<link rel="icon" type="image/png" href="<?php echo url();?>/public/backend/images/favicon-32x32.png" sizes="32x32" />
<link rel="icon" type="image/png" href="<?php echo url();?>/public/backend/images/favicon-16x16.png" sizes="16x16" />
<link type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.2/css/bootstrap-select.min.css" rel="stylesheet">

<link type="text/css" href="<?php echo url();?>/public/backend/css/chat-style.css" rel="stylesheet">
<link type="text/css" href="<?php echo url();?>/public/backend/css/theme.css" rel="stylesheet">
<link type="text/css" href="<?php echo url();?>/public/backend/css/mycustom.css" rel="stylesheet">
<!-- <link type="text/css" href="<?php //echo url();?>/public/backend/images/icons/css/font-awesome.css" rel="stylesheet"> -->
<link type="text/css" href="<?php echo url();?>/public/backend/css/font-awesome.min.css" rel="stylesheet">
<link type="text/css" href='http://fonts.googleapis.com/css?family=Open+Sans:400italic,600italic,400,600' rel='stylesheet'>

<link href="<?php echo url();?>/public/jQuery.filer/css/jquery.filer.css" type="text/css" rel="stylesheet" />
<link href="<?php echo url();?>/public/jQuery.filer/css/themes/jquery.filer-dragdropbox-theme.css" type="text/css" rel="stylesheet" />

<!-- Jvascript -->


<!-- <script src="<?php echo url();?>/public/backend/scripts/jquery-1.9.1.min.js" type="text/javascript"></script> -->
<script src="<?php echo url();?>/public/backend/scripts/jquery.min.js" type="text/javascript"></script>
<script src="<?php echo url();?>/resources/assets/js/jquery.validate-1.9.1.min.js"></script>
<script src="<?php echo url();?>/public/backend/scripts/jquery-ui-1.10.1.custom.min.js" type="text/javascript"></script>
<script src="<?php echo url();?>/public/backend/js/bootstrap-formhelpers-phone.js" type="text/javascript"></script>
<script src="http://ajax.googleapis.com/ajax/libs/angularjs/1.4.4/angular.min.js"></script>

<script src="<?php echo url();?>/public/backend/bootstrap/js/popper.min.js" type="text/javascript"></script>
<script src="<?php echo url();?>/public/backend/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>


<script src="<?php echo url();?>/public/jQuery.filer/js/jquery.filer.min.js"></script>

<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/4.3.0/dropzone-amd-module.js"></script> -->
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/4.3.0/min/dropzone-amd-module.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/4.3.0/min/dropzone.min.js"></script> -->

<script type="text/javascript">
// Change JQueryUI plugin names to fix name collision with Bootstrap.
$.widget.bridge('uitooltip', $.ui.tooltip);
$.widget.bridge('uibutton', $.ui.button);
</script>
<script type="text/javascript">
//implement csrf token for all AJAX request
$.ajaxPrefilter(function(options, originalOptions, xhr) { 
    var token = $('meta[name="_token"]').attr('content');
    if (token) {
        return xhr.setRequestHeader('X-CSRF-TOKEN', token);
    }
});
</script>
<!-- <script src="<?php echo url();?>/public/backend/bootstrap/js/bootstrap.min.js" type="text/javascript"></script> -->
<!-- <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css"> -->
<link type="text/css" href="<?php echo url();?>/public/backend/css/jquery-ui.css" rel="stylesheet">

<script src="<?php echo url();?>/resources/assets/js/additional-methods-1.10.0.js"></script>
<script src="<?php echo url();?>/public/backend/js/myscript.js" type="text/javascript"></script>
<script>
var base_url = "<?php echo url();?>";
</script>
 <!-- Sweetalert CSS Styles  -->
  <!--<link href="<?php echo url();?>/public/frontend/dist/sweetalert.css" rel="stylesheet">
  <script type="text/javascript" src="<?php echo url();?>/public/frontend/dist/sweetalert-dev.js"></script>-->
 <!-- SweetAlert -->

 


