@extends('admin/layout/admin_template')
 
@section('content')

  
@if(Session::has('success'))
        <div class="alert alert-success">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <strong>{!! Session::get('success') !!}</strong>
        </div>
 @endif
 <script type="text/javascript">
function goForSearch(){
    var search_key      = $('#search_key').val();

    var url = '<?php echo url(); ?>/admin/homesetting?';
    url += 'search_key='+search_key;
    window.location.href = url;
}

</script>
<div class="filter-sec d-flex align-items-center">
    


        <div class="filter-left-sec d-flex align-items-center" >


            <div class="search-field-holder">
                <input type="text" value="<?php echo isset($search_key) ? $search_key : ''; ?>" name="search_key" id="search_key" placeholder="Search by Name" class="field" onkeyup="getFromLocation('search_key');" />
            </div>


         


            <div>
                <input type="button" onclick="goForSearch();" class="btn btn-ylw" name="btn_search_hotel" id="btn_search_hotel" value="Search">
                <button type="button" class="input-reset-btn" onclick="window.location.href='<?php echo url(); ?>/admin/homesetting';" name="btn_search_hotel" id="btn_search_hotel" value=""></button>
            </div>


            </div>

        


</div><!--end filter-sec-->


   <div class="table-responsive-lg">
            <?php
            if($sitesettings->count()>0){?>                   
			<table cellpadding="0" cellspacing="0" border="0" class="table table-striped  display" width="100%">
				<thead>
					<tr>
						<!-- <th>Sl No.</th> -->
						<th>Name</th>
						<th>Display Name</th>
						<th>Value</th>
						<th>Edit</th>
					   
					</tr>
				</thead>
					
					
				<tbody>
					<?php $i=1;
					//print_r($cms); exit;?>
					@foreach ($sitesettings as $sitesetting)
					<tr class="odd gradeX">
						<!-- <td class=" sorting_1">

							<?php //echo $i; ?>
						</td> -->
						<td class=" ">
							{!! $sitesetting->name !!}
						</td>

						<td class=" ">
							{!! $sitesetting->display_name !!}
						</td>

						<td class=" ">
							<?php
							if($sitesetting->id==8){
								?>
								{!! nl2br($sitesetting->value) !!}
								<?php
							}
							else{
								?>
								{!! strip_tags($sitesetting->value) !!}
								<?php
							} 
							?>
							
						</td>

						<td>
							<a class="edit-icon" href="{!!route('admin.homesetting.edit',$sitesetting->id)!!}"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
						</td>
					   <!-- <td>
							{!! Form::open(['method' => 'DELETE', 'route'=>['admin.sitesetting.destroy', $sitesetting->id]]) !!}
							{!! Form::submit('Delete', ['class' => 'btn btn-danger']) !!}
							{!! Form::close() !!}
						</td>-->
					</tr>
					<?php $i++; ?>
						@endforeach
					</tbody>
					
				</table>

				<?php 
			}else{?>
			No Record Found
			<?php }?>

	  
		</div>

  <div><?php echo $sitesettings->render(); ?></div>
@endsection
