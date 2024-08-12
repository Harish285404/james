@extends('Admin.layouts.app')


@section('content')
<?php
// dd($data);

?>
 <main class="main-dashboard">
 
        <section class="categories-list product-list user-list">
            <div class="recent-sales">
                <div class="input-search-box-container">
                    <h2 class="font-26">Invoice list</h2>
                   
                </div>
                <div class="sorting_tab">
                <label for="sortSelect">Sort By:</label>
  <select id="sortSelect">
         <option value="date_desc">Date (Desc)</option>
   <option value="name_asc">Name (Asc)</option>
    <option value="name_desc">Name (Desc)</option>
    <option value="business_asc">Business Name (Asc)</option>
    <option value="business_desc">Business Name (Desc)</option>
    <option value="date_asc">Date (Asc)</option>
    <!-- Add more options as needed for other columns -->
  </select>
</div>
                <div class="main-table">
        <table  id="yourDataTable" class="display table table-striped table-bordered">
            <thead>
        <tr>
              
                <th>Name</th>
                <th>Business Name</th>
                <th>Email</th>
                <th>Address</th>
               <th>Price</th>        
                <th>Date</th>
            
                <th>Action</th>
        
       
        </tr>
       </thead>
       <tbody>
 
               @foreach($data as $product)
        <tr>
            
          
          <td>{{$product['name'] }}</td>
                <td>{{$product['bussiness_name'] }}</td>
                 <td>{{$product['email'] }}</td>
                   <td>{{$product['address'] }}</td>
                   <td>${{$product['total'] }}</td>
                     <td>{{$product['date'] }}</td>
               
   
                     <td>
                  <a href="{{url('admin/invoice-pdf/'.$product['quote_id'] )}}" type="button" class="btn btn-info">
   Download Invoice
  </a> 
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
        "order": [[5, "desc"]] // Initial sorting by the first column (Name)
      });

      $('#sortSelect').change(function() {
        var selectedValue = $(this).val();
        var columnIndex;
        var sortDirection = 'asc';

        switch (selectedValue) {
          case 'name_asc':
            columnIndex = 0; // Index of the "Name" column
            break;
          case 'name_desc':
            columnIndex = 0; // Index of the "Name" column
            sortDirection = 'desc';
            break;
          case 'business_asc':
            columnIndex = 1; // Index of the "Business Name" column
            break;
          case 'business_desc':
            columnIndex = 1; // Index of the "Business Name" column
            sortDirection = 'desc';
            break;
          case 'date_asc':
            columnIndex = 5; // Index of the "Date" column
            break;
          case 'date_desc':
            columnIndex = 5; // Index of the "Date" column
            sortDirection = 'desc';
            break;
          default:
            columnIndex = 5; // Default to sorting by the "Date" column
            break;
        }

        table.order([columnIndex, sortDirection]).draw(); // Sorting by the selected column and direction
      });
    });
  </script>
       
    
@endsection
