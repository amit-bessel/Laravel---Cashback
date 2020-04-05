// When the browser is ready...
$(function() {

if ($("#editprofile").length){
	
	var editprofileurl=$("#editprofileurl").val();
	var csrftoken=$("#csrftoken").val();

	$("#editprofile").validate({
		ignore: [],
		// Specify the validation rules
		rules: {
			name: {
				required: true,
				pattern: /^[a-zA-Z ]*$/
			},
			last_name: {
				required: true,
				pattern: /^[a-zA-Z ]*$/
			},
			email: {
		        required: true,
		        email: true,
		        "remote" :
		        {
		          url: editprofileurl,
		          type: "post",
		          data:
		          {
		            email: function()
		            {
		              return $('#email').val();
		            },
		            id: function(){
		              return $('#hid_user_id').val();
		            },
		            _token:csrftoken
		          },
		        },
		      },
           phone: {
           		required:true,
				minlength:9,
				maxlength:10,
				number: true
			},
           	password: {
           		required:true,
           		minlength : 8,
           		pattern: /^$|^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*[!@#$%^&*_]).{8,}$/
           		//pattern: /^$|^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-_]).{8,}$/
           },
           cpassword: {
           		required:true,
           		minlength : 8,
           		equalTo : "#password"
           },

           address: {
				required: true
			},
			city: {
				required: true
			},
			state: {
				required: true
			},
			zipcode: {
				required: true
			},
			country: {
				required: true
			},
			paypalid: {
				required: true
			},
			dob: {
				required: true
			},
		},
	// Specify the validation error messages
		messages: {
			hid_frm_submit_res: "",
			business_logo:{
               accept: 'Selected File should be an image',
			} ,
			password:{
				minlength:"Please enter minimum 8 character",
				pattern:"Password must contain one number, one special character, one small letter and one capital letter",
			},
			email: {
            remote:"Email is already registered."
          },
          name: "Only alphabets and spaces are allowed",

          last_name: "Only alphabets and spaces are allowed",
		},
		submitHandler: function(form) {
			form.submit();
		}
	});
}

if ($("#paypalsubmitform").length){

	$("#paypalsubmitform").validate({
		ignore: [],
		// Specify the validation rules
		rules: {
			paypalid: {
				required: true,
				email:true,
			},
		},
	// Specify the validation error messages
		messages: {
		   hid_frm_submit_res: "",
           paypalid: "Please enter valid email id"
		},
		submitHandler: function(form) {
			form.submit();
		}
	});
}


if ($("#unsubscribeuser").length){
		
	$("#unsubscribeuser").validate({
		ignore: [],
		// Specify the validation rules
		rules: {
			message: {
                required:true,
                message:true
            },
           	
		},
	
		submitHandler: function(form) {
			form.submit();
		}
	});
}
		   
if ($("#register_user").length){

	var editprofileurl=$("#editprofileurl").val();
	var csrftoken=$("#csrftoken").val();

	$("#register_user").validate({
		ignore: [],
		// Specify the validation rules
		rules: {
			name: {
				required: true,
				pattern: /^[a-zA-Z ]*$/
			},
			last_name: {
				required: true,
				pattern: /^[a-zA-Z ]*$/
			},
			email: {
		        required: true,
		        email: true,
		        "remote" :
		        {
		          url: editprofileurl,
		          type: "post",
		          data:
		          {
		            email: function()
		            {
		              return $('#email').val();
		            },
		            id: function(){
		              return $('#hid_user_id').val();
		            },
		            _token:csrftoken
		          },
		        },
		      },
           phone: {
           		required:true,
				minlength:9,
				maxlength:10,
				number: true
			},
           	password: {
           		required:true,
           		minlength : 8,
           		//pattern: /^$|^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*[!@#$%^&*_]).{8,}$/


           		//pattern: /^$|^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-_]).{8,}$/
           		pattern: /^(?=.*?[A-Z])(?=.*?[a-z]).*?[!@#$%^&*()_\d].*$/
           },
           cpassword: {
           		required:true,
           		minlength : 8,
           		equalTo : "#password"
           },

           address: {
				required: true
			},
			city: {
				required: true
			},
			state: {
				required: true
			},
			zipcode: {
				required: true
			},
			country: {
				required: true
			},
			
			dob: {
				required: true
			},


		},
	// Specify the validation error messages
		messages: {
			hid_frm_submit_res: "",
			business_logo:{
               accept: 'Selected File should be an image.',
			} ,
			password:{
				minlength:"Please enter minimum 8 character.",
				pattern:"Passwords must be at least 8 characters in length, contain upper and lowercase letters, and a special character or number.",
			},
			cpassword:"Passwords do not match.",
			email: {
            remote:"Email is already registered."
          },
          name: "Only alphabets and spaces are allowed.",

          last_name: "Only alphabets and spaces are allowed.",
		},
		submitHandler: function(form) {
			form.submit();
		}
	});
}



if ($("#signin_user").length){
		
	$("#signin_user").validate({
		ignore: [],
		// Specify the validation rules
		rules: {
			email: {
                required:true,
                email:true
            },
           	password: {
           		required:true,
           		//minlength : 8,
           		//pattern: /^$|^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-_]).{8,}$/,
           },
		},
	// Specify the validation error messages
		messages: {
			hid_frm_submit_res: "",
			business_logo:{
               accept: 'Selected File should be an image',
			} ,
			password:{
				minlength:"Min 8 characters",
				pattern:"Password must contain one number, one special character, one small letter and one capital letter",
			},
		},
		submitHandler: function(form) {
			form.submit();
		}
	});
}


if ($("#invitefriends").length){
		
	$("#invitefriends").validate({
		ignore: [],
		// Specify the validation rules
		rules: {
			email: {
                required:true,
                email:true
            },
           	fullname: {
           		required:true,
           		//minlength : 8,
           		//pattern: /^$|^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-_]).{8,}$/,
           },
		},
	
		submitHandler: function(form) {
			form.submit();
		}
	});
}

if ($("#invitesfriend").length){
		
	$("#invitesfriend").validate({
		ignore: [],
		// Specify the validation rules
		rules: {
			email: {
                required:true,
                email:true
            }

		},
	
		submitHandler: function(form) {
			form.submit();
		}
	});
}

if ($("#forgot_password").length){
		
	$("#forgot_password").validate({
		ignore: [],
		// Specify the validation rules
		rules: {
			email: {
                required:true,
                email:true
            }
		},
	// Specify the validation error messages
		messages: {
			hid_frm_submit_res: "",
			business_logo:{
               accept: 'Selected File should be an image',
			} ,
		},
		submitHandler: function(form) {
			form.submit();
		}
	});
}

if ($("#frm_reset_pasword").length){
		
	$("#frm_reset_pasword").validate({
		ignore: [],
		// Specify the validation rules
		rules: {
			password: {
           		required:true,
           		minlength : 8,
           		//pattern: /^$|^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-_]).{8,}$/,
           		pattern: /^(?=.*?[A-Z])(?=.*?[a-z]).*?[!@#$%^&*()_\d].*$/

           },
           retype_password: {
           		required:true,
           		equalTo : "#password"
           },
		},
	// Specify the validation error messages
		messages: {
			hid_frm_submit_res: "",
			business_logo:{
               accept: 'Selected File should be an image',
			} ,
			password:{
				minlength:"Min 8 characters",
				pattern:"Passwords must be at least 8 characters in length, contain upper and lowercase letters, and a special character or number.",
			},
			cpassword:"Passwords do not match.",
		},
		submitHandler: function(form) {
			form.submit();
		}
	});
}

if ($("#edit_bussiness").length){
	$("#edit_bussiness").validate({
		ignore: [],
		// Specify the validation rules
		rules: {
			business_category: {
				required: true
			},
			business_title: {
				required: true
			},
			website: {
				required: true,
				url: true
			},
			discount_offered: {
				required: true
			},
			business_logo:{
				accept: "image/*",
			}
			
			
		},
	// Specify the validation error messages
		messages: {
			hid_frm_submit_res: "",
			business_logo:{
               accept: 'Selected File should be an image',
			} ,
		},
		submitHandler: function(form) {
			form.submit();
		}
	});
}


if ($("#card_form").length){
	$("#card_form").validate({
		ignore: [],
		// Specify the validation rules
		rules: {
			card_name: {
				required: true
			},
			card_details: {
				required: true
			},
			card_price: {
				required: true,
				number: true,
			},
			card_image:{
                accept: "image/*",
			} 
			
		},
	// Specify the validation error messages
		messages: {
			hid_frm_submit_res: "",
			business_logo:{
               accept: 'Selected File should be an image',
			} ,
		},
		submitHandler: function(form) {
			form.submit();
		}
	});
}


/****************************************************************************************
 *				Bank Account validation rule 				 							*
 ***************************************************************************************/
if ($("#paypal_account").length){
		
	$("#paypal_account").validate({
		ignore: [],
		rules: {
			paypal_email: {
                required:true,
                email:true
            },
		},
		messages: {
			paypal_email:{
               required: 'This field is required',
               email: 'Please enter a valid Paypal email address.',
			},
		},
		submitHandler: function(form) {
			form.submit();
		}
	});
}

if ($("#stripe_account").length){
		
	$("#stripe_account").validate({
		ignore: [],
		rules: {
			publish_key: {
                required:true
            },
            secret_key: {
                required:true
            },
		},
		messages: {
			publish_key:{
               required: 'This field is required',
			},
			secret_key:{
               required: 'This field is required',
			},
		},
		submitHandler: function(form) {
			form.submit();
		}
	});
}

if ($("#contact_us").length){

	$("#contact_us").validate({

		ignore: [],

		// Specify the validation rules

		rules: {
			name: {
				required: true
			},
			email: {
				required: true,
				email: true
			},
			phone: {
				minlength:9,
				maxlength:10,
				number: true
			},
			/*phone: {
              	required:true,
              	minlength:8,
             	pattern: /^((\+\d{1,3}(-| )?\(?\d\)?(-| )?\d{1,5})|(\(?\d{2,6}\)?))(-| )?(\d{3,4})(-| )?(\d{4})(( x| ext)\d{1,5}){0,1}$/
            },*/
			
			message: {
				required: true,
			}

		},

		submitHandler: function(form) {

			form.submit();

		}

	});

}

if ($("#contact_form").length){

	$("#contact_form").validate({

		ignore: [],

		// Specify the validation rules

		rules: {
			name: {
				required: true
			},
			email: {
				required: true,
				email: true
			},
			phone: {
				minlength:9,
				maxlength:10,
				number: true
			},
			/*phone: {
              	required:true,
              	minlength:8,
             	pattern: /^((\+\d{1,3}(-| )?\(?\d\)?(-| )?\d{1,5})|(\(?\d{2,6}\)?))(-| )?(\d{3,4})(-| )?(\d{4})(( x| ext)\d{1,5}){0,1}$/
            },*/
			contact: {
				required: true,
				number: true,
				max: 12
			},
			message: {
				required: true,
			}

		},

		submitHandler: function(form) {

			form.submit();

		}

	});

}





/****************************************************************************************
 *				End of Bank Account validation rule 		 							*
 ***************************************************************************************/

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
					$('#user_email_msg').text('Email is already registered.');
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
	
	
	/***************	Check same email exists or not. 	************************/
	
	
	addValidationRules: function(){
		var form_type = $('#form_type').val();
		var pwd_field = $('#password').val();
		if(form_type=='ADD' || pwd_field!='')
		{
			$( "#password" ).rules( "add", {
				required: true,
				minlength:8,
				pattern: /^$|^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-_]).{8,}$/,
				messages: {
					minlength:"Please enter minimum 8 character",
					pattern: "Password should contain atleast one number, one uppercase letter, one lower case letter and one special character.",
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
			window.location.href = base_url+"/admin/users/remove/"+id;
		}
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