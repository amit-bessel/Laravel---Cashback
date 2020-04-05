<?php
//echo "<pre>";
//print_r($searchdata);exit();
if($searchdata->count()>0){

?>
<ul class="sracul">
<?php
	foreach ($searchdata as $key => $value) {
		

		?>
		<li class="sracli" onclick="searchstore('<?php echo $value->advertisername;?>');"><?php echo $value->advertisername;?></li>

		<?php
	}?>
</ul>
	<?php

}
else{
	echo "<p class='srhdnr'>No record found</p>";
}
?>