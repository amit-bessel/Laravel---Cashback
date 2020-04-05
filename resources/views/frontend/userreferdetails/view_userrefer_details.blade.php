@extends('../layout/frontend_template')
@section('content')
<div class="comn-main-wrap">
<div class="form-horizontal">   
	<table class="table table-striped">
		<thead>
      <tr>
        <th>Refer Code</th>
        <th>Link</th>
        <th>Name</th>
        <th>Email</th>
        <th>Phoneno</th>
        
        <th>View Details</th>
      </tr>
    </thead>
    <tbody>
	<?php
	if(!empty($user_details)){
		//echo "<pre>";
		//print_r($refer);exit();
		foreach ($user_details as $key => $value) {
			# code...
		/*$name=$refer['firstname'][$key]." ".$refer['lastname'][$key];
		$email=$refer['email'][$key];
		$refercode=$refer['refercode'][$key];
		$phoneno=$refer['phoneno'][$key];
		$userreferid=$refer['userreferid'][$key];
		$created_at=$refer['created_at'][$key];
		$updated_at=$refer['updated_at'][$key];*/
	?>
		



    
    
      <tr>
        <td><?php if($value->refercode!=''){ echo $value->refercode;}else {?>No refer code<?php }?></td>
        <td><?php if($value->refercode==''){ ?>Share using link<?php }?></td>
        <td style="font-weight: bold; color: blue; text-transform: capitalize;">{{$value->userreferlink1->firstname . " ". $value->userreferlink1->lastname}}</td>
        <td>{{$value->userreferlink1->email}}</td>
        <td>{{$value->userreferlink1->phoneno}}</td>
     
        <td><a href="<?php echo url('');?>/user/referuserdetails/<?php echo base64_encode($value->referto);?>" class="btn btn-primary">View User Details</a></td>
      </tr>
      
   
  



		
		
		
		
		

		


		
		
		
	

	<?php } } else {?> No record found <?php  }?>
	 </tbody>
	</table>
      <div>{!! $user_details->render() !!}</div>
 </div>
	
	</div>
@stop
    
    