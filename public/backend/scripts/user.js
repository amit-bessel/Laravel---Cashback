// When the browser is ready...
$(function() {


	// Setup form validation on the #register-form element
if ($("#cms_form").length){
	$("#cms_form").validate({
		ignore: [],
		// Specify the validation rules
		rules: {
			hid_frm_submit_res:{
				equalTo:"#hid_validate_res",
			},
			name: {
				required: true
			},
			last_name: {
				required: true
			},
			email: {
				required: true,
				email: true
			},
			contact: {
				required: true,
			}
		
		},
	
	 // Specify the validation error messages
		messages: {
			hid_frm_submit_res: "",
			subadmin_id: {
					required:"Please type subadmin name and choose from suggested admin"
			},
			email: {
					email:"Please enter valid email."
			},
			contact: {
				number: "Please enter number only.",
			},
		},               

		submitHandler: function(form) {
			form.submit();
		}
	});
	userJs.addValidationRules();
}


if ($("#note_form").length){
	
	$("#note_form").validate({
		rules: {
			
			note: {
				required: true
			},
			
		
		},
	
	 // Specify the validation error messages
		messages: {
			hid_frm_submit_res: "",
			note: {
					required:"Please enter note details"
			},
			
		},               

		submitHandler: function(form) {
			form.submit();
		}
	});
	userJs.addValidationRules();
}

//subadmin
if ($("#cms_form_sub").length){
	$("#cms_form_sub").validate({
		ignore: [],
		// Specify the validation rules
		rules: {
			hid_frm_submit_res:{
				equalTo:"#hid_validate_res",
			},
			user_type: {
				required: true
			},
			name: {
				required: true
			},
			email: {
				required: true,
				email: true
			},
			contact: {
				required: true,
				number: true,
				maxlength: 10
			},
			subadmin_id: {
				required: true
			},
			gender: {
				required: true
			},
			address: {
				required: true
			},
			city: {
				required: true
			},
			zip_code: {
				required: true,
				number: true
			},
			video_link: {
				required: true,
			}
		
		},
	
	 // Specify the validation error messages
		messages: {
			hid_frm_submit_res: "",
			subadmin_id: {
					required:"Please type subadmin name and choose from suggested admin"
			},
			email: {
					email:"Please enter valid email."
			},
			url: {
					email:"Please enter valid url."
			},
			contact: {
				number: "Please enter number only.",
			},
		},               

		submitHandler: function(form) {
			form.submit();
		}
	});
	userJs.addValidationRules();
}

if ($("#product_form").length){
	$("#product_form").validate({
		ignore: [],
		rules: {
			name: {
				required: true
			},
			price: {
				required: true,
				number: true
			}
		
		},
	
	 // Specify the validation error messages
		messages: {
			hid_frm_submit_res: "",
			name: {
					email:"Please enter valid email."
			},
			contact: {
				number: "Please enter number only.",
			},
		},               

		submitHandler: function(form) {
			form.submit();
		}
	});
}
});

/****************************************************************************************
 *				All functions related with user section		 							*
 ***************************************************************************************/
