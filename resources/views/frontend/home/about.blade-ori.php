@extends('../layout/template')
@section('content')


<section class="inner-page">
  <div class="container">
    <div class="register-form news sell-watch">
        <h2><span>{{ $about_arr['page_title'] }}</span></h2>
        <div class="news-block">
        <?php 
        echo htmlspecialchars_decode(htmlspecialchars_decode($about_arr['description_eng']));
         // echo $about_arr[0]['description'];
        ?>

         
        </div> 
       
    </div>
    </div>
</section>



@stop

