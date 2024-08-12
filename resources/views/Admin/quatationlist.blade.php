@extends('Admin.layouts.app')


@section('content')
<?php
// dd($data);

?>

<style>
  .main-table div.dataTables_wrapper table a.new-btns {
    font-size: 12px;
    line-height: 14px;
    margin: 0 5px;
}
  </style>
 <main class="main-dashboard">
   @if(Session::has('message'))
<p class="alert alert-info">{{ Session::get('message') }}</p>
@endif
 
        <section class="categories-list product-list user-list">
            <div class="recent-sales">
                <div class="input-search-box-container">
                    <h2 class="font-26">Quotes list</h2>
                   
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

                      <?php if( $product['status'] =='0' ){  ?>
<a href="{{url('admin/quatation-pdf/'.$product['quote_id'] )}}" type="button" class=" new-btns btn btn-info">
    Generate Invoice
  </a> 
    <a href="{{url('admin/edit-quote/'.$product['quote_id'] )}}" type="button" class="new-btns btn btn-success abc" >
    Edit Quote
  </a> 
                     <?php } else{ ?>
<a href="{{url('admin/quatation-pdf/'.$product['quote_id'] )}}" type="button" class=" new-btns btn btn-info" style="pointer-events: none;opacity: 0.6;">
     Invoice  Generated
  </a> 
   <a href="{{url('admin/edit-quote/'.$product['quote_id'] )}}" type="button" class="new-btns btn btn-success" style="pointer-events: none;opacity: 0.6;" >
    Edit Quote
  </a> 
                   <?php }  ?>
                  
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
        "order": [[5, "desc"]] // Initial sorting by the "Date" column in descending order
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
