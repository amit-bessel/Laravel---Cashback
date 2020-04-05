@extends('../layout/frontend_template')
@section('content')
    <!-- maincontent -->
      <section class="maincontent">
          <div class="container">
            <!-- you-are-here -->
            <div class="you-are-here">
              <ul>
                <li><span>Du befinner dig här:</span></li>
                <li><a href="{{url()}}">Home</a></li>
                <li><a href="{{url()}}/stores">Stores</a></li>
                <li><a href="javascript:void();">{{$title}}</a></li>
              </ul>
            </div>
            <!-- \\you-are-here -->
            <div class="purchase-area">
            <!-- <div class="purchase-video-box">
              <div class="purchase-video-table">
                <div class="purchase-video-cell">
                    <div class="video-pop examples1">
                      <a href="#" class="slvj-link-lightbox 1" data-videoid="fCYLShRKo3w" data-videosite="youtube">
                        <img src="{{url()}}/public/frontend/images/video-icon.png" alt="">
                      </a>
                    </div>
                </div>
              </div>
            </div> -->
              <h2>Therefore, we will give you cash back on all your purchases:</h2>
              <!-- <div class="step-area">
                <ol class="cd-breadcrumb triangle">
                  <li>
                  <a href="#0">
                  <span>1</span>
                  <p>1You shop in one of our 655 <br> online shops</p>
                  </a></li>
                  <li><a href="#0">
                  <span>2</span>
                  <p>1You shop in one of our 655<br> online shops</p>
                  </a></li>
                  <li class="current">
                  <a href="#0">
                  <span>3</span>
                  <p>1You shop in one of our 655<br> online shops</p>
                  </a>
                  </li>
                </ol>
              </div> -->
              <!-- <div class="row">
                <div class="col-sm-7">
                  <ul class="step-bottom-left">
                    <li>Cash back on all online purchases</li>
                    <li>to industry best customer service</li>
                    <li>Free membership</li>
                    <li>Flexible payouts</li>
                  </ul>
                </div>
                <div class="col-sm-5">
                  <div class="step-bottom-right">
                    <a href="#">Start shopping smart now!</a>
                  </div>
                </div>
              </div> -->
            </div>
            <!-- <div class="purchrd-area-bottom">              
              <span class="get-it">
                <a href="#"><span><i class="fa fa-chevron-up" aria-hidden="true"></i>
                </span>Thank you, I get it!</a>
              </span>
            </div> -->
            <!-- <div class="you-are-here">
              <ul>
                <li><span>Du befinner dig här:</span></li>
                <li><a href="{{url()}}">Home</a></li>
                <li><a href="{{url()}}/stores">Stores</a></li>
                <li><a href="javascript:void();">{{$title}}</a></li>
              </ul>
            </div> -->
            <div class="herw-two">
              <div class="row">
                <div class="col-sm-6 herw-two-lft">                    
                    <div class="new-contentpartner text-center">
                        <div class="new-logopartner"><img src="{{url()}}/public/frontend/images/apotek-logo.png" alt=""></div>
                        <p>Get {{$vendor_details['percentage']}}% bonus on your purchase of {{$title}}</p>
                        <a href="" class="btn btn-primary btn-block">Go to the Pharmacy Heart</a>                        
                        <?php if(!Session::has('user_id')){ ?>
                        <p class="loginpartnersection"><span>Not logged in?</span>
                        <a href="{{url('login')}}">Login</a> or <a href="{{url('signup')}}">Create Account</a></p> 
                        <?php }?>                        
                    </div>
                    <!-- <div class="block-row">
                        <h2>Current offers from {{$title}}</h2>
                        <?php echo html_entity_decode($vendor_details['description']);?>
                        
                        <div class="purchase-block">
                          <ul>
                            <li>
                            <span class="left-icon"><img src="{{url()}}/public/frontend/images/trygg-e-handel.png" alt=""></span>
                            <span class="right-content">
                              Certifierad för Trygg E-handel
                            </span>
                            </li>
                            <li>
                            <span class="left-icon"><img src="{{url()}}/public/frontend/images/paypal-logo.png" alt="" class="paypal-icon"></span>
                            </li>
                          </ul>
                        </div>
                    </div> -->
                </div>
                <div class="col-sm-6 herw-two-right">
                  <div class="details-logo">
                    <h6>Pharmacies heart always offer:</h6>
                    <ul>
                      <li>Everything in the health and well-being</li>
                      <li>Free shipping</li>
                    </ul>

                    <!-- <h3>{{$vendor_details['advertiser-name']}}</h3> -->
                    <h6>Pharmacies heart always offer:</h6>                    
                    <p>Between 15-75 days</p>
                    <p>When you have completed your purchase of Pharmacies heart, your refund will be registered with us within a few hours (sometimes it may take 48 hours). You will receive an email with the exact amount of the refund as soon registered.</p>                    
                  </div>
                  <!-- <h2>Get {{$vendor_details['percentage']}}% bonus on your purchase of {{$title}}</h2> -->
                  <!-- <div class="button-area">

                  <?php if(Session::has('user_id')){ ?>
                          <a class="button" href="{{url()}}/stores/click/{{$vendor_details['id']}}" target="_blank">Go to the {{$title}}</a>

                  <?php }else{ ?>
                          <a href="javascript:void(0);" onclick="loginFunction({{$vendor_details['id']}});" class="btn btn-primary btn-block">Go to the {{$title}}</a>
                  <?php } ?>
                  </div> -->
                  <!-- <div class="login-here">
                   <?php if(!Session::has('user_id')){ ?>
                    <span>Not logged in?</span>
                    <a href="{{url('login')}}">Login</a> or <a href="{{url('signup')}}">Create Account</a>
                    <?php }?>
                  </div> -->
                  <!-- <div class="herw-two-right-block">
                    <h3>{{$title}} always offer:</h3>
                    <ul>
                      @foreach($store_names_arr as $store_name)
                      <li>{{$store_name}}</li>
                      @endforeach
                    </ul>
                  </div> -->
                  <!-- <div class="herw-two-right-block">
                    <h3>Pharmacies heart always offer:</h3>
                    <p>Bonuses are not paid for prescription products.</p>
                  </div> -->
                  <!-- <div class="herw-two-right-block">
                    <?php // echo html_entity_decode($vendor_details['short_description']);?>
                  </div> -->
                </div>

              </div>
            </div>
            <div class="purchasenewpartnerdetails text-center">
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas maximus aliquet sem sit amet finibus. Integer vel dui eu erat condimentum maximus. Suspendisse scelerisque, enim et rutrum egestas.</p>
                <div class="text-center">
                    <a href=""><img src="{{url()}}/public/frontend/images/paypaltransparent.png" alt=""></a>
                    <a href=""><img src="{{url()}}/public/frontend/images/powered-bystripe.png" alt=""></a>
                </div>
            </div>
            <!-- <div class="tel-friend">
                <div class="col-sm-4">                  
                </div>
                <div class="col-sm-4">
                  <div class="login-section footer-section">
                  <div class="login-here">
                  <?php if(!Session::has('user_id')){ ?>
                      <span>Not logged in?</span>
                      <a href="{{url('login')}}">Login</a> or <a href="{{url('signup')}}">Create Account</a>
                    <?php } ?>
                  </div>
                </div>
                </div>
                <div class="col-sm-4">
                  <div class="activation-section">
                      <a class="store-link" href="https://www.refunder.se/click/landing/store/1879" target="_blank">Gå till A Days March</a>
                  </div>
                </div>
            </div> -->
          </div>
      </section>
    <!-- maincontent -->
      
    <!-- smallewr-showcase -->
    <section class="smallewr-showcase details-showcase">
        <div class="container">
        @if(!empty($related_store))
          <h2 class="text-center">More shops in {{$vendor_details['store_name']}}</h2>
        @endif
            <div class="row">
             @foreach($related_store as $store)
             <a href="{{url()}}/stores/{{$store['id']}}">
              <div class="col-md-3 col-sm-6">
                <div class="vendor-cell">
                  <div class="vendor-top">
                    <p>Upp till {{$store['percentage']}}% återbäring</p>
                    <div class="save-area-number">{{$store['percentage']}}%</div>
                  </div>                  
                  <div class="vendor-content">
                    <h3>Ellos</h3>
                    <p class="italic">Allt från mode, heminredning och elektronik.</p>
                    
                    <div class="bottom-text">
                      <span><img src="{{url()}}/uploads/vendor_image/{{$store['image']}}" alt="" height="50px" width="115px"></span>
                    </div>
                  </div>
                </div>
                <!-- <div class="vendor-cell">
                  <div class="vendor-cell-heading">
                    <span><img src="{{url()}}/uploads/vendor_image/{{$store['image']}}" alt="" height="50px" width="115px"></span>
                  </div>
                  <div class="vendor-content">
                    <h3>{{$store['advertiser-name']}}</h3>
                    <p class="italic">
                    <?php $storename=''; ?>
                    @foreach($store['get_store_category'] as $store_name)
                      <?php $storename=$storename.$store_name['name'].','; ?>
                    @endforeach
                    {{trim($storename,',')}}
                    </p>
                    <div class="save-area">
                      <div class="save-area-number-outer">
                        <div class="save-area-number">{{$store['percentage']}}%</div>
                      </div>
                    </div>
                    <div class="bottom-text">
                      <p>Upp till {{$store['percentage']}}% återbäring</p>
                    </div>
                  </div>
                </div> -->
              </div>
              </a>
              @endforeach
              <div class="col-md-3 col-sm-6">
                <div class="vendor-cell">
                  <div class="vendor-top">
                    <p>Upp till 5% återbäring</p>
                    <div class="save-area-number">4%</div>
                  </div>
                  <!-- <div class="vendor-cell-heading">
                    
                  </div> -->
                  <div class="vendor-content">
                    <h3>Ellos</h3>
                    <p class="italic">Allt från mode, heminredning och elektronik.</p>
                    <!-- <div class="save-area">
                      <div class="save-area-number-outer">
                        
                      </div>
                    </div> -->
                    <div class="bottom-text">
                      <span><img src="{{url()}}/public/frontend/images/vendor-l2.jpg" alt=""></span>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-md-3 col-sm-6">
                <div class="vendor-cell">
                  <div class="vendor-cell-heading">
                    <span><img src="{{url()}}/public/frontend/images/vendor-l3.jpg" alt=""></span>
                  </div>
                  <div class="vendor-content">
                    <h3>Boozt.com</h3>
                    <p class="italic">Mode för män, kvinnor och barn.</p>
                    <div class="save-area">
                      <div class="save-area-number-outer">
                        <div class="save-area-number">4%</div>
                      </div>
                    </div>
                    <div class="bottom-text">
                      <p>Upp till 5% återbäring</p>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-md-3 col-sm-6">
                <div class="vendor-cell">
                  <div class="vendor-cell-heading">
                    <span><img src="{{url()}}/public/frontend/images/vendor-l4.jpg" alt=""></span>
                  </div>
                  <div class="vendor-content">
                    <h3>Boozt.com</h3>
                    <p class="italic">Mode för män, kvinnor och barn.</p>
                    <div class="save-area">
                      <div class="save-area-number-outer">
                        <div class="save-area-number">4%</div>
                      </div>
                    </div>
                    <div class="bottom-text">
                      <p>Upp till 5% återbäring</p>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-md-3 col-sm-6">
                <div class="vendor-cell">
                <div class="massage-box">
                  <a href="#" data-toggle="tooltip" title="" data-original-title="Hooray!">
                    <img src="{{url()}}/public/frontend/images/icon-img1.jpg" alt="">
                  </a>
                </div>
                  <div class="vendor-cell-heading">
                    <span><img src="{{url()}}/public/frontend/images/vendor-l5.jpg" alt=""></span>
                  </div>
                  <div class="vendor-content">
                    <h3>Bubbleroom</h3>
                    <p class="italic">Moderiktiga och prisvärda kläder.</p>
                    <div class="save-area">
                      <div class="save-area-number-outer">
                        <div class="save-area-number">8.5%</div>
                      </div>
                    </div>
                    <div class="bottom-text">
                      <p>Upp till 8.5% återbäring</p>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-md-3 col-sm-6">
                <div class="vendor-cell">
                  <div class="vendor-cell-heading">
                    <span><img src="{{url()}}/public/frontend/images/vendor-l6.jpg" alt=""></span>
                  </div>
                  <div class="vendor-content">
                    <h3>bonprix</h3>
                    <p class="italic">Prisvärt mode, skor och möbler.</p>
                    <div class="save-area">
                      <div class="save-area-number-outer">
                        <div class="save-area-number">5%</div>
                      </div>
                    </div>
                    <div class="bottom-text">
                      <p>Upp till 5% återbäring</p>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-md-3 col-sm-6">
                <div class="vendor-cell">
                  <div class="vendor-cell-heading">
                    <span><img src="{{url()}}/public/frontend/images/vendor-l7.jpg" alt=""></span>
                  </div>
                  <div class="vendor-content">
                    <h3>bAsos</h3>
                    <p class="italic">Mode med stort sortiment från välkända märken.</p>
                    <div class="save-area">
                      <div class="save-area-number-outer">
                        <div class="save-area-number">6%</div>
                      </div>
                    </div>
                    <div class="bottom-text">
                      <p>Upp till 6% återbäring</p>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-md-3 col-sm-6">
                <div class="vendor-cell">
                  <div class="vendor-cell-heading">
                    <span><img src="{{url()}}/public/frontend/images/vendor-l8.jpg" alt=""></span>
                  </div>
                  <div class="vendor-content">
                    <h3>Odd Molly </h3>
                    <p class="italic">Mode för tjejer med själ, hjärta och samvete.</p>
                    <div class="save-area">
                      <div class="save-area-number-outer">
                        <div class="save-area-number">5%</div>
                      </div>
                    </div>
                    <div class="bottom-text">
                      <p>Upp till 5% återbäring</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
        </div>
    </section>
    <!--\\ smallewr-showcase -->
    <script type="text/javascript">
    function loginFunction(vendor_id){
      var token = '{{csrf_token()}}';
      $.ajax({
        type  : 'POST',
        url : '<?php echo url(); ?>/vendor/session',
        data  : {vendor_id: vendor_id,_token:token},
        async : false,
        success : function(response){
            if (response==1) {
              window.location.href = '{{url('login')}}';
            }
          }
        });
    }
    </script>
@stop