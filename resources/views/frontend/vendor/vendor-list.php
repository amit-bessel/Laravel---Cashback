@extends('../layout/frontend_template')
@section('content')

    <!-- maincontent -->
    <section class="maincontent">
        <div class="container">
            <!-- page-nameblock -->
            <div class="page-nameblock text-center">
                <ul class="breadcrumb-custom clearfix">                    
                    <li><a href="<?php echo url();?>">Home</a></li>                    
                    <li class="active"><a href="">Partners</a></li>
                </ul>
                <div class="common-headerblock text-center">
                    <h4 class="text-uppercase">DESIGNERS</h4>
                </div>
            </div>
            <!-- page-nameblock -->
            <div class="designer_boxes">
            <?php
           // print_r($all_vendors);exit;
            if(count($all_vendors)){

                foreach ($all_vendors as $key => $each_value) {
                    $new_created_slug = preg_replace('/[^\da-z]/i', '-', strtolower($each_value['advertiser-name']));
                    $new_created_slug=trim(preg_replace('/-+/', '-', $new_created_slug), '-');

                    if($each_value['vendor_url'] != ''){
                        $vendor_url = $each_value['vendor_url']'javascript:void(0);'
                    }else{
                        $vendor_url =  "javascript:void(0);"
                    }
            ?>
                
                   <div class="des_box">
                        <div class="des_head"><?php echo strtoupper($each_value['advertiser-name'][0]);?></div>
                        <div></div>
                        <div class="des_body">
                            <ul>
                                <li><a href="<?php echo $vendor_url;?>">{{$vendor_url}}<?php echo strtoupper($each_value['advertiser-name']);?>11</a></li>
                             </ul>
                        </div>
                    </div>                     
                
            <?php
                }
            }
            ?>
            </div>
        </div>
    </section>
    <!-- maincontent -->
@stop