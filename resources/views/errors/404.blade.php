<div class="inner_page_container nopage_back">
    	<div class="header_panel">
        	<div class="container">
        	 <h2>404 Page</h2>
            </div>
        </div>   
<!-- Start Products panel -->
<div class="products_panel">
	<div class="container">
    	<div class="table_col">
            <div class="col-sm-6 left_four">
                <div class="surr_notfound">
                <div class="oops_div">Oops!</div>
                <div class="semititle">This page cannot be found</div>
                <p class="top_p">Make sure the URL is entered correctly.</p>
                <?php
                    $get_url = $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
                    $segment_array = explode('/',$get_url);
                    if(in_array('admin', $segment_array)){
                        ?>
                        <a class="return_btn" href="<?php echo url();?>/admin">Return to homepage</a>
                        <?php
                    }
                    else{
                        ?>
                        <a class="return_btn" href="<?php echo url();?>">Return to homepage</a>
                        <?php
                    }
                ?>
                
                <p>or try searching what you are looking for</p>
               
                </div>
            </div>
            <div class="col-sm-6 right_four"><img alt="" src="<?php echo url();?>/public/frontend/images/404page/404img.png"></div>
        </div>
    </div>
</div>
<!-- End Products panel --> 
 </div>
