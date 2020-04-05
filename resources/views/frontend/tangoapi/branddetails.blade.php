@extends('../layout/frontend_template')
@section('content')
@if(Session::has('failure_message'))
<p class="alert {{ Session::get('alert-class', 'alert-danger') }}">{{ Session::get('failure_message') }}</p>
@endif

@if(Session::has('success_message'))
<p class="alert {{ Session::get('alert-class', 'alert-success') }}">{{ Session::get('success_message') }}</p>
@endif
<div class="comn-main-wrap-inr">
  <div class="main-heading">
    <h2>Buy Gift Card</h2>
  </div>
  <div class="custom-card giftCard-detail">
  <div class="custom-card-body">	
  <?php
    // if(count($catalog_details->brands) == 0){
    //     echo 'OOPS!! This gift card\'s price is greater than $100 , You Can not buy.'  ;
       
    // }

 // echo "<pre>";
  //print_r($giftcarddetails);
  //exit();
      $count=0;
      $cnt=0;
      
        ?>
          <div class="giftCard-detail-heading">
                <h2><?php echo $giftcarddetails[0]->giftcard->brandname;?></h2>
              <div class="giftCard-blnc-info">Balance: <span><?php echo number_format($userdetails->wallettotalamount,2);?>USD</span>
              </div>
          </div>	
          <div class="giftCard-topContent-wrap">
            <div class="giftCard-blnc-select-sec">
          		<ul>
                <?php
              foreach ($giftcarddetails as $each_catalog){
                    $count++;
                ?>
                	<li>
                		<div class="giftCard-blnc-radio">
                			<input type="radio" name="giftCard_blnc" id="giftCard_blnc_<?php echo $count;?>" <?php if($count==1){?>checked="checked"<?php }?>  onclick="getReward('<?php echo $count;?>');">
                      <label for="giftCard_blnc_<?php echo $count;?>">
                        <?php
                          if(!empty($each_catalog->facevalue)){ echo  $each_catalog->currencycode.$each_catalog->facevalue;}
                          if(!empty($each_catalog->min_value) && !empty($each_catalog->max_value)){ echo  $each_catalog->currencycode.$each_catalog->min_value ."- ".$each_catalog->currencycode.$each_catalog->max_value ;}
                        ?>
                      </label>
                		</div>
                	</li>
                <?php
              }
                ?>
          		</ul>
            </div>
            <input type="hidden" name="count" value="<?php echo $count;?>" id="count">
            <div class="giftCard-topContent-sec">
            <?php
            
           
              
            ?>
               <?php
      foreach ($giftcarddetails as $each_catalog)
      {
                $cnt++;
                ?>

              {!! Form::open(['url' => 'brand/order','method'=>'POST', 'files'=>true,'class'=>'aa','id'=>'','onsubmit'=>"return checkmail('$cnt')"]) !!}
                <input type="hidden" name="brandkey" value="<?php echo $brandkey;?>">
                <input type="hidden" name="utid" value="<?php if(!empty($each_catalog->utid)){ echo $each_catalog->utid; } ?>">
                <input type="hidden" name="faceValue" value="<?php if(!empty($each_catalog->facevalue)) { echo $each_catalog->facevalue; } ?>">
                <input type="hidden" name="currencycode" value="<?php if(!empty($each_catalog->currencycode)) { echo $each_catalog->currencycode; } ?>">
                <input type="hidden" name="minValue" value="<?php  if(!empty( $each_catalog->min_value)){ echo $each_catalog->min_value; }?>">
                <input type="hidden" name="maxValue" value="<?php if(!empty( $each_catalog->max_value)){ echo $each_catalog->max_value; }?>">
                <input type="hidden" name="brandname" value="<?php echo $each_catalog->giftcard->brandname;?>">  
                <input type="hidden" name="giftcardimage" value="<?php echo $giftcarddetails[0]->giftcardimages[5]['imageurl'];?>">
        		    <div class="row" id="giftcontent<?php echo $cnt;?>" <?php if($cnt>1){?> style="display:none;"<?php }?>>
                  <div class="col-lg-6 col-md-12 col-sm-12">
        				    <div class="giftCard-form-sec giftCard-selectValue-form">
            					<div class="value-info1"><?php echo $each_catalog->rewardname;?></div>
            					<div class="value-info2">
                        <?php
                        if(!empty($each_catalog->facevalue)){ echo  "Facevalue ".$each_catalog->currencycode.$each_catalog->facevalue;}
                        if(!empty($each_catalog->min_value) && !empty($each_catalog->max_value)){ echo  $each_catalog->currencycode.$each_catalog->min_value ."- ".$each_catalog->currencycode.$each_catalog->max_value ;}
                        ?>
                      </div>
                      <div class="comn-form-content giftCard-form-content">
                        <?php
                        if(empty($each_catalog->facevalue)){?>
                          <div class="form-group">
                            <label class="form-control-label">Your Value</label>
                            <input type="text" class="form-control" placeholder="Enter Your Value" name="amount">
                          </div>
                        <?php
                        } else {?>
                          <input type="hidden" name="amount" value="<?php echo $each_catalog->facevalue;?>" class="form-control">
                        <?php }?>
                        <div class="checkToFriend">
                          <div class="custom-checkbox">
                            <input type="checkbox" name="sendmailcheckbox" id="checkTo_Friend<?php echo $cnt;?>" value="1" class="chk">
                            <label for="checkTo_Friend<?php echo $cnt;?>">Email to a Friend</label>
                          </div>
                        </div>
                        <div class="form-group tangomaildiv" style="display: none;">
                          <label class="form-control-label">Email Address</label>
                          <input type="email" class="form-control" placeholder="Enter Your Email Address" name="email" id="email<?php echo $cnt;?>">
                          <span id="emailerror<?php echo $cnt;?>" class="error mailerror"></span>
                        </div>
						            <div class="form-row">
                          <div class="form-group col-lg-6 col-md-6 col-sm-6 tangomaildiv" style="display: none;">
                            <label class="form-control-label">First Name</label>
                            <input type="text" class="form-control" placeholder="Enter Your First Name" name="firstname" id="firstname<?php echo $cnt;?>">
                            <span id="firstnameerror<?php echo $cnt;?>" class="error mailerror"></span>
                          </div>
                          <div class="form-group col-lg-6 col-md-6 col-sm-6 tangomaildiv" style="display: none;">
                            <label class="form-control-label">Last Name</label>
                            <input type="text" class="form-control" placeholder="Enter Your Last Name" name="lastname" id="lastname<?php echo $cnt;?>">
                            <span id="lastnameerror<?php echo $cnt;?>" class="error mailerror"></span>
                          </div>
                        </div><!--end form-row-->
                        <div class="btn-holder d-flex align-items-center justify-content-lg-between">
                          <div class="sm-info-text"></div>
                            {!! Form::submit('Buy', ['class' => 'btn btn-solid-blue mybutton']) !!}
                          </div>
                      </div><!--end giftCard-form-content-->
        				    </div>
        		      </div>
            			<div class="col-lg-6 col-md-12 col-sm-12">
        				    <div class="giftCard-img-block">
                      <?php
                     // echo "<pre>";
                     // print_r($giftcarddetails[0]->giftcard->giftcardimages);

                      ?>

                      <?php if(!empty($giftcarddetails[0]->giftcard->giftcardimages[5]['imageurl'])){

                        ?>
                        <img src="<?php echo $giftcarddetails[0]->giftcard->giftcardimages[5]['imageurl'];?>">
                        <?php

                      }?>
        			       
        					   <div class="blnc-show-text"><!-- <sup>$</sup>50 -->
                     <?php
                      if(!empty($each_catalog->facevalue)){ echo  $each_catalog->currencycode.$each_catalog->facevalue;}
                        if(!empty($each_catalog->min_value) && !empty($each_catalog->max_value)){ echo  $each_catalog->currencycode.$each_catalog->min_value ."- ".$each_catalog->currencycode.$each_catalog->max_value ;}
                     ?>
                      </div>
        				    </div>
        			     </div>
        		    </div><!--end row-->
              {!! Form::close() !!}
      
    <?php
  }?>
        	</div><!--end giftCard-form-sec-->

         </div><!--end giftCard-topContent-wrap-->

            <div class="giftCard-dsc-block">
        		<div class="heading">
        		<h2>Description</h2>
        	    </div>
        		<?php echo $giftcarddetails[0]->giftcard->description;?>
        	</div>

        	<div class="giftCard-dsc-block">
        		<div class="heading">
        		<h2>Terms</h2>
        	     </div>
         <?php echo $giftcarddetails[0]->giftcard->terms;?>
        	</div>
        	 <div class="giftCard-dsc-block">
        	 	<div class="heading">
        		<h2>Disclaimer</h2>
        	    </div>
        		 <?php echo $giftcarddetails[0]->giftcard->disclaimer;?>
        	</div>




