// When the browser is ready...
$(function() {

	jQuery.validator.addMethod(
    "money",
    function(value, element) {
        var isValidMoney = /^\d{0,4}(\.\d{0,2})?$/.test(value);
        return this.optional(element) || isValidMoney;
    },
    "Please enter valid price"
);

	// Setup form validation on the #register-form element
	$("#cms_form").validate({
	
		ignore: [],
		// Specify the validation rules
		rules: {
			hid_frm_submit_res:{
				equalTo:"#hid_validate_res",
			},
			'gender[]': { required: true, minlength: 1 },
			name: {
				required: true
			},
			price: {
				required: true
			},
			retail_price: {
				required: true
			},

			status: {
				required: true
			},
			featured_image: {
     			extension: "jpg|jpeg|png|gif"
    		},
    		image_url_men:{
    			required:true,
    			url:true,
    		},
    		featured_image_women: {
     			extension: "jpg|jpeg|png|gif"
    		},
    		image_url_women:{
    			required:true,
    			url:true,
    		},
			
		
		},
	
	 // Specify the validation error messages
		messages: {
			hid_frm_submit_res: "",
		},               

		submitHandler: function(form) {
			form.submit();
		}
	});
});

/****************************************************************************************
 *				All functions related with category section		 						*
 ***************************************************************************************/
var category = {
	/***************	Check same category exixts or not. 	************************/
	checkCategoryName: function (operationMode) {
		//alert(base_url);
		var hid_category_id = "";
		if (operationMode=='EDIT') {
			hid_category_id	= $('#hid_category_id').val();
		}
		else{
			hid_category_id = "";
		}
		var category_name	= $('#name').val();
       // alert(hid_category_id+" "+category_name);
		$.ajax({
			type 	: 'get',
			data 	: {hid_category_id: hid_category_id,category_name: category_name},
			url 	: base_url+'/admin/category/check',
			async	: false,
			success	: function(response){
				//alert(response);
				if (response==1){
					$('#category_name_msg').text('Category already exists.');
					$('#category_name_msg').css('display','block');
					$('#hid_validate_res').val(0);
				}
				else{
					$('#category_name_msg').css('display','none');
					$('#hid_validate_res').val(1);
				}
			}
		});
    },
	checkBrandName: function (operationMode) {
		//alert(base_url);
		var hid_category_id = "";
		if (operationMode=='EDIT') {
			hid_category_id	= $('#hid_category_id').val();
		}
		else{
			hid_category_id = "";
		}
		var brand_name	= $('#name').val();
       // alert(hid_category_id+" "+category_name);
		$.ajax({
			type 	: 'get',
			data 	: {hid_category_id: hid_category_id,brand_name: brand_name},
			url 	: base_url+'/admin/brands/check',
			async	: false,
			success	: function(response){
				//alert(response);
				if (response==1){
					$('#brand_name_msg').text('Brand already exists.');
					$('#brand_name_msg').css('display','block');
					$('#hid_validate_res').val(0);
				}
				else{
					$('#brand_name_msg').css('display','none');
					$('#hid_validate_res').val(1);
				}
			}
		});
    },

	change_status: function(id){
		var this_val = $('#category_active_'+id).val();
		var this_id = $('#record_id_'+id).val();
		//alert(this_val)
		$.ajax({
			url: base_url+'/admin/category/status',
			type: "get",
			data: { this_val : this_val,this_id : this_id},
			success: function(data){
				if(data == '1'){
					$('.alert-success').html('');
					$('#success_status_span_'+id).html('Status updated.');
					$('#success_status_span_'+id).fadeIn('slow');
					$('#success_status_span_'+id).fadeOut('slow');
				}
			}
		});
	}
	/*-----------------------------------------------------------------------------*/
}

/*--------------------------------------------------------------------------------------*/

/****************************************************************************************/
/*			Function Name : dateRangeOverlaps											*/
/*			Uses	: Check date range overlap betwwen two date ranges					*/
/****************************************************************************************/
function dateRangeOverlaps(a_start, a_end, b_start, b_end) {
	if (a_start <= b_start && b_start <= a_end) return true; // b starts in a
	if (a_start <= b_end   && b_end   <= a_end) return true; // b ends in a
	if (b_start <  a_start && a_end   <  b_end) return true; // a in b
	return false;
}
/*--------------------------------------------------------------------------------------*/