<!-- <div class="container">
    <b class="copyright">&copy; <?php //echo date('Y');?>&nbsp;Checkout Saver </b>All rights reserved.
</div> -->



<!--<script src="<?php echo url();?>/public/backend/scripts/flot/jquery.flot.js" type="text/javascript"></script>
<script src="<?php echo url();?>/public/backend/scripts/flot/jquery.flot.resize.js" type="text/javascript"></script>-->
<!-- <script src="<?php echo url();?>/public/backend/js/jquery-ui.js" type="text/javascript"></script> -->
<script src="<?php echo url();?>/public/backend/scripts/datatables/jquery.dataTables.js" type="text/javascript"></script>
<script src="<?php echo url();?>/public/backend/scripts/common.js" type="text/javascript"></script>
<script src="<?php echo url();?>/public/backend/scripts/jquery-sortable.js" type="text/javascript"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.2/js/bootstrap-select.min.js" type="text/javascript"></script>

	<script>
	//var path = "<?php echo 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>";
	function selectAllRecords()
	{
		//alert(path);
		if($('#check_all_records').is(':checked')==true)
		{
			$('input[type="checkbox"][recordType="multipleRecord"]').prop('checked',true);
		}
		else
		{
			$('input[type="checkbox"][recordType="multipleRecord"]').prop('checked',false);
		}
	}
	function globalActiveInactiveMultipleRecords(table_name)
	{
		//alert(table_name);
		var status = $('#multiple_operation_status').val();
		var path = "<?php echo 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>";
		//alert('test');
		var record_id_array = new Array();
		var selected_record_length = $('input[type="checkbox"][recordType="multipleRecord"]:checked').length;
		//alert(selected_record_length);
		if(selected_record_length>0)
		{
			//alert(status);
			if(status!='')
			{
				$('input[type="checkbox"][recordType="multipleRecord"]').each(function(){
					if ($(this).is(':checked')==true)
					{
						var tile_id = $(this).attr('multipleRecord');
						record_id_array.push(tile_id);
					}
					
				});
				var record_id_array_str = record_id_array.toString();
				var record_ids 			= btoa(record_id_array_str);
				var return_url 			= btoa("<?php echo 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>");
				var table_name			= btoa(table_name);
				var url = "<?php echo url(); ?>/admin/multiple-record-operations?record_ids="+record_ids+"&return_url="+return_url+"&table_name="+table_name+"&status="+status;
				//alert(url);
				window.location.href=url;
			}
			else{
				$('#selected_record_msg').text("Please select status.");
			}
			
		}
		else{
			$('#selected_record_msg').text("Please select record first.");
		}
		
	}
	</script>
