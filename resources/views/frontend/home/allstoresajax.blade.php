<?php 
//echo $sortdata;exit();
if($sortdata!='salecommission'){

			$i=0;
			//echo "<pre>";
			//print_r($vendordetails);
			//exit();
			if(count($vendordetails)>0){
					
					foreach ($vendordetails as $key => $value) {
						//echo "<pre>";
						//print_r($value);
						
					if(!empty($value[0])){

						$i++;
				?>

				<div class="allstore-content-block">

				<div class="heading"><h3><?php echo $key;?></h3></div>

				<ul class="d-flex justify-content-between">
				<?php foreach ($value as $key1 => $value1) {
					if($value1->advertisername!=''){

						$advertisername=$value1->advertisername;

						$advertisername = str_replace(' ', '-', $advertisername); // Replaces all spaces with hyphens.

  			    		$advertisername= preg_replace('/[^A-Za-z0-9\-]/', '', $advertisername); // Removes special chars.
				?>
				<li class="d-flex justify-content-between align-items-center"><a href="<?php echo url().'/all-stores/details/'.$value1->id.'/'.$advertisername;?>"><span class="store-name"><?php echo $value1->advertisername;?> </span> </a><span class="store-discount"><?php echo $value1->salecommission;?></span></li>
				<?php 
			        }   
			    }

			    ?>
				</ul>	

				</div><!--end allstore-content-block-->

				<?php
			      }
			      
				}
		  }
		if($i==0)
		{
			?>
			<div class="allstore-content-block">
					No record found.
			</div>

			<?php
		}

}
else{


	$i=0;
			//echo "<pre>";
			//print_r($vendordetails);
			//exit();
	
					
					
				?>

				<div class="allstore-content-block">

				<?php
				if($vendordetails->count()>0)
				{
					?>

				<ul class="d-flex justify-content-between">
				<?php 
					


						foreach ($vendordetails as $key => $value1) 
						{
						
						
								if($value1->advertisername!='')
								{

									$advertisername=$value1->advertisername;

									$advertisername = str_replace(' ', '-', $advertisername); // Replaces all spaces with hyphens.

  			    					$advertisername= preg_replace('/[^A-Za-z0-9\-]/', '', $advertisername); // Removes special chars.


									$i++;
				?>
				<li class="d-flex justify-content-between align-items-center"><a href="<?php echo url().'/all-stores/details/'.$value1->id.'/'.$advertisername;?>"><span class="store-name"><?php echo $value1->advertisername;?> </span> </a><span class="store-discount"><?php echo $value1->salecommission;?></span></li>
				<?php 
			       				}  
			   			}

			    ?>
				</ul>	

				<?php
			    }
			    else{
			    ?>
			    No record found
			    <?php
			    }?>
				</div><!--end allstore-content-block-->

				<?php
			      
			      
				
		  
		
}
  ?>


		




