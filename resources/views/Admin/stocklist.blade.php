@extends('Admin.layouts.app')


@section('content')

   <?php 

// dd($data);

    ?>
    <main class="main-dashboard">
        <section class="categories-list stock-reconciliation">
            <div class="recent-sales">
                <div class="input-search-box-container">
                    <h2 class="font-26">Stock Reconciliation</h2>
                    <!-- <input type="text" id="Search-bar" placeholder="Search"> -->
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
                <th>Name of Product</th>
               <th>Current Quantity</th> 
                <th>Adjustment</th>
                 <th>Actual Quantity</th> 
                 <th>Action</th> 
       
        </tr>
       </thead>
       <tbody>

        <tr>
         
               @foreach($data as $product)
          <?php 
          
            $stock = explode(',',$product->current_quantity);
$stocks = array_unique($stock);

$qaun = explode(',',$product->current_adjustment);
$qauns = array_unique($qaun);

$tot = explode(',',$product->current_stock);
$tots = array_unique($tot);
          ?>
          <td>{{$product->title}}</td>
       <td><?php echo implode(',',$qauns) ?></td>
                 
                      <td><?php echo implode(',',$stocks) ?></td>
                     <td><?php echo implode(',',$tots) ?></td>
          
        <td>    
                <button type="button" class="btn btn-info" data-toggle="modal" data-target="#form" data-id="{{$product->id}}" id="edit">
                    Edit
                  </button> 
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