@extends('admin/layout/admin_template')



@section('content')



<script type="text/javascript">

    $(document).ready(function(){



        $.validator.addMethod("email", function(value, element) 

        { 

        return this.optional(element) || /^[a-zA-Z0-9._-]+@[a-zA-Z0-9-]+\.[a-zA-Z.]{2,5}$/i.test(value); 

        }, "Please enter a valid email address.");





        $('#admin_edit_pro').validate({

            rules: {

                    firstname:
                    {
                            required:true,
                            
                            
                            pattern: /^[a-zA-Z ]*$/
                    },

                    lastname:
                    {
                            required:true,
                            
                            
                            pattern: /^[a-zA-Z ]*$/
                    },

                    email: {
                        required: true,
                        email: true,
                        "remote" :
                        {
                          url: "<?php echo url().'/admin/adminuserpreviousemail/check' ?>",
                          type: "post",
                          data:
                          {
                            email: function()
                            {
                              return $('#email').val();
                            },
                            hid_user_id: function(){
                              return $('#hid_user_id').val();
                            },
                            _token:'{{csrf_token()}}'
                          },
                        },
                      },

                },

                messages: {

                    firstname: "Only alphabets and spaces are allowed",

                    lastname: "Only alphabets and spaces are allowed",

                    name: "Please enter your name",

                    email: {
                    remote:"Email is already registered."
                    },

                }

        });

     });



$(document).ready(function()
{

   var _URL = window.URL || window.webkitURL;

   $("#image").change(function (e) {

   var file, img;
   var ValidImageTypes = ["image/gif","image/GIF", "image/jpeg","image/JPEG","image/jpg","image/JPG", "image/png", "image/PNG"];

    if ($.inArray(this.files[0].type, ValidImageTypes) < 0) 
    {
        alert($.inArray(this.files[0].type, ValidImageTypes));

        $('#image').val(""); 

        $('#image_error').html('You must upload an image');
    }

    else if ((file = this.files[0])) 
    {
       img = new Image();

       img.onload = function () {

        if(this.width<100 || this.height<100)

        {

          $('#image').val(""); 

          $('#image_error').html('Image dimension should be greater than 100X100');

        }

        else if(this.width>1000 || this.height>1000){

            $('#image').val(""); 

            $('#image_error').html('Image dimension should be less than 1000X1000');

        }

        else

        {

               $('#image_error').html(""); 

        }

    };

    img.src = _URL.createObjectURL(file);

    }

   });  
 });

</script>

    @if(Session::has('success'))

        <div class="alert alert-success">

            <button type="button" class="close" data-dismiss="alert">×</button>

            <strong>{!! Session::get('success') !!}</strong>

        </div>

    @endif

    



       

    {!! Form::model($user, array('method' => 'PATCH','route' => array('admin.home.update', $user->id),'files'=>true,'id'=>'admin_edit_pro','name'=>'admin_edit_pro')) !!}

    {!! Form::hidden('hid_user_id',$user->id,['class'=>'span8','id'=>'hid_user_id']) !!}

        <!-- <form class="form-horizontal row-fluid"> -->

        <div class="form-group form-row">

        <label class="col-sm-2 col-form-label" for="basicinput">First Name</label>



        <div class="col-sm-10">

             {!! Form::text('firstname',null,['class'=>'span8']) !!}

        </div>

        </div>


        <div class="form-group form-row">

        <label class="col-sm-2 col-form-label" for="basicinput">Last Name</label>



        <div class="col-sm-10">

             {!! Form::text('lastname',null,['class'=>'span8']) !!}

        </div>

        </div>



        <div class="form-group form-row">

            <label class="col-sm-2 col-form-label" for="basicinput">Email</label>



            <div class="col-sm-10">

                {!! Form::text('email',null,['class'=>'span8','id'=>'email']) !!}

            </div>

        </div>

        <!-- <div class="form-group form-row">

            <label class="col-sm-2 col-form-label" for="basicinput">Admin Icon</label>



            <div class="col-sm-10">

                {!! Form::file('image',array('class'=>'form-control','id'=>'image','accept'=>"image/*")) !!}

                <p id="image_error" style="color:red;"></p>

            </div>
            <?php if($user->admin_icon != ''){?>
            <p class="new_avatar"><img  src="<?php echo url()?>/uploads/admin_profile/{!! $user->admin_icon; !!}" class="nav-avatar"></p>
            <?php } ?>

            {!! Form::hidden('admin_icon',null,['class'=>'span8']) !!}

        </div> -->





        <div class="form-group form-row">

            <div class="col-sm-12 text-right">

                <!-- <button type="submit" class="btn">Submit Form</button> -->

                {!! Form::submit('Save', ['class' => 'btn btn-blue']) !!}

            </div>

        </div>

        {!! Form::close() !!}

 
    @stop