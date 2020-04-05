@extends('../layout/home_template')
@section('content')

<!-- <style type="text/css">
	
	.mylt{

		    background-color: red;
    		color: white;
	}
	.sortparam{
		background-color: red;
    		color: white;
	}
</style> -->

<?php
 //echo "<pre>";
//print_r($vendordetails);exit();
?>
<div class="site-main-container">

<div class="container">

<div class="site-main-heading">
<h2>All Stores</h2>
</div>

<div class="site-main-content">

<div class="row">

<div class="col-lg-3 col-md-12 allstore-left-col">
<div class="allstore-left-content">
<h3>Category</h3>
<div class="allstore-left-content-list">
<ul>
	<?php

foreach ($vendorcat as $key => $value) {
	$catid=base64_encode($value->id);
	?>
	<li> <a href="<?php echo url();?>/vendorlist/<?php echo $catid;?>"><?php echo $value->name;?></a></li>
	<?php }?>
</ul>
</div>

</div><!--end allstore-left-content-->
</div><!--end col-->

<div class="col-lg-9 col-md-12 allstore-right-col">
<div class="allstore-right-content">

<div class="popular-store-sec">

<ul class="d-flex justify-content-xl-between justify-content-lg-center justify-content-center flex-wrap">
<?php

if(!empty($popularvendordetails)){

		foreach ($popularvendordetails as $key => $value2) {
	

	?>
	<li>
		<div class="stores-content-block">
		<div class="brand-img-holder d-flex align-items-center justify-content-center">
			<a href="<?php echo url().'/all-stores/details/'.$value2->id;?>"><span class="store-name"><?php echo $value2->advertisername;?> </span> </a>
	
		</div>
		<h4><?php echo $value2->salecommission;?> discount on shopping</h4>
		</div>
	</li>
	<?php
		}

}

?>
		
		
		
</ul>

</div><!--end popular-store-sec-->

<div class="custom-card allstore-card">

	<div class="allstore-filter-sec">

<div class="allstore-letter-filter-sec">
		<a href="javascript:void(0)" class="allstoreltsr mylt" mydata="all" id="allsel">All</a>
		<a href="javascript:void(0)" class="allstoreltsr" mydata="a">A</a>
		<a href="javascript:void(0)" class="allstoreltsr" mydata="b">B</a>
		<a href="javascript:void(0)" class="allstoreltsr" mydata="c">C</a>
		<a href="javascript:void(0)" class="allstoreltsr" mydata="d">D</a>
		<a href="javascript:void(0)" class="allstoreltsr" mydata="e">E</a>
		<a href="javascript:void(0)" class="allstoreltsr" mydata="f">F</a>
		<a href="javascript:void(0)" class="allstoreltsr" mydata="g">G</a>
		<a href="javascript:void(0)" class="allstoreltsr" mydata="h">H</a>
		<a href="javascript:void(0)" class="allstoreltsr" mydata="i">I</a>
		<a href="javascript:void(0)" class="allstoreltsr" mydata="j">J</a>
		<a href="javascript:void(0)" class="allstoreltsr" mydata="k">K</a>
		<a href="javascript:void(0)" class="allstoreltsr" mydata="l">L</a>
		<a href="javascript:void(0)" class="allstoreltsr" mydata="m">M</a>
		<a href="javascript:void(0)" class="allstoreltsr" mydata="n">N</a>
		<a href="javascript:void(0)" class="allstoreltsr" mydata="o">O</a>
		<a href="javascript:void(0)" class="allstoreltsr" mydata="p">P</a>
		<a href="javascript:void(0)" class="allstoreltsr" mydata="q">Q</a>
		<a href="javascript:void(0)" class="allstoreltsr" mydata="r">R</a>
		<a href="javascript:void(0)" class="allstoreltsr" mydata="s">S</a>
		<a href="javascript:void(0)" class="allstoreltsr" mydata="t">T</a>
		<a href="javascript:void(0)" class="allstoreltsr" mydata="u">U</a>
	    <a href="javascript:void(0)" class="allstoreltsr" mydata="v">V</a>
		<a href="javascript:void(0)" class="allstoreltsr" mydata="w">W</a>
		<a href="javascript:void(0)" class="allstoreltsr" mydata="x">X</a>
		<a href="javascript:void(0)" class="allstoreltsr" mydata="y">Y</a>
		<a href="javascript:void(0)" class="allstoreltsr" mydata="z">Z</a>
