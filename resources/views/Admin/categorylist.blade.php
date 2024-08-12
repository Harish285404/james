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
                    <h2 class="font-26">Categories</h2>
                   
                </div>
                             <div class="sorting_tab">
                <label for="sortSelect">Sort By:</label>
   <select id="sortSelect">
    <option value="name_asc">Category (Asc)</option>
    <option value="name_desc">Category (Desc)</option>
  </select>

</div>
                <div class="main-table">
                      <table  id="yourDataTable" class="display table table-striped table-bordered">
            <thead>
        <tr>
               
                <th>Name of category</th>
                <th>Status</th>
                <th>Action</th> 
       
        </tr>
       </thead>
       <tbody>

        <tr>
           
               @foreach($data as $category)
         
          <td>{{$category->name}}</td>
          <td>
            <?php
               if($category->status == 1){
                               echo '<span style="color: #34A854;">Live</span>';
                            }else{
                                echo '<span style="color: #F7B36B;">Hide</span>';
                            }

?>

          </td>

<td>
    <div>
    <a href="{{url('admin/edit-category/'.$category->id)}}"  class="Edit-btn button">Edit</a>
       <a href="javascript:void(0)"  class="View-btn button" data-id="{{$category->id}}" id="delete_buton">Delete</a> 
    <a href="{{url('admin/single-category/'.$category->id)}}"  class="Delete-btn button">View</a>
 


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
   
<!-- 
    <script type="text/javascript">
  $(function () {
    
    var table = $('.yajra-datatable').DataTable({
        processing: true,
        serverSide: true,
        ajax:{
            url:"{{ url('admin/get-category') }}",
            type: 'GET',
            },
                  
        columns: [
          {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {data: 'name', name: 'name'},
            {data: 'status', name: 'status'},
          {data: 'action', name: 'action', orderable: false, searchable: false},

          
        ]
    });
    
  });
</script> -->
   <script>
    $(document).ready(function() {
      var table = $('#yourDataTable').DataTable({
        "order": [[0, "asc"]] // Initial sorting by the first column (Name of category)
      });

      $('#sortSelect').change(function() {
        var selectedValue = $(this).val();
        var columnIndex;
        var sortDirection = 'asc';

        switch (selectedValue) {
          case 'name_asc':
            columnIndex = 0; // Index of the "Name of category" column
            break;
          case 'name_desc':
            columnIndex = 0; // Index of the "Name of category" column
            sortDirection = 'desc';
            break;
          case 'status_asc':
            columnIndex = 1; // Index of the "Status" column
            break;
          case 'status_desc':
            columnIndex = 1; // Index of the "Status" column
            sortDirection = 'desc';
            break;
          default:
            columnIndex = 0; // Default to sorting by the first column (Name of category)
            break;
        }

        table.order([columnIndex, sortDirection]).draw(); // Sorting by the selected column and direction
      });
    });
  </script>
       
    
@endsection