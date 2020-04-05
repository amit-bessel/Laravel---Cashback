@extends('../layout/frontend_template')
@section('content')
 <script src="https://apis.google.com/js/client.js"></script>
 <script src="<?php echo url(); ?>/public/frontend/js/sweetalert.min.js"></script>
<div class="comn-main-wrap-inr">

            
<div class="main-heading">
           <h2>Referrals</h2>
        </div>
<div style="margin-top: 100px; width: 50%; margin: 0 auto;">
  @if(Session::has('failure_message'))
    <p class="alert {{ Session::get('alert-class', 'alert-danger') }}">{{ Session::get('failure_message') }}</p>
  @endif
  <p id="login_error_msg"></p>
  @if(Session::has('success_message'))
    <p class="alert {{ Session::get('alert-class', 'alert-success') }}">{{ Session::get('success_message') }}</p>
  @endif
</div>

<div class="custom-card inviteFriend-card">
  
 <div class="row">

<div class="col-xl-7 col-lg-6 inviteFriend-left-col">

<div class="inviteFriend-left-pattern-box">

<h2>Cras vestibulum scelerisque</h2>
<p>Cras sodales lacus turpis. Maecenas rutrum pellentesque neque, eu interdum </p>

<div class="img-holder">
<!-- <img src="<?php echo url(); ?>/public/frontend/images/user-img.jpg"> -->

<?php if(!empty($profile_details['profileimage'])){?><img src="<?php echo url(); ?>/public/backend/profileimage/<?php echo $profile_details['profileimage'];?>"><?php } else {?><img src="<?php echo url(); ?>/public/frontend/images/demo-prfl-img.jpg"><?php }?>

</div>

</div><!--end inviteFriend-left-pattern-box-->

<div class="inviteFriend-code-sec">

<div class="inviteFriend-editCode-sec">
<h2 class="heading">Word O' Mouth Code</h2>
<span id="code_edit_msg"></span>
<div class="inviteFriend-editCode-content">
<div class="inviteFriend-input-editCode not-edit-mode">

<input type="text" id="refer-code" value="<?php echo $SiteUserReferId['referid']; ?>">
<button type="button" class="inviteFriend-editCode-edit-btn"><img src="<?php echo url(); ?>/public/frontend/images/icons/edit.svg"></button>
<button type="button" class="inviteFriend-editCode-save-btn" id="update-refer-code"><i class="fas fa-check"></i></button>
</div>
</div>
</div><!--end inviteFriend-editCode-sec-->

<div class="inviteFriend-email-sec">
<h2 class="heading" style="text-transform: uppercase;">or</h2>
{!! Form::open(['url' => 'user/invitefriend','method'=>'POST', 'files'=>true,'class'=>'row-fluid','id'=>'invitesfriend']) !!}
<div class="form-row">
  <input type="hidden" name="sharelink" id="sharelink" value="<?php echo $profile_details['sharelink']; ?>">
  <input type="hidden" name="refercode" id="refercode" value="<?php echo $SiteUserReferId['referid']; ?>">
  <div class="col-sm">
    <input type="text" name="email" class="form-control" placeholder="Email Address" id="email">
  </div>
  <div class="col-sm-auto text-center">
    <button type="submit" class="btn btn-solid-blue">Send Invites</button>
  </div>

</div>

{!! Form::close() !!}
<div class="importContact-link-sec">
<span class="text">Import Contacts</span> 
<a class="logo-link" id="import-google" href="javascript:void(0)"><img src="<?php echo url(); ?>/public/frontend/images/icons/gmail-logo.svg"></a>
<a class="logo-link" href="#"><img src="<?php echo url(); ?>/public/frontend/images/icons/fb-logo.svg"></a> 

</div>
</div><!--end inviteFriend-email-sec-->

<div class="inviteFriend-moreway-sec">
<h2>More ways to invite your friends</h2>
<div class="inviteFriend-moreway-content">
  <div class="input-group">
    <div class="input-group-prepend">
      <div class="input-group-text" onclick="copyfunction()">Copy</div>
    </div>
    <div class="form-control" id="share-link">
      <?php echo $profile_details['sharelink']; ?>
    </div>
     <!-- <span>currepelleconubiansque</span> -->
  </div>

  <div class="btn-holder">
 <?php    
