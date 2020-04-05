@extends('admin/layout/admin_template')
 
@section('content')

  <?php //echo $faq_class;  exit; ?>
@if(Session::has('success'))
        <div class="alert alert-success">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <strong>{!! Session::get('success') !!}</strong>
        </div>
 @endif
 <style type="text/css">
     
     .btn-danger{ float: left; margin-left:5px;   }

 </style>
 
 <div class="btn-add-holder">
 <a href="{!!url('admin/faq/create')!!}" class="btn btn-ylw btn-add">Create Content</a>
</div>

   <div class="table-responsive">
                               
            <table cellpadding="0" cellspacing="0" border="0" class="table table-bordered table-striped  display" width="100%">
                <thead>
                    <tr>
                        <th>Sl No.</th>
                        <th>Category</th>
                        <th>Sub Category</th>
                        <th>Action</th>
                       
                    </tr>
                </thead>
                    
                    
                <tbody>
                    <?php $i=1; ?>
                    @foreach ($faqsubcategory as $each_faq)
                    <tr class="odd gradeX"> 
                        <td class=" sorting_1">
                            <?php echo $i; ?>
                        </td>
                        <td class=" ">

                            <?php 
                            //echo "<pre>";
                            //print_r($each_faq->faqrelation2);
                            ?>

                            <?php echo $each_faq->faqrelation2['name'];?>


                        </td>
                        <td class=" ">
                            {!! $each_faq->name !!}


                        </td>
                        
                        <td>
                            <a class="edit-icon" href="{!!route('admin.faqsubcategory.edit',$each_faq->id)!!}" style="float: left;"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                            <a href="{!!route('admin.faq.show',$each_faq->id)!!}" class="view-icon" style="float: left; margin-left: 5px;"><i class="fa fa-eye" aria-hidden="true"></i></a>
                            {!! Form::open(['method' => 'DELETE', 'route'=>['admin.faq.destroy', $each_faq->id]]) !!}
                            {!! Form::submit('Delete', ['class' => 'btn btn-danger', 'onclick'=>"return confirm('Are you sure you want to delete this category?');" ]) !!}
                            {!! Form::close() !!}
                        </td>
                        
                    </tr>
                    <?php $i++; ?>
                        @endforeach
                    </tbody>
                    
                </table>

      
        </div>

  <div><?php echo $faqsubcategory->render(); ?></div>
@endsection
