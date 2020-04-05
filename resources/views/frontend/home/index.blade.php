@extends('../layout/frontend_template')
@section('content')
<h3>Category</h3>
<h1 style="text-align: center;margin: 0 auto;">Cashback site</h1>
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

<div style="float: left;width: 80%" class="form-horizontal">
<table class="table table-striped" >
    {!! Form::open(['url' => '/','method'=>'GET', 'files'=>true,'class'=>'form-horizontal row-fluid','id'=>'gift_form']) !!}
    <tr>
      
      <td>

        <input type="text" name="searchresult" value="" id="searchresult"  placeholder="Search by vendor or category " class="form-control">
        </td>

  
      
      
        <td>{!! Form::submit('Search', ['class' => 'btn btn-success mybutton']) !!}</td>
        {!! Form::close() !!}
        <td><a href="<?php echo url('')?>" class="btn btn-primary">Clear</a></td>
    </tr>
  </table>
<?php
		if(!empty($vendordetails)){
//echo "<pre>";
//print_r($vendordetails);exit();
			?>
			
	<table class="table table-striped" style="float: left;width: 80%">
		 <thead>
		<tr>
			<th style="width: 10%">Category</th>
			<th style="width: 10%">Vendor id</th>
			<th style="width: 20%">Vendor name</th>
			<th style="width: 20%">Description</th>
			<th style="width: 10%">Destination</th>
			<th style="width: 10%">Linkid</th>
			<th style="width: 10%">Linkname</th>
			<th style="width: 10%">Clickurl</th>
		</tr>
	</thead>
 <tbody>
		<?php
		foreach ($vendordetails as $key => $value) {
			
		
		?>
		<tr>
		<td style="width: 10%"><?php echo $value->vendorcategories->name;?></td>
		<td style="width: 10%"><?php echo $value->advertiserid;?></td>
		<td style="width: 20%"><?php echo $value->advertisername;?></td>
		<td style="width: 20%"><?php echo $value->description;?></td>
		<td style="width: 10%"><a href="<?php echo $value->destination;?>">Destination</a></td>
		<td style="width: 10%"><?php echo $value->linkid;?></td>
		<td style="width: 10%"><?php echo $value->linkname;?></td>
		<td style="width: 10%"><a href="<?php echo $value->clickurl;?>">Clickurl</a></td>
		</tr>
		<?php

		}?>
</tbody>

	</table>
<div style="clear:both;">{!! $vendordetails->render() !!}</div>
	</div>

<?php
		}
		?>


	<table class="table table-striped" >
    {!! Form::open(['url' => '/payment','method'=>'POST', 'files'=>true,'class'=>'form-horizontal row-fluid','id'=>'gift_form']) !!}
    <tr>
      
      <td>

        <input type="text" name="searchresult" value="" id="searchresult"  placeholder="paypal " class="form-control">
        </td>

  
      
      
        <td>{!! Form::submit('Payment', ['class' => 'btn btn-success mybutton']) !!}</td>
        {!! Form::close() !!}
       
    </tr>
  </table>

@stop

