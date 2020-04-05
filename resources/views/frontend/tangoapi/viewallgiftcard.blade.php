
@extends('../layout/frontend_template')
@section('content')

<script src="<?php echo url(); ?>/public/frontend/js/sweetalert.min.js"></script>

<?php

if(!empty($_GET['startdate'])){

  $startdate=$_GET['startdate'];
}
else{
  $startdate='';
}
if(!empty($_GET['enddate'])){

  $enddate=$_GET['enddate'];
}
else{
  $enddate='';
}
?>

<script type="text/javascript">
  
  $(function() {
       $('.datepicker').datepicker();
    });
  $(function() {
        $( "#dob1" ).datepicker({
            dateFormat : 'yy-mm-dd',
            changeMonth : true,
            changeYear : true,
            yearRange: '-100y:c+nn',
            maxDate: '-1d'
        });
    });
</script>

<div class="comn-main-wrap-inr">
@if(Session::has('failure_message'))
<p class="alert {{ Session::get('alert-class', 'alert-danger') }}">{{ Session::get('failure_message') }}</p>
@endif

@if(Session::has('success_message'))
<p class="alert {{ Session::get('alert-class', 'alert-success') }}">{{ Session::get('success_message') }}</p>
@endif
<div class="mygiftcard-nav">
  <a href="javascript:;" class="active">Bought</a>
  <a href="javascript:;" class="disabled">Sold</a>
</div>

<div class="custom-card mygiftcard-card">


<!--   <table class="table table-striped" >
    {!! Form::open(['url' => 'user/viewallgiftcard','method'=>'GET', 'files'=>true,'class'=>'form-horizontal row-fluid','id'=>'gift_form']) !!}
    <tr>
      
      <td><div class="input-group input-append date" id="datePicker">

        <input type="text" name="startdate" value="<?php // echo $startdate;?>" id="dob1" readonly="readonly">
        <span class="input-group-addon add-on" style="padding-right: 20px;"><span class="glyphicon glyphicon-calendar"></span></span></div></td>

      <td><div class="input-group input-append date" id="datePicker1">
       
          <input type="text" name="enddate" value="<?php // echo $enddate;?>" id="dob2" readonly="readonly">
        <span class="input-group-addon add-on" style="padding-right: 20px;"><span class="glyphicon glyphicon-calendar"></span></span></div></td>
      
        <td>{!! Form::submit('Search', ['class' => 'btn btn-success mybutton']) !!}</td>
        {!! Form::close() !!}
        <td><a href="<?php //echo url('')?>/user/viewallgiftcard" class="btn btn-primary">Clear</a></td>
    </tr>
  </table> -->
               

<?php
if($giftcount>0){?>
<div class="table-responsive">
<table class="table comn-table" >
    <thead class="thead-light">
      <tr>
        
        <!-- <th>Utid</th> -->
        <!-- <th>Reference Orderid</th> -->
        <!-- <th>Currency code</th> -->
        <!-- <th>Value</th> -->
        <!-- <th>Status</th> -->
        <th>Order Id</th>
        <th>Created</th>
        <th>Retailer</th>
        <th>Total</th>
        <th></th>
        <th></th>
        
      </tr>
    </thead>
    <tbody>

      <?php
      foreach ($allgiftcarddetails as $key => $value) {
       
      
      ?>

      <tr>
        <td><?php echo $value->referenceorderid;?></td>
        <td><?php echo $value->created_at;?></td>
        <td><span class="dark-text"><?php echo $value->rewardname;?></span></td>
        <td>$<?php echo $value->total;?> </td>
        <td>
          <?php
          if($value->receiverstatus==1){?>
          <span class="mail-send"><img src="<?php echo url('');?>/public/frontend/images/icons/envelope-blue.svg"></span> 
          <?php } else {?>
          <span class="mail-pending"><img src="<?php echo url('');?>/public/frontend/images/icons/envelope-gray.svg"></span>
          <?php }?>
        </td>
        <td>
          <div class="mygiftCard-table-action">
          <a tabindex="0" class="cardInfo-popover" data-toggle="popover" data-trigger="manual" data-html="true"

          data-content="<div class='card-popover-content'>
           <div class='card-popover-content-sec d-flex align-items-center'>
           <div class='card-popover-img-sec'><img src='<?php echo $value->giftcardimage;?>'>
           <div class='blnc-text'>$<?php echo $value->total;?></div>
           </div>
           <div class='card-popover-dsc-sec'>
            <div class='info-text-1'><?php echo $value->brandname;?></div>
            <div class='info-text-2'><?php echo $value->rewardname;?></div>
            <div class='info-text-3'>Send to: <?php echo $value->receiveremail;?></div>
            <div class='info-text-4'>$<?php echo $value->total;?></div>
           </div>
           </div>
          </div>">

          <i class="fas fa-info-circle"></i>
        </a>
    
        <a href="javascript:void(0)" class="delete-icon giftcarddelete" onclick="giftcarddelete('<?php echo $value->id;?>')"><i class="far fa-trash-alt"></i></a>
      </div>
        </td>
        <!-- <td><?php //echo $value->utid;?></td> -->
        <!-- <td><?php //echo $value->referenceorderid;?></td> -->
        <!-- <td><?php //echo $value->currencycode;?></td> -->
         <!-- <td><?php //echo $value->value;?></td> -->
          <!-- <td><?php //echo $value->status;?></td> -->
          
      </tr>
      

      <?php }?>

    </tbody>
  </table>
</div>

   <div class="comn-pagi-sec">{!! $allgiftcarddetails->render() !!}</div>

<?php
} else {?>

<div class="no-data-text"><h3>No record found</h3></div>

<?php
}?>

          
      </div><!--end custom-card-->

</div><!--end comn-main-wrap-inr-->

<script type="text/javascript">
  
$(document).ready(function() {
    $('#datePicker').datepicker({
            format: 'yyyy-mm-dd',
             autoclose: true
        })
        

     $('#datePicker1').datepicker({
            format: 'yyyy-mm-dd',
             autoclose: true
        })
        
});




</script>

<script type="text/javascript">
  
  function giftcarddelete(id){

              
     swal({
          title: "Are you sure?",
          text: "Once deleted, you will not be able to recover your gift card information!",
          icon: "warning",
          buttons: true,
          dangerMode: true,
      })
      .then((willDelete) => {
      if (willDelete) {
        //     swal("Poof! Your imaginary file has been deleted!", {
        //     icon: "success",
        // });
        //     location.reload();
        window.location.href="<?php echo url();?>/user/giftcarddelete/"+id;

      } else {
            swal("Your imaginary file is safe!");
      }
      });

  }

</script>


 @stop