var userJs = {
	
	/***************	Check same email exists or not for subadmin. 	************************/
	checkSubAdminEmail: function (operationMode) {
		var hid_user_id = "";
		if (operationMode=='EDIT') {
			hid_user_id	= $('#hid_user_id').val();
		}
		else{
			hid_user_id = "";
		}
		var email	= $('#email').val();
		$.ajax({
			type 	: 'get',
			data 	: {hid_user_id: hid_user_id,email: email},
			url 	: base_url+'/admin/subadmin/check',
			async	: false,
			success	: function(response){
				//alert(response);
				if (response==1){
					$('#user_email_msg').text('Email already exists.');
					$('#user_email_msg').css('display','block');
					$('#hid_validate_res').val(0);
				}
				else{
					$('#user_email_msg').css('display','none');
					$('#hid_validate_res').val(1);
				}
			}
		});
    },
	

	changesuperaffiliatestatus: function (id) {
		
	
		$.ajax({
			type 	: 'post',
			data 	: {userid: id},
			url 	: base_url+'/admin/siteuser/changesuperaffiliatestatus',
			async	: false,
			success	: function(response){
				
				if (response==1){

					alert("Super Affiliated")
					//$('.alert-success').hide();
					//$('#superaffiliate_status_span_'+id).html('Superaffiliated.');
					//$('#superaffiliate_status_span_'+id).fadeIn('slow').delay(900).fadeOut('slow');
				}
				else{
					//$('.alert-success').hide();
					//$('#superaffiliate_status_span_'+id).html('Not Superaffiliated.');
					//$('#superaffiliate_status_span_'+id).fadeIn('slow').delay(900).fadeOut('slow');
					alert("Not Super Affiliated")
				}
			}
		});
    },

    changegiftcardstatus: function (id) {
		
	
		$.ajax({
			type 	: 'post',
			data 	: {cardid: id},
			url 	: base_url+'/admin/gift-card/changegiftcardstatus',
			async	: false,
			success	: function(response){
				
				if (response==1){

					//$('.alert-success').hide();
					$('#giftcard_status_span_'+id).html('Enabled.');
					$('#giftcard_status_span_'+id).fadeIn('slow').delay(900).fadeOut('slow');
				}
				else{
					//$('.alert-success').hide();
					$('#giftcard_status_span_'+id).html('Disabled.');
					$('#giftcard_status_span_'+id).fadeIn('slow').delay(900).fadeOut('slow');
					
				}
			}
		});
    },


	
	/***************	Check same email exists or not. 	************************/
	checkUserEmail: function (operationMode) {
		var hid_user_id = "";
		if (operationMode=='EDIT') {
			hid_user_id	= $('#hid_user_id').val();
		}
		else{
			hid_user_id = "";
		}
		var email	= $('#email').val();
		$.ajax({
			type 	: 'get',
			data 	: {hid_user_id: hid_user_id,email: email},
			url 	: base_url+'/admin/user/check',
			async	: false,
			success	: function(response){
				//alert(response);
				if (response==1){
					$('#user_email_msg').text('Email already exists.');
					$('#user_email_msg').css('display','block');
					$('#hid_validate_res').val(0);
				}
				else{
					$('#user_email_msg').css('display','none');
					$('#hid_validate_res').val(1);
				}
			}
		});
    },
	
	changeStatus: function(value,id){
		//alert("kk");
		$.ajax({
			url: base_url+'/admin/user/status',
			type: "get",
			data: { this_val : value,this_id : id},
			success: function(data){
				if(data == '1'){
					$('.alert-success').hide();
					$('#success_status_span_'+id).html('Status updated.');
					$('#success_status_span_'+id).fadeIn('slow').delay(900).fadeOut('slow');
					//$('#success_status_span_'+id).fadeOut('slow');
				}
			}
		});
		
	},

	changeVendorPopularStatus: function(value,id){
		$.ajax({
			url: base_url+'/admin/vendor/changepopularstatus',
			type: "get",
			data: { this_val : value,this_id : id},
			success: function(data){
				if(data == '1'){
					$('.alert-success').hide();
					$('#success_status_span_'+id).html('Status updated.');
					$('#success_status_span_'+id).fadeIn('slow').delay(900).fadeOut('slow');
					//$('#success_status_span_'+id).fadeOut('7000');
				}
				else if(data=='0'){
					alert("You can set maximum 9 popular vendors");

					window.location.reload();

				}
			}
		});
		
	},


	generateSuperaffiliateCode: function(id){
		$.ajax({
			url: base_url+'/admin/siteuser/generateSuperaffiliateCode',
			type: "get",
			data: { this_id : id},
			success: function(data){
				
				if(data == '1'){
					//alert('Code Generated');
					//$('.alert-success').hide();
					$("#refer_buttion_"+id).hide();
					//$('#success_referstatus_span_'+id).show();
					//$('#success_referstatus_span_'+id).html('Successfully Affiliated.');
					alert("Successfully Affiliated");
					location.reload();
					
				}
				else
				{
					$("#refer_buttion_"+id).hide();
					//$('#success_referstatus_span_'+id).show();
					//$('#success_referstatus_span_'+id).html('Already Affiliated.');
					alert("Already Affiliated");
					location.reload();
				}
			}
		});
		
	},

	/*site user*/
	changesiteuserStatus: function(value,id){

		$.ajax({
			url: base_url+'/admin/siteuser/status',
			type: "get",
			data: { this_val : value,this_id : id},
			success: function(data){
				if(data == '1'){
					$('.alert-success').hide();
					$('#success_status_span_'+id).html('Status updated.');
					$('#success_status_span_'+id).fadeIn('slow').delay(900).fadeOut('slow');
					//$('#success_status_span_'+id).fadeOut('slow');
				}
			}
		});
		
	},
	
	addValidationRules: function(){
		var form_type = $('#form_type').val();
		var pwd_field = $('#password').val();
		if(form_type=='ADD' || pwd_field!='')
		{
			$( "#password" ).rules( "add", {
				required: true,
				minlength:8,
				//pattern: /^$|^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*[!@#$%^&*]).{8,}$/,
				//pattern: /^$|^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-_]).{8,}$/,
				pattern: /^$|^(?=.*?[a-zA-Z])(?=.*?[0-9])(?=.*[!@#$%^&*_]).{8,}$/,
				messages: {
					minlength:"Please enter minimum 8 character",
					//pattern: "Password must contain one number, one special character, one small letter and one capital letter.",
					pattern:"Password must contain one number, one letter , one special character.",
				}
			});
			$( "#conf_password" ).rules( "add", {
				required: true,
				equalTo:"#password",
				messages:"Please enter the same value again"
			});
		}
	},
	
	/*
	 *	During editing user, if password field is not empty then add validation
	 *	rules otherwise remove validation rules. 
	 */
	rulesWhileEditingUser: function(){
		var password = $('#password').val();
		if (password!='')
		{
			userJs.addValidationRules();
		}
		else
		{
			$( "#password, #conf_password" ).rules( "remove" );
		}
	},
	
	remove: function(id){
		var response = confirm('Do you really want to remove this user?');
		if(response==1){
			window.location.href = base_url+"/admin/user/remove/"+id;
		}
	},
	/*site user*/
	siteuserremove: function(id){
		
		
		var response = confirm('Do you really want to remove this user?');
		if(response==1){
			window.location.href = base_url+"/admin/siteuser/remove/"+id;
		}
	},

	/*remove vendor*/
	vendorremove: function(id){
		
		
		var response = confirm('Do you really want to remove this vendor?');
		if(response==1){
			window.location.href = base_url+"/admin/vendor/remove/"+id;
		}
	},

	remove_vendor: function(value,id){
		
		$.ajax({
			url: base_url+'/admin/vendors/remove-vendor',
			type: "get",
			data: { this_val : value,this_id : id,_token:$('meta[name="_token"]').attr('content')},
			success: function(data){
				if(data == '1'){
					$('.alert-success').html('');
					$('#success_status_span_'+id).html('Status updated.');
					$('#success_status_span_'+id).fadeIn('slow');
					$('#success_status_span_'+id).fadeOut('slow');
				}
			}
		});
		
	},
	/*-----------------------------------------------------------------------------*/
}

/*--------------------------------------------------------------------------------------*/
var productJs = {
	changeStatus: function(id){
		
		var this_val = $('#user_active_'+id).val();
		var this_id = $('#record_id_'+id).val();
		//alert(this_val)
		$.ajax({
			url: base_url+'/admin/products/status',
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
		
	},
	/*
	 *	During editing user, if password field is not empty then add validation
	 *	rules otherwise remove validation rules. 
	 */
	
	remove: function(id){
		var response = confirm('Do you really want to remove this user?');
		if(response==1){
			window.location.href = base_url+"/admin/products/remove/"+id;
		}
	},
	/*-----------------------------------------------------------------------------*/
}

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