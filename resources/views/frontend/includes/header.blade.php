<body>
<?php

//echo "<pre>";
//print_r($userprofileheadinfo);exit();
if(!empty($_GET['user-search-value'])){
  $usersearchvalue=$_GET['user-search-value'];
}
else{
  $usersearchvalue="";
}
?>


<header class="fixed-top main-header">
<nav class="navbar navbar-expand-lg main-navbar">
  <a class="navbar-brand" href="<?php echo url();?>"><img src="<?php echo url(); ?>/public/frontend/images/logo.png" alt=""></a>

    <div class="topSearch-sec">
      <input type="text" name="" placeholder="Search retailers" id="storesearch" value="<?php echo $usersearchvalue;?>">
      <div id="storesearchcontent">
        
      </div>
      <button type="button" id="searchheaderbutton"><img src="<?php echo url(); ?>/public/frontend/images/icons/search.svg"></button>
    </div>

  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#main-navbarToggler" aria-controls="main-navbarToggler" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"><i class="la la-navicon"></i></span>
  </button>



  <div class="collapse navbar-collapse justify-content-md-end" id="main-navbarToggler">

    <ul class="navbar-nav right-navbar-nav align-items-center " >

      <?php if(Session::has('user_id')){ ?>

      <li class="nav-item" style="display: none;">
        <a class="nav-link support-nav-link" href="#"><img src="<?php echo url(); ?>/public/frontend/images/icons/help-wht.svg"> <span class="numb">5</span></a>
      </li><!--end nav-item-->

      <li class="nav-item">
      <a class="nav-link blncShow-nav" href="<?php echo url();?>/user/cashbackbalance"><img src="<?php echo url(); ?>/public/frontend/images/icons/wallet-wht-2.svg"> <span class="text">$<?php echo number_format($userprofileheadinfo->wallettotalamount,2);?></span></a>
      </li><!--end nav-item-->

      <li class="dropdown nav-item prfl-dropdown-item">
      <a class="nav-link prfl-info-link" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" href="javascript:;" ><?php if(!empty($userprofileheadinfo->profileimage)){?><img src="<?php echo url(); ?>/public/backend/profileimage/<?php echo $userprofileheadinfo->profileimage;?>"><?php } else {?><img src="<?php echo url(); ?>/public/frontend/images/demo-prfl-img.jpg"><?php }?></a>
      
<div class="dropdown-menu prfl-dropdown-menu">
  <ul>
    <li><a href="{{ url('user/my-profile') }}">My Profile</a></li>
    <li><a href="<?php echo url();?>/user/mydashboard">Dashboard</a></li>
    <li><a href="{{ url('user/logout') }}">Logout</a></li>
  </ul>
</div>

      </li><!--end nav-item-->


      <!-- <li class="nav-item">
      <a class="btn btn-sign" href="{{ url('user/logout') }}" data-hover="CHA-CHING" data-active="CHA-CHING"><span>Logout</span></a>
      </li> --><!--end nav-item-->
      <?php } 

      else {?>  
      <li class="nav-item">
        <a class="nav-link" href="{{ url('/login') }}" >Login</a>
      </li><!--end nav-item-->
      <li class="nav-item btn-sign-holder">
        <a class="btn btn-sign" href="{{ url('/signup') }}" data-hover="CHA-CHING" data-active="CHA-CHING"><span>Sign Up</span></a>
      </li><!--end nav-item-->
      <?php }?>
    </ul>

  </div>
</nav>

<div class="navbar navbar-expand-lg second-navbar">

<ul class="navbar-nav second-left-navbar-nav">
<li class="dropdown nav-item cate-dropdown-item"><a class="nav-link" href="javascript:;" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Categories  </a>
<div class="dropdown-menu cate-dropdown-menu">
      <ul>
        <?php
          if($vendorcatscount>0)
          {

              foreach ($vendorcats as $key => $value) 
                {

                  $catid=base64_encode($value->id);
        ?>
                 <li>  <a href="<?php echo url();?>/vendorlist/<?php echo $catid;?>"><?php echo $value->name;?></a></li>
  <?php         } 
          }?>
    </ul>
</div> 
</li>

<li class="nav-item"><a  class="nav-link" href="{{ url('/all-stores') }}">All Stores</a></li>

<li class="nav-item"><a class="nav-link" href="<?php echo url();?>/cms/how-it-works">How it Works</a></li><!--end nav-item-->

</ul>

<div class="second-navbar-right">
<div class="sarver-checkout-text">
  <span class="icon"><img src="<?php echo url(); ?>/public/frontend/images/icons/crome-icon.png"></span>
  <span class="text">Get Checkout Saver</span>
</div>
</div>

</div>

</header>

<script type="text/javascript">
  $(window).load(function(){
  $(document).on("keyup","#storesearch",function(){

    var searchval=$("#storesearch").val();

    //alert(searchval);

          $.ajax({
          url: "<?php echo url();?>/storesearchautocomplete",
          type: "POST",
          data: {searchval : searchval,"_token": "{{ csrf_token() }}"},
          cache: false,

          success: function(data){
          
          //console.log(data);
         $("#storesearchcontent").show();

         $("#storesearchcontent").html(data);

            setTimeout(function(){
                if($('#storesearchcontent').length > 0) {
                ps = new PerfectScrollbar('#storesearchcontent', {
                wheelSpeed: 2,
                wheelPropagation: true,
                minScrollbarLength: 20,
                });
              }
            },1000);

          }

          });

  });

  });


  function searchstore(searchvalue){

    $("#storesearch").val(searchvalue);
    $(".sracul").hide();
    $("#storesearchcontent").hide();
  }

  $(document).on("click","#searchheaderbutton",function(){

    var searchval=$("#storesearch").val();

    window.location.href="<?php echo url();?>/all-stores?user-search-value="+searchval;
  });
</script>



