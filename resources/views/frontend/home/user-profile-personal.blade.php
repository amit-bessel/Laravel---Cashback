@extends('../layout/frontend_template')
@section('content')

<script src="<?php echo url(); ?>/public/frontend/js/sweetalert.min.js"></script>
    <div class="comn-main-wrap-inr">
 
              
              @if(Session::has('failure_message'))
                  <p class="alert {{ Session::get('alert-class', 'alert-danger') }}">{{ Session::get('failure_message') }}</p>
                @endif
      
                @if(Session::has('success_message'))
                  <p class="alert {{ Session::get('alert-class', 'alert-success') }}">{{ Session::get('success_message') }}</p>
                @endif
              
                {!! Form::open(['url' => 'user/edit-profile','method'=>'POST', 'files'=>true,'class'=>'','id'=>'editprofile']) !!}

                {!! Form::hidden('hid_user_id',$is_user_exists->id,['class'=>'span8','id'=>'hid_user_id']) !!}

                <input type="hidden" name="editprofileurl" value="<?php echo url(); ?>/check-email-availability" id="editprofileurl">
                <input type="hidden" name="csrftoken" value="{{csrf_token()}}" id="csrftoken">
                <div class="custom-card">
                  <div class="custom-card-body">

               <!--        <h3 class="user-heading">personal information <a href="{{ url('user/edit-profile') }}" class="pull-right red-link"><i class="fa fa-pencil-square-o" aria-hidden="true"></i>Edit profile</a></h3>       -->
                    
                    <div class="custom-card-heading">
                      <h2>Personal Information</h2>
                    </div>

                      <div class="prflImg-content-sec">


          <div class="upload_img_wrapper">
            <div class="upload_img_div">  
              <?php if($is_user_exists->profileimage!=''){?>
              <div class="upload_img_holder imgshow" id="preview">
              <?php } else {?>
              <div class="upload_img_holder" id="preview">
                <?php } if($is_user_exists->profileimage!=''){?>
                <img id="blah" src="<?php echo url('')."/public/backend/profileimage/".$is_user_exists->profileimage; ?>">
                 <?php } ?>
              </div>

                <?php
                
                if($is_user_exists->superaffiliateuser==1){
                ?>
                <span class="ribbon-holder"><img src="<?php echo url(); ?>/public/frontend/images/ribbon-img.png"></span>
                <?php
                }
                
              
                
                ?>
              


              <input type="file" class="upload_file" name="prfl_image" id="prfl_image" />
            </div>
            <span id="img_msg" style="color:red"></span>
             <?php if($is_user_exists->profileimage!=''){?>
            <span class="profileimg_remove" style="cursor: pointer;">remove photo</span>
            <?php }?>
          </div>

 

                      <div class="comn-form-content">
                          <div class="form-row">

                                  
                                  <div class="form-group col-md-6">
                                      <label class="form-control-label">First Name</label>
                                       <input type="text" class="form-control" placeholder="eg., John" name="name" id="first_name" value="<?php echo $is_user_exists->firstname; ?>">
                                  </div>
                                  <div class="form-group col-md-6">
                                      <label class="form-control-label">Last Name</label>
                                      <input type="text" class="form-control" placeholder="eg., Doe" name="last_name" id="last_name" value="<?php echo $is_user_exists->lastname; ?>">
                                  </div>

                          </div>

                          <div class="form-group">
                              <label class="form-control-label">Email Address</label>
                              <!-- onblur="check_duplication_email('<?php echo $is_user_exists->id; ?>');" -->
                             <input type="email" class="form-control" placeholder="eg., xxx@companyname.com" name="email" id="email"  value="<?php echo $is_user_exists->email; ?>" readonly="readonly">  
                          </div>


                                  <div class="btn-holder d-flex align-items-center justify-content-between">  
                                  <a href="<?php echo url();?>/user/change-password" class="chngPass-link">Change Password</a>  
                                        <button type="submit" class="btn btn-solid-blue">Submit</button>
                                </div>

                   </div><!--end comn-form-content-->

                    </div><!--end prfl-content-sec-->
              </div><!--end custom-card-body-->
      </div><!--end custom-card-->
      {!! Form::close() !!}

                
                <div class="custom-card">
                  <div class="custom-card-body">
           
                    <div class="custom-card-heading">
                      <h2><?php if(!empty($sitesettings['info']['profilewithdrawdetailstitle'][0]->value)) { echo $sitesettings['info']['profilewithdrawdetailstitle'][0]->value; }?></h2>
                    </div>


                    <div class="paypal-input-heading">
                     <img src="<?php echo url(); ?>/public/frontend/images/paypal-img.png">
                     <p><?php if(!empty($sitesettings['info']['profilewithdrawdetailsdescription'][0]->value)) { echo $sitesettings['info']['profilewithdrawdetailsdescription'][0]->value; }?></p>
                    </div>

                    {!! Form::open(['url' => 'user/edit-paypalid','method'=>'POST', 'files'=>true,'class'=>'','id'=>'paypalsubmitform']) !!}
                      <div class="comn-form-content paypal-form-content">
                               
                               <div class="form-row align-items-end justify-content-center">

                                   <div class="col-auto paypal-input-group">
                                      <label class="form-control-label">Paypal Email</label>
                                      <input type="text" class="form-control" placeholder="Enter Your Paypal Email" name="paypalid" id="paypalid" value="<?php echo $is_user_exists->paypalid;?>">
                                  </div>


                                  <div class="col-auto">  
                                   
                                        <button type="submit" class="btn btn-solid-blue">Save</button>
                                </div>

                              </div>

                   </div><!--end comn-form-content-->
                   {!! Form::close() !!}

              </div><!--end custom-card-body-->
      </div><!--end custom-card-->
      
                <div class="custom-card">
                  <div class="custom-card-body">

                    <div class="custom-card-heading">
                      <h2>Notification Settings</h2>
                    </div>

                   <!--    email notification turn on or off -->
                    <?php
                    if($emailnotification->count()>0){?>
                  <div class="emailnotification">


                     {!! Form::open(['url' => 'user/changeemailnotification','method'=>'POST', 'files'=>true,'class'=>'','id'=>'notifyemail']) !!}

                     <div class="row">
                    
                     <?php
                     //echo "<pre>";
                     // print_r($emailnotification);
                  foreach ($emailnotification as $notifykey => $notifyvalue)
                  {
                        if(!empty($notifyvalue->emailnotifications))
                        {
                        ?>
                        
                        <div class="col-md-4 emailnotfychkdiv">
                        <div class="custom-checkbox">
                        <input type="checkbox" name="<?php echo trim($notifyvalue->emailnotifications->shortname);?>" my-attr="<?php echo $notifyvalue->emailnotifications->slug;?>" value="<?php echo $notifyvalue->status;?>"<?php if($notifyvalue->status==1){?>  checked="checked"<?php }?> class="emailnotfychk" id="<?php echo $notifyvalue->emailnotifications->slug;?>">
                        <label for="<?php echo $notifyvalue->emailnotifications->slug;?>">
                        <?php echo trim($notifyvalue->emailnotifications->shortname);?>
                        </label>
                         </div>
                         </div>



                        <input type="hidden" name="notify[]" value="<?php echo $notifyvalue->emailnotifications->slug.",".$notifyvalue->status;?>">
                        <?php
                        }
                         
                  }
                    

                    ?>
                    </div>
                    <div class="btn-holder">  
                                   
                                  <button type="submit" class="btn btn-solid-blue">Submit</button>
                                  
                     </div>
                     {!! Form::close() !!}
                   </div>

                   <?php
                 }?>

                  </div><!--end custom-card-body-->
                </div><!--end custom-card-->

  </div>
    <!-- end  comn-main-wrap-inr-->
    