$useragent=$_SERVER['HTTP_USER_AGENT'];
$user_sharelink=$profile_details['sharelink'];

$useragent=$_SERVER['HTTP_USER_AGENT'];

if(preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4))){
?>
  <a href="javascript:void(0)" class="btn msngr-btn" onclick="messangertext('<?php echo url(); ?>/user/sharelinkinfb?code=<?php echo $SiteUserReferId['referid']; ?>','<?php echo $fbappid;?>');"><i class="fab fa-facebook-messenger"></i> Messenger</a>
  <?php
   }
  ?>

  <a href="https://twitter.com/intent/tweet?text=<?php echo $profile_details['sharelink'];?>" class="btn twtr-btn"><i class="fab fa-twitter"></i> Twitter</a>
  <a href="javascript:void(0)" class="btn fb-btn" onclick="sharelinkinfb('<?php echo $profile_details['sharelink']; ?>');"><i class="fab fa-facebook-f"></i> facebook</a>
  </div>

</div>
</div>


</div><!--end inviteFriend-code-sec-->


</div><!--end inviteFriend-left-col-->

<div class="col-xl-5 col-lg-6 inviteFriend-right-col">

<div class="inviteFriend-list-wrap clearfix">

<div class="heading d-flex align-items-center justify-content-between">
<h3>Referral Commision</h3>
<div class="blnc-info">Cash Back Earned: <span>$0</span></div>
</div><!--end heading-->

<div class="inviteFriend-list-table table-responsive">

<table class="table" border="0" cellspacing="0">
  <tbody>
    <?php 
        if(!empty($registered_invites)){
          foreach ($registered_invites as $key => $value) {
            ?>
            <tr>
            <td><?php echo $value['userreferlink1']['firstname'].' '.$value['userreferlink1']['lastname']; ?></td>
            <td><?php echo $value['userreferlink1']['email']; ?></td>
            <td align="right"><span class="blnc-text">$0</span></td>
          </tr>
          <?php 
        }
        }

        if(!empty($pending_invites)){
          foreach ($pending_invites as $key2 => $value2) {
            ?>
            <tr  class="pending-row">
            <td><?php echo $value2['email']; ?></td>
            <td><?php echo $value2['email']; ?></td>
            <td align="right"><span class="blnc-text">$0</span></td>
          </tr>
          <?php 
        }
        }
     ?>
    <!-- <tr>
      <td>Anne Stone</td>
      <td>anne123stone@checkout.com</td>
      <td align="right"><span class="blnc-text">$240</span></td>
    </tr>
    <tr class="pending-row">
      <td>Jhonath@gmail.com</td>
      <td>Jhonath@gmail.com</td>
      <td align="right"><span class="blnc-text">Pending</span></td>
    </tr>
        <tr>
      <td>Anne Stone</td>
      <td>anne123stone@checkout.com</td>
      <td align="right"><span class="blnc-text">$240</span></td>
    </tr>
        <tr>
      <td>Anne Stone</td>
      <td>anne123stone@checkout.com</td>
      <td align="right"><span class="blnc-text">$240</span></td>
    </tr>
        <tr>
      <td>Anne Stone</td>
      <td>anne123stone@checkout.com</td>
      <td align="right"><span class="blnc-text">$240</span></td>
    </tr>
        <tr class="pending-row">
      <td>Jhonath@gmail.com</td>
      <td>Jhonath@gmail.com</td>
      <td align="right"><span class="blnc-text">Pending</span></td>
    </tr>
            <tr class="pending-row">
      <td>Jhonath@gmail.com</td>
      <td>Jhonath@gmail.com</td>
      <td align="right"><span class="blnc-text">Pending</span></td>
    </tr> -->

  </tbody>
</table>


</div><!--end inviteFriend-list-table-->

</div><!--end inviteFriend-list-wrap-->

</div><!--end inviteFriend-right-col-->

  </div>


