@extends('../layout/home_template')
@section('content')

<div class="site-main-container">
  <div class="container">

<div class="site-main-heading">
<h2>Frequently asked questions</h2>
</div>

		<div class="site-main-content faq-content">



<div class="row">

<div class="col-lg-3 col-md-12 faq-left-col">

<div class="faq-left-content-wrap">
<div class="faq-left-content">
<h3>All Categories</h3>
<div class="faq-left-content-list">
<div class="nav flex-column" role="tablist" aria-orientation="vertical">

<?php 
				  $i=1;
				  //echo "<pre>";
				  //print_r($allfaq);exit();
				  if(count($allfaq)!=0)
				  {
					  foreach($allfaq as $eachfaq)
					  {
                   
                   ?>
 <a class="nav-link <?php if($i == '1') { echo 'active'; } ?>" id="faq_tab_nav<?php echo $i ;?>" data-toggle="pill" href="#faq_tab<?php echo $i ;?>"> {!! $eachfaq->name  !!}</a>

<?php
$i++;
}
}
				  else
				  {
				?>
			    <div>No Data Found.</div>
				<?php 
				}
			

?>

</div>
</div>

</div><!--end faq-left-content-->

<div class="need-help-content">
<h3>Need additional help?</h3>
<div class="btn-holder">
<a href="#" class="btn btn-ylw">Contact Us</a>
</div>
</div><!--end need-help-content-->
</div>


</div><!--end col-->


<div class="col-lg-9 col-md-12 faq-right-col">
<div class="faq-right-content">
<div class="faq-qus-content" >

              <?php 
				  $i=1;
				  //echo "<pre>";
				  //print_r($allfaq);exit();
				  if(!empty($allfaq))
				  {
					  foreach($allfaq as $eachfaq)
					  {
                   
                   ?>

             
                   
  <div class="faq-qus-holder <?php if($i == '1') { echo 'active'; } ?>" id="faq_tab<?php echo $i ;?>">

  <h2 class="faq-cat-heading">{!! $eachfaq->name  !!}</h2>

  	<?php 
  	  $q=1;
	  	if(count($eachfaq->faqs)!=0)
	  	{ 
	  		foreach ($eachfaq->faqs as $key => $value) 
	  		{
	?>

	  <div class="qus-block">
	  		<h3 class="qus-heading"><?php echo $q ?>. {!! html_entity_decode($value->question)  !!}</h3>
			{!! html_entity_decode($value->answer)  !!}
	  </div>

  	<?php 
  	$q++;
			} 

		}
		else
		{
		?>
		    <div class="qus-block">No Question Found.</div>
		<?php 
		}
  	?>

  </div><!--end tab-pane-->

              <?php 
					$i++;
					} 
				  }
				 else
				  {
				?>
			    <div>No Data Found.</div>
				<?php 
				}
				  ?>

</div>
</div>
</div>


</div><!--end row-->








		</div>


	</div><!--end container-->
</div>


 <script>
 $(document).ready(function(e) {
	$('.faq_cnt .panel-collapse').each(function(index, element) {
        var $this=$(this);
		if($this.hasClass('in'))
			$this.parent().find('.panel-heading .panel-title i').removeClass('fa fa-plus-square-o').addClass('fa fa-minus-square');
		else			
			$this.parent().find('.panel-heading .panel-title i').removeClass('fa fa-minus-square').addClass('fa fa-plus-square-o');		
    });
    $(document).on('show.bs.collapse','.faq_cnt .panel-collapse',function(){
		var $this=$(this);
		$this.parent().find('.panel-heading .panel-title i').removeClass('fa fa-plus-square-o').addClass('fa fa-minus-square');	
	});
	$(document).on('hide.bs.collapse','.faq_cnt .panel-collapse',function(){
		var $this=$(this);
		$this.parent().find('.panel-heading .panel-title i').removeClass('fa fa-minus-square').addClass('fa fa-plus-square-o');	
	});
});
 </script> 
@stop