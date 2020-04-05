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
                      <img src="{{url()}}/uploads/vendor_image/{{$vendor_details['image']}}" alt="">
                    </div>
                    <div class="top-cashback">
                      <h6>One moment please</h6>
                      <p>Congratulations Test,you're on your way to</p>
                      <h3>{{$vendor_details['percentage']}}% CASH BACK</h3>
                      <p class="small-text">Shopping Trip #12934534565 opened</p>
                      
                    </div>
                    <div class="cashback-bottom">
                      <h4>Nothing for you to do. It's that simple!</h4>
                      <p>Cashback will be automatically added to your account tomorrow</p>
                      <?php
                        if($vendor_details['vendor_url'] != ''){

                          if($vendor_details['api'] == 'CJ'){
                            $url = $vendor_details['vendor_url'].'?SID='.Session::get('user_id');
                          }else{
                            $url = $vendor_details['vendor_url'].'?u1='.Session::get('user_id');
                          }

                        }else{
                          $url = '';
                        }
                      ?>
                      <a onclick="redirect_function('{{$url}}')" href="javascript:void(0);" class="btn btn-primary btn-block">Activate Now</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

<?php }else{ 
header("Location: ".url("/login")); /* Redirect browser */
exit();
}
?>
    <!-- maincontent -->
  <script type="text/javascript">
    function redirect_function(return_url) {
      
      var token = '{{csrf_token()}}';
      $.ajax({
        type  : 'POST',
        url : '<?php echo url(); ?>/vendor-id/forgot/session',
        data  : {_token:token},
        async : false,
        success : function(response){
            if (response==1 && return_url!='') {
              window.location.href = return_url;
            }
          }
        });
    }
  </script>
@stop