$(document).ready(function(){
  //  $('input[type=checkbox]').removeAttr('checked');
    $("input[name='cardaction']").prop("type", "button");
	$('#generate_link').prop('checked', true);
	
        //add user page
		$( "#add_user" ).click(function() {
            if($('#add_card').is(':checked')) //if admin have to add credit card details
            {
                if($('#card_number').val() == "") 
                {
                    $('#card_error').show(); //card fields validation message
                    $("input[name='cardaction']").prop("type", "button");
                }
                else
                {
                    $('#card_error').hide();
                    formSubmit();
                }
            }
            else
            {
                $('#card_error').hide();
                formSubmit();
            }
			
		});
        
        //add card details page
        $( "#add_card_det" ).click(function() {
            var cardType = $.payment.cardType($('#card_number').val());
            var validcard = $.payment.validateCardNumber($('#card_number').val());
            var validcvc = $.payment.validateCardCVC($('#card_cvc').val(), cardType);
            var validexpiry = $.payment.validateCardExpiry($('#card_expiry_month').val(),$('#card_expiry_year').val());
            if(!validcard){
                $("#card_error").show();
            }else if(!validcvc){
                 $("#card_error").hide();
                 $("#card_cvc_error").show();
            }else if(!validexpiry){
                $("#card_error").hide();
                $("#card_cvc_error").hide();
                $("#card_expiry_error").show();
            }else{
                $("#card_error").hide();
                $("#card_cvc_error").hide();
                $("#card_expiry_error").hide();
                formSubmit();
            }
		});
	  // onclick delete text of text box
      // $('#suggestionbox').click(
      //   function(){
      //       $(this).val('');
      //   });
      
    ////////////////////////// payment page validation //////////////////////////////////////
    
    //payment validation

    if ($("#payment_form").length){
    	
    	$("#payment_form").validate({
    		ignore: [],
    		// Specify the validation rules
    		rules: {
                card_id: {
                    required:true

                },
    			amount: {
    				required: true
    			},
    			notes: {
    				required: true
    			},
    		},
    	
    	 // Specify the validation error messages
    		messages: {
                card_id: {
                        required:"Please select a card"
                },
    			amount: {
    					required:"Please add amount"
    			},
    			notes: {
    					required:"Please add some notes related to this payment"
    			},
    		},               

    		submitHandler: function(form) {
                $(".save-btn").prop('disabled',true);
                $(".back-btn").removeAttr('href');
    			form.submit();
    		}
    	});
    	//userJs.addValidationRules();
    }
    img_width =600;
    img_height=0;
    $.validator.addMethod('minImageWidth', function(value, element, minWidth) {
          return (img_width || 0) >= minWidth;
     }, function(minWidth, element) {
         return (img_width)
              ? ("Your image minimum width must be " + minWidth + "px")
              : "Selected file is not an image.";
    });
    $.validator.addMethod('minImageHeight', function(value, element, minHeight) {
          return (img_height || 0) >= minHeight;
     }, function(minWidth, element) {
          return (img_height)
              ? ("Your image minimum height must be " + minWidth + "px")
              : "Selected file is not an image.";
    });
    var _URL = window.URL || window.webkitURL;
     $("#banner_image").change(function (e) {
            var file, img;
            if ((file = this.files[0])) {
                img = new Image();
                img.onload = function () {
                    img_width = this.width;
                    img_height = this.height;
                };
                img.src = _URL.createObjectURL(file);
            }
            $("#banner_image").rules('add', {
                  accept: "image/*|video/*",
                  minImageWidth: 960,
                  minImageHeight: 260,
            });
    });


    $("#banner_image_women").change(function (e) {
            var file, img;
            if ((file = this.files[0])) {
                img = new Image();
                img.onload = function () {
                    img_width = this.width;
                    img_height = this.height;
                };
                img.src = _URL.createObjectURL(file);
            }
            $("#banner_image_women").rules('add', {
                  accept: "image/*",
                  minImageWidth: 960,
                  minImageHeight: 260,
            });
    });


    if ($("#banner_form").length){
         $("#banner_form").validate({
            rules: { 

               banner_text_eng: {
                    required:true
                },
                banner_text_arabic: {
                    required:true
                },
                banner_dec_eng: {
                    required:true
                },
                banner_dec_arabic: {
                    required:true
                }, 
                 
            },
            messages: {
                banner_image:{
                   required: 'Please select the image!',
                   accept: 'Selected File should be an image',
                } ,
            }       
        });
    }


    if ($("#edit_banner_form").length){
         $("#edit_banner_form").validate({
            rules: { 

               banner_text_eng: {
                    required:true
                },
                banner_text_arabic: {
                    required:true
                },
                banner_dec_eng: {
                    required:true
                },
                banner_dec_arabic: {
                    required:true
                }, 
                
            },
            messages: {
                banner_image:{
                   required: 'Please select the image!',
                   accept: 'Selected File should be an image',
                } ,
            }      
        });
    }
        
       



    if ($("#add_top_banner_form").length){
         $("#add_top_banner_form").validate({
            ignore: [], 
            rules: {
                upload_banner_image:{
                    required:true
                },
                /*banner_image:{
                   required:true,
                   accept: "image/*",
                },*/
                banner_url_men: {
                    required:true,
                    url:true
                },
                upload_banner_image_women:{
                    required:true
                },
                /*banner_image_women:{
                   required:true,
                   accept: "image/*",
                },*/
                banner_url_women: {
                    required:true,
                    url:true
                },
            },
            messages: {
                banner_image:{
                   required: 'Please select the image!',
                   accept: 'Selected File should be an image',
                } ,
                banner_image_women:{
                   required: 'Please select the image!',
                   accept: 'Selected File should be an image',
                } ,
            }       
        });
    }

    if ($("#top_banner_form").length){
         $("#top_banner_form").validate({
            rules: { 
                
                banner_url_men: {
                    required:true,
                    url:true
                },
                banner_url_women: {
                    required:true,
                    url:true
                },
               /*banner_first_text_eng: {
                    required:true,
                    maxlength: 25
                },
                banner_first_text_arabic: {
                    required:true,
                    maxlength: 25
                },
                banner_second_text_eng: {
                    required:true,
                    maxlength: 30
                },
                banner_second_text_arabic: {
                    required:true,
                    maxlength: 30
                },
                banner_third_text_eng: {
                    required:true,
                    maxlength: 30
                },
                banner_third_text_arabic: {
                    required:true,
                    maxlength: 30
                },*/
                 
            },
            messages: {
                banner_image:{
                   required: 'Please select the image!',
                   accept: 'Selected File should be an image',
                } ,
            }       
        });
    }


    	
    });

    function formSubmit() {
    		
    			
    			var card_number = $('#card_number').val().replace(/ /g,'');
    			if(card_number!="")
    			{
    				$('#add_user').attr("disabled", "disabled");
                    $(".payment-errors").html("");
    				$("#processing").html("Checking your card...");		
    				Stripe.createToken({
    					number: $('#card_number').val(),
    					cvc: $('#card_cvc').val(),
    					exp_month: $('#card_expiry_month').val(),
    					exp_year: $('#card_expiry_year').val(),
    					name: $("#name").val(),
    					address_line1: $("#address").val(),
    					address_state: $("#city").val(),
    					address_zip: $("#zip_code").val(),
    				}, stripeResponseHandler);
    			}
                
    			
    		
    		return false;
    }

    function stripeResponseHandler(status, response) {
        if (response.error) {
            // re-enable the submit button
            $('#add_user').removeAttr("disabled");
            // show the errors on the form
            $("#processing").html("");	
            $(".payment-errors").html(response.error.message);
        } else {
            
            $(".payment-errors").html("");
            var img_url = site_url+'/public/backend/images/icons/tick.png';
            
            $("#processing").html("<img src='"+img_url+"'>");		
            //var form$ = $("#payment-form");
            // token contains id, last4, and card type
            var token = response['id'];
            
            if(token!="")
            {
                var card_type = response['card']['brand'];
                var exp_month = response['card']['exp_month'];
                var exp_year = response['card']['exp_year'];
                var last4 = response['card']['last4'];
                
                $('#client_token').val(token);
                
                $('#card_type').val(card_type);
                $('#exp_month').val(exp_month);
                $('#exp_year').val(exp_year);
                $('#last4').val(last4);
                $('#add_user').removeAttr("disabled");
                $("input[name='action']").prop("type", "submit");
                
                $('#creditcard_frm').submit();
            }
        }
    }


    function getParameterByName(name, url) {
        if (!url) {
          url = window.location.href;
        }
        name = name.replace(/[\[\]]/g, "\\$&");
        var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
            results = regex.exec(url);
        if (!results) return null;
        if (!results[2]) return '';
        return decodeURIComponent(results[2].replace(/\+/g, " "));
    }


$(document).ready(function(){
    $(document).on('click','.makeleftcollapse',function(){
        $('.wrapper').toggleClass('hideleft');
        $('.customleft_bar').toggleClass('mainright');
    });
      $("#search_key").keyup(function(e){ 
        var code = e.which;
        if(code==13){
            goForSearch();
        }
    });
});