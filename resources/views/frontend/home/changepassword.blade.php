@extends('layout/frontend_template')

@section('content')

 <div class="comn-main-wrap-inr">
              
              @if(Session::has('failure_message'))
                <p class="alert {{ Session::get('alert-class', 'alert-danger') }}">{{ Session::get('failure_message') }}</p>
              @endif
              
              @if(Session::has('success_message'))
                <p class="alert {{ Session::get('alert-class', 'alert-success') }}">{{ Session::get('success_message') }}</p>
              @endif


<div class="custom-card">
      <div class="custom-card-body">

          <div class="custom-card-heading">
           <h2>Change password</h2>
           </div>

<div class="comn-form-content chngPass-form-content">
              <form name="frm_reset_pasword" id="frm_reset_pasword" method="POST" action="{{ url('user/change-password') }}"> 

              <input type="hidden" name="_token" value="{{csrf_token()}}"/> 

             <div class="form-row">
                  <div class="form-group col-md-4">
                    <label class="form-control-label">Old Password<sup class="star">*</sup></label>
                     <input type="password" class="form-control" placeholder="Enter Your Old Password" name="oldpassword" id="oldpassword" required="required">
                  </div>

                  <div class="form-group col-md-4">
                    <label class="form-control-label">New Password<sup class="star">*</sup></label>
                     <input type="password" class="form-control" placeholder="Enter Your New Password" name="password" id="password" required="required">
                  </div>

                   <div class="form-group col-md-4">
                    <label class="form-control-label">Confirm Password<sup class="star">*</sup></label>
                    <input type="password" class="form-control" placeholder="Enter Your Confirm Password" name="retype_password" id="retype_password" required="required">
                  </div>
              </div>    
                          <div class="btn-holder text-right">  
                                        <button type="submit" class="btn btn-solid-blue">Save</button>
                                </div>

            <!--       <div class="submitbtn-group">
                      {!! Form::submit('Save',array('class'=>'btn btn-primary pull-left register-btn','name'=>'action','id'=>'add_user','value'=>'save')) !!}
                  </div>  --> 
           </form>

</div><!--end comn-form-content-->

         </div><!--end custom-card-body-->
</div><!--end custom-card-->


</div>              
@stop