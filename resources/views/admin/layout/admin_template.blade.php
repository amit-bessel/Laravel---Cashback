<!DOCTYPE html>
<html lang="en">
<head>
	@include('admin.includes.head')
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cashback Admin</title>
	
	<script>
		var site_url = "<?php echo url(); ?>";
	</script>
	<script type="text/javascript" src="{{ URL::asset('public/backend/js/include.js') }}"></script>		
		
</head>
<body>
	 @include('admin.includes.header')
        <!-- /navbar -->
        <div class="wrapper">
            <div class="wrapper-inner">

                    <div class="customleft_bar">
                         @include('admin.includes.left')
                        
                        <!--/.sidebar-->
                    </div>
                    <!--/.span3-->
                    <div class="custom_rightbar">
                    	<div class="content">
							<div class="module maincontent">
								<div class="module-head">
									<h3>{{ isset($module_head) ? $module_head : 'Cashback Admin' }}</h3>
								</div>
								<div class="module-body">
                        			@yield('content')
                            	</div>
                            </div>
                        </div>
                        <!--/.content-->
                    </div>
                    <!--/.span9-->
              
            </div>
            <!--/.container-->

    <div class="footer">
     @include('admin.includes.footer')
    </div>
        </div>
        <!--/.wrapper-->

</body>
</html>