</div>

<div class="allstore-sortBy-filter-sec clearfix">

<div class="allstore-sortBy-filter-block">
<div class="d-flex align-items-center">
<div class="title">Sort By:</div>

<a href="javascript:void(0)" class="sortparam sortlink" mydata="all" sortdata="atoz" id="sort1">Alphabetically</a>
<a href="javascript:void(0)" class="sortlink" mydata="all" sortdata="salecommission" id="sort2">Highest Cashback</a>
</div>
</div><!--end allstore-sortBy-filter-block-->

<div class="allstore-search-filter-block">
<input type="text" name="searchresult" value="" id="searchresult"  placeholder="Search here" class="form-control" autocomplete="off" value="">
<a href="<?php echo url('')?>/all-stores" class="btn btn-clear"> <i class="fas fa-redo-alt"></i> </a>
</div>

</div><!--end sortBy-filter-sec-->


</div><!--end allstore-filter-sec-->


<div class="allstore-content-block-wrap" id="allstore">

	<?php 
	if(count($vendordetails)>0){

			foreach ($vendordetails as $key => $value) {
				//echo "<pre>";
				//print_r($value);
			if(!empty($value[0])){


		?>

		<div class="allstore-content-block">

		<div class="heading"><h3><?php echo $key;?></h3></div>

		<ul class="d-flex justify-content-between">
		<?php foreach ($value as $key1 => $value1) {
			if($value1->advertisername!=''){
		?>
		<li><a href="<?php echo url().'/all-stores/details/'.$value1->id;?>"><span class="store-name"><?php echo $value1->advertisername;?> </span> </a><span class="store-discount"><?php echo $value1->salecommission;?></span></li>
		<?php 
	        }   
	    }

	    ?>
		</ul>	

		</div><!--end allstore-content-block-->

		<?php
	      }
		}
  }?>

		




</div><!--end allstore-content-block-wrap-->


</div><!--end allstore-card-->

</div><!--end allstore-right-content-->
</div><!--end col-->

</div>

</div><!--end site-main-content-->

</div><!--end container-->

</div><!--end site-main-container-->


<div class="clearfix">
<h3>Category</h3>
<div style="float: left;width: 20%">
<ul class="list-group" style=" font-size: 15px; font-weight: bold;" >
	<?php

foreach ($vendorcat as $key => $value) {
	$catid=base64_encode($value->id);
	?>
	<li class="list-group-item"><a href="<?php echo url();?>/vendorlist/<?php echo $catid;?>"><?php echo $value->name;?></a></li>
	<?php }?>
</ul>
</div>

<div style="width: 100%; float: left;">

<h1>Popular Stores</h1>
<?php
//echo "<pre>";
//print_r($popularvendordetails);exit();
if(!empty($popularvendordetails)){

foreach ($popularvendordetails as $key => $value2) {
	

	?>

	<div style="width: 30%; float: left; border: 2px solid #ccc; padding:5px; font-size: 20px;">
		
		<p><a href="<?php echo $value2->clickurl;?>"><?php echo $value2->advertisername;?></a></p>
		<p style="color: red"><?php echo $value2->salecommission;?></p>
	</div>
	<?php
}

}

?>




<!-- <div><a href="javascript:void(0)" class="sortparam sortlink" mydata="all" style="margin-right: 3px; font-size: 20px; " class="" sortdata="atoz" id="sort1">A-Z</a> <a href="javascript:void(0)" class="sortlink" mydata="all" style="margin-right: 3px; font-size: 20px; " class="" sortdata="salecommission" id="sort2">Highest Cashback</a></div> -->

