            <?php
            if($serch_vendor!=''){
                ?>
                <div style="text-align:center;"><h4>Search result for vendor : {{$serch_vendor}}</h4></div>
                <?php
            }
            ?>
            <?php
            if(count($all_vendors)>0){
                ?>  

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
                                                
                                                <li><a href="<?php echo $vendor_url;?>" target="_blank"><?php echo ucfirst($value['advertiser-name']);?></a></li>
                                                
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
                                                
                                                <li><a href="<?php echo $vendor_url;?>" target="_blank"><?php echo ucfirst($value['advertiser-name']);?></a></li>
                                                
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
            