@extends('Admin.layouts.app')


@section('content')
<?php
// dd($data);

?>
 <main class="main-dashboard">
                    @if(Session::has('message'))
<p class="alert alert-info">{{ Session::get('message') }}</p>
@endif
        <section class="categories-list product-list user-list">
            <div class="recent-sales">
                <div class="input-search-box-container">
                    <h2 class="font-26">Products</h2>
                   
                </div>
                                          <div class="sorting_tab">
                <label for="sortSelect">Sort By:</label>
   <select id="sortSelect">
      <option value="name_asc">Name (Asc)</option>
    <option value="name_desc">Name (Desc)</option>
  </select>

</div>
                <div class="main-table">
        <table  id="yourDataTable" class="display table table-striped table-bordered">
            <thead>
        <tr>
               
                <th>Name of product</th>
                <th>Sku</th>
                <th>Stock</th>
                <th>Product Type</th>
                <th>Status</th>
                   <th>Sale price</th>
                      <th>Retail price</th>
                <th>Action</th> 
       
        </tr>
       </thead>
       <tbody>

        <tr>
        
               @foreach($data as $product)
        
          <td>{{$product->title}}</td>
             <td>{{$product->sku}}</td>
            <td>
<?php
if($product->current_stock!=0){
$stock = explode(',',$product->current_stock);
$stocks = array_unique($stock);
echo '<p><span style="color: #7ad03a;">In stock</span><p style="font-size: 14px;">(';echo implode(',',$stocks).')</p></p>';

}else{
    $stock = explode(',',$product->current_stock);
$stocks = array_unique($stock);
  echo '<p><span style="color: #a44;">stock out</span><p style="font-size: 14px;">(';echo implode(',',$stocks).')</p></p>';
}
?>
            </td>
           <td>{{$product->product_type}}</td>
          <td> <?php
               if($product->status == 'publish'){
                               echo '<span style="color: #34A854;">Published</span>';
                            }else{
                                echo '<span style="color: #F7B36B;">Draft</span>';
                            }

?></td>
          <td>@if($product->sales_price==null)

                <?php  echo "$0"?>
@else
 ${{$product->sales_price}}
@endif</td>

<td>${{$product->retail_price}}</td>

<td>
    <div>
       @if($product->product_type=='simple')
        <a href="{{url('admin/edit-product/'.$product->id)}}"  class="Edit-btn button">Edit</a>
        @else
          <a href="{{url('admin/edit-bundle-product/'.$product->id)}}"  class="Edit-btn button">Edit</a>
          @endif

       <a href="javascript:void(0)"  class="View-btn button" data-id="{{$product->id}}" id="delete">Delete</a> 
        @if($product->product_type=='simple')
       <a href="{{url('admin/single-product/'.$product->id)}}"  class="Delete-btn button">View</a>
        @else
          <a href="{{url('admin/bundle-product/'.$product->id)}}"  class="Delete-btn button">View</a>
          @endif
 
    </div>
</td>
    

   

         
      
        </tr>
         @endforeach
        </tbody>
        <tfooter></tfooter>

     
      </table>
                </div>
            </div>
        </section>
    </main>

<!--     <script type="text/javascript">
  $(function () {
    
    var table = $('.yajra-datatable').DataTable({
        processing: true,
        serverSide: true,
        ajax:{
            url:"{{ url('admin/get-product') }}",
            type: 'GET',
            },
                  
        columns: [
          {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {data: 'title', name: 'title'},
            {data: 'status', name: 'status'},
           
             
            
          {data: 'action', name: 'action', orderable: false, searchable: false},

          
        ]
    });
    
  });
</script> -->
  <script>
    $(document).ready(function() {
      var table = $('#yourDataTable').DataTable({
        "order": [[0, "asc"]] // Initial sorting by the first column (Name of product)
      });

      $('#sortSelect').change(function() {
        var selectedValue = $(this).val();
        var columnIndex;
        var sortDirection = 'asc';

        switch (selectedValue) {
          case 'name_asc':
            columnIndex = 0; // Index of the "Name of product" column
            break;
          case 'name_desc':
            columnIndex = 0; // Index of the "Name of product" column
            sortDirection = 'desc';
            break;
          default:
            columnIndex = 0; // Default to sorting by the first column (Name of product)
            break;
        }

        table.order([columnIndex, sortDirection]).draw(); // Sorting by the selected column and direction
      });
    });
  </script>
       
    
@endsection
