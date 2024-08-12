@extends('Admin.layouts.app')


@section('content')
<?php
// dd($data);

?>
 <main class="main-dashboard">
 
        <section class="categories-list product-list user-list">
            <div class="recent-sales">
                <div class="input-search-box-container">
                    <h2 class="font-26">Rejected Quotes list</h2>
                   
                </div>
                <div class="main-table">
        <table  id="" class="display table table-striped table-bordered">
            <thead>
        <tr>
                <th>Sr.No.</th>
                <th>Name</th>
                <th>Email</th>
             <!--    <th>Product Name</th>
                <th>Product Price</th> -->
                <th>Total Price</th>
                <th>Date</th>
                <th>Status</th>
                <th>Reason</th>
        
       
        </tr>
       </thead>
       <tbody>
  <?php $index='1'; ?>
               @foreach($data as $product)
        <tr>
            
                       <td><?php echo $index++; ?></td>
          <td>{{$product['name'] }}</td>
             <td>{{$product['email'] }}</td>
                   <td>${{$product['total'] }}</td>
                     <td>{{$product['date'] }}</td>
                 <td><?php  echo "Rejected";?></td>                     
                     <td><?php echo $product['reason'] ; ?></td>

        </tr>
         @endforeach
        </tbody>
        <tfooter></tfooter>

     
      </table>
                </div>
            </div>
        </section>
    </main>


 
       
    
@endsection
