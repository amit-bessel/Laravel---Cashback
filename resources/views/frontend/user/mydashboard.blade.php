@extends('../layout/frontend_template')
@section('content')

<div class="comn-main-wrap-inr">
<div class="newUser-dsboard-card ">

<div class="newUser-dsboard-content-wrap d-flex justify-content-center align-items-center">

<div class="newUser-dsboard-content text-center">

<div class="icon-holder">
<img src="<?php echo url('');?>/public/frontend/images/cashbag-img.png">
</div>

<p>It looks like you're just getting started. Why not try <span>inviting friends to earn commission,</span> or <span>shopping</span> through our portal to get cash back.</p>

<div class="btn-holder">
    <a href="<?php echo url();?>/user/invitefriendslist" class="btn btn-blue">Invite Friends</a>
    <a href="<?php echo url();?>/all-stores" class="btn btn-ylw">All Stores</a>
</div>

</div>

</div>

</div>
</div>

@stop