@extends('../layout/home_template')
@section('content')


<section class="inner-page">
  <div class="container">
    <div class="register-form news sell-watch">
        <h2><span>Frequently Asked Questions</span></h2>
        <div class="news-block">
        <ul>
        <?php
           // print_r($about_arr);
            foreach ($about_arr as $key => $value) {
                echo '<li><h4>'.$value['question'].'</h4><h5>'.$value['answer'].'</h5></li>';
            }
        ?>
        </ul>
        </div> 
       
    </div>
    </div>
</section>



@stop

