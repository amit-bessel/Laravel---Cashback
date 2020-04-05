<?php 
$authuser =  Auth::user();
$open = "class='collapsed'";    
$open1 = "class='unstyled collapse'";
    if(!isset($home_class))
        $home_class = '';
    if(!isset($cms_class))
        $cms_class = '';
    if(!isset($sitesetting_class))
        $sitesetting_class = '';
    if(!isset($city_class))
        $city_class = '';
	if(!isset($user_class))
        $user_class = '';
   if(!isset($category_class))
        $category_class = '';
   if(!isset($topbanner_class))
        $topbanner_class = '';
    if(!isset($product_class))
        $product_class = '';
    if(!isset($brand_class))
        $brand_class = '';
    if(!isset($map_class))
        $map_class = '';
    if(!isset($map_category_class))
        $map_category_class = '';
    if(!isset($vendor_class))
        $vendor_class = '';
    if(!isset($homepage_class))
        $homepage_class = '';
    if(!isset($stores_class))
        $stores_class = '';
    if(!isset($tranction_class))
        $tranction_class = '';
    if(!isset($withdrawl_class))
        $withdrawl_class = '';
    if(!isset($withdraw2_class))
        $withdraw2_class = '';
    if(!isset($admin_card_details_class))
        $admin_card_details_class = '';
    if(!isset($faq_class))
        $faq_class = '';
    if(!isset($datafeed_class))
        $datafeed_class = '';
   // echo "<pre>";
    //print_r($Usersmodules);exit();
