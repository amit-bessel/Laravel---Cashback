@extends('../layout/frontend_template')
@section('content')
<?php
if(!empty($_GET['utid'])){
  $utid=$_GET['utid'];
}
else
{
  $utid='';
}

?>
  <?php //echo $faq_class;  exit; ?>

@if(Session::has('failure_message'))
<p class="alert {{ Session::get('alert-class', 'alert-danger') }}">{{ Session::get('failure_message') }}</p>
@endif

@if(Session::has('success_message'))
<p class="alert {{ Session::get('alert-class', 'alert-success') }}">{{ Session::get('success_message') }}</p>
@endif
@if(Session::has('success'))
        <div class="alert alert-success">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <strong>{!! Session::get('success') !!}</strong>
        </div>
 @endif
 <style type="text/css">
     
     .btn-danger{ float: left; margin-left:5px;  }
     table {
    table-layout: fixed;
    word-wrap: break-word;
}
 </style>
 <!--<a href="{!!url('admin/faqcategory/create')!!}" class="btn btn-success pull-right">Create Content</a>-->

 
   
    <div class="comn-main-wrap-inr">
        <div class="module">
         <div class="main-heading">
           <h2>Buy Gift Card</h2>
        </div>
<div class="row">

            <?php

            //echo "<pre>";
            //print_r($usergiftcard);exit();

                foreach ($usergiftcard as $each_catalog){
                    ?>

<div class="col-lg-3 col-md-4 col-sm-2">
  <a href="<?php echo url();?>/brand/details/<?php echo $each_catalog->id;?>">
  <div class="brand-img-block">  
    <div class="brand-img-holder d-flex align-items-center justify-content-center">
    <img src="<?php echo $each_catalog->giftcardimages[3]->imageurl;?>" style="">                  
     </div>
  </div><!--end brand-img-block-->
  </a>
</div> 

                         <?php 

                }
            ?>

</div><!--end row-->


        </div>
      </div><!--end comn-main-wrap-inr-->
    </div>

  <script type="text/javascript">
      
$(document).on("change",".chk",function(){
if (this.checked) {
$(this).closest(".tangomailpurpose").find(".email").show();
$(this).closest(".tangomailpurpose").find(".firstname").show();
$(this).closest(".tangomailpurpose").find(".lastname").show();  
}else{
$(this).closest(".tangomailpurpose").find(".email").hide();
$(this).closest(".tangomailpurpose").find(".firstname").hide();
$(this).closest(".tangomailpurpose").find(".lastname").hide();  
}


});

  </script>
  
<script type="text/javascript">
  
  $(document).ready(function() {
    $('.site-loader').hide();
});
</script>


  

@stop
