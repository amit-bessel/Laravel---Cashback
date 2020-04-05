<?php 

    /*if(!isset($home_class))
        $home_class = '';*/
$site_url=$_SERVER['REQUEST_URI'];
$countar=explode("/", $site_url);
$count=count($countar);
$lasturl=$countar[$count-1];
$prevurl=$countar[$count-2];
?>
<div class="comn-left-bar-wrap">

	<div class="comn-left-toggle">
		
	</div>

<div class="comn-left-bar">

	<!-- <button type="button" class="toggle-sidebar for-mob collapsed" data-toggle="collapse" data-target="#user-menu" aria-expanded="false">Toggle User Menu<span class="total-bars"><span class="bar"></span><span class="bar"></span><span class="bar"></span></span></button> -->

<div class="comn-left-menu">
	<ul>
		<li class="menu-item <?php if($prevurl=='user' && $lasturl=='my-profile'){?>active <?php }?>"><a href="<?php echo url('');?>/user/my-profile"> 
			<span class="menu-item-icon"><img src="<?php echo url(); ?>/public/frontend/images/icons/user-wht.svg"></span>
			 <span class="menu-item-text">Profile</span></a></li>

		<li class="menu-item <?php if($prevurl=='user' && $lasturl=='dashboard'){?>active <?php }?>"><a href="<?php echo url('');?>/user/dashboard">
			<span class="menu-item-icon"><img src="<?php echo url(); ?>/public/frontend/images/icons/analytics-wht.svg"></span> 
			<span class="menu-item-text">Balance</span></a></li>

			<li class="menu-item <?php if($prevurl=='user' && $lasturl=='cashbackbalance'){?>active <?php }?>"><a href="<?php echo url('');?>/user/cashbackbalance">
			<span class="menu-item-icon"><img src="<?php echo url(); ?>/public/frontend/images/icons/wallet-wht.svg"></span> 
			<span class="menu-item-text">Withdraw</span></a></li>

		<li class="menu-item"><a href="<?php echo url();?>/user/viewcreditsdebits">
			<span class="menu-item-icon"><img src="<?php echo url(); ?>/public/frontend/images/icons/currency-wht.svg"></span> 
			<span class="menu-item-text">Statement</span></a></li>


		<li class="menu-item">
			<a href="<?php echo url();?>/user/invitefriendslist">
			<span class="menu-item-icon"><img src="<?php echo url(); ?>/public/frontend/images/icons/user-wht.svg"></span> 
			<span class="menu-item-text">Referrals</span></a>
		</li>

		<li class="menu-item submenu-wrap <?php if($lasturl=='brand' || $lasturl=='viewallgiftcard'){?>active<?php }?>">
			<a href="javascript:;">
				<span class="menu-item-icon"><img src="<?php echo url(); ?>/public/frontend/images/icons/gift-card-wht.svg"></span> 
				<span class="menu-item-text">Gift Cards</span></a>
			<div class="submenu-content">
				<ul>
					<li><a href="<?php echo url();?>/brand" onclick="displayloader();">Buy Gift Card</a></li>
					<li><a href="{{ url('user/viewallgiftcard') }}">My Gift Cards</a></li>
					<li><a href="{{ url('user/sellgiftcard') }}">Sell Gift Card</a></li>
				</ul>
			</div>
		</li>

		<li class="menu-item submenu-wrap">
			<a href="javascript:;" style="pointer-events: none; opacity: 0.5">
			<span class="menu-item-icon"><img src="<?php echo url(); ?>/public/frontend/images/icons/discount-wht.svg"></span> 
			<span class="menu-item-text">Coupons</span>
		   </a>
		   <div class="submenu-content">
		   		<ul>
					<li><a href="#">Coupons</a></li>
					<li><a href="#">Coupons</a></li>
					<li><a href="#">Coupons</a></li>
				</ul>
			</div>
		</li>

		<li class="menu-item <?php if($prevurl=='ticket' && $lasturl=='view'){?>active <?php }?>"><a href="<?php echo url();?>/ticket/view">
			<span class="menu-item-icon"><img src="<?php echo url(); ?>/public/frontend/images/icons/support-wht.svg"></span> 
			<span class="menu-item-text">Support</span></a></li>


	</ul>

</div>


</div><!--comn-left-bar-->

<div class="comn-left-footer">
	<div class="comn-left-footer-social">
	 <ul class="clearfix">
      <li><a href=""><i class="fab fa-facebook-f"></i></a></li>
      <li><a href=""><i class="fab fa-twitter"></i></a></li>
      <li><a href=""><i class="fab fa-instagram"></i></a></li>
    </ul>
	</div>
	<div class="copyright-text">
		© 2018 Checkout saver
	</div>
</div>

</div>
<script type="text/javascript">
	
	function displayloader(){

		$('.site-loader').show();
	}
</script>