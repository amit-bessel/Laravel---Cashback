@extends('admin/layout/admin_template')
 
@section('content')

  <?php //echo $faq_class;  exit; ?>
@if(Session::has('success'))
        <div class="alert alert-success">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <strong>{!! Session::get('success') !!}</strong>
        </div>
 @endif
 <style type="text/css">
     
     .btn-danger{ float: left; margin-left:5px;  }
     table {
    table-layout: fixed;
    word-wrap: break-word;
}
 </style>
 <!--<a href="{!!url('admin/faqcategory/create')!!}" class="btn btn-success pull-right">Create Content</a>-->
 <hr>
 
   <div class="module">
                               
            <table cellpadding="0" cellspacing="0" border="0" class="table table-bordered table-striped  display" width="100%">
                <thead>
                    <tr>
                        <th style="width: 3%"> No</th>
                        <th style="width: 7%">Brand Key</th>
                        <th style="width: 7%">Brand Name</th>
                        <th style="width: 17%">Disclaimer</th>
                        <th style="width: 20%">Description</th>
                        <th style="width: 14%">Short Desc</th>
                         <th style="width: 10%">Terms</th>
                         <th style="width: 10%">Gift items</th>
                         <th style="width: 12%">Images</th>
                    </tr>
                </thead>
                    
                    
                <tbody>
                    <?php $i=1; ?>

                    <?php
                   // echo "<pre>";
                    //print_r($catalog_details);exit();
                    ?>


                    @foreach ($catalog_details->brands as $each_catalog)
                    <tr class="odd gradeX">
                        <td class=" sorting_1" style="width: 3%">
                            <?php echo $i; ?>
                        </td>
                        <td class=" " style="width: 7%">
                            {!! $each_catalog->brandKey !!}


                        </td>
                        
                        
                        <td class=" " style="width: 7%">
                            {!! $each_catalog->brandName !!}


                        </td>

                        <td class=" " style="width: 17%">
                            {!! $each_catalog->disclaimer !!}


                        </td>

                        <td class=" " style="width: 20%">
                            {!! $each_catalog->description !!}


                        </td>

                         <td class=" " style="width: 14%">
                            {!! $each_catalog->shortDescription !!}


                        </td>

                        <td class=" " style="width: 10%">
                            {!! $each_catalog->terms !!}


                        </td>


                        <td class=" " style="width: 10%">
                        <?php
                        foreach ($each_catalog->items as $key => $value) {
                            
                            ?>
                            <p>
                                <?php echo $value->rewardName;?>
                                <?php echo $value->currencyCode;?>
                                <?php
                                if(!empty($value->minValue)){ echo  "minvalue :".$value->minValue;}
                                ?>
                                <?php
                                if(!empty($value->maxValue)){ echo  "maxvalue :".$value->maxValue;}
                                ?>

                                 <?php
                                if(!empty($value->faceValue)){ echo  "facevalue :".$value->faceValue;}
                                ?>

                            </p>
                            <?php
                        }
                        ?>


                        </td>

                        <td class=" " style="width: 12%">
                           <?php

                           foreach ($each_catalog->imageUrls as $key => $value) {
                               # code...
                            ?>
                            <img src="<?php echo $value;?>" style="height: 60px; width: 100px; margin-bottom: 2px; ">
                            <?php
                           }

                           ?>


                        </td>
                       
                    </tr>
                    <?php $i++; ?>
                        @endforeach
                    </tbody>
                    
                </table>

      
        </div>

  
@endsection
