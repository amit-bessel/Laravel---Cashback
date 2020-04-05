@extends('../layout/home_template')
@section('content')



<div class="site-main-container">
  <div class="container">

<div class="site-main-heading cms-site-main-heading">
<h2>{{ $about_arr['page_title'] }}</h2>
</div>

   <div class="site-main-content cms-content">

<!--    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. In auctor posuere sem, vitae molestie nisi condimentum non. Nunc sollicitudin, purus eget pretium pharetra, ante purus mollis risus, eu consectetur turpis eros ac mi. Duis aliquet vestibulum venenatis. Vivamus at pretium lectus. Sed justo metus, luctus eu porttitor et, scelerisque sit amet magna. Vivamus vitae vestibulum ex, et facilisis odio.</p>

     <p>Nullam mattis massa quis mauris varius, id efficitur elit commodo. Praesent facilisis enim id velit ultrices ornare. Ut faucibus lacus a elit aliquet volutpat. Maecenas in pharetra mauris. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Morbi ut odio turpis. </p>

        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. In auctor posuere sem, vitae molestie nisi condimentum non. Nunc sollicitudin, purus eget pretium pharetra, ante purus mollis risus, eu consectetur turpis eros ac mi. Duis aliquet vestibulum venenatis. Vivamus at pretium lectus. Sed justo metus, luctus eu porttitor et, scelerisque sit amet magna. Vivamus vitae vestibulum ex, et facilisis odio.</p>

     <p>Nullam mattis massa quis mauris varius, id efficitur elit commodo. Praesent facilisis enim id velit ultrices ornare. Ut faucibus lacus a elit aliquet volutpat. Maecenas in pharetra mauris. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Morbi ut odio turpis. </p>


     <div class="about-info-block-sec">
<p>Proin nec ex eget urna ullamcorper dictumurabitur id dignissim dui</p>
<div class="btn-holder">
<a href="#" class="btn btn-ylw">How It Works</a>
</div>

     </div>

     <h2 class="heading-1">Why Choose Us</h2>

     <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. In auctor posuere sem, vitae molestie nisi condimentum non. Nunc sollicitudin, purus eget pretium pharetra, ante purus mollis risus, eu consectetur turpis eros ac mi. Duis aliquet vestibulum venenatis. Vivamus at pretium lectus. Sed justo metus, luctus eu porttitor et, scelerisque sit amet magna. Vivamus vitae vestibulum ex, et facilisis odio.</p>

     <p>Nullam mattis massa quis mauris varius, id efficitur elit commodo. Praesent facilisis enim id velit ultrices ornare. Ut faucibus lacus a elit aliquet volutpat. Maecenas in pharetra mauris. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Morbi ut odio turpis. </p> -->

          <?php 
            echo htmlspecialchars_decode(htmlspecialchars_decode($about_arr['description_eng']));
             // echo $about_arr[0]['description'];
            ?>
    </div>
  </div>
</div>



@stop

