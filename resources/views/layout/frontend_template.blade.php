@include('frontend.includes.head')

@include('frontend.includes.header')

  <?php if (Session::has('user_id')){?>
@include('frontend.includes.left')
<?php }?>

<div class="site-loader" style="display: none;">
<div class="cssload-thecube">
    <div class="cssload-cube cssload-c1"></div>
    <div class="cssload-cube cssload-c2"></div>
    <div class="cssload-cube cssload-c4"></div>
    <div class="cssload-cube cssload-c3"></div>
</div>
</div>
    <!-- maincontent -->
    <div class="comn-main-wrap clearfix <?php if (!Session::has('user_id')){?>noleft <?php }?>">
     @yield('content')
    </div>
    <!-- maincontent -->

<?php if (Session::has('user_id')){?>
@include('frontend.includes.footer')    
<?php }?>

