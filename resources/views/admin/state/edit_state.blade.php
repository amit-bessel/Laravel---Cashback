{!! HTML::script('resources/assets/js/ckeditor/ckeditor.js') !!} 

@extends('admin/layout/admin_template')

@section('content')

<!-- jQuery Form Validation code -->
  <script>
  
  // When the browser is ready...
  $(function() {
  
    // Setup form validation on the #register-form element
    $("#cms_form").validate({
        
        ignore: [],
        // Specify the validation rules
        rules: {
            country_id: "required",
			name: "required",
			hid_frm_submit_res:{
				equalTo:"#hid_validate_res",
			},
        },
        
        // Specify the validation error messages
        messages: {
            hid_frm_submit_res: ""
        },               

        submitHandler: function(form) {
            form.submit();
        }
    });

  });
  
    
    function validate_city_info(){
		var base_url 	= "<?php echo url(); ?>/";
        var country_id 	= $('#country_id').val();
        var name 		= $('#name').val();
		var state_id	= '<?php echo $state_details->id ?>';
		var frm_response= 1;
		
       
        if(country_id!=''  && name!=''){
			//alert(city_id+"   "+base_url+'admin/check-city-availability');
			$.ajax({
				type	: 'GET',
				url		: base_url+'admin/check-state-availability',
				data	: {country_id: country_id,state_id: state_id,name: name},
				async	: false,
				success	: function(response){
					//alert(response);
					if (response==1) {
						$('#name_msg').text('State already exists.').css( "color", "red" );
						frm_response = 0;
					}
					else{
						 $('#name_msg').text('');
						 $('#hid_validate_res').val(1);
					}
					
				}
			});
		}
      
    }
  </script>

        {!! Form::open(['url' => 'admin/Edit-state/'.$country_id.'/'.$state_details->id,'method'=>'POST', 'files'=>true,'class'=>'form-horizontal row-fluid','id'=>'cms_form','onsubmit'=>'return validate_city_info();']) !!}
            
            <input type="hidden" name="hid_validate_res" id="hid_validate_res" value="0" />
			<input type="hidden" name="hid_frm_submit_res" id="hid_frm_submit_res" value="1" />
           <div class="control-group" style="display:block;">
                <label class="control-label" for="basicinput">Country Name</label>
                <div class="controls">
                    <select name="country_id" id="country_id" disabled>
                        <option value="" >Select Country</option>
                        <?php foreach($countries as $row){ ?>
                     <option value="<?php echo $row->id;?>" <?php echo ($country_id==$row->id)?'selected="selected"':''; ?>><?php echo $row->name;?></option>
                        <?php } ?>
                        
                    </select>
                     <span id="country_id_msg" style=""></span>
                </div>
            </div>
			
            <div class="control-group" style="display:block;">
                <label class="control-label" for="basicinput">State Name</label>
                <div class="controls">
                    <input type="text" name="name" id="name" value="<?php echo $state_details->name;?>" class="span8">
                     <span id="name_msg"></span>
                </div>

            </div>

           <div class="control-group">
                <div class="controls">
                    {!! Form::submit('Save',array('class'=>'btn','name'=>'action','value'=>'save')) !!}
                    <a href="{!! url('admin/state-list/'.$country_id)!!}" class="btn">Back</a>
                </div>
            </div>
                
        
        {!! Form::close() !!}
    @stop
    
    