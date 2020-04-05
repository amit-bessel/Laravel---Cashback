@extends('../layout/template')
@section('content')

<section class="maincontent">
  <div class="container">
    <div class="about-page pd-0">
      <h2 class="text-center">Contact Us</h2>
      <h3 class="text-center">if you have any questions please feel free to contact us.</h3>
      <div class="row">
        <div class="col-sm-6 col-md-8">
          <h4 class="contact-heading">Send a message</h4>
          <div class="contact-form-body">

          @if(Session::has('success_message'))
            <p class="alert {{ Session::get('alert-class', 'alert-success') }}">{{ Session::get('success_message') }}</p>
            @endif
            
            @if(Session::has('failure_message'))
            <p class="alert {{ Session::get('alert-class', 'alert-danger') }}">{{ Session::get('failure_message') }}</p>
            @endif

            {!! Form::open(['url' => 'submit-contact-us','method'=>'POST', 'files'=>true,'class'=>'form-horizontal row-fluid','id'=>'contact_form']) !!}

              <div class="col-sm-12">
              <div class="form-group">
               <label>Name</label>
                {!! Form::text('name',null,['class'=>'form-control','id'=>'first_name','required'=>true,'placeholder'=>'Name']) !!}
                
              </div>
              </div>
              <div class="col-sm-12 col-md-6">
              <div class="form-group">
                <label>Phone Number</label>
                {!! Form::text('phone',null,['class'=>'form-control','id'=>'contact','required'=>true,'placeholder'=>'Phone Number']) !!}
              </div>
              </div>
              <div class="col-sm-12 col-md-6">
              <div class="form-group">
                <label>Email Address</label>
                {!! Form::text('email',null,['class'=>'form-control ','id'=>'email','required'=>true,'placeholder'=>'Email Address']) !!}
              </div>
              </div>
              <div class="col-sm-12">
              <div class="form-group">
                <label>Message</label>
                {!! Form::textarea('message',null,['class'=>'form-control','id'=>'message','required'=>true,'placeholder'=>'Message']) !!}
                
              </div>
              </div>
               <div class="col-sm-12">
                 <div class="form-group">
                 {!! Form::submit('Send Message',array('class'=>'btn register-btn','name'=>'action','value'=>'Submit')) !!}
                  
                </div>
              </div>
            </form>
          </div>
        </div>
        <div class="col-sm-6 col-md-4">
          <h4 class="contact-heading">Contact Info</h4>
          
          <div class="contact-address">
            
            <div class="row address-cell">
              <div class="col-xs-2">
                <i class="fa fa-envelope" aria-hidden="true"></i>
              </div>
              <div class="col-xs-10">
                <h3>E-mail</h3>
                  <p><a href="mailto:{{$site_email}}">{{$site_email}}</a></p>
              </div>
            </div>

           <!--  <div class="row address-cell">
              <div class="col-xs-2">
                <i class="fa fa-map-marker" aria-hidden="true"></i>
              </div>
              <div class="col-xs-10">
                  <h3>Address</h3>
                  <p>{{$site_address}}</p>
              </div>
            </div>
            <div class="row address-cell">
              <div class="col-xs-2">
                <i class="fa fa-phone" aria-hidden="true"></i>
              </div>
              <div class="col-xs-10">
                  <h3>Phone</h3>
                  <p>
                        <a href="tel:{{$site_contact}}">+{{$site_contact}} </a>
                  </p>
              </div>
            </div> -->
            
          </div>
        </div>
      </div>
      
      
    </div>
  </div>
</section>

<!-- 
<script type="text/javascript">
$(document).ready(function () {
function initialize() {
 
// Define the latitude and longitude positions
var latitude = parseFloat("<?php echo $latitude; ?>"); // Latitude get from above variable
var longitude = parseFloat("<?php echo $longitude; ?>"); // Longitude from same
var latlngPos = new google.maps.LatLng(latitude, longitude);
 
// Set up options for the Google map
var myOptions = {
  zoom: 15,
  center: latlngPos,
  mapTypeId: google.maps.MapTypeId.ROADMAP,
  zoomControlOptions: true,
zoomControlOptions: {
  style: google.maps.ZoomControlStyle.LARGE
}
};
// Define the map
map = new google.maps.Map(document.getElementById("display_map"), myOptions);
 
addMarker(latlngPos, 'Default Marker', map);
  
  google.maps.event.addListener(map, 'dragstart', function(event) {
  //infowindow.open(map,marker);
      addMarker(event.latlngPos, 'Click Generated Marker', map);
    
     var lat, lng, address;
                    
  });
 
   
}
  function addMarker(latlng,title,map) {
    var marker = new google.maps.Marker({
            position: latlng,
            map: map,
            title: title,
      icon:'public/frontend/images/map-marker.png',
            draggable:true,
       animation: google.maps.Animation.DROP
    });
 
    google.maps.event.addListener(marker,'drag',function(event) {
        document.getElementById('lat').value = event.latLng.lat();
        document.getElementById('lng').value= event.latLng.lng();
    });
 
    google.maps.event.addListener(marker,'dragend',function(event) {
        document.getElementById('lat').value = event.latLng.lat();
        document.getElementById('lng').value = event.latLng.lng();
    alert(marker.getPosition());
    });
     google.maps.event.addListener(map, 'zoom_changed', function () {
      document.getElementById('zoom').value =map.getZoom();
    });
  
}
google.maps.event.addDomListener(window, 'load', initialize);
});
</script>

 -->

@stop
  