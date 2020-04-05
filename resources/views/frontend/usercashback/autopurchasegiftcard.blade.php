@extends('../layout/frontend_template')
@section('content')
<div class="comn-main-wrap">
<div class="form-horizontal">   
	
<?php

if(!empty($SiteUser)){
  //echo "<pre>";
  //print_r($giftcarddetail);exit();


?>
<h3 style="text-align: center; color: blue">Wallet balance : <?php echo $SiteUser->wallettotalamount;?>$</h3>



<?php
}?>
<?php
$str="<a href='".url('')."/user/edit-profile'>Click this link to set paypal id</a>"; 
?>
<?php if($SiteUser->paypalid==''){?>
<h3 style="text-align: center; color: blue"><?php echo $str;?></h3>
<?php } ?>
<h3 style="text-align: center; color: blue">Withdraw cashback balance</h3>
<div style="margin-top: 100px; width: 50%; margin: 0 auto;">
	@if(Session::has('failure_message'))
                  <p class="alert {{ Session::get('alert-class', 'alert-danger') }}">{{ Session::get('failure_message') }}</p>
                @endif
                <p id="login_error_msg"></p>
                @if(Session::has('success_message'))
                  <p class="alert {{ Session::get('alert-class', 'alert-success') }}">{{ Session::get('success_message') }}</p>
                @endif
{!! Form::open(['url' => 'user/auto-purchase-giftcard','method'=>'POST', 'files'=>true,'class'=>'row-fluid','id'=>'withdrawcashbackamount']) !!}
                
                  <?php
                  $rangeflag=0;//==============used for minmax range status check==================
                  ?>

                <div class="form-group">
                    <label><input type="checkbox" name="userwithdrawoption" value="1" <?php if($SiteUser->userwithdrawoption==1){?> checked="checked"<?php } else if($SiteUser->userwithdrawoption==0){ ?> checked="checked" <?php } ?> id="auto_paypal" > Autopaypal </label><br/>
                     
                  </div>
                
                  <label><input type="checkbox" name="userwithdrawoption" value="2" <?php if($SiteUser->userwithdrawoption==2){?> checked="checked"<?php }?> id="auto_giftcard"> Autogiftcard </label><br/>
          <div id="mainautocontent" <?php if($SiteUser->userwithdrawoption==1){?> style="display: none;"<?php }?>>
                  <div class="form-group">
                    
                    <label>Auto Purchase Gift Card </label>
                    <select id="autogiftcard" name="autogiftcardid">
                      <option value="">Select Gift Card</option>
                      <?php
                      if($giftcard->count()>0){

                        foreach ($giftcard as $key => $value) {
                          
                        
                      ?>
                      <option value="<?php echo $value->id;?>" <?php if($Withdrawldetailscount>0){ if($giftcards_id==$value->id){ ?> selected="selected"<?php } }?> ><?php echo $value->brandname;?></option>
                      <?php
                      }
                    }?>
                    </select> 
                  </div>


                  <div class="form-group">
                    <label>Price </label>
                    <select id="autogiftcardprice" name="autogiftcard_details_id">
                      <option value="">Select Gift Card Price</option>
                     <?php if($Withdrawldetailscount>0){
                      foreach ($giftcarddetail as $key => $value) {
                       
                      if($value->facevalue>0){

                        $type='facevalue';
                      }
                      else{
                        $type='minmaxrange';
                      }
                      if($type=='facevalue'){
                      ?>
                      <option value="<?php echo $value->id;?>-<?php echo $value->facevalue;?>-<?php echo $value->currencycode;?>-<?php echo $type;?>"<?php if($giftcarddetails_id==$value->id){?> selected="selected"<?php }?>><?php echo $value->currencycode;?><?php echo $value->facevalue;?></option>

                      <?php
                    } 
                    else if($type=='minmaxrange'){?>
                    <option value="<?php echo $value->id;?>-<?php echo $value->min_value;?>-<?php echo $value->currencycode;?>-<?php echo $type;?>"<?php if($giftcarddetails_id==$value->id){ $rangeflag=1;?> selected="selected"<?php }?>><?php echo $value->currencycode;?><?php echo $value->min_value;?>-<?php echo $value->max_value;?></option>
                    <?php

                    }
                    ?>

                     <?php } }?>
                    </select> 
                  </div>

                  <div class="form-group"  id="yourvaluediv" <?php if($rangeflag!=1){?>style="display: none;"<?php }?>>
                    <label>Your Value </label>
                    <input type="text" name="yourvalue" value="<?php if($Withdrawldetailscount>0){ echo $Withdrawldetails[0]->amount;}?>" id="yourvalue" >
                  </div>
               </div>   
                  <div class="submitbtn-group">
                     <input type="submit" class="btn btn-primary pull-left" value="Save" name="submit">                      
                  </div>  
          
               {!! Form::close() !!}
           </div><br/><br/><br/>

 </div>
	</div>
	
<script type="text/javascript">

$(document).on("change","#autogiftcard",function(){

      var giftcardid=$("#autogiftcard").val();

      //alert(giftcardid);

      $.ajax({
      url: "<?php echo url();?>/user/auto-purchase-giftcard-facevalue",
      type: "POST",
      data: {giftcardid : giftcardid,"_token": "{{ csrf_token() }}"},
       cache: false,
       
      success: function(data){
         var obj = JSON.parse(data);
       
        var currency=obj.currency;
        var giftfacevalue=obj.facevalue;
        var giftcarddetailid=obj.giftcarddetailid;
        var minvalue=obj.minvalue;
        var maxvalue=obj.maxvalue;

        
        var len=giftfacevalue.length;
        console.log(len);
        var str='<option value="">Select Gift Card Price</option>';
        
        if(len>0){
          for(i=0;i<=(len-1);i++){

            if(giftfacevalue[i]>0){

            str=str+"<option value='"+giftcarddetailid[i]+'-'+giftfacevalue[i]+'-'+currency[i]+'-'+'facevalue'+"'>"+currency[i]+giftfacevalue[i]+"</option>";
             

            }
            else{
              
              str=str+"<option value='"+giftcarddetailid[i]+'-'+minvalue[i]+'-'+currency[i]+'-'+'minmaxrange'+"'>"+currency[i]+minvalue[i]+'-'+maxvalue[i]+"</option>";
              
            }
          }
        }
       // console.log(str);
       $("#autogiftcardprice").html(str);
      }

    });

    
});

  $(document).on("change","#autogiftcardprice",function(){


        var price=$("#autogiftcardprice").val();
       // alert(price);
        var pricear = price.split("-");
        var type=pricear[3];
        $("#yourvalue").val('');

            if(type=='facevalue')
            {
               
               $("#yourvaluediv").hide();
            }
            else if (type=='minmaxrange') 
            {

              
              $("#yourvaluediv").show();
            }

      });
  
  $(document).on("change","#auto_paypal",function(){


    var auto_paypal=$("#auto_paypal").prop("checked");
    if(auto_paypal==true){
      $("#auto_giftcard").prop("checked",false);
      $("#mainautocontent").hide();
    }
    else{
      $("#auto_giftcard").prop("checked",true);
      $("#mainautocontent").show();
    }

  });


  $(document).on("change","#auto_giftcard",function(){


    var auto_giftcard=$("#auto_giftcard").prop("checked");
    if(auto_giftcard==true){
      $("#auto_paypal").prop("checked",false);
      $("#mainautocontent").show();
    }
    else{
      $("#auto_paypal").prop("checked",true);
      $("#mainautocontent").hide();
    }

  });

</script>

@stop
    
    