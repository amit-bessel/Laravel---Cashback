@extends('../layout/frontend_template')
@section('content')

    <!-- maincontent -->
    <section class="maincontent">
        <div class="container">
            <hr class="special-divider">
            <!-- common-headerblock -->
            <div class="common-headerblock text-center">
                <h4 class="text-uppercase">passbook</h4>
                <p>Nulla at velit eget nulla accumsan interdum</p>
            </div>
            <!--\\ common-headerblock -->
            <div class="row mt20">
            
              @include('frontend.includes.left')

              <div class="col-sm-8 col-md-9 right-userpanel">
              @if(Session::has('failure_message'))
                  <p class="alert {{ Session::get('alert-class', 'alert-danger') }}">{{ Session::get('failure_message') }}</p>
                @endif
      
                @if(Session::has('success_message'))
                  <p class="alert {{ Session::get('alert-class', 'alert-success') }}">{{ Session::get('success_message') }}</p>
                @endif
                  <div class="user-formpanel">
                      <div class="row">
                          <div class="col-sm-4 text-centerformob">
                              <img src="<?php echo url(); ?>/public/frontend/images/cashback-img.png" class="img-responsive" alt="">
                          </div>
                          <div class="col-sm-8">
                            <div class="custom-padleft">
                              <h3 class="user-heading">cashback balance</h3> 
                              <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer congue lacus est, id dignissim ligula faucibus at.</p> 
                              <div class="wallet">
                                  ${{$cashback_amount}}
                              </div>
                              <?php if($cashback_amount>0.60){
                               if(!empty($is_user_banck_details))
                                {
                                ?>
                                <a href="{{url()}}/user/my-bank-transfer" class="btn btn-primary">sent to bank</a>
                              <?php }else{  ?>
                                  <p>Your bank account not available please add bank account <a href="{{url()}}/user/my-account-details">Click here</a> </p>
                               <?php } }?>
                            </div>  
                          </div>
                      </div>                      
                  </div>
              </div>
            </div>
        </div>
    </section>
    @stop