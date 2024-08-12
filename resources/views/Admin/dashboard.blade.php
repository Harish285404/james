@extends('Admin.layouts.app')


@section('content')
   <?php 

// dd($data);

    ?>
    <main class="main-dashboard">
        <section class="dashboard">
            <div class="submit_section">
                <div class="date">
                    <form class="search-form-box" action="{{url('admin')}}" method="get">
            <input type="date" id="calender"   name="start_date" placeholder="select date">
            <input type="date" id="calender"  name="end_date" >
            <button class="main-btn search-btn"  type="submit">Search</button>
          </form>
                </div>
            </div>
            <div class="income-section">
                <div class="earn-box total-sales">
                    <div class="eraning-left">
                        <h3>Total Sales</h3>
                        <h1>${{$Sale}}</h1>

                    </div>
                    <div class="eraning-right">
                        <div class="monthly-detail">
                            <div class="total-sales-icon">
                                <img src="{{asset('Admin/images/sale-icon-1.svg')}}">

                            </div>
                        </div>
                    </div>
                </div>
                <div class="earn-box total-categories">
                    <a href="javascript: void(0)">
                        <h2 class="heading-main">{{$Category}}</h2>
                        <p>Total Categories</p>
                    </a>

                </div>
                <div class="earn-box total-products">
                    <a href="javascript: void(0))">
                        <h2 class="heading-main">{{$Product}}</h2>
                        <p>Total Products</p>
                    </a>
                </div>
            </div>

            <div class="recent-sales">
                <h2 class="font-26">Recent Sales</h2>
                <div class="main-table">
                                         <table  id="" class="display table table-striped table-bordered">
            <thead>
        <tr>
                   <th>Sr.No.</th>
                <th>Name of Product</th>
                <th>Mode of Purchase</th>
                <th>Price</th> 
       
        </tr>
       </thead>
       <tbody>
        <?php $i=1;?>
       @foreach($data as $product)

        <tr>
          
         <td><?php echo $i++;?></td>


       
          <td>{{$product->title}}</td>
          <td>
                        <?php
              
                               echo '<span style="color: #34A853;">online</span>';
    

?>
          </td>
          <td>${{$product->subtotal}}</td>
        

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