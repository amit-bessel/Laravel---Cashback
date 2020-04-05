@extends('admin/layout/admin_template')
 
@section('content')
<script>
function changeCMSPageStatus(status,cms_page_id) {
	//alert(status+" "+cms_page_id);
	$.ajax({
		type	: 'GET',
		url		: base_url+'/admin/change-cmspage-status',
		data	: {status: status,cms_page_id: cms_page_id},
		async	: false,
		success	: function(response){
			//alert(response);
			if (response==1) {
				$('#cmspage_status_msg'+cms_page_id).text('Status Updated!');
				$('#cmspage_status_msg'+cms_page_id).fadeIn('slow');
				$('#cmspage_status_msg'+cms_page_id).fadeOut('slow');
			}
		}
	});
}
</script>
  
@if(Session::has('success'))
        <div class="alert alert-success">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <strong>{!! Session::get('success') !!}</strong>
        </div>
 @endif
 
   <div class="table-responsive">
                               
                                <table cellpadding="0" cellspacing="0" border="0" class="table table-bordered table-striped  display" width="100%">
                                    <thead>
                                        <tr>
                                            <th width="10%">Sl No.</th>
                                            <th width="35%">Page Name</th>
                                            <th width="45%">Page Title</th>
                                            <!-- <th width="30%">Meta Keywords</th> -->
                                            <th width="10%">Edit</th>
                                        </tr>
                                    </thead>
                                        
                                        
                                    <tbody>
                                        <?php $i=2;
                                        //print_r($cms); exit;?>
                                        <tr class="odd gradeX">
                                            <td class=" sorting_1">1</td>
                                            <td>Home Page</td>
                                            <td>Home Page</td>
                                            <td>
                                                <a class="edit-icon" href="<?php echo url();?>/admin/homesetting" ><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                                            </td>
                                        </tr>
                                        @foreach ($cms as $each_cms)
                                        <tr class="odd gradeX">
                                            <td class=" sorting_1">

                                                <?php echo $i; ?>
                                            </td>
                                            <td class=" ">
                                                {!! $each_cms->page_name !!}
                                            </td>
                                            <td class=" ">
                                             {!! $each_cms->page_title !!}
                                            </td>
                                          <!--   <td class=" ">
                                                {!! $each_cms->meta_keyword !!}
                                            </td> -->
												
											<!--<td class=" ">
												<select style="width:150px;" onchange="changeCMSPageStatus(this.value,'<?php echo $each_cms->id; ?>');">
													<option value="1" <?php echo ($each_cms->status==1)?'selected="selected"':''?>>Active</option>
													<option value="0" <?php echo ($each_cms->status==0)?'selected="selected"':''?>>Inactive</option>
												</select>
												<div style="clear:both;color:green;display:none;" id="cmspage_status_msg<?php echo $each_cms->id; ?>"></div>
                                            </td>-->
                                            
                                            <td>
                                                <a class="edit-icon" href="{!!route('admin.cms.edit',$each_cms->id)!!}" ><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                                            </td>
                                            
                                        </tr>
                                        <?php $i++; ?>
                                            @endforeach
                                        </tbody>
                                        
                                    </table>

                          
                            </div>

  <div><?php echo $cms->render(); ?></div>
@endsection
