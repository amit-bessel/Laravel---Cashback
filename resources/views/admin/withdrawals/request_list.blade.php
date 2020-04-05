@extends('admin/layout/admin_template')
 
@section('content')

<style>
.country input{margin-right:0px;}
#search_key{
    margin-right:10px;
}
.country select{margin-right:10px;}

.country .btn{
        position: relative;
    top: -5px;
}

.country tr{
    float:right;
    width:100%;

}
</style>
  
@if(Session::has('success_message'))
<p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('success_message') }}</p>
@endif

@if(Session::has('failure_message'))
<p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('failure_message') }}</p>
@endif

<script type="text/javascript">
function goForSearch(){
    var start_date = $('#start-date').val();
    var end_date = $('#end-date').val();
    var search_by = $('#search_by').val();

    var url = '<?php echo url(); ?>/admin/user/withdrawls-requests?';
    url += 'start_date='+start_date+'&end_date='+end_date+'&search_by='+search_by;
    window.location.href = url;
}
</script>


<link href="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.4.6/bootstrap-editable/css/bootstrap-editable.css" rel="stylesheet"/>
<script src="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.4.6/bootstrap-editable/js/bootstrap-editable.min.js"></script>



<div class="country">
    <table cellpadding="0" cellspacing="0" border="0" width="100%"> 
        <tr>
           <td widht="50%">
            <div id="date_range">
               <input type='text' class="search_key" placeholder="Start Date" id='start-date' id='start-date' class="form-control" value="{{$start_date}}" style="background-color:transparent;width:170px;" readonly/>
               <input type='text' class="search_key"  id='end-date' id='end-date' placeholder="End Date" class="form-control" value="{{$end_date}}" readonly style="background-color:transparent;width:170px;" />
                <select name="search_by" id="search_by" class="field">
                    <option value="">Pay By</option>
                    <option value="1" <?php echo $search_by=='1' ? "selected='selected'" : ""; ?>>Stripe Pay</option>
                    <option value="2" <?php echo $search_by=='2' ? "selected='selected'" : ""; ?>>Paypal Pay</option>
                    <option value="3" <?php echo $search_by=='3' ? "selected='selected'" : ""; ?>>Bank Pay</option>
                </select>            </div>
         </td>
        </tr>
        <tr>
            <td width="30%" style="padding:0px;">
                <input type="button" onclick="goForSearch();" class="btn" name="btn_search_hotel" id="btn_search_hotel" value="Search">
                <input type="button" onclick="window.location.href='<?php echo url(); ?>/admin/user/withdrawls-requests';" class="btn" name="btn_search_hotel" id="btn_search_hotel" value="Reset" />
            </td>
        </tr>
    </table>
</div>

<div class="module">

    <table cellpadding="0" cellspacing="0" border="0" class="table table-bordered table-striped  display user-datatable" width="100%">
        <thead>
            <tr style="color:#FFFFFF; background:#444;">
                <th width="3%">Sl No.</th> 
                <th width="30%">User</th>
                <th width="15%">Amount</th>
                <th width="30%">Date & Time</th>
                <th width="15%" align="center">Pay By</th>
            </tr>
        </thead>

        <tbody>
            <?php $indx = $sl_no ?>
            <?php if(count($all_tranctions) > 0){ ?>
            @foreach ($all_tranctions as $v)
                <tr class="odd gradeX">
                <td class="">{{ $indx }}</td>
                <td class="">{{ $v['name'].' '.$v['last_name'] }}</td>
                <td class="">{{ $v['amount'] }}</td>
                <td class="" style="text-align:center;">
                    {{date('d-m-Y H:i:s',strtotime($v['created_at']))}}
                </td>
               <td class="" style="text-align:center;">
                @if($v['send_to']==1)
                    <a href="<?php echo url(); ?>/admin/user/pay/{{ base64_encode($v['id']) }}" class="btn btn-warning">Stripe Pay</a>
                @elseif($v['send_to']==2)
                    <form  action="https://www.sandbox.paypal.com/cgi-bin/webscr" method="post" name="_xcart" id="payment_form">
                        <input type=hidden name=cmd value="_cart" />
                        <input type=hidden name=upload value="1">
                                
                        <input type="hidden" name="rm" value="2">
                        <input type="hidden" name="business" value="{{$v['paypal_email']}}">
                        <input type="hidden" name="return" value="<?php echo url();?>/admin/user/paypal/return-url/<?php echo base64_encode($v['id']);?>?action=sucess">
                        <input type="hidden" name="cancel_return" value="<?php echo url();?>/admin/user/paypal/return-url/<?php echo base64_encode($v['id']);?>?action=cancel">
                        <input type="hidden" name="notify_url" value="<?php echo url();?>/admin/user/paypal/notify-url/<?php echo base64_encode($v['id']);?>">
                        <input type="hidden" name="currency_code" value="USD" />
                        <input type="hidden" name="item_name_1" id="item_name_1" value="Frontmood Cashback">
                        <input type="hidden" name="quantity_1" id="quantity_1" value="1">
                        <input type="hidden" name="amount_1" id="amount_1" value="{{$v['amount']}}">
                        <input type="submit" name="" class="btn btn-warning paypalBtn" value="Paypal Pay">
                    </form>
                    <!-- <a href="javascript:void(0)" onClick='submitDetailsForm()' class="btn btn-warning paypalBtn">Paypal Pay</a> -->
                @elseif($v['send_to']==3)
                    <a href="<?php echo url(); ?>/admin/user/pay/{{ base64_encode($v['id']) }}" class="btn btn-warning">Bank Pay</a>
                @endif
                </td>
                
            </tr>
            <?php $indx++; ?>
            @endforeach
            <?php }else{ ?> 
            <tr><td colspan="8">No Records.</td></tr>
            <?php } ?>
               
        </tbody>           
    </table>    
</div>
{!! $all_tranctions->appends(['start_date'=>$start_date,'end_date'=>$end_date])->render() !!}

<link href="{{url()}}/public/backend/css/bootstrap-datepicker.css" rel="stylesheet">
<script type="text/javascript" src="{{url()}}/public/backend/js/bootstrap-datepicker.js"></script>
<script type="text/javascript">
    function submitDetailsForm(){
       $("#payment_form").submit();
    }
    $(document).ready(function(){
        
        $("#start-date").datepicker({
           endDate: "<?php echo date('m-d-Y');?>",
       }).on('changeDate', function (selected) {
               var minDate = new Date(selected.date.valueOf());
               $('#end-date').datepicker('setStartDate', minDate);
               $('#start-date').datepicker('hide');
       });
       $("#end-date").datepicker({
           endDate: "<?php echo date('m-d-Y');?>",
       }).on('changeDate', function (selected) {
           var maxDate = new Date(selected.date.valueOf());
            $('#start-date').datepicker('setEndDate', maxDate);
            $('#end-date').datepicker('hide');
       });
   });
</script>
@endsection
