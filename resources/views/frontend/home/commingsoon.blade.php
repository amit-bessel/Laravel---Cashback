@extends('../layout/template')
@section('content')


<div class="comingsoon-sec-wrap">

	<div class="comingsoon-sec">
		<div class="logo-holder">
		<a href=""><img src="<?php echo url(); ?>/public/frontend/images/logo-blue.png" alt=""></a>
         </div>

         <div class="newsletter-sec">
         	<div class="newsletter-content-holder d-flex align-items-center justify-content-center">
             
             <div class="newsletter-content">
             	<div class="newsletter-content-heading">
             		<h2><span>Our awesome site is</span> COMING SOON! </h2>
             		<p>Cras vitae metus arcu. Integer luctus lectus vitae sollicitudin dignissim metus.</p>
             	</div>

               <div class="newsletter-input-group">

               	<input type="text" placeholder="Email Address">
               	<button type="submit" class="btn btn-ylw">Subscribe</button>

               </div>

             </div>
             
         	</div>
         </div>

	</div>

</div>

@stop