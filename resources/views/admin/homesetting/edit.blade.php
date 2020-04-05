{!! HTML::script('resources/assets/js/ckeditor/ckeditor.js') !!} 
@extends('admin/layout/admin_template')

@section('content')
    
    <!-- jQuery Form Validation code -->
  <script>
  
   //When the browser is ready...
   $(function() {
     // Setup form validation on the #register-form element
     $("#cms_form").validate();

   });
	
	$(document).ready(function(){
		//alert('test');
		var sitesetting_id = '<?php echo $sitesettings->id; ?>';
		//alert(sitesetting_id);
		if (sitesetting_id==5) {
			$('form[id="cms_form"]').attr('onsubmit','return validate_sitesetting();');
			//alert('test');
		}
		
	});
	
	function validate_sitesetting() {
		//alert('test');
		var check_lmd_response = true;
		var db_hotel_limit = '<?php echo snake_case($sitesettings->value); ?>';
		var home_page_hotel_limit = $('input[type="number"][name="value"]').val();
		var display_text = '';
		//alert(home_page_hotel_limit);
		$.ajax({
			type	: "GET",
			url		: "<?php echo url(); ?>/admin/check-added-lmd-hotels-for-home",
			data 	: {home_page_hotel_limit: home_page_hotel_limit},
			async	: false,
			success	: function(response){
				//alert(response);
				var text = inWords(response);
				//alert(text);
				if (response==0)
				{
					check_lmd_response = true;
				}
				else if (response==1) {
					$('#lmd_hotel_msg').text('You have already added '+db_hotel_limit+' hotel for last minute deals for '+text+' departure city.');
					check_lmd_response = false;
				}
				else
				{
					
					$('#lmd_hotel_msg').text('You have already added '+db_hotel_limit+' hotels for last minute deals for '+text+' departure cities.');
					check_lmd_response = false;
				}
			}
		});
		//alert(check_lmd_response);
		return check_lmd_response;
		
	}
	
function inWords (num) {
	var a = ['','one ','two ','three ','four ', 'five ','six ','seven ','eight ','nine ','ten ','eleven ','twelve ','thirteen ','fourteen ','fifteen ','sixteen ','seventeen ','eighteen ','nineteen '];
	var b = ['', '', 'twenty','thirty','forty','fifty', 'sixty','seventy','eighty','ninety'];

    if ((num = num.toString()).length > 9) return 'overflow';
    n = ('000000000' + num).substr(-9).match(/^(\d{2})(\d{2})(\d{2})(\d{1})(\d{2})$/);
    if (!n) return; var str = '';
    str += (n[1] != 0) ? (a[Number(n[1])] || b[n[1][0]] + ' ' + a[n[1][1]]) + 'crore ' : '';
    str += (n[2] != 0) ? (a[Number(n[2])] || b[n[2][0]] + ' ' + a[n[2][1]]) + 'lakh ' : '';
    str += (n[3] != 0) ? (a[Number(n[3])] || b[n[3][0]] + ' ' + a[n[3][1]]) + 'thousand ' : '';
    str += (n[4] != 0) ? (a[Number(n[4])] || b[n[4][0]] + ' ' + a[n[4][1]]) + 'hundred ' : '';
    str += (n[5] != 0) ? ((str != '') ? 'and ' : '') + (a[Number(n[5])] || b[n[5][0]] + ' ' + a[n[5][1]]): '';
    return str;
}
  </script>
    {!! Form::model($sitesettings,['method' => 'PATCH','id'=>'cms_form','files'=>true,'class'=>'form-horizontal row-fluid','route'=>['admin.homesetting.update',$sitesettings->id]]) !!}
   
    <div class="form-group form-row">
        <label class="col-sm-2 col-form-label" for="basicinput">Name</label>

        <div class="col-sm-10">
             {!! Form::text('display_name',null,['class'=>'span8','id'=>'display_name','required'=>true]) !!}
        </div>
    </div>


    <div class="form-group form-row">
        <label class="col-sm-2 col-form-label" for="basicinput">Value</label>

        <div class="col-sm-10">
             <?php 
             echo  Form::hidden('type',$sitesettings->type);
             //echo $sitesettings->type;
            if($sitesettings->type == 'textarea')
            {
               echo  Form::textarea('value',null,['class'=>'span8','id'=>snake_case($sitesettings->name),'required'=>true]) ;

            }
            else if($sitesettings->type == 'text')
            {
                echo  Form::text('value',null,['class'=>'span8','id'=>snake_case($sitesettings->name),'required'=>true]) ;
            }
			else if($sitesettings->type == 'number')
            {
				?>
				<input type="number" value="<?php echo snake_case($sitesettings->value); ?>" name="value" min="1" class="span8" id="<?php echo snake_case($sitesettings->name); ?>" required/>
				<div style="clear:both;color:red;" id="lmd_hotel_msg"></div>
				<?php
            }
            else if($sitesettings->type == 'radio')
            {
                echo "<div class='label_siteadmin pull-left'><label>". Form::radio('value', 'test',true,['id'=>snake_case($sitesettings->name)])."Test</label></div>";
                echo "<div class='label_siteadmin pull-left'><label>". Form::radio('value', 'live',['id'=>snake_case($sitesettings->name)])."Live</label></div>";
                
            }
            elseif($sitesettings->type == 'file')
            {
              echo  Form::file('image',array('class'=>'form-control','id'=>'image','accept'=>"image/*")) ;
              ?>
              <p><span>Image size should be larger than 200x200 </span></p>
              <span  style="color:red" id="image_error"></span>
              <p class="new_avatar"><img  src="<?php echo url()?>/uploads/share_image/{!! $sitesettings->value !!}" class="nav-avatar"></p>

            <?php 
            echo Form::hidden('share_icon',null,['class'=>'span8']);
            }
            ?>
        </div>
    </div>

    

    <div class="form-group form-row">
        <div class="col-sm-12 text-right">
            {!! Form::submit('Save', ['class' => 'btn btn-blue']) !!}
           
             <a href="{!! url('admin/homesetting')!!}" class="btn btn-ylw">Back</a>
           
        </div>
    </div>
        
    {!! Form::close() !!}

    <script>
        /*---------*/
        
         $( document ).ready(function() {       
          
          var _URL = window.URL || window.webkitURL;
         $("#image").change(function (e) {
             var file, img;
             if ((file = this.files[0])) {
                 img = new Image();
                 img.onload = function () {
                    if(this.width<200 || this.height<200)
                   {
                        $('#image').val(""); 
                        sweetAlert("Oops...", "Social image size should be greater than 200X200", "error");
                        //$('#image_error').html("Social image size should be greater than 200X200"); 
                   }
                   else
                   {
                         $('#image_error').html(""); 
                   }
                 };
                 img.src = _URL.createObjectURL(file);
             }
         });   
         })
</script>
@stop