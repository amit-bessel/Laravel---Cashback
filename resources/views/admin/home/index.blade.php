@extends('admin/layout/admin_template')

@section('content')

  <!-- /navbar -->
@include('admin.partials.errors')
<!-- For For Got password Success mail -->
@if(Session::has('success'))
  <div class="alert alert-success">
      <button type="button" class="close" data-dismiss="alert">×</button>
      <strong>{!! Session::get('success') !!}</strong>
  </div>
@endif

  @if(Session::has('error'))
    <div class="alert alert-error">
        <button type="button" class="close" data-dismiss="alert">×</button>
        <strong>{!! Session::get('error') !!}</strong>
    </div>
  @endif

@if(Session::has('failure_message'))
<p class="alert {{ Session::get('alert-class', 'alert-danger') }}">{{ Session::get('failure_message') }}</p>
@endif
	<div class="btn-controls">
		<div class="card-columns dashboard-card-columns">

<?php

		if($userrole==2)
		{
										$SiteUserList=0;
										$InviteList=0;
										$Withdrawl=0;
										$SuperAffiliatePayout=0;

					if($Usersmodules->count()>0)
					{
							

							foreach ($Usersmodules as $key => $modulevalue) 
							{


										//=======Set all slug flags for checking permission of subadmin=======
										//echo $modulevalue->modules->slug;

            					 		if($modulevalue->modules->slug=='SiteUserList')
            					 		{ 
            					 			$SiteUserList=1;
										}
										
										if($modulevalue->modules->slug=='InviteList'){
											$InviteList=1;
										}
										
										if($modulevalue->modules->slug=='Withdrawl'){
											$Withdrawl=1;
										}
										
										if($modulevalue->modules->slug=='SuperAffiliatePayout'){
											$SuperAffiliatePayout=1;
										}
										

							}

					}
		}

		
		?>



			

			<?php  						
		if($userrole==1 || ($userrole==2 && $SiteUserList==1)){?>

			<div class="card dashboard-card">
				<div class="card-block">
					
						<span class="iconic-repres"><!-- <i class="fa fa-users" aria-hidden="true"></i> --><?php echo count($totel_user); ?></span>
						<p class="dashboard-card-text">
						<span> </span>
							Total Registered User
						</p>
			

					<a href="<?php echo url();?>/admin/siteuser" class="view-customer">View  </a> 
						 			
				
				 
				
					
				</div>	
			</div>

		<?php }



		if($userrole==1 || ($userrole==2 && $SiteUserList==1))
		{?>


			<div class="card dashboard-card">
				<div class="card-block">
					
						<span class="iconic-repres"><!-- <i class="fa fa-user" aria-hidden="true"></i> --><?php echo count($totel_active_user); ?></span>
						<p class="dashboard-card-text">
						<span></span>
							Total Active User
						</p>
						

				<a href="<?php echo url();?>/admin/siteuser" class="view-customer">View  </a> 
						 			
				
				
				
					
				</div>	
			</div>


	<?php
	    }  			
			if($userrole==1 || ($userrole==2 && $InviteList==1))
				{?>
				<div class="card dashboard-card">
				<div class="card-block">
					
						<span class="iconic-repres"><!-- <i class="fa fa-users" aria-hidden="true"></i> --><?php echo $userreferdetails->count(); ?></span>
						<p class="dashboard-card-text">
						<span> </span>
							Total Referred Registrations
						</p>
			

						<a href="<?php echo url();?>/admin/siteuser/invite-friend-list-registered" class="view-customer">View  </a> 
						 			
				
				
				
					
				</div>	
			</div>
				<?php 
			   }





        if($userrole==1 || ($userrole==2 && $SiteUserList==1))
		{?>


			<div class="card dashboard-card">
				<div class="card-block">
					
						<span class="iconic-repres"><!-- <i class="fa fa-users" aria-hidden="true"></i> --><?php echo $independentsiteuserdetails->count(); ?></span>
						<p class="dashboard-card-text">
						<span> </span>
							Total Independent Registrations
						</p>
			

						<a href="<?php echo url();?>/admin/siteuser" class="view-customer">View  </a> 
						 			
				
				
				
					
				</div>	
			</div>
            <?php 
        }
		

									
			if($userrole==1 || ($userrole==2 && $InviteList==1))
			{?>

			<div class="card dashboard-card">

							<div class="card-block">
								
									<span class="iconic-repres"><!-- <i class="fa fa-users" aria-hidden="true"></i> -->  <?php echo $totalinvitefrienddetails->count(); ?></span>
									<p class="dashboard-card-text">
									<span></span>
										Total Email Invites Sent
									</p>
						

									<a href="<?php echo url();?>/admin/siteuser/invite-friend-list-notregistered" class="view-customer">View  </a> 
									 			
							
							 
							
								
							</div>	
			</div>
				<?php 


			} 

	if($userrole==1 || ($userrole==2 && $SiteUserList==1))
	{?>

	<div class="card dashboard-card">
				<div class="card-block">
					
						<span class="iconic-repres"><!-- <i class="fa fa-users" aria-hidden="true"></i> --></span>
						<p class="dashboard-card-text">
						<span style="font-size: 15px;"> $ <?php if(!empty($walletsum)){ echo number_format($walletsum,2); } else { echo "0" ; } ?></span><br/>
							 Total User Account Balance
						</p>
			
				
				
				<a href="<?php echo url();?>/admin/siteuser" class="view-customer">View  </a> 
				
					
				</div>	
	</div>		

	<?php 
    } if($userrole==1 || ($userrole==2 && $SuperAffiliatePayout==1))

    {?>



			<div class="card dashboard-card">
							<div class="card-block">
								
									<span class="iconic-repres"><!-- <i class="fa fa-users" aria-hidden="true"></i> --><?php echo $transactiondetails->count(); ?></span>
									<p class="dashboard-card-text">
									<span> </span>
										 Transaction Count
									</p>
						
						
							
							<a href="<?php echo url();?>/admin/siteuser/all-transaction" class="view-customer">View  </a> 
						
								
							</div>	
			</div>
	<?php 
    } if($userrole==1 || ($userrole==2 && $Withdrawl==1))
    { ?>



			<div class="card dashboard-card">
							<div class="card-block">
								
									<span class="iconic-repres"><!-- <i class="fa fa-users" aria-hidden="true"></i> --> <?php echo $withdrawdetailspending->count(); ?></span>
									<p class="dashboard-card-text">
									<span></span>
										 Withdraws Pending
									</p>
						
							
							<a href="<?php echo url();?>/admin/userwithdrawl/request" class="view-customer">View  </a> 
							
								
							</div>	
			</div>
	<?php 
    } if($userrole==1 || ($userrole==2 && $SiteUserList==1)){?>


			<div class="card dashboard-card">
							<div class="card-block">
								
									<span class="iconic-repres"><!-- <i class="fa fa-users" aria-hidden="true"></i> --><?php echo $cashbackprocessing->count(); ?></span>
									<p class="dashboard-card-text">
									<span> </span>
										 Cashback Processing
									</p>
						
							
							
							<a href="<?php echo url();?>/admin/siteuser" class="view-customer">View </a> 
							
								
							</div>	
			</div>

			<?php } if($userrole==1 || ($userrole==2 && $SuperAffiliatePayout==1) ){ ?>




			<div class="card dashboard-card">
							<div class="card-block">
								
									<span class="iconic-repres"><!-- <i class="fa fa-users" aria-hidden="true"></i> --></span>
									<p class="dashboard-card-text">
									<span style="font-size: 15px;">$ <?php if(!empty($totalsuperaffiliatepayout[0]->totalsuperaffiliatecommission)) { echo $totalsuperaffiliatepayout[0]->totalsuperaffiliatecommission; } else {  echo "0"; } ?> </span><br/>
										Total Super affiliate payout 
									</p>
					
						
							
							<a href="<?php echo url();?>/admin/super_affliate_payout" class="view-customer">View  </a> 
							
								
							</div>	
			</div>

			<?php }?>




		</div>
	</div>
			
		
                  
@stop