</div><!--end custom-card-body-->
</div><!--end custom-card-->

 </div>
<script type="text/javascript">
  
  function getReward(id){

    //alert(id);
    var length=$("#count").val();
    for(i=1;i<=length;i++){
      $("#giftcontent"+i).hide();
    }

    $("#giftcontent"+id).show();
  }

</script>

 <script type="text/javascript">
      
$(document).on("change",".chk",function(){
if (this.checked) {

$(".mailerror").html("");
$(this).closest(".giftCard-form-content").find(".tangomaildiv").show();

}else{
  
$(".mailerror").html("");
$(this).closest(".giftCard-form-content").find(".tangomaildiv").hide();
 
}


});

function checkmail(id){

//alert(id);

    if($("#checkTo_Friend"+id).is(":checked")){

        if($("#email"+id).val()==""){
          $("#emailerror"+id).html("Please enter your email");
          return false;
        }
        else if($("#firstname"+id).val()==""){
          $("#emailerror"+id).html("");
          $("#firstnameerror"+id).html("Please enter your first name");
          return false;
        }
        else if($("#lastname"+id).val()==""){
          $("#emailerror"+id).html("");
          $("#firstnameerror"+id).html("");
          $("#lastnameerror"+id).html("Please enter your last name");
          return false;
        }
        else{
          $("#emailerror"+id).html("");
          $("#firstnameerror"+id).html("");
          $("#lastnameerror"+id).html("");
          return true;
        }

      
      
    }
    else{
      $("#emailerror"+id).html("");
      $("#firstnameerror"+id).html("");
      $("#lastnameerror"+id).html("");
      return true;

    }
  
}



  </script>
@stop