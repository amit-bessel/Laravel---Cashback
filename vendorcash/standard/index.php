<html>
        <head>
                <title>Paypal Standard</title>
        </head>
</html>
<div style="margin-bottom: 20px; font-weight: bold;"> Pay to  amit.unified@gmail.com </div>

<?php
$base_url = "http://www.phppowerhousedemo.com/webroot/team13/payment_gateway/paypal/standard/";
?>
<form  action="https://www.sandbox.paypal.com/cgi-bin/webscr" method="post" name="_xcart" id="payment_form">
        <input type=hidden name=cmd value="_cart" />
		<input type=hidden name=upload value="1">
                
        <input type="hidden" name="rm" value="2">
        <!--<input type="hidden" name="business" value="<?php echo $admin_details['paypal_busi_id'];?>">-->
        <input type="hidden" name="business" value="amit.unified@gmail.com">
        <input type="hidden" name="return" value="<?php echo $base_url;?>sucess_cancel.php?action=sucess">
        <input type="hidden" name="cancel_return" value="<?php echo $base_url;?>sucess_cancel.php?action=cancel">
        <input type="hidden" name="notify_url" value="<?php echo $base_url;?>/ipn.php">
        <input type="hidden" name="currency_code" value="USD" />
        <input type="hidden" name="item_name_1" id="item_name_1" value="Test1">
        <input type="hidden" name="quantity_1" id="quantity_1" value="2">
        <input type="hidden" name="amount_1" id="amount_1" value="5.00">
        <input type="hidden" name="item_name_2" id="item_name_2" value="Test2">
        <input type="hidden" name="quantity_2" id="quantity_2" value="1">
        <input type="hidden" name="amount_2" id="amount_2" value="5.00">
        <!--<input type="hidden" name="shipping" value="5.00">
	<input type="hidden" name="shipping2" value="3.00">-->
        <input type="hidden" name="handling_cart" id="handling_cart" value="2.00" >
        <input type="hidden" name="discount_amount_cart" id="discount_amount_cart" value="5.00" >
        <input type="hidden" name="custom" id="custom" value="2" >
        <input type="image" src="https://www.sandbox.paypal.com/en_US/i/btn/btn_buynowCC_LG.gif" border="0" name="submit">
</form>