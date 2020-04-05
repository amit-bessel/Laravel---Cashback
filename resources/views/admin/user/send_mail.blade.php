<p>Hi, {!! $name !!}</p><br/>
<?php
	if($operation_mode=='ADD')
	{
	?>
	<p>Welcome to ClearDoc !!</p>
	<p>You have sucessfully registered with ClearDoc.</p>
	<?php
	}
	else
	{
	?>
	<p>You account info has been updated, please login to view.</p>
	<?php
	}
?>
<p>Login here:</p>
<p><?php echo url(); ?></p>
<p>You login credentials are:-</p>
<p>Email:- {!! $email !!}</p>
<p>Password:- {!! $user_pass !!}</p>
<br /><br />
<p>Thanks,<br />ClearDoc Team</p>
