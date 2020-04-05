@extends('../layout/frontend_template')
@section('content')
<?php if(Session::has('user_id')){ ?>
    <!-- maincontent -->
    <section class="maincontent">
        <div class="container">
            <hr class="special-divider">
            <!-- common-headerblock -->
            <div class="common-headerblock text-center">
                <h4 class="text-uppercase">Cashback Details</h4>                
            </div>
            <!--\\ common-headerblock -->
            <div class="col-sm-8 col-sm-offset-2">
                <div class="cashback-block text-center">
                    <div class="advertiser-logo">
                      <img src="{{$product_details['image_url']}}" alt="">
                    </div>
                    <div class="top-cashback">
                      <h6>One moment please</h6>
                      <p>Congratulations Test,you're on your way to</p>
                      <h3>{{$product_details['percentage']}}% CASH BACK</h3>
                      <p class="small-text">Shopping Trip #12934534565 opened</p>
                      <input type="hidden" name="buy_url" id="buy_url" value="{{$product_details['buy_url']}}">
                    </div>
                    <div class="cashback-bottom">
                      <h4>Nothing for you to do. It's that simple!</h4>
                      <p>Cashback will be automatically added to your account tomorrow</p>
                      <a onclick="redirect_function()" href="javascript:void();" class="btn btn-primary btn-block">Activate Now</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php }else{ 
  ?>
  <section class="maincontent">
        <div class="container">
            <hr class="special-divider">
            <!-- common-headerblock -->
            <div class="common-headerblock text-center">
                <h4 class="text-uppercase">Cashback Details</h4>                
            </div>
            <!--\\ common-headerblock -->
            <div class="col-sm-8 col-sm-offset-2">
                <div class="cashback-block text-center">
                    <div class="advertiser-logo">
                      <img src="{{$product_details['image_url']}}" alt="">
                    </div>
                    <div class="top-cashback">
                      <h6>One moment please</h6>
                      <p>Congratulations Guest,you're  are not eligible for cash back if you don’t sign up. </p>
                    </div>
                    <div class="cashback-bottom">
                      <input type="hidden" name="buy_url" id="buy_url" value="{{$product_details['buy_url']}}">
                      <h4>Nothing for you to do. It's that simple!</h4>
                      <!-- // <p>Cashback will be automatically added to your account tomorrow</p>-->
                      <a onclick="redirect_function()" href="javascript:void();" class="btn btn-primary btn-block">Activate Now</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
  <?php
//header("Location: ".url("/login")); /* Redirect browser */
//exit();
}
?>

    <!-- maincontent -->
  <script type="text/javascript">
    function redirect_function(url) {
      var return_url = $('#buy_url').val();
      var token = '{{csrf_token()}}';
      $.ajax({
        type  : 'POST',
        url : '<?php echo url(); ?>/product-id/forgot/session',
        data  : {_token:token},
        async : false,
        success : function(response){
            if (response==1) {
              window.location.href = return_url;
            }
          }
        });
    }
  </script>
@stop