</div><!--end inviteFriend-card-->
	
	</div><!--end comn-main-wrap-inr-->

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
  

  function sharelinkinfb(sharelink){

      FB.ui({
                href: '<?php echo url(); ?>/user/sharelinkinfb?code=<?php echo $SiteUserReferId['referid']; ?>',
                method: 'share'
        });
      // FB.ui({
      // method: 'share',
      // href: '<?php //echo url(); ?>/user/sharelinkinfb?code=<?php //echo $SiteUserReferId['refercode']; ?>',
      // }, function(response){});
  }
 
  function copyfunction(){
    var $temp = $("<input>");
    $("body").append($temp);
    $temp.val($('#share-link').html()).select();
    document.execCommand("copy");
    $temp.remove();
  }

  $('#update-refer-code').click( function()
    {
      // alert($('#refer-code').val());
      $('#code_edit_msg').html('');
      var new_refer_code = $('#refer-code').val();
      var old_refer_code = '<?php echo $SiteUserReferId['refercode']; ?>';
      /*if(new_refer_code == old_refer_code){
        $('#code_edit_msg').html('Code already exists.').css( "color", "red" );
        $('.inviteFriend-input-editCode input').focus();
      }
      else{*/
        if(new_refer_code == ''){
           $('#code_edit_msg').html('Please give a code.').css( "color", "red" );
           $('.inviteFriend-input-editCode input').focus();
        }
        else{
          var user_id = '<?php echo $profile_details['id']; ?>';
          // alert(user_id);

          $.ajax({
        type  : 'POST',
        url : '<?php echo url(); ?>/change-refer-code',
        data  : {newcode: new_refer_code, oldcode: old_refer_code, id: user_id , _token: "{{ csrf_token() }}",},
        async : false,
        success : function(response){
          if (response==1) {
           /*$('#response_msg').html('Email already exists.').css( "color", "red" );*/
           $('html,body').scrollTop(0);
           location.reload();
            }
            // if(response == 0){
            //     $('#code_edit_msg').html('Code already exists.').css( "color", "red" );
            //     $('.inviteFriend-input-editCode input').focus();
            // }
          
          
          }
        });
        }
        
      // }
      
    });

</script>

<script type="text/javascript">
      
          var clientId = '<?php echo $importfriendgoogleclientid;?>';
          var apiKey = '<?php echo $importfriendgoogleapikey;?>';
          var scopes = 'https://www.googleapis.com/auth/contacts.readonly';
          $(document).on("click","#import-google", function(){

          swal({
              title: "Are you sure?",
              text: "Once you confirmed all your contacts will get an email with this invitation!",
              icon: "warning",
              buttons: true,
              dangerMode: true,
          })
          .then((willDelete) => {
          if (willDelete) {
           
            gapi.client.setApiKey(apiKey);
            window.setTimeout(authorize);

          } else {
                swal("Canceled!");
          }
          });

            

          });
          function authorize() {
            gapi.auth.authorize({client_id: clientId, scope: scopes, immediate: false}, handleAuthorization);
          }
          function handleAuthorization(authorizationResult) {
            if (authorizationResult && !authorizationResult.error) {
              $.get("https://www.google.com/m8/feeds/contacts/default/thin?alt=json&access_token=" + authorizationResult.access_token + "&max-results=500&v=3.0",
                function(response){
                  //process the response here
                  // console.log(response);
                  var email_arr = [];

                  var arr = $.map(response, function(el) { return el });
                  var arr_entry = arr[2]['entry'];

                   $.each(arr_entry, function(index, value) {
                    var email = value['gd$email'];
                     if(email != undefined){
                       $.each(email, function(index1, value1) {
                        // $('#showAdd').append('<li>'+value1['address']+'</li>');

                       email_arr.push(value1['address']);
                       });
                     }

                   });
                   // console.log(email_arr);
                    var refer_code = '<?php echo $SiteUserReferId['refercode']; ?>';
                    var user_id = '<?php echo $profile_details['id']; ?>';
                  if (typeof email_arr !== 'undefined' && email_arr.length > 0) {
                   $.ajax({
                      type  : 'POST',
                      url : '<?php echo url(); ?>/send-refer-code-bulk',
                      data  : {refercode: refer_code, id: user_id, email: email_arr , _token: "{{ csrf_token() }}",},
                      async : false,
                      success : function(response){
                        // console.log(response);
                        location.reload();

                      }
                    });
                  }






                });
            }
          }
        </script>

        <script type="text/javascript">
          function messangertext(link,app_id){
            window.open('fb-messenger://share?link=' + encodeURIComponent(link) + '&app_id=' + encodeURIComponent(app_id));
          }
          
        </script>

@stop
    
    