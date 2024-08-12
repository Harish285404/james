@extends('Admin.layouts.app')


@section('content')
 <main class="main-dashboard">





                                                                            
        <section class="categories-list product-list user-list">
            <div class="recent-sales">
                <div class="input-search-box-container">
                    <h2 class="font-26">Customer List’s</h2>
                   
                </div>
                
                                                          <div class="sorting_tab">
                <label for="sortSelect">Sort By:</label>
<select id="sortSelect">
    <option value="first_name_asc">First Name (Asc)</option>
    <option value="first_name_desc">First Name (Desc)</option>
    <option value="last_name_asc">Last Name (Asc)</option>
    <option value="last_name_desc">Last Name (Desc)</option>
    <option value="registered_date_asc">Registered Date (Asc)</option>
    <option value="registered_date_desc">Registered Date (Desc)</option>
  </select>

</div>
                <div class="main-table">
        <table  id="yourDataTable" class="display table table-striped table-bordered">
            <thead>
        <tr>
            
                <th>First Name</th>
                <th>Last Name</th>
                <th>Email</th>
                <th>Registered Date</th>
        </tr>
       </thead>
       <tbody>

        <tr>
         
               
             
               @foreach($user as $users)
          
          <td>{{$users->first_name}}</td>
          <td>{{$users->last_name}}</td>
          <td>{{$users->email}}</td>     
           <td>
<?php
$dateString = $users->created_at;

if ($dateString == NULL) {
    $formattedDate = "-"; // You can change this to any message you prefer
    echo $formattedDate;
} else {
   $formattedDate = date("F d, Y", strtotime($dateString));
echo $formattedDate;
}


?>

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
        "order": [[0, "asc"]] // Initial sorting by the first column (First Name)
      });

      $('#sortSelect').change(function() {
        var selectedValue = $(this).val();
        var columnIndex;
        var sortDirection = 'asc';

        switch (selectedValue) {
          case 'first_name_asc':
            columnIndex = 0; // Index of the "First Name" column
            break;
          case 'first_name_desc':
            columnIndex = 0; // Index of the "First Name" column
            sortDirection = 'desc';
            break;
          case 'last_name_asc':
            columnIndex = 1; // Index of the "Last Name" column
            break;
          case 'last_name_desc':
            columnIndex = 1; // Index of the "Last Name" column
            sortDirection = 'desc';
            break;
          case 'registered_date_asc':
            columnIndex = 3; // Index of the "Registered Date" column
            break;
          case 'registered_date_desc':
            columnIndex = 3; // Index of the "Registered Date" column
            sortDirection = 'desc';
            break;
          default:
            columnIndex = 0; // Default to sorting by the first column (First Name)
            break;
        }

        table.order([columnIndex, sortDirection]).draw(); // Sorting by the selected column and direction
      });
    });
  </script>

 
       
    
@endsection


             