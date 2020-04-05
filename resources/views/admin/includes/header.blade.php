    <header class="fixed-top main-header">
        <nav class="navbar navbar-expand-sm">

				<!-- <a class="brand admin-logo" href="<?php //echo url();?>/admin/home"><img src="<?php //echo url();?>/public/backend/images/logo.png" alt="logo"></a> -->	

				<a class="navbar-brand" href="<?php echo url();?>/admin/home"><img src="<?php echo url();?>/public/backend/images/admin-logo-blue.png"></a>


<div class="main-header-right">
    <ul class="navbar-nav navbar-setting ml-auto">


        				<li class="nav-item dropdown">

						<a href="javascript:;" class="nav-link dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				  	 <!--  <?php if(Auth::user()->admin_icon != ''){ ?>
						<img src="<?php echo url();?>/uploads/admin_profile/{!! Auth::user()->admin_icon !!}" class="nav-avatar" />
						<?php }else{ ?>
						<img src="<?php echo url();?>/public/backend/images/icons/NoAvatar_member.png" class="nav-avatar" />
				  	<?php } ?> -->
				  	<strong>
				  		<?php
				  		$firstname=Auth::user()->firstname;
				  		$lastname=Auth::user()->lastname;
				  		echo $firstname." ".$lastname;
				  		?>
				  		 </strong>
						</a>
						<ul class="dropdown-menu dropdown-menu-right">
						   
							<li><a href="<?php echo url();?>/admin/admin-profile"> <i class="fa fa-pencil" aria-hidden="true"></i> Edit Profile</a></li>
							<li><a href="<?php echo url();?>/admin/change-password"> <i class="fa fa-key" aria-hidden="true"></i> Change Password</a></li>
							<li class="divider"></li>
							<li><a href="<?php echo url();?>/auth/logout"> <i class="fa fa-sign-out" aria-hidden="true"></i> Logout</a></li>
						</ul>
					</li>
					<li><a href="javascript:void(0);" class="makeleftcollapse pull-right"><i class="fa fa-expand"></i></a></li>

    </ul>
</div>
			   	


		
	</nav>
	<!-- /navbar-inner -->
</header>