?>
<div class="sidebar">
    <ul class="navbar-nav ml-auto sidebar-nav">
        <li class="{!! $home_class !!}">
            <a href="<?php echo url().'/admin/home'?>">
                <i class="fa fa-tachometer menu-icon" aria-hidden="true"></i>Dashboard
            </a>
        </li>
        <?php
        if($userrole==2){

        if($Usersmodules->count()>0){

            foreach ($Usersmodules as $key => $modulevalue) {
        
            
            ?>

        
        <?php if($modulevalue->modules->slug=='sitesettings'){?>

		<!-- <li class="{!! $sitesetting_class !!}">
            <a href="<?php //echo url().'/admin/sitesetting'?>">
                <i class="menu-icon fa fa-cogs"></i>Site Settings
            </a>
        </li> -->

        

         <li class="{!! $sitesetting_class !!}">
            <a href="<?php echo url().'/admin/sitesetting'?>">
                <i class="fa fa-question menu-icon" aria-hidden="true"></i>Site Settings
            </a>
        </li>
        <?php }?>	

        <?php if($modulevalue->modules->slug=='contentlist'){?>
        <li class="{!! $cms_class !!}">
            <a href="<?php echo url().'/admin/cms'?>">
                <i class="fa fa-cube menu-icon" aria-hidden="true"></i>Page Management
            </a>
        </li>
        <?php }?>

        <?php if($modulevalue->modules->slug=='faq'){?>
        <li class="nav-item dropdown">

        <a href="javascript:;" class=" dropdown-toggle" id="nav_faq" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-question menu-icon" aria-hidden="true"></i> FAQ</a>
        <ul  class="dropdown-menu" aria-labelledby="nav_faq">
            <li class="{!! $faq_class !!}"><a href="<?php echo url().'/admin/faqcategory'?>">FAQ Categoy List </a></li>    
           
            <li class="{!! $faq_class !!}"><a href="<?php echo url().'/admin/faq'?>">FAQ List </a></li>
        </ul>
        </li>
        <?php }?>

        <?php if($modulevalue->modules->slug=='Withdrawl'){?>
        <li class="nav-item dropdown">

        <a href="javascript:void(0);" class=" dropdown-toggle" id="nav_faq" data-toggle="dropdown-menu" aria-haspopup="true" aria-expanded="false"><i class="fa fa-question menu-icon" aria-hidden="true"></i> Cashback</a>
        <ul  class="dropdown-menu" aria-labelledby="nav_faq">
            <li class="{!! $faq_class !!}"><a href="<?php echo url().'/admin/userwithdrawl/request'?>">Withdrawl Request </a></li> 
            <li class="{!! $faq_class !!}"><a href="<?php echo url().'/admin/userwithdrawl/success'?>">Withdrawl History </a></li>   
           
           
        </ul>
        </li>
        <?php }?>

        <?php if($modulevalue->modules->slug=='BannerList'){?>
       <li class="{!! $topbanner_class !!}">
            <a href="<?php echo url().'/admin/banner/banner-list'?>">
                <i class="menu-icon fa fa-picture-o"></i>Banner List
            </a>
        </li>
        <?php }?>

       <?php if($modulevalue->modules->slug=='SiteUserList'){?>
       <li class="{!! $user_class !!}">
            <a href="<?php echo url().'/admin/siteuser/list'?>">
                <i class="fa fa-users menu-icon" aria-hidden="true"></i>Site User List
            </a>
        </li>
        <?php }?>

        <?php if($modulevalue->modules->slug=='AdminUserList'){?>
		<li class="{!! $user_class !!}">
			<a href="<?php echo url().'/admin/user/list'?>">
				<i class="fa fa-user menu-icon" aria-hidden="true"></i> Admin User List
			</a>
		</li>
         <?php }?>

        <?php if($modulevalue->modules->slug=='VendorsList'){?>
        <li class="{!! $user_class !!}">
            <a href="<?php echo url().'/admin/vendor/list'?>">
                <i class="fa fa-users menu-icon"></i>Vendors List
            </a>
        </li>
        <?php }?>

        <?php if($modulevalue->modules->slug=='GiftCards'){?>
         <li class="{!! $user_class !!}">
            <a href="<?php echo url().'/admin/gift-card/list'?>">
                <i class="fa fa-gift menu-icon"></i>Gift Cards
            </a>
            
        </li>
        <?php }?>

         <?php if($modulevalue->modules->slug=='ReferralList'){?>
        <li class="{!! $user_class !!}">
            <a href="<?php echo url().'/admin/userreferdetails/list'?>">
                <i class="fa fa-list menu-icon" aria-hidden="true"></i> Referral List
            </a>
        </li>
        <?php }?>

        <?php if($modulevalue->modules->slug=='InviteUser'){?>
        <li class="{!! $user_class !!}">
            <a href="<?php echo url().'/admin/siteuser/invite'?>">
                <i class="menu-icon fa fa-cogs"></i>Admin Invite 
            </a>
        </li>
         <?php }?>


         <?php if($modulevalue->modules->slug=='InviteList'){?>
        <li class="nav-item dropdown">

        <a href="javascript:;" class=" dropdown-toggle" id="nav_faq" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="menu-icon fa fa-cogs" aria-hidden="true"></i> Invite Users</a>
        <ul  class="dropdown-menu" aria-labelledby="nav_faq">
            <li class="{!! $user_class !!}"><a href="<?php echo url().'/admin/siteuser/invite-friend-list-notregistered'?>">Pending Users </a></li>    
           <li class="{!! $user_class !!}"><a href="<?php echo url().'/admin/siteuser/invite-friend-list-registered'?>">Registered Users </a></li> 
            
        </ul>
        </li>
        <?php }?>


        <?php if($modulevalue->modules->slug=='SuperAffiliatePayout'){?>
        <li class="nav-item dropdown">

        <a href="javascript:;" class=" dropdown-toggle" id="nav_faq" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="menu-icon fa fa-cogs" aria-hidden="true"></i> Transaction</a>
        <ul  class="dropdown-menu" aria-labelledby="nav_faq">
            <li class="{!! $user_class !!}"><a href="<?php echo url().'/admin/siteuser/all-transaction'?>">All Transaction </a></li>    
           <li class="{!! $user_class !!}"><a href="<?php echo url().'/admin/super_affliate_payout'?>">Super Affiliate Payout </a></li> 
            
        </ul>
        </li>
        <?php }?>

         <?php if($modulevalue->modules->slug=='TicketList'){?>

         <li class="{!! $user_class !!}">
            <a href="{{route('TicketList')}}">
                <i class="menu-icon fa fa-ticket"></i>Ticket List
            </a>
        </li>

        <li class="{!! $user_class !!}"><a href="{{route('TicketIssueList')}}">Issue Management</a></li>    

        <li class="{!! $user_class !!}"><a href="{{route('TicketList')}}">Active Ticket List </a></li>
        <li class="{!! $user_class !!}"><a href="{{route('ClosedTicketList')}}">Closed Ticket List </a></li>
        <?php }?>

        <?php
        }
      }
    }elseif($userrole==1){?>



       <!--  <li class="nav-item dropdown">

        <a href="javascript:;" class=" dropdown-toggle" id="nav_faq" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-question menu-icon" aria-hidden="true"></i> Settings</a>
        <ul  class="dropdown-menu" aria-labelledby="nav_faq">
            <li class="{!! $sitesetting_class !!}"><a href="<?php //echo url().'/admin/sitesetting'?>">Site Settings </a></li>    
           
            <li class="{!! $sitesetting_class !!}"><a href="<?php //echo url().'/admin/homesetting'?>">Home Settings </a></li> 
        </ul>
        </li> -->
         
        <li class="{!! $sitesetting_class !!}">
            <a href="<?php echo url().'/admin/sitesetting'?>">
                <i class="fa fa-question menu-icon" aria-hidden="true"></i>Site Settings
            </a>
        </li>
       
        <li class="{!! $cms_class !!}">
            <a href="<?php echo url().'/admin/cms'?>">
                <i class="fa fa-cube menu-icon" aria-hidden="true"></i>Page Management
            </a>
        </li>
       

        
        <li class="nav-item dropdown">

        <a href="javascript:;" class="dropdown-toggle" id="nav_faq" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-question menu-icon" aria-hidden="true"></i> FAQ</a>
        <ul  class="dropdown-menu" aria-labelledby="nav_faq">
            <li class="{!! $faq_class !!}"><a href="<?php echo url().'/admin/faqcategory'?>">FAQ Categoy List </a></li>    
           
            <li class="{!! $faq_class !!}"><a href="<?php echo url().'/admin/faq'?>">FAQ List </a></li>
        </ul>
        </li>
        

       
        <li class="nav-item dropdown">

        <a href="javascript:;" class="dropdown-toggle" id="nav_faq" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-question menu-icon" aria-hidden="true"></i> Cashback</a>
        <ul  class="dropdown-menu" aria-labelledby="nav_faq">
            <li class="{!! $faq_class !!}"><a href="<?php echo url().'/admin/userwithdrawl/request'?>">Withdrawl Request </a></li> 
            <li class="{!! $faq_class !!}"><a href="<?php echo url().'/admin/userwithdrawl/success'?>">Withdrawl History </a></li>   
           
           
        </ul>
        </li>
        

        
       <li class="{!! $topbanner_class !!}">
            <a href="<?php echo url().'/admin/banner/banner-list'?>">
                <i class="menu-icon fa fa-picture-o"></i>Banner List
            </a>
        </li>
        

       
       <li class="{!! $user_class !!}">
            <a href="<?php echo url().'/admin/siteuser/list'?>">
                <i class="fa fa-users menu-icon" aria-hidden="true"></i>Site User List
            </a>
        </li>
        

       
        <li class="{!! $user_class !!}">
            <a href="<?php echo url().'/admin/user/list'?>">
                <i class="fa fa-user menu-icon" aria-hidden="true"></i> Admin User List
            </a>
        </li>
        

     
        <li class="{!! $user_class !!}">
            <a href="<?php echo url().'/admin/vendor/list'?>">
                <i class="fa fa-handshake-o menu-icon"></i>Vendors List
            </a>
        </li>

        <li class="{!! $user_class !!}">
            <a href="<?php echo url().'/admin/gift-card/list'?>">
                <i class="fa fa-gift menu-icon"></i>Gift Cards
            </a>
            
        </li>
       

         
        <li class="{!! $user_class !!}">
            <a href="<?php echo url().'/admin/userreferdetails/list'?>">
                <i class="fa fa-list menu-icon" aria-hidden="true"></i> Referral List
            </a>
        </li>
       

        
        <li class="{!! $user_class !!}">
            <a href="<?php echo url().'/admin/siteuser/invite'?>">
                <i class="menu-icon fa fa-cogs"></i>Admin Invite 
            </a>
        </li>
        
        <li class="nav-item dropdown">

        <a href="javascript:;" class=" dropdown-toggle" id="nav_faq" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="menu-icon fa fa-cogs" aria-hidden="true"></i> Invite Users</a>
        <ul  class="dropdown-menu" aria-labelledby="nav_faq">
            <li class="{!! $user_class !!}"><a href="<?php echo url().'/admin/siteuser/invite-friend-list-notregistered'?>">Pending Users </a></li>    
           
            <li class="{!! $user_class !!}"><a href="<?php echo url().'/admin/siteuser/invite-friend-list-registered'?>">Registered Users </a></li> 
        </ul>
        </li>


        <li class="nav-item dropdown">

        <a href="javascript:;" class=" dropdown-toggle" id="nav_faq" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="menu-icon fa fa-cogs" aria-hidden="true"></i> Transaction</a>
        <ul  class="dropdown-menu" aria-labelledby="nav_faq">
            <li class="{!! $user_class !!}"><a href="<?php echo url().'/admin/siteuser/all-transaction'?>">All Transaction </a></li>    
           <li class="{!! $user_class !!}"><a href="<?php echo url().'/admin/super_affliate_payout'?>">Super Affiliate Payout </a></li> 
            
        </ul>
        </li>
        

       <!--   <li class="{!! $user_class !!}">
            <a href="{{route('TicketList')}}">
                <i class="menu-icon fa fa-ticket"></i>Ticket List
            </a>
        </li>
 -->
         <li class="nav-item dropdown">

        <a href="javascript:;" class=" dropdown-toggle" id="nav_ticket" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="menu-icon fa fa-ticket" aria-hidden="true"></i> Ticket</a>
        <ul  class="dropdown-menu" aria-labelledby="nav_ticket">
            <li class="{!! $user_class !!}"><a href="{{route('TicketIssueList')}}">Issue Management</a></li>    
           
            <li class="{!! $user_class !!}"><a href="{{route('TicketList')}}">Active Ticket List </a></li>
            <li class="{!! $user_class !!}"><a href="{{route('ClosedTicketList')}}">Closed Ticket List </a></li>
        </ul>
        </li>
        <?php
    }?>
  

	</ul>	


  
    


<!--     <ul class="widget widget-menu unstyled">
       
        <li><a href="<?php //echo url();?>/auth/logout">
				<i class="menu-icon icon-signout"></i>Logout
			</a>
		</li>
			
    </ul> -->

</div>
