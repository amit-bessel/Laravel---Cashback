@extends('../layout/frontend_template')
@section('content')

    <!-- maincontent -->
    <section class="maincontent">
        <div class="container">
            <div class="top-partnerimage">
                <img src="<?php echo url(); ?>/public/frontend/images/vendorimage.jpg" alt="">
            </div>
            <div id="maincategories" class="clearfix">

                <select>
                    <option>AZ</option>
                    @if (count($stores_info) > 0)
                        @foreach ($stores_info as $stores)
                            <option>{{$stores['name']}}</option>
                        @endforeach
                    @endif               
                </select>

                
                <ul>
                    <li class="active"><a href=""><span class="only-text">AZ</span><span class="simple-text">All our {{$total_vendors}} affiliated stores</span></a></li>
                    @if (count($stores_info) > 0)
                        @foreach ($stores_info as $stores)
                            <li><a href="javascript:void(0);" onclick="serchVendor({{$stores['id']}})"><span class="for-icon"><i class="{{$stores['icon']}}" aria-hidden="true"></i></span><span class="simple-text">{{$stores['name']}}</span></a></li>
                        @endforeach
                    @endif
                </ul>
            </div>
            <div class="search-box text-center">
              <div class="search-cont">
                    <input type="text" class="form-control" name="serch_vendor" id="serch_vendor" />
                    <button type="button" class="vendor-search" onclick="serchVendor()"><i class="fa fa-search" aria-hidden="true"></i></button>
              </div>  
            </div>
            <div class="totalatoz clearfix" id="totalAtoZ">
                <span>Go To</span>
                <ol>
                    <li><a href="javascript:void(0);" data-goTo="A">A</a></li>
                    <li><a href="javascript:void(0);" data-goTo="B">B</a></li>
                    <li><a href="javascript:void(0);" data-goTo="C">C</a></li>
                    <li><a href="javascript:void(0);" data-goTo="D">D</a></li>
                    <li><a href="javascript:void(0);" data-goTo="E">E</a></li>
                    <li><a href="javascript:void(0);" data-goTo="F">F</a></li>
                    <li><a href="javascript:void(0);" data-goTo="G">G</a></li>
                    <li><a href="javascript:void(0);" data-goTo="H">H</a></li>
                    <li><a href="javascript:void(0);" data-goTo="I">I</a></li>
                    <li><a href="javascript:void(0);" data-goTo="J">J</a></li>
                    <li><a href="javascript:void(0);" data-goTo="K">K</a></li>
                    <li><a href="javascript:void(0);" data-goTo="L">L</a></li>
                    <li><a href="javascript:void(0);" data-goTo="M">M</a></li>
                    <li><a href="javascript:void(0);" data-goTo="N">N</a></li>
                    <li><a href="javascript:void(0);" data-goTo="O">O</a></li>
                    <li><a href="javascript:void(0);" data-goTo="P">P</a></li>
                    <li><a href="javascript:void(0);" data-goTo="Q">Q</a></li>
                    <li><a href="javascript:void(0);" data-goTo="R">R</a></li>
                    <li><a href="javascript:void(0);" data-goTo="S">S</a></li>
                    <li><a href="javascript:void(0);" data-goTo="T">T</a></li>
                    <li><a href="javascript:void(0);" data-goTo="U">U</a></li>
                    <li><a href="javascript:void(0);" data-goTo="V">V</a></li>
                    <li><a href="javascript:void(0);" data-goTo="W">W</a></li>
                    <li><a href="javascript:void(0);" data-goTo="X">X</a></li>
                    <li><a href="javascript:void(0);" data-goTo="Y">Y</a></li>
                    <li><a href="javascript:void(0);" data-goTo="Z">Z</a></li>
                </ol>
            </div>
            <!-- all-listing -->
            <!-- <select onchange="sortVendorByCashback(this.value)">
                <option value="name">Adviser Name</option>
                <option value="cashback">Cashback</option>
            </select> -->
            <?php
            if(count($all_vendors)>0){
                ?>
                <div class="all-listing" id="vendor_list">
                    <?php
                    foreach ($all_vendors as $key => $each_value) {
                        if(!empty($each_value)){
                            if($key != 'special'){
                                ?>
                                <div class="singleletterlisting" id="<?php echo strtoupper($key);?>">
                                    <h3><?php echo strtoupper($key);?></h3>
                                    <ul class="clearfix">
                                        <?php 
                                
                                            foreach ($each_value as $key1 => $value) {
                                                if($value['vendor_url'] != ''){
                                                    $vendor_url = $value['vendor_url'];
                                                }else{
                                                    $vendor_url =  "javascript:void(0)";
                                                }
                                                ?>
                                                
                                                <li><a href="<?php echo url();?>/stores/{{$value['id']}}"><?php echo ucfirst($value['advertiser-name']);?></a></li>
                                                
                                            <?php
                                            }
                                        ?>
                                    </ul>
                                </div>
                                <?php
                            }
                            else{
                                ?>
                                <div class="singleletterlisting" id="<?php echo strtoupper($key);?>">
                                    <h3><?php echo strtoupper($key);?></h3>
                                    <ul class="clearfix">
                                        <?php 
                                
                                        foreach ($each_value as $key1 => $value) {
                                            if(!preg_match("/[a-z]/i", $value['advertiser-name'][0])){ 
                                                if($value['vendor_url'] != ''){
                                                    $vendor_url = $value['vendor_url'];
                                                }else{
                                                    $vendor_url =  "javascript:void(0)";
                                                }
                                                ?>
                                                
                                                <li><a href="<?php echo url();?>/stores/{{$value['id']}}" target="_blank"><?php echo ucfirst($value['advertiser-name']);?></a></li>
                                                
                                                <?php
                                            }
                                        }
                                    ?>
                                    </ul>
                                </div>
                                <?php
                            }
                        }
                    }
                    ?>
                <?php
            }
            ?>
            
        </div>
    </section>
    <!-- maincontent -->
    <script type="text/javascript">
        var cat_id = '';
        function serchVendor(id){
            if(id != null){
                cat_id = id;
            }

            $('.cs-loader').removeClass('hideloader');
            var serch_vendor = $('#serch_vendor').val();

                $.ajax({
                    type    : "GET",
                    url     : "<?php echo url() ?>/search-vendor",
                    data    : "cat_id="+cat_id+"&serch_vendor="+serch_vendor+"&_token=<?php echo csrf_token(); ?>",
                    dataType: "HTML",
                    async   : true,
                    success : function(response,tedst,xhr){
                        $('#vendor_list').html(response);
                        $('.cs-loader').addClass('hideloader');
                        $('#totalAtoZ').show();
                    }
                });
            
        }

        function sortVendorByCashback(value){
            if(value == 'cashback'){
                $('.cs-loader').removeClass('hideloader');
                $.ajax({
                    type    : "GET",
                    url     : "<?php echo url() ?>/sort-vendor-by-cashback",
                    data    : "value="+value+"&_token=<?php echo csrf_token(); ?>",
                    dataType: "HTML",
                    async   : true,
                    success : function(response,tedst,xhr){
                        $('#vendor_list').html(response);
                        $('.cs-loader').addClass('hideloader');
                        $('#totalAtoZ').hide();
                    }
                });
            }else{
                serchVendor();
            }
        }

        function searchStoreCat(id){

            $('.cs-loader').removeClass('hideloader');
            $.ajax({
                type    : "GET",
                url     : "<?php echo url() ?>/sort-vendor-by-category",
                data    : "value="+id+"&_token=<?php echo csrf_token(); ?>",
                dataType: "HTML",
                async   : true,
                success : function(response,tedst,xhr){
                    $('#vendor_list').html(response);
                    $('.cs-loader').addClass('hideloader');
                }
            });
        }
    </script>
@stop