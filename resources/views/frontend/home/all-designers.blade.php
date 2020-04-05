@extends('../layout/frontend_template')
@section('content')

    <!-- maincontent -->
    <section class="maincontent">
        <div class="container">
            <!-- page-nameblock -->
            <div class="page-nameblock text-center">
                <ul class="breadcrumb-custom clearfix">                    
                    <li><a href="#">Home</a></li>                    
                    <li class="active"><a href="#">Designers</a></li>
                </ul>
                <div class="common-headerblock text-center">
                    <h4 class="text-uppercase">DESIGNERS</h4>
                </div>
            </div>
            <!-- page-nameblock -->
            <div class="designer_boxes">
            <?php
            //print_r($pageindex);exit;
            if(count($pageindex)){

                foreach ($pageindex as $key => $value) {
                    if(!empty($value)){
            ?>
                
                    <div class="des_box">
                        <div class="des_head"><?php echo strtoupper($key);?></div>
                        <div></div>
                        <div class="des_body">
                            <ul>
                            <?php 
                            //echo '<pre>';print_r($value);
                                
                            foreach ($value as $key => $each_value) {
                                $new_created_slug = preg_replace('/[^\da-z]/i', '-', strtolower($each_value->brand_name));
                                $new_created_slug=trim(preg_replace('/-+/', '-', $new_created_slug), '-');
                            ?>
                               
                                <li><a href="<?php echo url();?>/search/products?search_by={{$each_value->id}}&header_serach_by={{$each_value->brand_name}}"><?php echo strtoupper($each_value->brand_name);?></a></li>
                               
                            <?php
                            }
                                
                            ?>
                             </ul>
                        </div>
                    </div>                    
                
            <?php
                   }
                }
            }


            ?>
            </div>
        </div>
    </section>
    <!-- maincontent -->
@stop