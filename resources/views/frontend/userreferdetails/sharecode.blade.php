@extends('../layout/frontend_template')
@section('content')

<?php
//echo "<pre>";
//print_r($SiteUserReferId);exit();
 $refercode='';
 $link='';
$countt=0;
foreach ($SiteUserReferId as $key => $value) {
  $countt++;
  if($value->superaffiliate_status==1){
    $refercode=$value->referid;
    $link='';
  }
  else if($value->superaffiliate_status==0){
    //$refercode=$value->referid;
    $link=$user_details->sharelink;
    $refercode='';
  }
  else
  {
  $refercode='';
  }
}

if($countt==0)
{
  $refercode='';  
  $link=$user_details->sharelink;  
}

?>
<div class="comn-main-wrap">
<div style="margin-top: 100px; width: 50%; margin: 0 auto;">
@if(Session::has('failure_message'))
                  <p class="alert {{ Session::get('alert-class', 'alert-danger') }}">{{ Session::get('failure_message') }}</p>
                @endif
                <p id="login_error_msg"></p>
                @if(Session::has('success_message'))
                  <p class="alert {{ Session::get('alert-class', 'alert-success') }}">{{ Session::get('success_message') }}</p>
                @endif
<!--<div class="fb-share-button" data-href="http://dev.uiplonline.com/cashback-justin/" data-layout="button_count" data-size="large" data-mobile-iframe="true"><a class="fb-xfbml-parse-ignore" target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=http%3A%2F%2Fdev.uiplonline.com%2Fcashback-justin%2F&amp;src=sdkpreparse"><img src="<?php echo url('');?>/public/frontend/images/fbshare.jpeg" style="height: 50px; width: 150px;"></a></div>-->
                  <h1>Invite Friends</h1>
                    {!! Form::open(['url' => 'user/sharecodeinsert','method'=>'POST', 'files'=>true,'class'=>'row-fluid','id'=>'invitefriends']) !!}
                <?php
                $remembertoken=rand().time();
                ?>
                <input type="hidden" name="refercode" value="<?php echo  $refercode;?>">
                 <input type="hidden" name="link" value="<?php echo  $link;?>">
                 <input type="hidden" name="remembertoken" value="<?php echo $remembertoken;?>">
                  <div class="form-group">
                    <label>Email Address</label>
                    <input type="text" class="form-control" placeholder="eg., xxx@companyname.com" name="email" id="email" autocomplete="off">  
                  </div>
                  <div class="form-group">
                  <?php
                    if(isset($id) && $id != ""){
                      ?>
                      <input type="hidden" name="id" value="<?php echo $id; ?>">
                      <?php
                    }
                  ?>
                    <label>Name</label>
                     <input type="text" class="form-control" placeholder="eg., sumit roy" name="fullname" id="fullname" autocomplete="off">  
                   
                  </div>
                  <div class="submitbtn-group">
                     <input type="submit" class="btn btn-primary pull-left" value="invite" name="invite">                      
                  </div>  
               {!! Form::close() !!}


<a href="javascript:void(0);" class="fb-icon" onclick="fb_share('Developmentteam','http://staging.uiplonline.com/cashback-justin/');"><i class="fa fa-facebook"></i></a>

<a href="javascript:void(0)" onclick="messanger('http://staging.uiplonline.com/cashback-justin/r/gopi5a90326a0406a','152168048896494')">Send In Messenger</a>

<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = 'https://connect.facebook.net/en_GB/sdk.js#xfbml=1&version=v2.12&appId=152168048896494&autoLogAppEvents=1';
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>

<div class="fb-share-button" data-href="http://staging.uiplonline.com/cashback-justin/" data-layout="button_count" data-size="small" data-mobile-iframe="true"><a target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=http%3A%2F%2Fstaging.uiplonline.com%2Fcashback-justin%2F&amp;src=sdkpreparse" class="fb-xfbml-parse-ignore">Share</a></div>
<?php
if($refercode!=''){
  $twitmsg="Referal code is  ".$refercode;
}
else if($link!=''){
 $twitmsg="Register link  is  ".$link;
}
else{
  $twitmsg='';
}
?>
<a class="twitter-share-button"
  href="https://twitter.com/intent/tweet?text=<?php echo $twitmsg;?>"
  data-size="large" style="font-size: 20px;">
Tweet</a>
</div>
</div>
<script type="text/javascript">
	
	 function fb_share(team_name,url) {
  //alert(team_name+'-'+url);
    FB.ui(
    {
      method: 'share',
      name: team_name,  
      href: url
    },
    
    function(response) {
      //if (response && !response.error_code) {
      //  alert('Posting completed.');
      //} else {
      //  alert('Error while posting.');
      //}
    }
  );
}
function messanger(link,app_id){

  window.open('fb-messenger://share?link=' + encodeURIComponent(link) + '&app_id=' + encodeURIComponent(app_id));
}

</script>
@stop