<script>
    function check_duplication_email($id)
    {
      var email = $('#email').val();
      if(email!="")
      {
        $.ajax({
        type  : 'POST',
        url : '<?php echo url(); ?>/check-email-availability',
        data  : {email: email, id: $id , _token: "{{ csrf_token() }}",},
        async : false,
        success : function(response){
            if (response==1) {
              $('#email').val('');
              $('#email_msg').show();
              $('#email_msg').html('Email already exists.').css( "color", "red" );
              
            }
            else{
               $('#email_msg').hide();
               $('#email_msg').text('');
               
            }
          
          }
        });
      }
    }
  </script>

<script type="text/javascript">
  
 $(document).on("change",".emailnotfychk",function(){
  
      var slug=$(this).attr("my-attr");
      
      if($(this).is(":checked")){
          
          $(this).closest(".emailnotfychkdiv").next().val(slug+","+'1');//mail on
      }
      else{
          $(this).closest(".emailnotfychkdiv").next().val(slug+","+'0');//mail off 
      }
  

 });

  function copyToClipboard(element) {
    
    var $temp = $("<input>");
    $("body").append($temp);
    $temp.val($(element).text()).select();
    
    document.execCommand("copy");
    alert('Successfully copy link');
    $temp.remove();
  }
// prfl image upload


function readURL(input) 
{
  if (input.files && input.files[0]) 
  {
    var reader = new FileReader();
    reader.onload = function (e) {
        var data = reader.result;
        if (data.match(/^data:image\//)) 
        {
          $('#preview').show();
          $('#preview').addClass('imgshow');
          $('#preview').html('<img id="blah" src="'+data+'">');
         // $('#blah').attr('src', data);
        } 
        else 
        {
            $('#img_msg').html("Not An Image");
            $('#blah').hide();
            $('#preview').hide();
            $('#hotel_image').val('');
        }
    }
    reader.readAsDataURL(input.files[0]);
  }
}

$('.profileimg_remove').on('click',function(){

swal({
  title: "Are you sure?",
  text: "Once deleted, you will not be able to recover your profile image!",
  icon: "warning",
  buttons: true,
  dangerMode: true,
})
.then((willDelete) => {
  if (willDelete) {

        $('#preview').removeClass('imgshow');
        $('#blah').remove();
        var type='ajax';
        $.ajax({
            type  : 'POST',
            url : '<?php echo url(); ?>/user/removeprofileimage',
            data  : { type: type , _token: "{{ csrf_token() }}",},
            async : false,
            success : function(response){
                if (response==1) {
                  //alert("1");
                  $(".profileimg_remove").hide();
                  
                }
                else{
                  //alert("0"); 
                   
                }
              
              }
            });

    swal(" Your profile image has been deleted!", {
      icon: "success",
    });

  } else {
    swal("Your profile image is safe!");
  }
});




  


});

$("#prfl_image").change(function(e)
{
  var file, img;
  var _URL = window.URL || window.webkitURL;    
  if ((file = this.files[0]))
  {
    img = new Image();
    img.onload = function() 
    {    
        if ((this.width<200) || (this.height<200)) 
        {
               
            $('#img_msg').html("Image size should be min 200X200");
            $('#blah').hide();
            $('#preview').hide();
            $('#prfl_image').val('');
        }
        else
        {
          $('#img_msg').html("");
          $('#blah').show();
          $('#preview').show();
        }
    };
    img.src = _URL.createObjectURL(file);
  }
  readURL(this);
});
</script>




@stop