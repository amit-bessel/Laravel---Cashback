<?php $indx = isset($sl_no)?$sl_no:""; ?>
<?php 
    if(count($products) > 0){ ?>
    @foreach ($products as $v)
    <tr class="odd gradeX" hasProduct="Yes">
        <td class="">{{ $indx }}<input type="hidden" id="count_total_product" name="count_total_product" value="{{$no_of_product}}" />
        </td>
        <td class=""><input type="checkbox" class="checkbox" name="check_product[]" id="all_<?php echo $v->id; ?>" value="<?php echo $v->id; ?>"></td>
        <td class="">{{ $v->name }}</td>
        <td class="">{{ $v['advertiser-name'] }}</td>
        <td class="">{{ $v->gender }}</td>
        <td class=""><img width="150px" src="{{ $v->image_url}}"></td>
        
    </tr>
    <?php $indx++; ?>
    @endforeach
    <?php }

    else{ ?> 
    <tr><td colspan="8">No Records.</td></tr>
<?php } ?>