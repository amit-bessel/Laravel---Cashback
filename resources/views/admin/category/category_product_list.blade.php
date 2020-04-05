<?php $indx = isset($sl_no)?$sl_no:""; ?>
    <?php if(count($products) > 0){ ?>
    @foreach ($products as $v)
    <tr class="odd gradeX" hasProduct="Yes">
        <td><input type="checkbox" checked="checked" class="checkbox" name="check_product[]" id="all_<?php echo $v->id; ?>" value="<?php echo $v->id; ?>"><input type="hidden" id="count_total_product" name="count_total_product" value="{{$no_of_product}}" /></td>
        <td class="">{{ $indx }}</td>
        <td class="">{{ $v->name }}</td>
        <td class="">{{ $v['advertiser-name'] }}</td>
        <td class=""><img src="{{ $v->image_url }}" width="200px;" height="150px;" /></td>
        <td>
            <a href="javascript:void(0);" onclick="delete_product(<?php echo $v->id; ?>);"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
        </td>
    </tr>
    <?php $indx++; ?>
    @endforeach
    
    <?php }else{ ?> 
    <tr><td colspan="8">No Records.</td></tr>
<?php } ?> 