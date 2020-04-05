            
<?php
    if(count($all_vendors)>0){
            foreach ($all_vendors as $key => $each_value) {
            ?>
            <div class="singleletterlisting" id="<?php echo strtoupper($key);?>">
                
                <ul class="clearfix">
                <?php 
                    if($each_value['vendor_url'] != ''){
                        $vendor_url = $each_value['vendor_url'];
                    }else{
                        $vendor_url =  "javascript:void(0)";
                    }
                    ?>
                    <li><a href="<?php echo $vendor_url;?>" target="_blank"><?php echo ucfirst($each_value['advertiser-name']);?></a></li>
                </ul>
            </div>
            <?php
            }
            ?>
    <?php
    }
?>
            