<table class="table table-striped" >
    
    <tr>
      
      <td>

        <input type="text" name="searchresult1" value="" id="searchresult1"  placeholder="Search by vendor or category " class="form-control" autocomplete="off">
        </td>

  
      
      
        
        <td><a href="<?php echo url('')?>/all-stores" class="btn btn-primary">Clear</a></td>
    </tr>
  </table>



	<div id="allstore">
<?php
		if(!empty($vendordetails['pp'])){
//echo "<pre>";
//print_r($vendordetails);exit();

$count=1;
		foreach ($vendordetails as $key => $value1) {
			
			?>

		<div style="width: 30%; float: left; padding: 10px; "  >
			
			<a href="<?php echo $value1->clickurl;?>"><span style="font-size: 15px; word-wrap: break-word;"><?php echo $value1->advertisername;?></span></a> <span style="font-size: 15px; color: red;"><?php echo $value1->salecommission;?> </span>
		</div>
		<?php
		$count++;

	}
}?>
</div>
</div>



<script type="text/javascript">
	
//search by letter

$(document).on("click",".allstoreltsr",function(){

var sortdata=$(".sortparam").attr("sortdata");

$(".mylt").removeClass("mylt");
$(this).addClass("mylt");
var searchbyletter=$(".mylt").attr("mydata");
var type='myajax';

$("#searchresult").val('');
var searchbyword=$("#searchresult").val();

search(searchbyletter,searchbyword,type,sortdata);

});

//main search function

function search(searchbyletter,searchbyword,type,sortdata){
//alert(sortdata);

$.ajax({
  url: "<?php echo url();?>/searchbyletter",
  type: "POST",
  data: {searchbyletter : searchbyletter, searchbyword:searchbyword,sortdata:sortdata,searchtype: type,catid:'<?php echo $encodedcatid;?>',"_token": "{{ csrf_token() }}"},
   cache: false,
   
  success: function(data){

  	//console.log(data.vendordetails);
 //  	var count=data.vendordetails.length;
 //  	var str='';
  	
 //  	if(count==0){
 //  		str=str+'<h1>No record found</h1>';
 //  	}
  	
 //  	for(i=0;i<count;i++){

  		
 //  		str=str+'<div style="width: 30%; float: left; padding: 10px; "  ><a href="'+data.vendordetails[i].clickurl+'"><span style="font-size: 15px;">'+data.vendordetails[i].advertisername+'</span></a> <span style="font-size: 15px; color: red;">'+data.vendordetails[i].salecommission+' </span></div>';


 //  	}

	// $.each(data.vendordetails, function (key, val) {

	// 	str='<div class="allstore-content-block"><div class="heading"><h3>'+key+'</h3></div><ul class="d-flex justify-content-between">';	
		
	// 	$.each(val, function (key, val) {

	// 		//console.log(val.clickurl);

	// 		str=str+'<li><a href="<?php echo url();?>/all-stores/details/'+val.id+'"><span class="store-name">'+val.advertisername+'</span> </a><span class="store-discount">'+val.salecommission+'</span></li>';


	// 	});

	// 	str+='</ul>';
	// });



  	$("#allstore").html(data);

  	
  }
});


}

//search by word

$(document).on("keyup","#searchresult",function(){

var sortdata=$(".sortparam").attr("sortdata");
var searchbyletter=$(".mylt").attr("mydata");
var type='myajax';
var searchbyword=$("#searchresult").val();

search(searchbyletter,searchbyword,type,sortdata);

});

// sorting a to z

$(document).on("click","#sort1",function(){

$(".sortparam").removeClass("sortparam");
$(this).addClass("sortparam");
var sortdata=$(".sortparam").attr("sortdata");
var searchbyletter=$(".mylt").attr("mydata");
var type='myajax';
var searchbyword=$("#searchresult").val();

search(searchbyletter,searchbyword,type,sortdata);

});


// sorting highest cashback 

$(document).on("click","#sort2",function(){

$(".sortparam").removeClass("sortparam");
$(this).addClass("sortparam");
var sortdata=$(".sortparam").attr("sortdata");
var searchbyletter=$(".mylt").attr("mydata");
var type='myajax';
var searchbyword=$("#searchresult").val();

search(searchbyletter,searchbyword,type,sortdata);

});


</script>

</div>

@stop